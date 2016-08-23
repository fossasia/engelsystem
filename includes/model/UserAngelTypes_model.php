<?php
/**
 * User angeltypes model
 */

/**
 * List users angeltypes.
 *
 * @param User $user
 */
function User_angeltypes($user) {
  return sql_select("
      SELECT `AngelTypes`.*, `UserAngelTypes`.`confirm_user_id`, `UserAngelTypes`.`coordinator`
      FROM `UserAngelTypes`
      JOIN `AngelTypes` ON `UserAngelTypes`.`angeltype_id` = `AngelTypes`.`id`
      WHERE `UserAngelTypes`.`user_id`='" . sql_escape($user['UID']) . "'
      ");
}

/**
 * Gets unconfirmed user angeltypes for angeltypes of which the given user is a coordinator.
 *
 * @param User $user
 */
function User_unconfirmed_AngelTypes($user) {
  return sql_select("
    SELECT
      `UserAngelTypes`.*,
      `AngelTypes`.`name`,
      count(`UnconfirmedMembers`.`user_id`) as `count`
    FROM `UserAngelTypes`
    JOIN `AngelTypes` ON `UserAngelTypes`.`angeltype_id`=`AngelTypes`.`id`
    JOIN `UserAngelTypes` as `UnconfirmedMembers` ON `UserAngelTypes`.`angeltype_id`=`UnconfirmedMembers`.`angeltype_id`
    WHERE `UserAngelTypes`.`user_id`='" . sql_escape($user['UID']) . "'
      AND `UserAngelTypes`.`coordinator`=TRUE
      AND `AngelTypes`.`restricted`=TRUE
      AND `UnconfirmedMembers`.`confirm_user_id` IS NULL
    GROUP BY `UserAngelTypes`.`angeltype_id`
    ORDER BY `AngelTypes`.`name`");
}

/**
 * Returns true if user is angeltype coordinator or has privilege admin_user_angeltypes.
 *
 * @param User $user
 * @param AngelType $angeltype
 */
function User_is_AngelType_coordinator($user, $angeltype) {
  return (sql_num_query("
      SELECT `id`
      FROM `UserAngelTypes`
      WHERE `user_id`='" . sql_escape($user['UID']) . "'
      AND `angeltype_id`='" . sql_escape($angeltype['id']) . "'
      AND `coordinator`=TRUE
      LIMIT 1") > 0) || in_array('admin_user_angeltypes', privileges_for_user($user['UID']));
}

/**
 * Add or remove coordinator rights.
 *
 * @param int $user_angeltype_id
 * @param bool $coordinator
 */
function UserAngelType_update($user_angeltype_id, $coordinator) {
  return sql_query("
      UPDATE `UserAngelTypes`
      SET `coordinator`=" . sql_bool($coordinator) . "
      WHERE `id`='" . sql_escape($user_angeltype_id) . "'
      LIMIT 1");
}

/**
 * Delete all unconfirmed UserAngelTypes for given Angeltype.
 *
 * @param int $angeltype_id
 */
function UserAngelTypes_delete_all($angeltype_id) {
  return sql_query("
      DELETE FROM `UserAngelTypes`
      WHERE `angeltype_id`='" . sql_escape($angeltype_id) . "'
      AND `confirm_user_id` IS NULL");
}

/**
 * Confirm all unconfirmed UserAngelTypes for given Angeltype.
 *
 * @param int $angeltype_id
 * @param User $confirm_user
 */
function UserAngelTypes_confirm_all($angeltype_id, $confirm_user) {
  return sql_query("
      UPDATE `UserAngelTypes`
      SET `confirm_user_id`='" . sql_escape($confirm_user['UID']) . "'
      WHERE `angeltype_id`='" . sql_escape($angeltype_id) . "'
      AND `confirm_user_id` IS NULL");
}

/**
 * Confirm an UserAngelType with confirming user.
 *
 * @param int $user_angeltype_id
 * @param User $confirm_user
 */
function UserAngelType_confirm($user_angeltype_id, $confirm_user) {
  return sql_query("
      UPDATE `UserAngelTypes`
      SET `confirm_user_id`='" . sql_escape($confirm_user['UID']) . "'
      WHERE `id`='" . sql_escape($user_angeltype_id) . "'
      LIMIT 1");
}

/**
 * Delete an UserAngelType.
 *
 * @param UserAngelType $user_angeltype
 */
function UserAngelType_delete($user_angeltype) {
  return sql_query("
      DELETE FROM `UserAngelTypes`
      WHERE `id`='" . sql_escape($user_angeltype['id']) . "'
      LIMIT 1");
}

/**
 * Create an UserAngelType.
 *
 * @param User $user
 * @param Angeltype $angeltype
 */
function UserAngelType_create($user, $angeltype) {
  $result = sql_query("
    INSERT INTO `UserAngelTypes` SET
    `user_id`='" . sql_escape($user['UID']) . "',
    `angeltype_id`='" . sql_escape($angeltype['id']) . "'");
  if ($result === false)
    return false;
  return sql_id();
}

/**
 * Get an UserAngelType by its id.
 *
 * @param int $user_angeltype_id
 */
function UserAngelType($user_angeltype_id) {
  $angeltype = sql_select("
      SELECT *
      FROM `UserAngelTypes`
      WHERE `id`='" . sql_escape($user_angeltype_id) . "'
      LIMIT 1");
  if ($angeltype === false)
    return false;
  if (count($angeltype) == 0)
    return null;
  return $angeltype[0];
}

/**
 * Get an UserAngelType by user and angeltype.
 *
 * @param User $user
 * @param Angeltype $angeltype
 */
function UserAngelType_by_User_and_AngelType($user, $angeltype) {
  $angeltype = sql_select("
      SELECT *
      FROM `UserAngelTypes`
      WHERE `user_id`='" . sql_escape($user['UID']) . "'
      AND `angeltype_id`='" . sql_escape($angeltype['id']) . "'
      LIMIT 1");
  if ($angeltype === false)
    return false;
  if (count($angeltype) == 0)
    return null;
  return $angeltype[0];
}

/**
 * Create an UserAngelType.
 *
 * @param User $user_id
 * @param Angeltype $selected_angel_type_id
 */
function insert_UserAngelTypes($user_id, $selected_angel_type_id) {
  return sql_query("INSERT INTO `UserAngelTypes` SET `user_id`='" . sql_escape($user_id) . "', `angeltype_id`='" . sql_escape($selected_angel_type_id) . "'");
}

/**
 * Insert an UserAngelType.
 *
 * @param User $user_id
 * @param Angeltype $selected_type_id
 */
function inserts_user_angeltype($user_id, $selected_type_id) {
  return sql_query("INSERT INTO `UserAngelTypes` (`user_id`, `angeltype_id`) VALUES ('" . sql_escape($user_id) . "', '" . sql_escape($selected_type_id) . "')");
}

/**
 * Return Count of UserAngelType.
 *
 * @param Angeltype $selected_type_id
 * @param User $user_id
 */
function counts_user_angeltype($selected_type_id, $user_id) {
  return sql_num_query("SELECT * FROM `UserAngelTypes` INNER JOIN `AngelTypes` ON `AngelTypes`.`id` = `UserAngelTypes`.`angeltype_id` WHERE `angeltype_id` = '" . sql_escape($selected_type_id) . "' AND `user_id` = '" . sql_escape($user_id) . "' ");
}

/**
 * Return UserAngelType.
 *
 * @param Angeltype $type_id
 * @param User $uid
 */
function selects_user_angeltype($type_id, $uid) {
  return sql_select("SELECT * FROM `UserAngelTypes` JOIN `AngelTypes` ON (`UserAngelTypes`.`angeltype_id` = `AngelTypes`.`id`) WHERE `AngelTypes`.`id` = '" . sql_escape($type_id) . "' AND (`AngelTypes`.`restricted` = 0 OR (`UserAngelTypes`.`user_id` = '" . sql_escape($uid) . "' AND NOT `UserAngelTypes`.`confirm_user_id` IS NULL)) LIMIT 1");
}

?>
