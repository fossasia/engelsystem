<?php

function user_count() {
  return sql_select("SELECT count(*) as `user_count` FROM `User`");
}

function user_count_arrived() {
  return sql_select("SELECT count(*) as `user_count` FROM `User` WHERE `Gekommen`=1");
}

function user_done_shifts() {
  return sql_select_single_cell("SELECT SUM(`Shifts`.`end` - `Shifts`.`start`) FROM `ShiftEntry` JOIN `Shifts` USING (`SID`) WHERE `Shifts`.`end` < UNIX_TIMESTAMP()");
}

function user_action_source() {
  return sql_select("SELECT `Shifts`.`start`, `Shifts`.`end` FROM `ShiftEntry` JOIN `Shifts` ON `Shifts`.`SID`=`ShiftEntry`.`SID` WHERE UNIX_TIMESTAMP() BETWEEN `Shifts`.`start` AND `Shifts`.`end`");
}

?>
