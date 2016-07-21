<?php

function message_unread($uid) {
  return sql_num_query("SELECT * FROM `Messages` WHERE isRead='N' AND `RUID`='" . sql_escape($uid) . "'");
}

function user_by_nick($uid) {
  return sql_select("SELECT * FROM `User` WHERE NOT `UID`='" . sql_escape($uid) . "' ORDER BY `Nick`");
}

function select_group() {
  return sql_select("SELECT * FROM `Groups` ORDER BY `Name`");
}

function select_angeltypes() {
  return sql_select("SELECT * FROM `AngelTypes` ORDER BY  `name`");
}

function select_messages($uid) {
  return sql_select("SELECT * FROM `Messages` WHERE `SUID`='" . sql_escape($uid) . "' OR `RUID`='" . sql_escape($uid) . "' ORDER BY `isRead`,`Datum` DESC");
}

function messages_by_id($id) {
  return sql_select("SELECT * FROM `Messages` WHERE `id`='" . sql_escape($id) . "' LIMIT 1");
}

function messages_read_by_id($id) {
  return sql_query("UPDATE `Messages` SET `isRead`='Y' WHERE `id`='" . sql_escape($id) . "' LIMIT 1");
}

function messages_delete($id) {
  return sql_query("DELETE FROM `Messages` WHERE `id`='" . sql_escape($id) . "' LIMIT 1");
}

function user_count() {
  return sql_num_query("SELECT * FROM `User`");
}

function select_usergroups($to) {
  return sql_select("SELECT * FROM `UserGroups` WHERE `group_id`='" . sql_escape($to) . "'");
}

function select_userangeltypes($id) {
  return sql_select("SELECT * FROM `UserAngelTypes` WHERE `angeltype_id`='" . sql_escape($id) . "'");
}

?>
