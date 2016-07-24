<?php

function WelcomeMessage() {
  return sql_select("SELECT * FROM `Welcome_Message`");
}

function count_user_by_nick($nick) {
return sql_num_query("SELECT * FROM `User` WHERE `Nick`='" . sql_escape($nick) . "' LIMIT 1");
}

function count_user_by_email($email) {
return sql_num_query("SELECT * FROM `User` WHERE `email`='" . sql_escape($mail) . "' LIMIT 1");
}

function insert_user($default_theme, $nick, $prename, $lastname, $age, $tel, $dect, $native_lang, $other_langs, $mobile, $mail, $email_shiftinfo, $jabber, $tshirt_size, $password_hash, $comment, $hometown, $twitter, $facebook, $github, $organization, $current_city, $organization_web, $timezone, $planned_arrival_date) {
  return sql_query("
          INSERT INTO `User` SET
          `color`='" . sql_escape($default_theme) . "',
          `Nick`='" . sql_escape($nick) . "',
          `Vorname`='" . sql_escape($prename) . "',
          `Name`='" . sql_escape($lastname) . "',
          `Alter`='" . sql_escape($age) . "',
          `Telefon`='" . sql_escape($tel) . "',
          `DECT`='" . sql_escape($dect) . "',
          `native_lang`='" . sql_escape($native_lang) . "',
          `other_langs`='" . sql_escape($other_langs) . "',
          `Handy`='" . sql_escape($mobile) . "',
          `email`='" . sql_escape($mail) . "',
          `email_shiftinfo`=" . sql_bool($email_shiftinfo) . ",
          `jabber`='" . sql_escape($jabber) . "',
          `Size`='" . sql_escape($tshirt_size) . "',
          `Passwort`='" . sql_escape($password_hash) . "',
          `kommentar`='" . sql_escape($comment) . "',
          `Hometown`='" . sql_escape($hometown) . "',
          `CreateDate`=NOW(),
          `Sprache`='" . sql_escape($_SESSION["locale"]) . "',
          `arrival_date`=NULL,
          `twitter`='" . sql_escape($twitter) . "',
          `facebook`='" . sql_escape($facebook) . "',
          `github`='" . sql_escape($github) . "',
          `organization`='" . sql_escape($organization) . "',
          `current_city`='" . sql_escape($current_city) . "',
          `organization_web`='" . sql_escape($organization_web) . "',
          `timezone`='" . sql_escape($timezone) . "',
          `planned_arrival_date`='" . sql_escape($planned_arrival_date) . "'");
}

function insert_UserGroups_by_id($user_id) {
  return sql_query("INSERT INTO `UserGroups` SET `uid`='" . sql_escape($user_id) . "', `group_id`=-2");
}

function insert_UserAngelTypes($user_id, $selected_angel_type_id) {
  return sql_query("INSERT INTO `UserAngelTypes` SET `user_id`='" . sql_escape($user_id) . "', `angeltype_id`='" . sql_escape($selected_angel_type_id) . "'");
}

?>
