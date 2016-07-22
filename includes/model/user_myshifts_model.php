<?php

function count_users_by_id($id) {
  return sql_num_query("SELECT * FROM `User` WHERE `UID`='" . sql_escape($id) . "'");
}

function user_by_id($id) {
  return sql_select("SELECT * FROM `User` WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

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

function shiftentry_select($sid, $uid) {
  return sql_select("
      SELECT *
      FROM `Shifts`
      INNER JOIN `ShiftEntry` USING (`SID`)
      WHERE `ShiftEntry`.`id`='" . sql_escape($sid) . "' AND `UID`='" . sql_escape($uid) . "'");
}

?>
