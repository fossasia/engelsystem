<?php

/**
 * Returns users groups
 * @param User $user
 */
function User_groups($user) {
  return sql_select("
      SELECT `Groups`.*
      FROM `UserGroups`
      JOIN `Groups` ON `Groups`.`UID`=`UserGroups`.`group_id`
      WHERE `UserGroups`.`uid`='" . sql_escape($user['UID']) . "'
      ORDER BY `UserGroups`.`group_id`
      ");
}

/**
 * Insert User to Group = -2
 * @param User $user_id
 */
function insert_UserGroups_by_id($user_id) {
  return sql_query("INSERT INTO `UserGroups` SET `uid`='" . sql_escape($user_id) . "', `group_id`=-2");
}

/**
 * Returns users from UserGroups
 * @param User $id
 */
function UserGroups_by_id($id) {
  return sql_select("SELECT * FROM `UserGroups` WHERE `uid`='" . sql_escape($id) . "' ORDER BY `group_id` LIMIT 1");
}

/**
 * Delete User from UserGroups
 * @param User $id
 */
function delete_UserGroups_id($id) {
  return sql_query("DELETE FROM `UserGroups` WHERE `uid`='" . sql_escape($id) . "'");
}

/**
 * Insert users from UserGroups
 * @param User $id
 * @param Group $group
 */
function insert_UserGroups_id($id, $group) {
  return sql_query("INSERT INTO `UserGroups` SET `uid`='" . sql_escape($id) . "', `group_id`='" . sql_escape($group) . "'");
}

/**
 * Insert User to Group = -2
 * @param User $user_id
 */
function Set_user_group($user_id) {
return sql_query("INSERT INTO `UserGroups` SET `uid`='" . sql_escape($user_id) . "', `group_id`=-2");
}

?>
