<?php
function select_id_name_Angeltypes() {
  return sql_select("SELECT `id`, `name` FROM `AngelTypes` ORDER BY `name`");
}

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
?>
