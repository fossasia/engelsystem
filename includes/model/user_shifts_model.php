<?php

function select_visible_rooms() {
  return sql_select("SELECT * FROM `Room` WHERE `show`='1' ORDER BY `Name`");
}

function del_shifts($sid) {
  return sql_query("DELETE FROM `Shifts` WHERE `SID` = '$sid'");
}

function select_shift_entry_source($entry_id) {
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

function select_shift_by_shiftid($shift_id) {
  return sql_select("
      SELECT `ShiftTypes`.`name`, `Shifts`.*, `Room`.* FROM `Shifts`
      JOIN `Room` ON (`Shifts`.`RID` = `Room`.`RID`)
      JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
      WHERE `SID`='" . sql_escape($shift_id) . "'");
}

function select_angeltypes_by_name() {
  return sql_select("SELECT * FROM `AngelTypes` ORDER BY `name`");
}

function select_needed_angeltypes_by_roomid($rid) {
  return sql_select("SELECT `AngelTypes`.*, `NeededAngelTypes`.`count` FROM `AngelTypes` LEFT JOIN `NeededAngelTypes` ON (`NeededAngelTypes`.`angel_type_id` = `AngelTypes`.`id` AND `NeededAngelTypes`.`room_id`='" . sql_escape($sid) . "') ORDER BY `AngelTypes`.`name`");
}

function select_needed_angeltypes_by_shiftid($sid) {
  return sql_select("SELECT `AngelTypes`.*, `NeededAngelTypes`.`count` FROM `AngelTypes` LEFT JOIN `NeededAngelTypes` ON (`NeededAngelTypes`.`angel_type_id` = `AngelTypes`.`id` AND `NeededAngelTypes`.`shift_id`='" . sql_escape($sid) . "') ORDER BY `AngelTypes`.`name`");
}

function delete_needed_angeltypes_by_id($shift_id) {
  return sql_query("DELETE FROM `NeededAngelTypes` WHERE `shift_id`='" . sql_escape($shift_id) . "'");
}

function insert_needed_angeltypes($shift_id, $type_id, $count) {
  return sql_query("INSERT INTO `NeededAngelTypes` SET `shift_id`='" . sql_escape($shift_id) . "', `angel_type_id`='" . sql_escape($type_id) . "', `count`='" . sql_escape($count) . "'");
}

function select_angeltypes_by_type($type_id) {
  return sql_select("SELECT * FROM `AngelTypes` WHERE `id`='" . sql_escape($type_id) . "' LIMIT 1");
}

function select_userangeltypes($type_id, $uid) {
  return sql_select("SELECT * FROM `UserAngelTypes` JOIN `AngelTypes` ON (`UserAngelTypes`.`angeltype_id` = `AngelTypes`.`id`) WHERE `AngelTypes`.`id` = '" . sql_escape($type_id) . "' AND (`AngelTypes`.`restricted` = 0 OR (`UserAngelTypes`.`user_id` = '" . sql_escape($uid) . "' AND NOT `UserAngelTypes`.`confirm_user_id` IS NULL)) LIMIT 1");
}

function count_user_by_id($user_id) {
  return sql_num_query("SELECT * FROM `User` WHERE `UID`='" . sql_escape($user_id) . "' LIMIT 1");
}

function count_shiftentry_by_id($sid, $user_id) {
  return sql_num_query("SELECT * FROM `ShiftEntry` WHERE `SID`='" . sql_escape($sid) . "' AND `UID` = '" . sql_escape($user_id) . "'");
}

function count_userangeltypes($selected_type_id, $user_id) {
  return sql_num_query("SELECT * FROM `UserAngelTypes` INNER JOIN `AngelTypes` ON `AngelTypes`.`id` = `UserAngelTypes`.`angeltype_id` WHERE `angeltype_id` = '" . sql_escape($selected_type_id) . "' AND `user_id` = '" . sql_escape($user_id) . "' ");
}

function insert_userangeltypes($user_id, $selected_type_id) {
  return sql_query("INSERT INTO `UserAngelTypes` (`user_id`, `angeltype_id`) VALUES ('" . sql_escape($user_id) . "', '" . sql_escape($selected_type_id) . "')");
}

function count_freeloaded_shifts() {
  return sql_select("SELECT *, (SELECT count(*) FROM `ShiftEntry` WHERE `freeloaded`=1 AND `ShiftEntry`.`UID`=`User`.`UID`) AS `freeloaded` FROM `User` ORDER BY `Nick`");
}

function get_days() {
  return sql_select_single_col("
      SELECT DISTINCT DATE(FROM_UNIXTIME(`start`)) AS `id`, DATE(FROM_UNIXTIME(`start`)) AS `name`
      FROM `Shifts`
      ORDER BY `start`");
}

function get_rooms() {
  return sql_select("SELECT `RID` AS `id`, `Name` AS `name` FROM `Room` WHERE `show`='1' ORDER BY `Name`");
}

function select_angeltype_id() {
  return sql_select("SELECT `id`, `name` FROM `AngelTypes` ORDER BY `AngelTypes`.`name`");
}

function select_restricted_angeltypes_by_id() {
  return sql_select("SELECT `AngelTypes`.`id`, `AngelTypes`.`name`, (`AngelTypes`.`restricted`=0 OR (NOT `UserAngelTypes`.`confirm_user_id` IS NULL OR `UserAngelTypes`.`id` IS NULL)) as `enabled` FROM `AngelTypes` LEFT JOIN `UserAngelTypes` ON (`UserAngelTypes`.`angeltype_id`=`AngelTypes`.`id` AND `UserAngelTypes`.`user_id`='" . sql_escape($user['UID']) . "') ORDER BY `AngelTypes`.`name`");
}

function select_unrestricted_angeltypes() {
  sql_select("SELECT `id`, `name` FROM `AngelTypes` WHERE `restricted` = 0");
}

function select_session_shifttypes($session_var, $starttime, $endtime) {
  return sql_select("
      SELECT `ShiftTypes`.`name`, `Shifts`.*
      FROM `Shifts`
      INNER JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
      INNER JOIN `ShiftEntry` ON (`Shifts`.`SID` = `ShiftEntry`.`SID` AND `ShiftEntry`.`UID` = '" . sql_escape($user['UID']) . "')
      WHERE `Shifts`.`RID` IN (" . implode(',', $session_var) . ")
      AND `start` BETWEEN " . $starttime . " AND " . $endtime);
}

function select_entries($sid, $angeltype_id) {
  return sql_select("SELECT * FROM `ShiftEntry` JOIN `User` ON (`ShiftEntry`.`UID` = `User`.`UID`) WHERE `SID`='" . sql_escape($sid) . "' AND `TID`='" . sql_escape($angeltype_id) . "' ORDER BY `Nick`");
}

function count_needed_angeltypes($sid) {
  return sql_num_query("SELECT `id` FROM `NeededAngelTypes` WHERE `shift_id` = " . $sid);
}

function count_user_shifts($sid, $uid) {
  return sql_num_query("SELECT * FROM `ShiftEntry` WHERE `SID`='" . sql_escape($sid) . "' AND `UID`='" . sql_escape($uid) . "' LIMIT 1");
}

?>
