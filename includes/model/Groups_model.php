<?php

function Groups_by_id_groups($id, $my_highest_group) {
  return sql_select("SELECT * FROM `Groups` LEFT OUTER JOIN `UserGroups` ON (`UserGroups`.`group_id` = `Groups`.`UID` AND `UserGroups`.`uid` = '" . sql_escape($id) . "') WHERE `Groups`.`UID` >= '" . sql_escape($my_highest_group) . "' ORDER BY `Groups`.`Name`");
}

function Groups_by_name() {
  return sql_select("SELECT * FROM `Groups` ORDER BY `Name`");
}

function Groups_by_id($id) {
  return sql_select("SELECT * FROM `Groups` WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

function selects_groups_by_id() {
  $Groups_source = sql_select("SELECT * FROM `Groups` ");
  if ($Groups_source === false)
    return false;
  if (count($Groups_source) > 0)
    return $Groups_source;
  return null;
}

function create_new_group($name, $uid) {
  return sql_query("
    INSERT INTO `Groups` SET
    `Name`='" . sql_escape($name) . "',
    `UID`='" . sql_escape($uid) . "'");
}

function inserts_into_group_privileges($uid, $priv) {
 return sql_query("INSERT INTO `GroupPrivileges` SET `group_id`='" . sql_escape($uid) . "', `privilege_id`='" . sql_escape($priv) . "'");
}

function delete_group($uid) {
  return sql_query("
      DELETE FROM `Groups`
      WHERE `id`='" . sql_escape($uid) . "'
      LIMIT 1");;
}
?>
