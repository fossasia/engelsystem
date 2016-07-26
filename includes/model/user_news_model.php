<?php

function select_news_treffen($page, $disp_news) {
  return sql_select("SELECT * FROM `News` WHERE `Treffen`=1 ORDER BY `Datum` DESC LIMIT " . sql_escape($page * $disp_news) . ", " . sql_escape($disp_news));
}

function select_news() {
  return sql_num_query("SELECT * FROM `News`");
}

function count_news_by_id($nid) {
  return sql_num_query("SELECT * FROM `News` WHERE `ID`='" . sql_escape($nid) . "' LIMIT 1");
}

function select_news_by_id($nid) {
  return sql_select("SELECT * FROM `News` WHERE `ID`='" . sql_escape($nid) . "' LIMIT 1");
}

function insert_news($nid, $text, $uid) {
  return sql_query("INSERT INTO `NewsComments` (`Refid`, `Datum`, `Text`, `UID`) VALUES ('" . sql_escape($nid) . "', '" . date("Y-m-d H:i:s") . "', '" . sql_escape($text) . "', '" . sql_escape($uid) . "')");
}

function select_newscomments_by_id($nid) {
  return sql_select("SELECT * FROM `NewsComments` WHERE `Refid`='" . sql_escape($nid) . "' ORDER BY 'ID'");
}

function insert_news_val($betreff, $text, $uid, $treffen) {
  return sql_query("INSERT INTO `News` (`Datum`, `Betreff`, `Text`, `UID`, `Treffen`) " . "VALUES ('" . sql_escape(time()) . "', '" . sql_escape($betreff) . "', '" . sql_escape($text) . "', '" . sql_escape($uid) . "', '" . sql_escape($treffen) . "');");
}

function select_news_by_date($page, $disp_news) {
  return sql_select("SELECT * FROM `News` ORDER BY `Datum` DESC LIMIT " . sql_escape($page * $disp_news) . ", " . sql_escape($disp_news));
}

?>
