<?php
function Questions() {
return sql_num_query("SELECT * FROM `Questions` WHERE `AID` IS NULL");
}

function Questions_by_id($id) {
  return sql_select("SELECT * FROM `Questions` WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}

function update_questions($user, $answer, $id) {
  return sql_query("UPDATE `Questions` SET `AID`='" . sql_escape($user['UID']) . "', `Answer`='" . sql_escape($answer) . "' WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}

function delete_questions_by_id($id) {
  return sql_query("DELETE FROM `Questions` WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}
?>
