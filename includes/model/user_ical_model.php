<?php

function shifts_ical($uid) {
  return sql_select("
      SELECT `ShiftTypes`.`name`, `Shifts`.*, `Room`.`Name` as `room_name`
      FROM `ShiftEntry`
      INNER JOIN `Shifts` ON (`ShiftEntry`.`SID` = `Shifts`.`SID`)
      JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
      INNER JOIN `Room` ON (`Shifts`.`RID` = `Room`.`RID`)
      WHERE `UID`='" . sql_escape($uid) . "'
      ORDER BY `start`");
}

?>
