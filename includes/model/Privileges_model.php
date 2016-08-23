<?php

/**
 * Returns Privileges Group by id
 *
 */
function Privileges_Group_by_id($id) {
  return sql_select("SELECT `Privileges`.*, `GroupPrivileges`.`group_id` FROM `Privileges` LEFT OUTER JOIN `GroupPrivileges` ON (`Privileges`.`id` = `GroupPrivileges`.`privilege_id` AND `GroupPrivileges`.`group_id`='" . sql_escape($id) . "') ORDER BY `Privileges`.`name`");
}

/**
 * Return Privileges by id
 *
 */
function Privileges_by_id($id) {
  return sql_select("SELECT * FROM `Privileges` WHERE `id`='" . sql_escape($id) . "' LIMIT 1");
}

?>
