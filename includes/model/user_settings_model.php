<?php
function update_user_details($nick, $prename, $lastname, $age, $tel, $dect, $mobile, $mail, $email_shiftinfo, $jabber, $tshirt_size, $hometown, $planned_arrival_date, $planned_departure_date, $timezone, $uid) {
  return sql_query("
      UPDATE `User` SET
      `Nick`='" . sql_escape($nick) . "',
      `Vorname`='" . sql_escape($prename) . "',
      `Name`='" . sql_escape($lastname) . "',
      `Alter`='" . sql_escape($age) . "',
      `Telefon`='" . sql_escape($tel) . "',
      `DECT`='" . sql_escape($dect) . "',
      `Handy`='" . sql_escape($mobile) . "',
      `email`='" . sql_escape($mail) . "',
      `email_shiftinfo`=" . sql_bool($email_shiftinfo) . ",
      `jabber`='" . sql_escape($jabber) . "',
      `Size`='" . sql_escape($tshirt_size) . "',
      `Hometown`='" . sql_escape($hometown) . "',
      `planned_arrival_date`='" . sql_escape($planned_arrival_date) . "',
      `planned_departure_date`=" . sql_null($planned_departure_date) . "
      `timezone`='" . sql_escape($timezone) . "',
      WHERE `UID`='" . sql_escape($uid) . "'");
}
function update_user_sn($twitter, $facebook, $github, $uid) {
  return   sql_query("
      UPDATE `User` SET
      `twitter`='" . sql_escape($twitter) . "',
      `facebook`='" . sql_escape($facebook) . "',
      `github`='" . sql_escape($github) . "',
      WHERE `UID`='" . sql_escape($uid) . "'");
}
function update_user_org($organization, $organization_web, $uid) {
  return sql_query("
    UPDATE `User` SET
    `organization`='" . sql_escape($organization) . "',
    `organization_web`='" . sql_escape($organization_web) . "',
     WHERE `UID`='" . sql_escape($uid) . "'");
}
function update_user_langs($native_lang, $other_langs, $uid) {
  return sql_query("
    UPDATE `User` SET
    `native_lang`='" . sql_escape($native_lang) . "',
    `other_langs`='" . sql_escape($other_langs) . "',
     WHERE `UID`='" . sql_escape($uid) . "'");
}
function update_theme($selected_theme, $uid) {
  return sql_query("UPDATE `User` SET `color`='" . sql_escape($selected_theme) . "' WHERE `UID`='" . sql_escape($uid) . "'");
}
function update_sys_lang($selected_language, $uid) {
  return sql_query("UPDATE `User` SET `Sprache`='" . sql_escape($selected_language) . "' WHERE `UID`='" . sql_escape($uid) . "'");
}
function update_display_msg($display_message) {
  return sql_query("UPDATE `Welcome_Message` SET `display_msg`='" . sql_escape($display_message) . "'");
}
?>
