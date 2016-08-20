<?php
function install_title() {
  return _("Install Engelsystem");
}

function install_admin() {

  $ok = false;
  $msg = "";
  $username = "";
  $mail = "";
  $settings = array();
  if (test_import())
    $settings = Settings();
  if ($settings == false) {
    $no_migrated = 0;
  }
  else {
    $no_migrated = $settings[0]['table_migrated'];
  }
  if ($no_migrated == 1) {
    redirect(page_link_to('login'));
  }
  else {
    // importing tables
    $val = import_tables();
    if (isset($_REQUEST['install'])) {
      $ok = true;
      if (isset($_REQUEST['username']) && strlen(strip_request_item('username')) > 1) {
        $username = strip_request_item('username');
      } else {
        $ok = false;
        $msg = error(sprintf(_("Your username is too short (min. 2 characters).")), true);
      }

      if (isset($_REQUEST['password']) && strlen($_REQUEST['password']) >= MIN_PASSWORD_LENGTH) {
        if ($_REQUEST['password'] != $_REQUEST['password2']) {
          $ok = false;
          $msg = error(_("Your passwords don't match."), true);
        }
      } else {
        $ok = false;
        $msg = error(sprintf(_("Your password is too short (please use at least %s characters)."), MIN_PASSWORD_LENGTH), true);
      }

      if (isset($_REQUEST['mail']) && strlen(strip_request_item('mail')) && preg_match("/^[a-z0-9._+-]{1,64}@(?:[a-z0-9-]{1,63}\.){1,125}[a-z]{2,63}$/", $_REQUEST['mail']) > 0) {
        $mail = strip_request_item('mail');
        if (! check_email($mail)) {
          $ok = false;
          $msg = error(_("E-mail address is not correct."), true);
        }
      } else {
        $ok = false;
        $msg = error(_("Please enter your correct e-mail (in lowercase)."), true);
      }
  }

  if ($ok) {
    $uid = 1;
    if ($val) {
      $no_migrated = 1;
      insert_table_migrated($no_migrated);
      update_nick($username, $uid);
      update_mail($mail, $uid);
      set_password($uid, $_REQUEST['password']);
      success(_("Files imported successfully to database"));
      success(_("Installation successful."));
      redirect(page_link_to('login'));
    }
    else {
      error(_("Installation Failed"));
      redirect(page_link_to('install'));
    }
  }
  return page_with_title(install_title(), array(
      $msg,
      div('well well-sm text-center', [
       ('Welcome')
      ]).div('row', array(
            div('col-md-12', array(
                form(array(
                  form_info('', _("Welcome to the famous five-minute Engelsystem installation process! Just fill in the information below and you’ll be on your way to volunteer management application for events with admin rights.")),
                ))
            ))
        )).div('well well-sm text-center', [
              _('Information Needed')
          ]).div('row', array(
            div('col-md-12', array(
                form(array(
                  form_info('', _("Please provide the following information. Don’t worry, you can always change these settings later. All fields are compulsory")),
                  form_text('username', _("Enter Admin Username"), $username),
                  form_password('password', _("Enter New Password")),
                  form_password('password2', _("Confirm Password")),
                  form_email('mail', _("Enter E-Mail"), $mail),
                  form_submit('install', _("Install Engelsystem"))
                ))
            ))
        ))
    ));
  }
}
?>
