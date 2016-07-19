<?php

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

function User_set_active($uid) {
	return sql_query("UPDATE `User` SET `Aktiv` = 1 WHERE `UID`='" . sql_escape($uid) . "'");
}

function User_actice_force_active() {
  return sql_query("UPDATE `User` SET `Aktiv`=1 WHERE `force_active`=TRUE");
}


function User_update_active($id) {
	return sql_query("UPDATE `User` SET `Aktiv`=1 WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");

}
function User_update_inactive($id) {
	return sql_query("UPDATE `User` SET `Aktiv`=0 WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");

}

function User_update_tshirt($id) {
	return sql_query("UPDATE `User` SET `Tshirt`=1 WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

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

function Shirt_statistics_needed($size) {
	return sql_select_single_cell("SELECT count(*) FROM `User` WHERE `Size`='" . sql_escape($size) . "' AND `Gekommen`=1");
}

function Shirt_statistics_given($size) {
	return sql_select_single_cell("SELECT count(*) FROM `User` WHERE `Size`='" . sql_escape($size) . "' AND `Tshirt`=1");
}
?>
