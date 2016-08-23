<?php

/**
 * Returns Open Questions
 *
 * @param uid id of Users
 */
function select_open_questions($uid) {
  sql_select("SELECT * FROM `Questions` WHERE `AID` IS NULL AND `UID`='" . sql_escape($uid) . "'");
}

/**
 * Returns Answered Questions
 *
 * @param uid id of Users
 */
function select_ansd_questions($uid) {
  return sql_select("SELECT * FROM `Questions` WHERE NOT `AID` IS NULL AND `UID`='" . sql_escape($uid) . "'");
}

/**
 * Insert New Questions
 *
 * @param uid id of Users
 * @param question, Asked Questions
 */
function insert_new_question($uid, $question) {
  return sql_query("INSERT INTO `Questions` SET `UID`='" . sql_escape($uid) . "', `Question`='" . sql_escape($question) . "'");
}

/**
 * Returns Questions by ID
 *
 * @param id, id of Question
 */
function select_ques_by_id($id) {
  return sql_select("SELECT * FROM `Questions` WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Delete Questions by ID
 *
 * @param id, id of Question
 */
function delete_ques_by_id($id) {
  return sql_query("DELETE FROM `Questions` WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Returns count of Un-asnwered Questions
 *
 */
function Questions() {
return sql_num_query("SELECT * FROM `Questions` WHERE `AID` IS NULL");
}

/**
 * Returns Questions by ID
 *
 * @param id, id of Question
 */
function Questions_by_id($id) {
  return sql_select("SELECT * FROM `Questions` WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Update Questions
 *
 * @param user from `Users`
 * @param answer from `Questions`
 * @param id, id of Question
 */
function update_questions($user, $answer, $id) {
  return sql_query("UPDATE `Questions` SET `AID`='" . sql_escape($user['UID']) . "', `Answer`='" . sql_escape($answer) . "' WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Delete Questions by ID
 *
 * @param id, id of Question
 */
function delete_questions_by_id($id) {
  return sql_query("DELETE FROM `Questions` WHERE `QID`='" . sql_escape($id) . "' LIMIT 1");
}

?>
