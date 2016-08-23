<?php

/**
 * Update Welcome Message.
 *
 * @param $display_message of Settings
 */
function update_display_msg($display_message) {
  return sql_query("UPDATE `Welcome_Message` SET `display_msg`='" . sql_escape($display_message) . "'");
}

/**
 * Update Welcome Message.
 *
 */
function welcome_msg() {
  return sql_select("SELECT * FROM `Welcome_Message`");
}

function WelcomeMessage() {
  return sql_select("SELECT * FROM `Welcome_Message`");
}

?>
