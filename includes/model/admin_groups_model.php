<?php

function Groups_by_name() {
  return sql_select("SELECT * FROM `Groups` ORDER BY `Name`");
}

function select_GroupPrivileges($group) {
  return sql_select("SELECT * FROM `GroupPrivileges` JOIN `Privileges` ON (`GroupPrivileges`.`privilege_id` = `Privileges`.`id`) WHERE `group_id`='" . sql_escape($group['UID']) . "'");
}

function Groups_by_id($id) {
  return sql_select("SELECT * FROM `Groups` WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

function Privileges_Group_by_id($id) {
  return sql_select("SELECT `Privileges`.*, `GroupPrivileges`.`group_id` FROM `Privileges` LEFT OUTER JOIN `GroupPrivileges` ON (`Privileges`.`id` = `GroupPrivileges`.`privilege_id` AND `GroupPrivileges`.`group_id`='" . sql_escape($id) . "') ORDER BY `Privileges`.`name`");
}

function delete_GroupPrivileges($id) {
  return sql_query("DELETE FROM `GroupPrivileges` WHERE `group_id`='" . sql_escape($id) . "'");
}
function Privileges_by_id($id) {
  return sql_select("SELECT * FROM `Privileges` WHERE `id`='" . sql_escape($id) . "' LIMIT 1");
}

function insert_GroupPrivilege($id, $priv) {
  return sql_query("INSERT INTO `GroupPrivileges` SET `group_id`='" . sql_escape($id) . "', `privilege_id`='" . sql_escape($priv) . "'");
}
?>
