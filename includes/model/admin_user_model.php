<?php

function UserGroups_by_id($id) {
  return sql_select("SELECT * FROM `UserGroups` WHERE `uid`='" . sql_escape($id) . "' ORDER BY `group_id` LIMIT 1");
}

function Groups_by_id_groups($id, $my_highest_group) {
  return sql_select("SELECT * FROM `Groups` LEFT OUTER JOIN `UserGroups` ON (`UserGroups`.`group_id` = `Groups`.`UID` AND `UserGroups`.`uid` = '" . sql_escape($id) . "') WHERE `Groups`.`UID` >= '" . sql_escape($my_highest_group) . "' ORDER BY `Groups`.`Name`");
}

function delete_UserGroups_id($id) {
  return sql_query("DELETE FROM `UserGroups` WHERE `uid`='" . sql_escape($id) . "'");
}

function insert_UserGroups_id($id, $group) {
  return sql_query("INSERT INTO `UserGroups` SET `uid`='" . sql_escape($id) . "', `group_id`='" . sql_escape($group) . "'");
}

function update_user($eNick, $eName, $eVorname, $eTelefon, $eHandy, $eAlter, $eDECT, $eemail, $email_shiftinfo, $ejabber, $eSize, $eGekommen, $eAktiv, $force_active, $eTshirt, $Hometown, $id) {
  return "UPDATE `User` SET
              `Nick` = '" . sql_escape($eNick) . "',
              `Name` = '" . sql_escape($eName) . "',
              `Vorname` = '" . sql_escape($eVorname) . "',
              `Telefon` = '" . sql_escape($eTelefon) . "',
              `Handy` = '" . sql_escape($eHandy) . "',
              `Alter` = '" . sql_escape($eAlter) . "',
              `DECT` = '" . sql_escape($eDECT) . "',
              `email` = '" . sql_escape($eemail) . "',
              `email_shiftinfo` = " . sql_bool(isset($email_shiftinfo)) . ",
              `jabber` = '" . sql_escape($ejabber) . "',
              `Size` = '" . sql_escape($eSize) . "',
              `Gekommen`= '" . sql_escape($eGekommen) . "',
              `Aktiv`= '" . sql_escape($eAktiv) . "',
              `force_active`= " . sql_escape($force_active) . ",
              `Tshirt` = '" . sql_escape($eTshirt) . "',
              `Hometown` = '" . sql_escape($Hometown) . "'
              WHERE `UID` = '" . sql_escape($id) . "'
              LIMIT 1";
}
?>
