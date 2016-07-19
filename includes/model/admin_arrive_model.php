<?php

function User_update_unset_Gokemon($id) {
  return sql_query("UPDATE `User` SET `Gekommen`=0, `arrival_date` = NULL WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}

function User_update_set_Gokemon($id) {
return sql_query("UPDATE `User` SET `Gekommen`=1, `arrival_date`='" . time() . "' WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
}
?>
