<?php
function user_news_comments_title() {
  return _("News comments");
}

function news_title() {
  return _("News");
}

function meetings_title() {
  return _("Meetings");
}

function user_meetings() {
  global $DISPLAY_NEWS, $privileges, $user;

  $html = '<div class="col-md-12"><h1>' . meetings_title() . '</h1>' . msg();

  if (isset($_REQUEST['page']) && preg_match("/^[0-9]{1,}$/", $_REQUEST['page']))
    $page = $_REQUEST['page'];
  else
    $page = 0;

  $news = sql_select("SELECT * FROM `News` WHERE `Treffen`=1 ORDER BY `Datum` DESC LIMIT " . sql_escape($page * $DISPLAY_NEWS) . ", " . sql_escape($DISPLAY_NEWS));
  foreach ($news as $entry)
    $html .= display_news($entry);

  $dis_rows = ceil(sql_num_query("SELECT * FROM `News`") / $DISPLAY_NEWS);
  $html .= '<div class="text-center">' . '<ul class="pagination">';
  for ($i = 0; $i < $dis_rows; $i ++) {
    if (isset($_REQUEST['page']) && $i == $_REQUEST['page'])
      $html .= '<li class="active">';
    elseif (! isset($_REQUEST['page']) && $i == 0)
      $html .= '<li class="active">';
    else
      $html .= '<li>';
    $html .= '<a href="' . page_link_to("user_meetings") . '&page=' . $i . '">' . ($i + 1) . '</a></li>';
  }
  $html .= '</ul></div></div>';

  return $html;
}

function display_news($news) {
  global $privileges, $p;

  $html = '';
  $html .= '<div class="panel' . ($news['Treffen'] == 1 ? ' panel-info' : ' panel-default') . '">';
  $html .= '<div class="panel-heading">';
  $html .= '<h3 class="panel-title">' . ($news['Treffen'] == 1 ? '[Meeting] ' : '') . ReplaceSmilies($news['Betreff']) . '</h3>';
  $html .= '</div>';
  $html .= '<div class="panel-body">' . ReplaceSmilies(nl2br($news['Text'])) . '</div>';

  $html .= '<div class="panel-footer text-muted">';
  if (in_array("admin_news", $privileges))
    $html .= '<div class="pull-right">' . button_glyph(page_link_to("admin_news") . '&action=edit&id=' . $news['ID'], 'edit', 'btn-xs') . '</div>';

  $html .= '<span class="glyphicon glyphicon-time"></span> ' . date("Y-m-d H:i", $news['Datum']) . '&emsp;';

  $user_source = User($news['UID']);
  if ($user_source === false)
    engelsystem_error(_("Unable to load user."));

  $html .= User_Nick_render($user_source);
  if ($p != "news_comments")
    $html .= '&emsp;<a href="' . page_link_to("news_comments") . '&nid=' . $news['ID'] . '"><span class="glyphicon glyphicon-comment"></span> ' . _("Comments") . ' &raquo;</a> <span class="badge">' . sql_num_query("SELECT * FROM `NewsComments` WHERE `Refid`='" . sql_escape($news['ID']) . "'") . '</span>';
  $html .= '</div>';
  $html .= '</div>';
  return $html;
}

function user_news_comments() {
  global $user;

  $html = '<div class="col-md-12"><h1>' . user_news_comments_title() . '</h1>';
  if (isset($_REQUEST["nid"]) && preg_match("/^[0-9]{1,}$/", $_REQUEST['nid']) && sql_num_query("SELECT * FROM `News` WHERE `ID`='" . sql_escape($_REQUEST['nid']) . "' LIMIT 1") > 0) {
    $nid = $_REQUEST["nid"];
    list($news) = sql_select("SELECT * FROM `News` WHERE `ID`='" . sql_escape($nid) . "' LIMIT 1");
    if (isset($_REQUEST["text"])) {
      $text = preg_replace("/([^\p{L}\p{P}\p{Z}\p{N}\n]{1,})/ui", '', strip_tags($_REQUEST['text']));
      sql_query("INSERT INTO `NewsComments` (`Refid`, `Datum`, `Text`, `UID`) VALUES ('" . sql_escape($nid) . "', '" . date("Y-m-d H:i:s") . "', '" . sql_escape($text) . "', '" . sql_escape($user["UID"]) . "')");
      engelsystem_log("Created news_comment: " . $text);
      $html .= success(_("Entry saved."), true);
    }

    $html .= display_news($news);

    $comments = sql_select("SELECT * FROM `NewsComments` WHERE `Refid`='" . sql_escape($nid) . "' ORDER BY 'ID'");
    foreach ($comments as $comment) {
      $user_source = User($comment['UID']);
      if ($user_source === false)
        engelsystem_error(_("Unable to load user."));

      $html .= '<div class="panel panel-default">';
      $html .= '<div class="panel-body">' . nl2br($comment['Text']) . '</div>';
      $html .= '<div class="panel-footer text-muted">';
      $html .= '<span class="glyphicon glyphicon-time"></span> ' . $comment['Datum'] . '&emsp;';
      $html .= User_Nick_render($user_source);
      $html .= '</div>';
      $html .= '</div>';
    }

    $html .= '<hr /><h2>' . _("New Comment:") . '</h2>';
    $html .= form(array(
        form_textarea('text', _("Message"), ''),
        form_submit('submit', _("Save"))
    ), page_link_to('news_comments') . '&nid=' . $news['ID']);

  } else {
    $html .= _("Invalid request.");
  }

  return $html . '</div>';
}

function user_news() {
  global $DISPLAY_NEWS, $privileges, $user;

  $html = '<div class="col-md-12"><h1>' . news_title() . '</h1>' . msg();

  if (isset($_POST["text"]) && isset($_POST["betreff"]) && in_array("admin_news", $privileges)) {
    if (! isset($_POST["treffen"]) || ! in_array("admin_news", $privileges))
      $_POST["treffen"] = 0;
    sql_query("INSERT INTO `News` (`Datum`, `Betreff`, `Text`, `UID`, `Treffen`) " . "VALUES ('" . sql_escape(time()) . "', '" . sql_escape($_POST["betreff"]) . "', '" . sql_escape($_POST["text"]) . "', '" . sql_escape($user['UID']) . "', '" . sql_escape($_POST["treffen"]) . "');");
    engelsystem_log("Created news: " . $_POST["betreff"] . ", treffen: " . $_POST["treffen"]);
    success(_("Entry saved."));
    redirect(page_link_to('news'));
  }

  if (isset($_REQUEST['page']) && preg_match("/^[0-9]{1,}$/", $_REQUEST['page']))
    $page = $_REQUEST['page'];
  else
    $page = 0;

  $news = sql_select("SELECT * FROM `News` ORDER BY `Datum` DESC LIMIT " . sql_escape($page * $DISPLAY_NEWS) . ", " . sql_escape($DISPLAY_NEWS));
  foreach ($news as $entry)
    $html .= display_news($entry);

  $dis_rows = ceil(sql_num_query("SELECT * FROM `News`") / $DISPLAY_NEWS);
  $html .= '<div class="text-center">' . '<ul class="pagination">';
  for ($i = 0; $i < $dis_rows; $i ++) {
    if (isset($_REQUEST['page']) && $i == $_REQUEST['page'])
      $html .= '<li class="active">';
    elseif (! isset($_REQUEST['page']) && $i == 0)
      $html .= '<li class="active">';
    else
      $html .= '<li>';
    $html .= '<a href="' . page_link_to("news") . '&page=' . $i . '">' . ($i + 1) . '</a></li>';
  }
  $html .= '</ul></div>';

  if (in_array("admin_news", $privileges)) {
    $html .= '<hr />';
    $html .= '<h2>' . _("Create news:") . '</h2>';

    $html .= form(array(
        form_text('betreff', _("Subject"), ''),
        form_textarea('text', _("Message"), ''),
        form_checkbox('treffen', _("Meeting"), false, 1),
        form_submit('submit', _("Save"))
    ));
  }
  return $html . '</div>';
}
?>
