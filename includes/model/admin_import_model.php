<?php
function Room_by_FromPentabarf() {
  return sql_select("SELECT * FROM `Room` WHERE `FromPentabarf`='1'");
}
function Room_all() {
  return sql_select("SELECT * FROM `Room`");
}
function Shifts_by_start() {
  return sql_select("SELECT * FROM `Shifts` WHERE `PSID` IS NOT NULL ORDER BY `start`");
}
function delete_room_by_name ($room) {
  return sql_query("DELETE FROM `Room` WHERE `Name`='" . sql_escape($room) . "' LIMIT 1");
}
?>
