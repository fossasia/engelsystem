<?php
/**
 * Update Setting.
 *
 * @param string $event_name
 * @param int $buildup_start_date
 * @param int $event_start_date
 * @param int $event_end_date
 * @param int $teardown_end_date
 * @param string $event_welcome_msg
 */
function Settings_update($event_name, $buildup_start_date, $event_start_date, $event_end_date, $teardown_end_date, $event_welcome_msg) {
  return sql_query("UPDATE `Settings` SET
      `event_name`='" . sql_escape($event_name) . "',
      `buildup_start_date`='" . sql_escape($buildup_start_date) . "',
      `event_start_date`='" . sql_escape($event_start_date) . "',
      `event_end_date`='" . sql_escape($event_end_date) . "',
      `teardown_end_date`='" . sql_escape($teardown_end_date) . "',
      `event_welcome_msg`='" . sql_escape($event_welcome_msg) . "'");
}

/**
 * Create Settings.
 *
 * @param string $event_name
 * @param int $buildup_start_date
 * @param int $event_start_date
 * @param int $event_end_date
 * @param int $teardown_end_date
 * @param string $event_welcome_msg
 */
function Settings_create($event_name, $buildup_start_date, $event_start_date, $event_end_date, $teardown_end_date, $event_welcome_msg) {
  return sql_query("INSERT INTO `Settings` SET
      `event_name`='" . sql_escape($event_name) . "',
      `buildup_start_date`='" . sql_escape($buildup_start_date) . "',
      `event_start_date`='" . sql_escape($event_start_date) . "',
      `event_end_date`='" . sql_escape($event_end_date) . "',
      `teardown_end_date`='" . sql_escape($teardown_end_date) . "',
      `event_welcome_msg`='" . sql_escape($event_welcome_msg) . "'");
}

function Settings() {
  return sql_select("SELECT * FROM `Settings`");
}
?>
