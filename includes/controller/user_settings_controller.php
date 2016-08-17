<?php
function settings_title() {
  return _("Settings");
}
function user_settings() {
  global $enable_tshirt_size, $tshirt_sizes, $themes, $locales;
  global $user;
  $msg = "";
  $nick = $user['Nick'];
  $lastname = $user['Name'];
  $prename = $user['Vorname'];
  $age = $user['Alter'];
  $tel = $user['Telefon'];
  $dect = $user['DECT'];
  $mobile = $user['Handy'];
  $mail = $user['email'];
  $email_shiftinfo = $user['email_shiftinfo'];
  $jabber = $user['jabber'];
  $hometown = $user['Hometown'];
  $tshirt_size = $user['Size'];
  $password_hash = "";
  $selected_theme = $user['color'];
  $selected_language = $user['Sprache'];
  $planned_arrival_date = $user['planned_arrival_date'];
  $planned_departure_date = $user['planned_departure_date'];
  $timezone = $user['timezone'];
  $timezone_identifiers = DateTimeZone::listIdentifiers();
  $message_source = welcome_msg();
  $display_message = $message_source[0]['display_msg'];
  $organization = $user['organization'];
  $organization_web = $user['organization_web'];
  $facebook = $user['facebook'];
  $twitter = $user['twitter'];
  $github = $user['github'];
  $current_city = $user['current_city'];
  $native_lang = $user['native_lang'];
  $other_langs = $user['other_langs'];
  $auto_update = false;
  if (isset($_REQUEST['submit'])) {
    $ok = true;
    if (isset($_REQUEST['mail']) && strlen(strip_request_item('mail')) > 0) {
      $mail = strip_request_item('mail');
      if (! check_email($mail)) {
        $ok = false;
        $msg .= error(_("E-mail address is not correct."), true);
      }
    } else {
      $ok = false;
      $msg .= error(_("Please enter your e-mail."), true);
    }
    $email_shiftinfo = isset($_REQUEST['email_shiftinfo']);
    if (isset($_REQUEST['jabber']) && strlen(strip_request_item('jabber')) > 0) {
      $jabber = strip_request_item('jabber');
      if (! check_email($jabber)) {
        $ok = false;
        $msg .= error(_("Please check your jabber account information."), true);
      }
    }
    if (isset($_REQUEST['tshirt_size']) && isset($tshirt_sizes[$_REQUEST['tshirt_size']]))
      $tshirt_size = $_REQUEST['tshirt_size'];
    elseif ($enable_tshirt_size) {
      $ok = false;
    }
    if (isset($_REQUEST['planned_arrival_date']) && DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_arrival_date']))) {
      $planned_arrival_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_arrival_date']))->getTimestamp();
    } else {
      $ok = false;
      $msg .= error(_("Please enter your planned date of arrival."), true);
    }
    if (isset($_REQUEST['planned_departure_date']) && $_REQUEST['planned_departure_date'] != '') {
      if (DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_departure_date']))) {
        $planned_departure_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_departure_date']))->getTimestamp();
      } else {
        $ok = false;
        $msg .= error(_("Please enter your planned date of departure."), true);
      }
    } else
      $planned_departure_date = null;
      // Trivia
    if (isset($_REQUEST['lastname']))
      $lastname = strip_request_item('lastname');
    if (isset($_REQUEST['prename']))
      $prename = strip_request_item('prename');
    if (isset($_REQUEST['age']) && preg_match("/^[0-9]{0,4}$/", $_REQUEST['age']))
      $age = strip_request_item('age');
    if (isset($_REQUEST['tel']))
      $tel = strip_request_item('tel');
    if (isset($_REQUEST['dect']))
      $dect = strip_request_item('dect');
    if (isset($_REQUEST['mobile']))
      $mobile = strip_request_item('mobile');
    if (isset($_REQUEST['hometown']))
      $hometown = strip_request_item('hometown');
    if (isset($_REQUEST['timezone']))
      $timezone = strip_request_item('timezone');
    if ($ok) {
      update_user_details($nick, $prename, $lastname, $age, $tel, $dect, $mobile, $mail, $email_shiftinfo, $jabber, $tshirt_size, $hometown, $planned_arrival_date, $planned_departure_date, $timezone, $user['UID']);
      success(_("Settings saved."));
      redirect(page_link_to('user_settings'));
    }
  } elseif (isset($_REQUEST['submit_sn'])) {
      $ok = true;
      if (isset($_REQUEST['twitter']))
        $dect = strip_request_item('twitter');
      if (isset($_REQUEST['facebook']))
        $dect = strip_request_item('facebook');
      if (isset($_REQUEST['github']))
        $dect = strip_request_item('github');
      if ($ok) {
      update_user_sn($twitter, $facebook, $github, $user['UID']);
         success(_("Social Network Updates saved."));
     redirect(page_link_to('user_settings'));
    }
  }
  elseif (isset($_REQUEST['submit_org'])) {
      $ok = true;
      if (isset($_REQUEST['organization']))
        $dect = strip_request_item('organization');
      if (isset($_REQUEST['organization_web']))
        $dect = strip_request_item('organization_web');
       if ($ok) {
        update_user_org($organization, $organization_web, $user['UID']);
         success(_("Organization Updates saved."));
      redirect(page_link_to('user_settings'));
    }
  }
  elseif (isset($_REQUEST['submit_lang'])) {
    $ok = true;
    if (isset($_REQUEST['native_lang']) && strlen(strip_request_item('native_lang')) > 0) {
      $lastname = strip_request_item('native_lang');
    }
    else {
      $ok = false;
      $msg .= error(_("Please enter your Native Language."), true);
    }
    if (isset($_REQUEST['other_langs'])) {
      $other_langs = "";
      $langs = $_REQUEST['other_langs'];
      foreach($langs as $lang) {
        $other_langs .= $lang . ',';
      }
    }
    if ($ok) {
      update_user_langs($native_lang, $other_langs, $user['UID']);
        success(_("Languages Updates saved."));
      redirect(page_link_to('user_settings'));
    }
  }
  elseif (isset($_REQUEST['submit_password'])) {
    $ok = true;
    if (! isset($_REQUEST['password']) || ! verify_password($_REQUEST['password'], $user['Passwort'], $user['UID']))
      $msg .= error(_("-> not OK. Please try again."), true);
    elseif (strlen($_REQUEST['new_password']) < MIN_PASSWORD_LENGTH)
      $msg .= error(_("Your password is to short (please use at least 6 characters)."), true);
    elseif ($_REQUEST['new_password'] != $_REQUEST['new_password2'])
      $msg .= error(_("Your passwords don't match."), true);
    elseif (set_password($user['UID'], $_REQUEST['new_password']))
      success(_("Password saved."));
    else
      error(_("Failed setting password."));
    redirect(page_link_to('user_settings'));
  } elseif (isset($_REQUEST['submit_theme'])) {
    $ok = true;
    if (isset($_REQUEST['theme']) && isset($themes[$_REQUEST['theme']]))
      $selected_theme = $_REQUEST['theme'];
    else
      $ok = false;
    if ($ok) {
      update_theme($selected_theme, $user['UID']);
      success(_("Theme changed."));
      redirect(page_link_to('user_settings'));
    }
  } elseif (isset($_REQUEST['submit_language'])) {
    $ok = true;
    if (isset($_REQUEST['language']) && isset($locales[$_REQUEST['language']]))
      $selected_language = $_REQUEST['language'];
    else
      $ok = false;
    if ($ok) {
      supdate_sys_lang($selected_language, $user['UID']);
      $_SESSION['locale'] = $selected_language;
      success("Language changed.");
      redirect(page_link_to('user_settings'));
    }
  }elseif (isset($_REQUEST['submit_message'])){
      $ok=true;
      if(isset($_REQUEST['display_message']))
        $display_message=strip_request_item('display_message');
      else
        $ok = false;

      if ($ok){
        update_display_msg($display_message);
        success("Message Changed");
        redirect(page_link_to('user_settings'));
      }
  }
  elseif (isset($_REQUEST['update'])) {
    $ok = true;

    $online_ver = file_get_contents("https://raw.githubusercontent.com/fossasia/engelsystem/master/Version.txt");
    $current_ver = file_get_contents(" ./Version.txt");
    if (strcmp($current_ver, $online_ver) != 0) {
      echo shell_exec("sudo sh ./update.sh");
    }
    else {
      $ok = false;
      $msg .= error(_("The system is already Up-to date with the version on GitHub."), true);
    }
    if ($ok) {
      success(_("System Updated to the latest Version!"));
      redirect(page_link_to('user_settings'));
    }
  }
  elseif (isset($_REQUEST['autoupdate_check'])) {
    $ok = true;

    if (isset($_REQUEST['auto_update']))
      $auto_update = true;
    else {
      $ok = false;
      $auto_update = false;
      $msg .= error(_("Disabled the Automatic Updates."), true);
    }

    if ($ok) {
      autoupdater($auto_update);
      success("Enabled Automatic Updates!");
      redirect(page_link_to('user_settings'));
    }
  }

  if ($ok) {
      $_SESSION['uid'] = $login_user['UID'];
      $_SESSION['locale'] = $login_user['Sprache'];
  }
 // Admin settings page
if( $_SESSION['uid'] == 1){
  return page_with_title("Admin Settings", array(
      $msg,
      msg(),
      div('row', array(
          div('col-md-6', array(
              form(array(
                  form_info('', _("Here you can change your user details.")),
                  form_info(entry_required() . ' = ' . _("Entry required!")),
                  form_text('nick', _("Nick"), $nick, true),
                  form_text('lastname', _("Last name"), $lastname),
                  form_text('prename', _("First name"), $prename),
                  form_date('planned_arrival_date', _("Planned date of arrival") . ' ' . entry_required(), $planned_arrival_date, time()),
                  form_date('planned_departure_date', _("Planned date of departure"), $planned_departure_date, time()),
                  form_text('age', _("Age"), $age),
                  form_text('tel', _("Phone"), $tel),
                  form_text('dect', _("DECT"), $dect),
                  form_text('mobile', _("Mobile"), $mobile),
                  form_text('mail', _("E-Mail") . ' ' . entry_required(), $mail),
                  form_checkbox('email_shiftinfo', _("Please send me an email if my shifts change"), $email_shiftinfo),
                  form_text('jabber', _("Jabber"), $jabber),
                  form_text('hometown', _("Hometown"), $hometown),
                  $enable_tshirt_size ? form_select('tshirt_size', _("Shirt size"), $tshirt_sizes, $tshirt_size) : '',
                  form_select('timezone', _("Timezone") . ' ' . entry_required(), $timezone_identifiers , $timezone),
                  form_info('', _('Please visit the angeltypes page to manage your angeltypes.')),
                  form_submit('submit', _("Save"))
              ))
          )),
          div('col-md-6', array(
              form(array(
                  form_info(_("Here you can change your password.")),
                  form_password('password', _("Old password:")),
                  form_password('new_password', _("New password:")),
                  form_password('new_password2', _("Password confirmation:")),
                  form_submit('submit_password', _("Save"))
              )),
              form(array(
                  form_info(_("Here you can choose your color settings:")),
                  form_select('theme', _("Color settings:"), $themes, $selected_theme),
                  form_submit('submit_theme', _("Save"))
              )),
              form(array(
                  form_info(_("Here you can choose your language:")),
                  form_select('language', _("Language:"), $locales, $selected_language),
                  form_submit('submit_language', _("Save"))
              )),
              form(array(
                  form_info(_("Here you can write your display message for registration:")),
                  form_text('display_message', _("Message"), $display_message),
                  form_submit('submit_message', _("Save"))
              )),
              form(array(
                  form_info(_("Here you can set for Auto updates and check for updates:")),
                  form_checkbox('auto_update', _("Do you want to enable AutoUpdate for the system"), $auto_update),
                  form_info('', _("Checking the box will enable the autoupdate for the system")),
                  form_submit('autoupdate_check', _("Save")),
                  form_submit('update', _("Update System Now!"))
              )),
          ))
      ))
  ));
}
// User settings page
if( $_SESSION['uid'] > 1){
  return page_with_title("User Settings", array(
      $msg,
      msg(),
      div('row', array(
          div('col-md-6', array(
              form(array(
                  form_info('', _("Here you can change your user details.")),
                  form_info(entry_required() . ' = ' . _("Entry required!")),
                  form_text('nick', _("Nick"), $nick, true),
                  form_text('lastname', _("Last name"), $lastname),
                  form_text('prename', _("First name"), $prename),
                  form_date('planned_arrival_date', _("Planned date of arrival") . ' ' . entry_required(), $planned_arrival_date, time()),
                  form_date('planned_departure_date', _("Planned date of departure"), $planned_departure_date, time()),
                  form_text('age', _("Age"), $age),
                  form_text('tel', _("Phone"), $tel),
                  form_text('dect', _("DECT"), $dect),
                  form_text('mobile', _("Mobile"), $mobile),
                  form_text('mail', _("E-Mail") . ' ' . entry_required(), $mail),
                  form_checkbox('email_shiftinfo', _("Please send me an email if my shifts change"), $email_shiftinfo),
                  form_text('jabber', _("Jabber"), $jabber),
                  form_text('hometown', _("Hometown"), $hometown),
                  form_text('current_city', _("Current City") . ' ' . entry_required(), $current_city),
                  $enable_tshirt_size ? form_select('tshirt_size', _("Shirt size"), $tshirt_sizes, $tshirt_size) : '',
                  form_select('timezone', _("Timezone") . ' ' . entry_required(), $timezone_identifiers , $timezone),
                  form_info('', _('Please visit the angeltypes page to manage your angeltypes.')),
                  form_submit('submit', _("Save"))
              ))
          )),
          div('col-md-6', array(
              form(array(
                  form_info(_("Here you can change your password.")),
                  form_password('password', _("Old password:")),
                  form_password('new_password', _("New password:")),
                  form_password('new_password2', _("Password confirmation:")),
                  form_submit('submit_password', _("Save"))
              )),
              form(array(
                  form_info(_("Here you can change links to your social networks")),
                  form_text('facebook' , _("Facebook"), $facebook),
                  form_text('github' , _("Github"), $github),
                  form_text('twitter' , _("Twitter"), $twitter),
                  form_submit('submit_sn', _("Save"))
              )),
              form(array(
                  form_info(_("Here you can change Information about your Organization")),
                  form_text('organization' , _("Organisation Name (University or Company Name)"), $organization),
                  form_text('organization_web', _("Organization Website"), $organization_web),
                  form_submit('submit_org', _("Save"))
              )),
              form(array(
                  form_info(_("Here you can change your languages")),
                  form_select('native_lang', _("Native Language").' '. entry_required(), languages(), $native_lang, 'English'),
                  form_multiselect('other_langs', _("Other Languages"), languages(), $other_langs, 'English'),
                  form_submit('submit_lang', _("Save"))
              )),
              form(array(
                  form_info(_("Here you can choose your color settings:")),
                  form_select('theme', _("Color settings:"), $themes, $selected_theme),
                  form_submit('submit_theme', _("Save"))
              )),
              form(array(
                  form_info(_("Here you can choose your language:")),
                  form_select('language', _("Language:"), $locales, $selected_language),
                  form_submit('submit_language', _("Save"))
              ))
          ))
      ))
  ));
}
}
?>
