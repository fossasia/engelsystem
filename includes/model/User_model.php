<?php

/**
 * User model
 */

/**
 * Delete a user
 *
 * @param int $user_id
 */
function User_delete($user_id) {
  return sql_query("DELETE FROM `User` WHERE `UID`='" . sql_escape($user_id) . "'");
}

/**
 * Update user.
 *
 * @param User $user
 */
function User_update($user) {
  return sql_query("UPDATE `User` SET
      `Nick`='" . sql_escape($user['Nick']) . "',
      `Name`='" . sql_escape($user['Name']) . "',
      `Vorname`='" . sql_escape($user['Vorname']) . "',
      `Alter`='" . sql_escape($user['Alter']) . "',
      `Telefon`='" . sql_escape($user['Telefon']) . "',
      `DECT`='" . sql_escape($user['DECT']) . "',
      `Handy`='" . sql_escape($user['Handy']) . "',
      `email`='" . sql_escape($user['email']) . "',
      `email_shiftinfo`=" . sql_bool($user['email_shiftinfo']) . ",
      `jabber`='" . sql_escape($user['jabber']) . "',
      `Size`='" . sql_escape($user['Size']) . "',
      `Gekommen`='" . sql_escape($user['Gekommen']) . "',
      `Aktiv`='" . sql_escape($user['Aktiv']) . "',
      `force_active`=" . sql_bool($user['force_active']) . ",
      `Tshirt`='" . sql_escape($user['Tshirt']) . "',
      `color`='" . sql_escape($user['color']) . "',
      `Sprache`='" . sql_escape($user['Sprache']) . "',
      `Hometown`='" . sql_escape($user['Hometown']) . "',
      `got_voucher`='" . sql_escape($user['got_voucher']) . "',
      `arrival_date`='" . sql_escape($user['arrival_date']) . "',
      `planned_arrival_date`='" . sql_escape($user['planned_arrival_date']) . "',
      `current_city`='" . sql_escape($user['current_city']) . "',
      `twitter`='" . sql_escape($user['twitter']) . "',
      `facebook`='" . sql_escape($user['facebook']) . "',
      `github`='" . sql_escape($user['github']) . "',
      `organization`='" . sql_escape($user['organization']) . "',
      `organization_web`='" . sql_escape($user['organization_web']) . "',
      `timezone`='" . sql_escape($user['timezone']) . "'
      WHERE `UID`='" . sql_escape($user['UID']) . "'");
}

/**
 * Counts all forced active users.
 *
 */
function User_force_active_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `User` WHERE `force_active` = 1");
}

/**
 * Counts all active users.
 *
 */
function User_active_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `User` WHERE `Aktiv` = 1");
}

/**
 * Return Counts of Vouche of user.
 *
 */
function User_got_voucher_count() {
  return sql_select_single_cell("SELECT SUM(`got_voucher`) FROM `User`");
}

/**
 * Counts all arrived users.
 *
 */
function User_arrived_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `User` WHERE `Gekommen` = 1");
}

/**
 * Return Counts of T-Shirts
 *
 */
function User_tshirts_count() {
  return sql_select_single_cell("SELECT COUNT(*) FROM `User` WHERE `Tshirt` = 1");
}

/**
 * Returns all column names for sorting in an array.
 */
function User_sortable_columns() {
  return array(
      'Nick',
      'Name',
      'Vorname',
      'Alter',
      'DECT',
      'email',
      'Size',
      'Gekommen',
      'Aktiv',
      'force_active',
      'Tshirt',
      'lastLogIn'
  );
}

/**
 * Get all users, ordered by Nick by default or by given param.
 *
 * @param string $order_by
 */
function Users($order_by = 'Nick') {
  return sql_select("SELECT * FROM `User` ORDER BY `" . sql_escape($order_by) . "` ASC");
}

/**
 * Returns true if user is freeloader
 *
 * @param User $user
 */
function User_is_freeloader($user) {
  global $max_freeloadable_shifts, $user;

  return count(ShiftEntries_freeloaded_by_user($user)) >= $max_freeloadable_shifts;
}

/**
 * Returns all users that are not member of given angeltype.
 *
 * @param Angeltype $angeltype
 */
function Users_by_angeltype_inverted($angeltype) {
  return sql_select("
      SELECT `User`.*
      FROM `User`
      LEFT JOIN `UserAngelTypes` ON (`User`.`UID`=`UserAngelTypes`.`user_id` AND `angeltype_id`='" . sql_escape($angeltype['id']) . "')
      WHERE `UserAngelTypes`.`id` IS NULL
      ORDER BY `Nick`");
}

/**
 * Returns all members of given angeltype.
 *
 * @param Angeltype $angeltype
 */
function Users_by_angeltype($angeltype) {
  return sql_select("
      SELECT
      `User`.*,
      `UserAngelTypes`.`id` as `user_angeltype_id`,
      `UserAngelTypes`.`confirm_user_id`,
      `UserAngelTypes`.`coordinator`,
      `UserDriverLicenses`.*
      FROM `User`
      JOIN `UserAngelTypes` ON `User`.`UID`=`UserAngelTypes`.`user_id`
      LEFT JOIN `UserDriverLicenses` ON `User`.`UID`=`UserDriverLicenses`.`user_id`
      WHERE `UserAngelTypes`.`angeltype_id`='" . sql_escape($angeltype['id']) . "'
      ORDER BY `Nick`");
}

/**
 * Returns User id array
 */
function User_ids() {
  return sql_select("SELECT `UID` FROM `User`");
}

/**
 * Strip unwanted characters from a users nick.
 *
 * @param string $nick
 */
function User_validate_Nick($nick) {
  return preg_replace("/([^a-z0-9üöäß. _+*-]{1,})/ui", '', $nick);
}

/**
 * Returns user by id.
 *
 * @param $id UID
 */
function User($id) {
  $user_source = sql_select("SELECT * FROM `User` WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
  if ($user_source === false)
    return false;
  if (count($user_source) > 0)
    return $user_source[0];
  return null;
}

/**
 * TODO: Merge into normal user function
 * Returns user by id (limit informations.
 *
 * @param $id UID
 */
function mUser_Limit($id) {
  $user_source = sql_select("SELECT `UID`, `Nick`, `Name`, `Vorname`, `Telefon`, `DECT`, `Handy`, `email`, `jabber` FROM `User` WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
  if ($user_source === false)
    return false;
  if (count($user_source) > 0)
    return $user_source[0];
  return null;
}

/**
 * Returns User by api_key.
 *
 * @param string $api_key
 *          User api key
 * @return Matching user, null or false on error
 */
function User_by_api_key($api_key) {
  $user = sql_select("SELECT * FROM `User` WHERE `api_key`='" . sql_escape($api_key) . "' LIMIT 1");
  if ($user === false)
    return false;
  if (count($user) == 0)
    return null;
  return $user[0];
}

/**
 * Returns User by email.
 *
 * @param string $email
 * @return Matching user, null or false on error
 */
function User_by_email($email) {
  $user = sql_select("SELECT * FROM `User` WHERE `email`='" . sql_escape($email) . "' LIMIT 1");
  if ($user === false)
    return false;
  if (count($user) == 0)
    return null;
  return $user[0];
}

/**
 * Returns User by password token.
 *
 * @param string $token
 * @return Matching user, null or false on error
 */
function User_by_password_recovery_token($token) {
  $user = sql_select("SELECT * FROM `User` WHERE `password_recovery_token`='" . sql_escape($token) . "' LIMIT 1");
  if ($user === false)
    return false;
  if (count($user) == 0)
    return null;
  return $user[0];
}

/**
 * Generates a new api key for given user.
 *
 * @param User $user
 */
function User_reset_api_key(&$user, $log = true) {
  $user['api_key'] = md5($user['Nick'] . time() . rand());
  $result = sql_query("UPDATE `User` SET `api_key`='" . sql_escape($user['api_key']) . "' WHERE `UID`='" . sql_escape($user['UID']) . "' LIMIT 1");
  if ($result === false)
    return false;
  if ($log)
    engelsystem_log(sprintf("API key resetted (%s).", User_Nick_render($user)));
}

/**
 * Generates a new password recovery token for given user.
 *
 * @param User $user
 */
function User_generate_password_recovery_token(&$user) {
  $user['password_recovery_token'] = md5($user['Nick'] . time() . rand());
  $result = sql_query("UPDATE `User` SET `password_recovery_token`='" . sql_escape($user['password_recovery_token']) . "' WHERE `UID`='" . sql_escape($user['UID']) . "' LIMIT 1");
  if ($result === false)
    return false;
  engelsystem_log("Password recovery for " . User_Nick_render($user) . " started.");
  return $user['password_recovery_token'];
}

/**
 * Returns Users elegible for Vouchers.
 *
 * @param $user Users
 */
function User_get_eligable_voucher_count(&$user) {
  global $voucher_settings;

	$shifts_done = count(ShiftEntries_finished_by_user($user));

	$earned_vouchers = $user['got_voucher'] - $voucher_settings['initial_vouchers'];
	$elegible_vouchers = $shifts_done / $voucher_settings['shifts_per_voucher'] - $earned_vouchers;
	if ( $elegible_vouchers < 0) {
		return 0;
	}

	return $elegible_vouchers;
}

/**
 * Return AngelType of Shifts.
 *
 * @param $sid ID of Shifts
 */
function shift_needed_angeltypes($sid) {
  return sql_select("SELECT DISTINCT `AngelTypes`.* FROM `ShiftEntry` JOIN `AngelTypes` ON `ShiftEntry`.`TID`=`AngelTypes`.`id` WHERE `ShiftEntry`.`SID`='" . sql_escape($sid) . "'  ORDER BY `AngelTypes`.`name`");
}

/**
 * Return Needed AngelType of Shifts.
 *
 * @param $sid ID of Shifts
 * @param $needed_angeltype_id of Shifts
 */
function needed_angeltype_by_shift($sid, $needed_angeltype_id) {
  return sql_select("
      SELECT `ShiftEntry`.`freeloaded`, `User`.*
      FROM `ShiftEntry`
      JOIN `User` ON `ShiftEntry`.`UID`=`User`.`UID`
      WHERE `ShiftEntry`.`SID`='" . sql_escape($sid) . "'
      AND `ShiftEntry`.`TID`='" . sql_escape($needed_angeltype_id) . "'");
}

/**
 * Update User Gekommen = 0
 *
 * @param $id ID of Users
 */
function User_update_unset_Gokemon($id) {
  return sql_query("UPDATE `User` SET `Gekommen`=0, `arrival_date` = NULL WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Update User Gekommen = 1
 *
 * @param $id ID of Users
 */
function User_update_set_Gokemon($id) {
return sql_query("UPDATE `User` SET `Gekommen`=1, `arrival_date`='" . time() . "' WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Update active user Tshirt
 *
 */
function User_update_activ_tshirt() {
  return sql_query("UPDATE `User` SET `Aktiv` = 0 WHERE `Tshirt` = 0");
}

function User_select_set_active() {
   return sql_select("
          SELECT `User`.*, COUNT(`ShiftEntry`.`id`) as `shift_count`, ${shift_sum_formula} as `shift_length`
          FROM `User`
          LEFT JOIN `ShiftEntry` ON `User`.`UID` = `ShiftEntry`.`UID`
          LEFT JOIN `Shifts` ON `ShiftEntry`.`SID` = `Shifts`.`SID`
          WHERE `User`.`Gekommen` = 1 AND `User`.`force_active`=0
          GROUP BY `User`.`UID`
          ORDER BY `force_active` DESC, `shift_length` DESC" . $limit);
}

/**
 * Return Active User by ID
 *
 * @param $uid ID of Users
 */
function User_set_active($uid) {
  return sql_query("UPDATE `User` SET `Aktiv` = 1 WHERE `UID`='" . sql_escape($uid) . "'");
}

/**
 * Return force active User
 *
 */
function User_actice_force_active() {
  return sql_query("UPDATE `User` SET `Aktiv`=1 WHERE `force_active`=TRUE");
}

/**
 * Update User to active
 *
 * @param $id ID of Users
 */
function User_update_active($id) {
  return sql_query("UPDATE `User` SET `Aktiv`=1 WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Update User to inactive
 *
 * @param $id ID of Users
 */
function User_update_inactive($id) {
  return sql_query("UPDATE `User` SET `Aktiv`=0 WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Update User Tshirts
 *
 * @param $id ID of Users
 */
function User_update_tshirt($id) {
  return sql_query("UPDATE `User` SET `Tshirt`=1 WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Update User for no Tshirt
 *
 * @param $id ID of Users
 */
function User_update_not_tshirt($id) {
  return sql_query("UPDATE `User` SET `Tshirt`=0 WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

function User_select_not_tshirt($shift_sum_formula, $show_all_shifts, $limit) {
  return  sql_select("
      SELECT `User`.*, COUNT(`ShiftEntry`.`id`) as `shift_count`, ${shift_sum_formula} as `shift_length`
      FROM `User` LEFT JOIN `ShiftEntry` ON `User`.`UID` = `ShiftEntry`.`UID`
      LEFT JOIN `Shifts` ON `ShiftEntry`.`SID` = `Shifts`.`SID`
      WHERE `User`.`Gekommen` = 1
      " . ($show_all_shifts ? "" : "AND (`Shifts`.`end` < " . time() . " OR `Shifts`.`end` IS NULL)") . "
      GROUP BY `User`.`UID`
      ORDER BY `force_active` DESC, `shift_length` DESC" . $limit);
}

/**
 * Return T-Shirt size of Users with Gekommen = 1
 *
 * @param $size Size of Users
 */
function Shirt_statistics_needed($size) {
  return sql_select_single_cell("SELECT count(*) FROM `User` WHERE `Size`='" . sql_escape($size) . "' AND `Gekommen`=1");
}

/**
 * Return T-Shirt size of Users with Gekommen = 0
 *
 * @param $size Size of Users
 */
function Shirt_statistics_given($size) {
  return sql_select_single_cell("SELECT count(*) FROM `User` WHERE `Size`='" . sql_escape($size) . "' AND `Tshirt`=1");
}

/**
 * Select Free Users
 *
 */
function User_select_free($angeltypesearch) {
  return sql_select("
      SELECT `User`.*
      FROM `User`
      $angeltypesearch
      LEFT JOIN `ShiftEntry` ON `User`.`UID` = `ShiftEntry`.`UID`
      LEFT JOIN `Shifts` ON (`ShiftEntry`.`SID` = `Shifts`.`SID` AND `Shifts`.`start` < '" . sql_escape(time()) . "' AND `Shifts`.`end` > '" . sql_escape(time()) . "')
      WHERE `User`.`Gekommen` = 1 AND `Shifts`.`SID` IS NULL
      GROUP BY `User`.`UID`
      ORDER BY `Nick`");
}

/**
 * Return User by Nick
 *
 * @param $nick Nick of User
 */
function User_select_nick($nick) {
  return sql_num_query("SELECT * FROM `User` WHERE `Nick`='" . sql_escape($nick) . "' LIMIT 1");
}

/**
 * Return User by email
 *
 * @param $mail email of User
 */
function User_select_mail($mail) {
  return sql_num_query("SELECT * FROM `User` WHERE `email`='" . sql_escape($mail) . "' LIMIT 1");
}

/**
 * Insert into User
 *
 * @param $nick Nick of User
 * @param $prename Vorname of User
 * @param $lastname Name of User
 * @param $age Alter of User
 * @param $tel Telefon of User
 * @param $dect DECT of User
 * @param $mobile Handy of User
 * @param $mail email of User
 * @param $email_shiftinfo email_shiftinfo of User
 * @param $jabber jabber of User
 * @param $tshirt_size Size of User
 * @param $password_hash Passwort of User
 * @param $comment kommentar of User
 * @param $hometown Hometown of User
 * @param $twitter twitter of User
 * @param $facebook facebook of User
 * @param $github githb of User
 * @param $organization organization of User
 * @param $organization_web organization_web of User
 * @param $timezone timezone of User
 * @param $planned_arrival_date planned_arrival_date of User
 */
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

/**
 * Update User
 *
 * @param $enick Nick of User
 * @param $eprename Vorname of User
 * @param $elastname Name of User
 * @param $eage Alter of User
 * @param $etel Telefon of User
 * @param $edect DECT of User
 * @param $emobile Handy of User
 * @param $email email of User
 * @param $eemail_shiftinfo email_shiftinfo of User
 * @param $ejabber jabber of User
 * @param $etshirt_size Size of User
 * @param $epassword_hash Passwort of User
 * @param $ecomment kommentar of User
 * @param $ehometown Hometown of User
 * @param $id ID of User
 */
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

/**
 * Return User by Nick
 *
 * @param $nick Nick of User
 */
function select_user_by_nick($nick) {
  return sql_select("SELECT * FROM `User` WHERE `Nick`='" . sql_escape($nick) . "'");
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

/**
 * Return User by Nick
 *
 * @param $nick Nick of User
 */
function count_user_by_nick($nick) {
return sql_num_query("SELECT * FROM `User` WHERE `Nick`='" . sql_escape($nick) . "' LIMIT 1");
}

function count_user_by_email($mail) {
return sql_num_query("SELECT * FROM `User` WHERE `email`='" . sql_escape($mail) . "' LIMIT 1");
}

/**
 * Return count of Users
 *
 */
function usercount() {
  return sql_select("SELECT count(*) as `user_count` FROM `User`");
}

/**
 * Return count of arrived Users
 *
 */
function user_count_arrived() {
  return sql_select("SELECT count(*) as `user_count` FROM `User` WHERE `Gekommen`=1");
}

function counts_user_by_ids($user_id) {
  return sql_num_query("SELECT * FROM `User` WHERE `UID`='" . sql_escape($user_id) . "' LIMIT 1");
}

/**
 * Update User details
 *
 * @param $nick Nick of User
 * @param $prename Vorname of User
 * @param $lastname Name of User
 * @param $age Alter of User
 * @param $tel Telefon of User
 * @param $dect DECT of User
 * @param $mobile Handy of User
 * @param $mail email of User
 * @param $email_shiftinfo email_shiftinfo of User
 * @param $jabber jabber of User
 * @param $tshirt_size Size of User
 * @param $hometown Hometown of User
 * @param $timezone timezone of User
 * @param $planned_arrival_date planned_arrival_date of User
 * @param $uid ID of Users
 */
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

/**
 * Update User Social network
 *
 * @param $twitter twitter of User
 * @param $facebook facebook of User
 * @param $github githb of User
 * @param $uid ID of User
 */
function update_user_sn($twitter, $facebook, $github, $uid) {
  return   sql_query("
      UPDATE `User` SET
      `twitter`='" . sql_escape($twitter) . "',
      `facebook`='" . sql_escape($facebook) . "',
      `github`='" . sql_escape($github) . "',
      WHERE `UID`='" . sql_escape($uid) . "'");
}

/**
 * Update User Organization
 *
 * @param $organization organization of User
 * @param $organization_web organization_web of User
 * @param $uid ID of User
 */
function update_user_org($organization, $organization_web, $uid) {
  return sql_query("
    UPDATE `User` SET
    `organization`='" . sql_escape($organization) . "',
    `organization_web`='" . sql_escape($organization_web) . "',
     WHERE `UID`='" . sql_escape($uid) . "'");
}

/**
 * Update User Native and other language
 *
 * @param $native_lang native_lang of User
 * @param $other_langs other_langs of User
 * @param $uid ID of User
 */
function update_user_langs($native_lang, $other_langs, $uid) {
  return sql_query("
    UPDATE `User` SET
    `native_lang`='" . sql_escape($native_lang) . "',
    `other_langs`='" . sql_escape($other_langs) . "',
     WHERE `UID`='" . sql_escape($uid) . "'");
}

/**
 * Update User Theme
 *
 * @param $selected_theme Color of User
 * @param $uid ID of User
 */
function update_theme($selected_theme, $uid) {
  return sql_query("UPDATE `User` SET `color`='" . sql_escape($selected_theme) . "' WHERE `UID`='" . sql_escape($uid) . "'");
}

/**
 * Update User System language
 *
 * @param $selected_language Sprache of User
 * @param $uid ID of User
 */
function update_sys_lang($selected_language, $uid) {
  return sql_query("UPDATE `User` SET `Sprache`='" . sql_escape($selected_language) . "' WHERE `UID`='" . sql_escape($uid) . "'");
}

/**
 * Return User by ID
 *
 * @param $uid ID of User
 */
function count_users_by_id($id) {
  return sql_num_query("SELECT * FROM `User` WHERE `UID`='" . sql_escape($id) . "'");
}

function user_by_id($id) {
  return sql_select("SELECT * FROM `User` WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

/**
 * Update User Nick
 *
 * @param $username Nick of User
 * @param $uid ID of User
 */
function update_nick($username, $uid) {
  return sql_query("UPDATE `User` SET `Nick`='" . sql_escape($username) . "' WHERE `UID`='" . sql_escape($uid) . "'");
}

/**
 * Update User Email
 *
 * @param $email email of User
 * @param $uid ID of User
 */
function update_mail($email, $uid) {
  return sql_query("UPDATE `User` SET `email`='" . sql_escape($email) . "' WHERE `UID`='" . sql_escape($uid) . "'");
}
?>
