<?php

/**
 * Counts all freeloaded shifts.
 */
function ShiftEntries_freeleaded_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `ShiftEntry` WHERE `freeloaded` = 1");
}

/**
 * List users subsribed to a given shift.
 */
function ShiftEntries_by_shift($shift_id) {
  return sql_select("
      SELECT `User`.`Nick`, `User`.`email`, `User`.`email_shiftinfo`, `User`.`Sprache`, `ShiftEntry`.`UID`, `ShiftEntry`.`TID`, `ShiftEntry`.`SID`, `AngelTypes`.`name` as `angel_type_name`, `ShiftEntry`.`Comment`, `ShiftEntry`.`freeloaded`
      FROM `ShiftEntry`
      JOIN `User` ON `ShiftEntry`.`UID`=`User`.`UID`
      JOIN `AngelTypes` ON `ShiftEntry`.`TID`=`AngelTypes`.`id`
      WHERE `ShiftEntry`.`SID`='" . sql_escape($shift_id) . "'");
}

/**
 * Create a new shift entry.
 *
 * @param ShiftEntry $shift_entry
 */
function ShiftEntry_create($shift_entry) {
  mail_shift_assign(User($shift_entry['UID']), Shift($shift_entry['SID']));
  return sql_query("INSERT INTO `ShiftEntry` SET
      `SID`='" . sql_escape($shift_entry['SID']) . "',
      `TID`='" . sql_escape($shift_entry['TID']) . "',
      `UID`='" . sql_escape($shift_entry['UID']) . "',
      `Comment`='" . sql_escape($shift_entry['Comment']) . "',
      `freeload_comment`='" . sql_escape($shift_entry['freeload_comment']) . "',
      `freeloaded`=" . sql_bool($shift_entry['freeloaded']));
}

/**
 * Update a shift entry.
 */
function ShiftEntry_update($shift_entry) {
  return sql_query("UPDATE `ShiftEntry` SET
      `Comment`='" . sql_escape($shift_entry['Comment']) . "',
      `freeload_comment`='" . sql_escape($shift_entry['freeload_comment']) . "',
      `freeloaded`=" . sql_bool($shift_entry['freeloaded']) . "
      WHERE `id`='" . sql_escape($shift_entry['id']) . "'");
}

/**
 * Get a shift entry.
 */
function ShiftEntry($shift_entry_id) {
  $shift_entry = sql_select("SELECT * FROM `ShiftEntry` WHERE `id`='" . sql_escape($shift_entry_id) . "'");
  if ($shift_entry === false)
    return false;
  if (count($shift_entry) == 0)
    return null;
  return $shift_entry[0];
}

/**
 * Delete a shift entry.
 */
function ShiftEntry_delete($shift_entry_id) {
  $shift_entry = ShiftEntry($shift_entry_id);
  mail_shift_removed(User($shift_entry['UID']), Shift($shift_entry['SID']));
  return sql_query("DELETE FROM `ShiftEntry` WHERE `id`='" . sql_escape($shift_entry_id) . "'");
}

/**
 * Returns next (or current) shifts of given user.
 *
 * @param User $user
 */
function ShiftEntries_upcoming_for_user($user) {
  return sql_select("
      SELECT *
      FROM `ShiftEntry`
      JOIN `Shifts` ON (`Shifts`.`SID` = `ShiftEntry`.`SID`)
      JOIN `ShiftTypes` ON `ShiftTypes`.`id` = `Shifts`.`shifttype_id`
      WHERE `ShiftEntry`.`UID`=" . sql_escape($user['UID']) . "
      AND `Shifts`.`end` > " . sql_escape(time()) . "
      ORDER BY `Shifts`.`end`
      ");
}

/**
 * Returns shifts completed by the given user.
 *
 * @param User $user
 */
function ShiftEntries_finished_by_user($user){
	  return sql_select("
      SELECT *
      FROM `ShiftEntry`
      JOIN `Shifts` ON (`Shifts`.`SID` = `ShiftEntry`.`SID`)
      JOIN `ShiftTypes` ON `ShiftTypes`.`id` = `Shifts`.`shifttype_id`
      WHERE `ShiftEntry`.`UID`=" . sql_escape($user['UID']) . "
      AND `Shifts`.`end` < " . sql_escape(time()) . "
      ORDER BY `Shifts`.`end`
      ");
}

/**
 * Returns all shift entries in given shift for given angeltype.
 *
 * @param int $shift_id
 * @param int $angeltype_id
 */
function ShiftEntries_by_shift_and_angeltype($shift_id, $angeltype_id) {
  return sql_select("
      SELECT *
      FROM `ShiftEntry`
      WHERE `SID`=" . sql_escape($shift_id) . "
      AND `TID`=" . sql_escape($angeltype_id) . "
      ");
}

/**
 * Returns all freeloaded shifts for given user.
 *
 */
function ShiftEntries_freeloaded_by_user($user) {
  return sql_select("SELECT *
      FROM `ShiftEntry`
      WHERE `freeloaded` = 1
      AND `UID`=" . sql_escape($user['UID']));
}

/**
 * Returns sum of Shifts for given user.
 *
 */
function user_done_shifts() {
  return sql_select_single_cell("SELECT SUM(`Shifts`.`end` - `Shifts`.`start`) FROM `ShiftEntry` JOIN `Shifts` USING (`SID`) WHERE `Shifts`.`end` < UNIX_TIMESTAMP()");
}

/**
 * Returns actions source for given user.
 *
 */
function user_action_source() {
  return sql_select("SELECT `Shifts`.`start`, `Shifts`.`end` FROM `ShiftEntry` JOIN `Shifts` ON `Shifts`.`SID`=`ShiftEntry`.`SID` WHERE UNIX_TIMESTAMP() BETWEEN `Shifts`.`start` AND `Shifts`.`end`");
}

/**
 * Returns Shifts for given user.
 *
 * @param $sid ID of Shifts
 * @param $uid ID of Users
 */
function select_shifts($sid, $uid) {
  return sql_select("SELECT
      `ShiftEntry`.`freeloaded`,
      `ShiftEntry`.`freeload_comment`,
      `ShiftEntry`.`Comment`,
      `ShiftEntry`.`UID`,
      `ShiftTypes`.`name`,
      `Shifts`.*,
      `Room`.`Name`,
      `AngelTypes`.`name` as `angel_type`
      FROM `ShiftEntry`
      JOIN `AngelTypes` ON (`ShiftEntry`.`TID` = `AngelTypes`.`id`)
      JOIN `Shifts` ON (`ShiftEntry`.`SID` = `Shifts`.`SID`)
      JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
      JOIN `Room` ON (`Shifts`.`RID` = `Room`.`RID`)
      WHERE `ShiftEntry`.`id`='" . sql_escape($sid) . "'
      AND `UID`='" . sql_escape($uid) . "' LIMIT 1");
}

/**
 * Returns shifts for given user.
 *
 * @param $starttime Start time of Shifts
 * @param $endtime End Time of Shifts
 * @param $uid ID of Users
 */
function gets_shifts($starttime, $endtime, $uid) {
  $SQL = "SELECT DISTINCT `Shifts`.*, `ShiftTypes`.`name`, `Room`.`Name` as `room_name`, nat2.`special_needs` > 0 AS 'has_special_needs'
  FROM `Shifts`
  INNER JOIN `Room` USING (`RID`)
  INNER JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
  LEFT JOIN (SELECT COUNT(*) AS special_needs , nat3.`shift_id` FROM `NeededAngelTypes` AS nat3 WHERE `shift_id` IS NOT NULL GROUP BY nat3.`shift_id`) AS nat2 ON nat2.`shift_id` = `Shifts`.`SID`
  INNER JOIN `NeededAngelTypes` AS nat ON nat.`count` != 0 AND nat.`angel_type_id` IN (" . implode(',', $_SESSION['user_shifts']['types']) . ") AND ((nat2.`special_needs` > 0 AND nat.`shift_id` = `Shifts`.`SID`) OR ((nat2.`special_needs` = 0 OR nat2.`special_needs` IS NULL) AND nat.`room_id` = `RID`))
  LEFT JOIN (SELECT se.`SID`, se.`TID`, COUNT(*) as count FROM `ShiftEntry` AS se GROUP BY se.`SID`, se.`TID`) AS entries ON entries.`SID` = `Shifts`.`SID` AND entries.`TID` = nat.`angel_type_id`
  WHERE `Shifts`.`RID` IN (" . implode(',', $_SESSION['user_shifts']['rooms']) . ")
  AND `start` BETWEEN " . $starttime . " AND " . $endtime;

  if (count($_SESSION['user_shifts']['filled']) == 1) {
    if ($_SESSION['user_shifts']['filled'][0] == 0)
      $SQL .= "
      AND (nat.`count` > entries.`count` OR entries.`count` IS NULL OR EXISTS (SELECT `SID` FROM `ShiftEntry` WHERE `UID` = '" . sql_escape($uid) . "' AND `ShiftEntry`.`SID` = `Shifts`.`SID`))";
    elseif ($_SESSION['user_shifts']['filled'][0] == 1)
      $SQL .= "
    AND (nat.`count` <= entries.`count`  OR EXISTS (SELECT `SID` FROM `ShiftEntry` WHERE `UID` = '" . sql_escape($uid) . "' AND `ShiftEntry`.`SID` = `Shifts`.`SID`))";
  }
  $SQL .= "
  ORDER BY `start`";

  return sql_select($SQL);
}

/**
 * Returns count of shifts for given user.
 *
 * @param $sid ID of Shifts
 * @param $uid ID of Users
 */
function counts_user_shiftss($sid, $uid) {
  return sql_num_query("SELECT * FROM `ShiftEntry` WHERE `SID`='" . sql_escape($sid) . "' AND `UID`='" . sql_escape($uid) . "' LIMIT 1");
}

/**
 * Returns all shifts for given Angeltype.
 *
 * @param $uid ID of Users
 * @param $angeltype_id ID of AngelType
 */
function selects_entries($sid, $angeltype_id) {
  return sql_select("SELECT * FROM `ShiftEntry` JOIN `User` ON (`ShiftEntry`.`UID` = `User`.`UID`) WHERE `SID`='" . sql_escape($sid) . "' AND `TID`='" . sql_escape($angeltype_id) . "' ORDER BY `Nick`");
}

/**
 * Returns count of freeloaded shifts
 *
 */
function counts_freeloaded_shifts() {
  return sql_select("SELECT *, (SELECT count(*) FROM `ShiftEntry` WHERE `freeloaded`=1 AND `ShiftEntry`.`UID`=`User`.`UID`) AS `freeloaded` FROM `User` ORDER BY `Nick`");
}

/**
 * Returns count of shifts for given user.
 *
 * @param $sid ID of Shifts
 * @param $uid ID of Users
 */
function counts_shift_entry_by_ids($sid, $user_id) {
  return sql_num_query("SELECT * FROM `ShiftEntry` WHERE `SID`='" . sql_escape($sid) . "' AND `UID` = '" . sql_escape($user_id) . "'");
}

/**
 * Returns shifts by Entry Source
 *
 * @param $entry_id ID of ShiftsEntry
 */
function selects_shift_entry_source($entry_id) {
  return sql_select("
      SELECT `User`.`Nick`, `ShiftEntry`.`Comment`, `ShiftEntry`.`UID`, `ShiftTypes`.`name`, `Shifts`.*, `Room`.`Name`, `AngelTypes`.`name` as `angel_type`
      FROM `ShiftEntry`
      JOIN `User` ON (`User`.`UID`=`ShiftEntry`.`UID`)
      JOIN `AngelTypes` ON (`ShiftEntry`.`TID` = `AngelTypes`.`id`)
      JOIN `Shifts` ON (`ShiftEntry`.`SID` = `Shifts`.`SID`)
      JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
      JOIN `Room` ON (`Shifts`.`RID` = `Room`.`RID`)
      WHERE `ShiftEntry`.`id`='" . sql_escape($entry_id) . "'");
}

?>
