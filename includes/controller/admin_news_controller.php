<?php
function admin_news() {
  global $user;

  if (! isset($_GET["action"])) {
    redirect(page_link_to("news"));
  } else {
    $html = '<div class="col-md-12"><h1>' . _("Edit news entry") . '</h1>' . msg();
    if (isset($_REQUEST['id']) && preg_match("/^[0-9]{1,11}$/", $_REQUEST['id']))
      $id = $_REQUEST['id'];
    else
      return error("Incomplete call, missing News ID.", true);

    $news = News_by_id($id);
    if (count($news) > 0) {
      switch ($_REQUEST["action"]) {
        default:
          redirect(page_link_to('news'));
        case 'edit':
          list($news) = $news;

          $user_source = User($news['UID']);
          if ($user_source === false)
            engelsystem_error("Unable to load user.");

          $html .= form(array(
              form_info(_("Date"), date("Y-m-d H:i", $news['Datum'])),
              form_info(_("Author"), User_Nick_render($user_source)),
              form_text('eBetreff', _("Subject"), $news['Betreff']),
              form_textarea('eText', _("Message"), $news['Text']),
              form_checkbox('eTreffen', _("Meeting"), $news['Treffen'] == 1, 1),
              form_submit('submit', _("Save"))
          ), page_link_to('admin_news_controller&action=save&id=' . $id));

          $html .= '<a class="btn btn-danger" href="' . page_link_to('admin_news_controller&action=delete&id=' . $id) . '"><span class="glyphicon glyphicon-trash"></span> ' . _("Delete") . '</a>';
          break;

        case 'save':
          list($news) = $news;
          News_update($_POST["eBetreff"], $_POST["eText"], $_POST["eTreffen"], $id, $user);
          engelsystem_log("News updated: " . $_POST["eBetreff"]);
          success(_("News entry updated."));
          redirect(page_link_to("news"));
          break;

        case 'delete':
          list($news) = $news;
          delete_by_id($id);
          engelsystem_log("News deleted: " . $news['Betreff']);
          success(_("News entry deleted."));
          redirect(page_link_to("news"));
          break;
      }
    } else
      return error("No News found.", true);
  }
  return $html . '</div>';
}
?>
