<?php

function create_temporary_table() {
  return sql_query("CREATE TEMPORARY TABLE `temp_tb` SELECT * FROM `User`");
}

function alter_table($col) {
  return sql_query("ALTER TABLE `temp_tb` DROP $col");
}

function select_column() {
  return sql_select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'User' ");

}

function select_temp_tb() {
  return sql_select("SELECT * FROM `temp_tb`");
}

function User_select_nick($nick) {
  return sql_num_query("SELECT * FROM `User` WHERE `Nick`='" . sql_escape($nick) . "' LIMIT 1");
}

function User_select_mail($mail) {
  return sql_num_query("SELECT * FROM `User` WHERE `email`='" . sql_escape($mail) . "' LIMIT 1");
}

function Set_user_group($user_id) {
return sql_query("INSERT INTO `UserGroups` SET `uid`='" . sql_escape($user_id) . "', `group_id`=-2");
}

function User_insert($nick, $prename, $lastname, $age, $tel, $dect, $mobile, $mail, $email_shiftinfo, $jabber, $tshirt_size, $password_hash, $comment, $hometown, $twitter, $facebook, $github, $organization, $organization_web, $timezone, $planned_arrival_date) {
  return  sql_query("
            INSERT INTO `User` SET
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
            `Passwort`='" . sql_escape($password_hash) . "',
            `kommentar`='" . sql_escape($comment) . "',
            `Hometown`='" . sql_escape($hometown) . "',
            `CreateDate`= NOW(),
            `Sprache`='" . sql_escape($_SESSION["locale"]) . "',
            `arrival_date`= NULL,
            `twitter`='" . sql_escape($twitter) . "',
            `facebook`='" . sql_escape($facebook) . "',
            `github`='" . sql_escape($github) . "',
            `organization`='" . sql_escape($organization) . "',
            `current_city`='" . sql_escape($current_city) . "',
            `organization_web`='" . sql_escape($organization_web) . "',
            `timezone`='" . sql_escape($timezone) . "',
            `planned_arrival_date`='" . sql_escape($planned_arrival_date) . "'");
}
?>
