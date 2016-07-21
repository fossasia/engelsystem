<?php

function news_select($disp_news) {
  return sql_select("SELECT * FROM `News` " . (empty($_REQUEST['meetings'])? '' : 'WHERE `Treffen` = 1 ') . "ORDER BY `ID` DESC LIMIT " . sql_escape($disp_news));
}

?>
