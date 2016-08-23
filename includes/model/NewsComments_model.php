<?php

/**
 * Insert NewsComments
 *
 * @param nid Reference ID
 * @param text text NewsComments
 * @param uid ID NewsComments
 */

function insert_news($nid, $text, $uid) {
  return sql_query("INSERT INTO `NewsComments` (`Refid`, `Datum`, `Text`, `UID`) VALUES ('" . sql_escape($nid) . "', '" . date("Y-m-d H:i:s") . "', '" . sql_escape($text) . "', '" . sql_escape($uid) . "')");
}

/**
 * Select NewsComments
 *
 * @param nid Reference ID
 */
function select_newscomments_by_id($nid) {
  return sql_select("SELECT * FROM `NewsComments` WHERE `Refid`='" . sql_escape($nid) . "' ORDER BY 'ID'");
}

?>
