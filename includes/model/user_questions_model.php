<?php
function select_open_questions($uid) {
  sql_select("SELECT * FROM `Questions` WHERE `AID` IS NULL AND `UID`='" . sql_escape($uid) . "'");
}
function select_ansd_questions($uid) {
  return sql_select("SELECT * FROM `Questions` WHERE NOT `AID` IS NULL AND `UID`='" . sql_escape($uid) . "'");
}
function insert_new_question($uid, $question) {
  return sql_query("INSERT INTO `Questions` SET `UID`='" . sql_escape($uid) . "', `Question`='" . sql_escape($question) . "'");
}
function select_ques_by_id($id) {
  return sql_select("SELECT * FROM `Questions` WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}
function delete_ques_by_id($id) {
  return sql_query("DELETE FROM `Questions` WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}
?>