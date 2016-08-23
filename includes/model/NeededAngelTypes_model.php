<?php

/**
 * Returns all needed angeltypes and already taken needs.
 *
 * @param shiftID id of shift
 */
function NeededAngelTypes_by_shift($shiftId) {
  $needed_angeltypes_source = sql_select("
        SELECT `NeededAngelTypes`.*, `AngelTypes`.`name`, `AngelTypes`.`restricted`
        FROM `NeededAngelTypes`
        JOIN `AngelTypes` ON `AngelTypes`.`id` = `NeededAngelTypes`.`angel_type_id`
        WHERE `shift_id`='" . sql_escape($shiftId) . "'
        AND `count` > 0
        ORDER BY `room_id` DESC
        ");
  if ($needed_angeltypes_source === false)
    return false;

    // Use settings from room
  if (count($needed_angeltypes_source) == 0) {
    $needed_angeltypes_source = sql_select("
        SELECT `NeededAngelTypes`.*, `AngelTypes`.`name`, `AngelTypes`.`restricted`
        FROM `NeededAngelTypes`
        JOIN `AngelTypes` ON `AngelTypes`.`id` = `NeededAngelTypes`.`angel_type_id`
        JOIN `Shifts` ON `Shifts`.`RID` = `NeededAngelTypes`.`room_id`
        WHERE `Shifts`.`SID`='" . sql_escape($shiftId) . "'
        AND `count` > 0
        ORDER BY `room_id` DESC
        ");
    if ($needed_angeltypes_source === false)
      return false;
  }

  $needed_angeltypes = array();
  foreach ($needed_angeltypes_source as $angeltype) {
    $shift_entries = ShiftEntries_by_shift_and_angeltype($shiftId, $angeltype['angel_type_id']);
    if ($shift_entries === false)
      return false;

    $angeltype['taken'] = count($shift_entries);
    $needed_angeltypes[] = $angeltype;
  }

  return $needed_angeltypes;
}

/**
 * Returns all needed angeltypes by room id.
 *
 * @param ID id of room
 */
function NeededAngelTypes_by_room($id) {
  return sql_select("SELECT * FROM `NeededAngelTypes` WHERE `room_id`='" . sql_escape($id) . "'");
}

/**
 * Delete needed angeltypes
 *
 * @param ID id of room
 */
function delete_NeededAngelTypes_by_id($id) {
  return sql_query("DELETE FROM `NeededAngelTypes` WHERE `room_id`='" . sql_escape($id) . "'");
}

/**
 * Insert needed angeltypes
 *
 * @param ID id of room
 * @param angeltypeID id of AngelType
 * @param angeltypecount count of angeltype
 */
function insert_by_room($id, $angeltype_id, $angeltype_count) {
 return sql_query("INSERT INTO `NeededAngelTypes` SET `room_id`='" . sql_escape($id) . "', `angel_type_id`='" . sql_escape($angeltype_id) . "', `count`='" . sql_escape($angeltype_count) . "'");
}

/**
 * Insert needed angeltypes
 *
 * @param ShiftID id of shifts
 * @param typeID typeid of AngelType
 * @param count count of Neededangeltype
 */
function insert_by_shift($shift_id, $type_id, $count) {
  return sql_query("INSERT INTO `NeededAngelTypes` SET `shift_id`='" . sql_escape($shift_id) . "', `angel_type_id`='" . sql_escape($type_id) . "', `count`='" . sql_escape($count) . "'");
}

/**
 * Retrun Selected angeltypes
 *
 * @param uid id of Users
 * @param sid id of Shifts
 * @param rid id of Rooms
 * @param shift_special_needs special needs of Shifts from shifts page
 */
function gets_angeltype($uid, $sid, $rid, $shift_special_needs) {
  $query = "SELECT `NeededAngelTypes`.`count`, `AngelTypes`.`id`, `AngelTypes`.`restricted`, `UserAngelTypes`.`confirm_user_id`, `AngelTypes`.`name`, `UserAngelTypes`.`user_id`
  FROM `NeededAngelTypes`
  JOIN `AngelTypes` ON (`NeededAngelTypes`.`angel_type_id` = `AngelTypes`.`id`)
  LEFT JOIN `UserAngelTypes` ON (`NeededAngelTypes`.`angel_type_id` = `UserAngelTypes`.`angeltype_id`AND `UserAngelTypes`.`user_id`='" . sql_escape($uid) . "')
  WHERE `count` > 0 AND ";

  if ($shift_special_needs)
    $query .= "`shift_id` = '" . sql_escape($sid) . "'";
  else
    $query .= "`room_id` = '" . sql_escape($rid) . "'";
  if (! empty($_SESSION['user_shifts']['types']))
    $query .= " AND `angel_type_id` IN (" . implode(',', $_SESSION['user_shifts']['types']) . ") ";
  $query .= " ORDER BY `AngelTypes`.`name`";

  return sql_select($query);
}

/**
 * Returns special angeltypes
 *
 * @param uid id of Users
 * @param special_needs special needs of Shifts from shifts page
 * @param sid id of Shifts
 * @param rid id of Rooms
 */
function gets_special_angeltypes($uid, $special_needs, $sid, $rid) {
  $query = "SELECT `NeededAngelTypes`.`count`, `AngelTypes`.`id`, `AngelTypes`.`restricted`, `UserAngelTypes`.`confirm_user_id`, `AngelTypes`.`name`, `UserAngelTypes`.`user_id`
  FROM `NeededAngelTypes`
  JOIN `AngelTypes` ON (`NeededAngelTypes`.`angel_type_id` = `AngelTypes`.`id`)
  LEFT JOIN `UserAngelTypes` ON (`NeededAngelTypes`.`angel_type_id` = `UserAngelTypes`.`angeltype_id`AND `UserAngelTypes`.`user_id`='" . sql_escape($uid) . "')
  WHERE ";

  if ($special_needs)
    $query .= "`shift_id` = '" . sql_escape($sid) . "'";
  else
    $query .= "`room_id` = '" . sql_escape($rid) . "'";
  $query .= "               AND `count` > 0 ";
  if (! empty($_SESSION['user_shifts']['types']))
    $query .= "AND `angel_type_id` IN (" . implode(',', $_SESSION['user_shifts']['types']) . ") ";
  $query .= "ORDER BY `AngelTypes`.`name`";

  return sql_select($query);
}

/**
 * Returns count of Needed Angeltypes
 *
 * @param sid id of Shifts
 */
function counts_needed_angeltype($sid) {
  return sql_num_query("SELECT `id` FROM `NeededAngelTypes` WHERE `shift_id` = " . $sid);
}
?>
