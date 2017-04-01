<?php

function login_title() {
  return _("Login");
}

function register_title() {
  return _("Register");
}

function logout_title() {
  return _("Logout");
}

// Angel registration
function guest_register() {
  global $tshirt_sizes, $enable_tshirt_size, $default_theme;

  $msg = "";
  $nick = "";
  $lastname = "";
  $prename = "";
  $age = "";
  $tel = "";
  $dect = "";
  $mobile = "";
  $mail = "";
  $email_shiftinfo = false;
  $jabber = "";
  $hometown = "";
  $comment = "";
  $twitter = "";
  $facebook = "";
  $github = "";
  $organization = "";
  $current_city = "";
  $organization_web = "";
  $tshirt_size = '';
  $password_hash = "";
  $selected_angel_types = array();
  $planned_arrival_date = null;
  $timezone = "";
  $timezone_identifiers = DateTimeZone::listIdentifiers();

  $welcome_message = WelcomeMessage();
  $display_message = $welcome_message[0]['display_msg'];
  $angel_types_source = select_angeltype();
  $angel_types = array();
  foreach ($angel_types_source as $angel_type) {
    $angel_types[$angel_type['id']] = $angel_type['name'] . ($angel_type['restricted'] ? " (restricted)" : "");
    if (! $angel_type['restricted'])
      $selected_angel_types[] = $angel_type['id'];
  }

  if (isset($_REQUEST['submit'])) {
    $ok = true;

    if (isset($_REQUEST['nick']) && strlen(User_validate_Nick($_REQUEST['nick'])) > 1) {
      $nick = User_validate_Nick($_REQUEST['nick']);
      if (count_user_by_nick($nick) > 0) {
        $ok = false;
        $msg .= error(sprintf(_("Your nick &quot;%s&quot; already exists."), $nick), true);
      }
    } else {
      $ok = false;
      $msg .= error(sprintf(_("Your nick &quot;%s&quot; is too short (min. 2 characters)."), User_validate_Nick($_REQUEST['nick'])), true);
    }

    if (isset($_REQUEST['mail']) && strlen(strip_request_item('mail')) && preg_match("/^[a-z0-9._+-]{1,64}@(?:[a-z0-9-]{1,63}\.){1,125}[a-z]{2,63}$/", $_REQUEST['mail']) > 0) {
      $mail = strip_request_item('mail');
      if (! check_email($mail)) {
        $ok = false;
        $msg .= error(_("E-mail address is not correct."), true);
      }
      if (count_user_by_email($mail) > 0) {
        $ok = false;
        $msg .= error(sprintf(_("Your E-mail &quot;%s&quot; already exists.<a href=%s>Forgot password?</a>"), $mail,page_link_to_absolute('user_password_recovery')), true);
      }
    } else {
      $ok = false;
      $msg .= error(_("Please enter your correct e-mail (in lowercase)."), true);
    }

    if (isset($_REQUEST['email_shiftinfo']))
      $email_shiftinfo = true;

    if (isset($_REQUEST['jabber']) && strlen(strip_request_item('jabber')) > 0) {
      $jabber = strip_request_item('jabber');
      if (! check_email($jabber)) {
        $ok = false;
        $msg .= error(_("Please check your jabber account information."), true);
      }
    }

    if ($enable_tshirt_size) {
      if (isset($_REQUEST['tshirt_size']) && isset($tshirt_sizes[$_REQUEST['tshirt_size']]) && $_REQUEST['tshirt_size'] != '')
        $tshirt_size = $_REQUEST['tshirt_size'];
      else {
        $ok = false;
        $msg .= error(_("Please select your shirt size."), true);
      }
    }

    if (isset($_REQUEST['password']) && strlen($_REQUEST['password']) >= MIN_PASSWORD_LENGTH) {
      if ($_REQUEST['password'] != $_REQUEST['password2']) {
        $ok = false;
        $msg .= error(_("Your passwords don't match."), true);
      }
    } else {
      $ok = false;
      $msg .= error(sprintf(_("Your password is too short (please use at least %s characters)."), MIN_PASSWORD_LENGTH), true);
    }

    if (isset($_REQUEST['planned_arrival_date']) && DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_arrival_date']))) {
      $planned_arrival_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['planned_arrival_date']))->getTimestamp();
    } else {
      $ok = false;
      $msg .= error(_("Please enter your planned date of arrival."), true);
    }

    $selected_angel_types = array();
    foreach ($angel_types as $angel_type_id => $angel_type_name)
      if (isset($_REQUEST['angel_types_' . $angel_type_id]))
        $selected_angel_types[] = $angel_type_id;

      // Trivia
    if (isset($_REQUEST['lastname']) && strlen(strip_request_item('lastname')) > 0){
      $lastname = strip_request_item('lastname');
      }
    else {
      $ok = false;
      $msg .= error(_("Please enter your Last Name."), true);
    }

    if (isset($_REQUEST['prename']) && strlen(strip_request_item('prename')) > 0){
      $prename = strip_request_item('prename');
      }
    else {
      $ok = false;
      $msg .= error(_("Please enter your First Name."), true);
    }
    if (isset($_REQUEST['current_city']) && strlen(strip_request_item('current_city')) > 0){
      $current_city = strip_request_item('current_city');
      }
    else {
      $ok = false;
      $msg .= error(_("Please enter your Current City."), true);
    }
    if (isset($_REQUEST['native_lang']) && strlen(strip_request_item('native_lang')) > 0){
      $native_lang = strip_request_item('native_lang');
      }
    else {
      $ok = false;
      $msg .= error(_("Please enter your Native Language."), true);
    }
    /**
     * Google reCaptcha Server-Side Handling
     */
    if (capflg) {
      if (isset($_REQUEST['g-recaptcha-response']) && !empty($_REQUEST['g-recaptcha-response'])) {
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => 'hppts://www.google.com/recaptcha/api/siteverify',
          CURLOPT_POST => 1,
          CURLOPT_POSTFIELDS => [
            'secret' => CAPTCHA_KEY_PRIVATE,
            'response' => $_REQUEST['g-recaptcha-response'],
          ]
        ]);
        $response = json_decode(curl_exec($curl));
        $msg .= error(sprintf(_(print_r($response)), $nick), true);
      }
      else {
        $ok = false;
        $msg .= error(_("You are a Robot."), true);
      }
    }
    if (isset($_REQUEST['timezone'])) {
      $timezone = strip_request_item('timezone');
    } else {
      $ok = false;
      $msg .= error(_("Please select a timezone"), true);
    }
    if (isset($_REQUEST['age']) && preg_match("/^[0-9]{0,4}$/", $_REQUEST['age']))
      $age = strip_request_item('age');
    if (isset($_REQUEST['tel']))
      $tel = strip_request_item('tel');
    if (isset($_REQUEST['dect']))
      $dect = strip_request_item('dect');
    if (isset($_REQUEST['native_lang']))
      $native_lang = strip_request_item('native_lang');
    if (isset($_REQUEST['other_lang'])) {
      $other_langs = "";
      $langs = $_REQUEST['other_lang'];
      foreach($langs as $lang) {
        $other_langs .= $lang . ',';
      }
    }
    if (isset($_REQUEST['mobile']))
      $mobile = strip_request_item('mobile');
    if (isset($_REQUEST['hometown']))
      $hometown = strip_request_item('hometown');
    if (isset($_REQUEST['comment']))
      $comment = strip_request_item_nl('comment');
    if (isset($_REQUEST['twitter']))
      $dect = strip_request_item('twitter');
    if (isset($_REQUEST['facebook']))
      $dect = strip_request_item('facebook');
    if (isset($_REQUEST['github']))
      $dect = strip_request_item('github');
    if (isset($_REQUEST['organization']))
      $dect = strip_request_item('organization');
    if (isset($_REQUEST['organization_web']))
      $dect = strip_request_item('organization_web');

    if ($ok) {
      insert_user($default_theme, $nick, $prename, $lastname, $age, $tel, $dect, $native_lang, $other_langs, $mobile, $mail, $email_shiftinfo, $jabber, $tshirt_size, $password_hash, $comment, $hometown, $twitter, $facebook, $github, $organization, $current_city, $organization_web, $timezone, $planned_arrival_date);
      // Assign user-group and set password
      $user_id = sql_id();
      insert_UserGroups_by_id($user_id);
      set_password($user_id, $_REQUEST['password']);

      // Assign angel-types
      $user_angel_types_info = array();
      foreach ($selected_angel_types as $selected_angel_type_id) {
        insert_UserAngelTypes($user_id, $selected_angel_type_id);
        $user_angel_types_info[] = $angel_types[$selected_angel_type_id];
      }

      engelsystem_log("User " . User_Nick_render(User($user_id)) . " signed up as: " . join(", ", $user_angel_types_info));
      success(_("Angel registration successful!"));

      redirect('?');
    }
  }

  return page_with_title(register_title(), array(
      _($display_message),
      $msg,
      msg(),
      form(array(
          div('row', array(
              div('col-md-6', array(
                  div('row', array(
                      div('col-sm-8', array(
                          form_text('nick', _("Nick") . ' ' . entry_required(), $nick)
                      )),
                  )) ,
                  div('row', array(
                      div('col-sm-8', array(
                          form_email('mail', _("E-Mail") . ' ' . entry_required(), $mail),
                          form_checkbox('email_shiftinfo', _("Please send me an email if my shifts change"), $email_shiftinfo)
                      ))
                  )),
                  div('row', array(
                      div('col-sm-8', array(
                          form_date('planned_arrival_date', _("Planned date of arrival") . ' ' . entry_required(), $planned_arrival_date, time())
                      )),
                  )),
                    div('row', array(
                      div('col-sm-8', array(
                          $enable_tshirt_size ? form_select('tshirt_size', _("Shirt size") . ' ' . entry_required(), $tshirt_sizes, $tshirt_size) : ''
                      ))
                  )),
                    div('row', array(
                      div('col-sm-8', array(
                          form_password('password', _("Password") . ' ' . entry_required())
                      )),
                  )),
                  div('row', array(
                      div('col-sm-8', array(
                          form_password('password2', _("Confirm password") . ' ' . entry_required())
                      ))
                  )),
                 div('row', array(
                      div('col-sm-8', array(
                          form_text('dect', _("DECT"), $dect)
                      ))
                  )),
                  div('row', array(
                      div('col-sm-4', array(
                          form_text('twitter', _("Twitter"), $twitter )
                      )),
                      div('col-sm-4', array(
                          form_text('facebook', _("Facebook"), $facebook )
                      )),
                  )),

                  div('row', array(
                      div('col-sm-8', array(
                          form_text('github', _("Github"), $github )
                      ))
                  )),

                  form_checkboxes('angel_types', _("What do you want to do?") . sprintf(" (<a href=\"%s\">%s</a>)", page_link_to('angeltypes') . '&action=about', _("Description of job types")), $angel_types, $selected_angel_types),
                  form_info("", _("Restricted angel types need will be confirmed later by an archangel. You can change your selection in the options section."))
              )),
              div('col-md-6', array(
                div('row', array(
                      div('col-sm-8', array(
                          form_text('prename', _("First name") . ' ' . entry_required(), $prename)
                      )),
                  )),
                  div('row', array(
                      div('col-sm-8', array(
                          form_text('lastname', _("Last name") . ' ' . entry_required(), $lastname)
                      ))
                  )),
                  div('row', array(
                  div('col-sm-8', array(
                          form_text('current_city', _("Current City"). ' ' . entry_required(), $current_city)
                      )),
                  )),
                  div('row', array(
                  div('col-sm-8', array(
                    form_select('native_lang', _("Native Language").' '. entry_required(), languages())
                      ))
                  )),
                  div('row', array(
                  div('col-sm-8', array(
                          form_multiselect('other_langs', _("Other Languages"), languages())
                      ))
                  )),
                  div('row', array(
                    div('col-sm-8', array(
                          form_select('timezone', _("Timezone") . ' ' . entry_required(), $timezone_identifiers, $timezone)
                      ))
                  )),
                  div('row', array(
                      div('col-sm-4', array(
                          form_text('mobile', _("Mobile"), $mobile)
                      )),
                      div('col-sm-4', array(
                          form_text('tel', _("Phone"), $tel)
                      ))
                  )),
                  div('row', array(
                  div('col-sm-8', array(
                  form_text('jabber', _("Jabber"), $jabber),
                  )),
                  )),

                  div('row', array(
                      div('col-sm-4', array(
                          form_text('age', _("Age"), $age)
                      )),
                      div('col-sm-4', array(
                          form_text('hometown', _("Hometown"), $hometown)
                      )),
                  )),
                  div('row', array(
                      div('col-sm-8', array(
                          form_text('organization', _("Organisation Name (University or Company Name)"), $organization)
                      )),
                  )),
                  div('row', array(
                      div('col-sm-8', array(
                          form_text('organization_web', _("Organization Website"), $organization_web)
                      )),
                  )),
                  div('row', array(
                      div('col-sm-8', array(
                          reCaptcha(capflg)
                      )),
                  )),
                  form_info(entry_required() . ' = ' . _("Entry required!"))
              ))
          )),
          // form_textarea('comment', _("Did you help at former CCC events and which tasks have you performed then?"), $comment),
          form_submit('submit', _("Register"))
      ))
  ));
}

function entry_required() {
  return '<span class="text-info glyphicon glyphicon-warning-sign"></span>';
}

function guest_logout() {
  session_destroy();
  redirect(page_link_to("start"));
}

function guest_login() {
  global $user, $privileges;

  $nick = "";

  unset($_SESSION['uid']);

  if (isset($_REQUEST['submit'])) {
    $ok = true;

    if (isset($_REQUEST['nick']) && strlen(User_validate_Nick($_REQUEST['nick'])) > 0) {
      $nick = User_validate_Nick($_REQUEST['nick']);
      $login_user = select_user_by_nick($nick);
      if (count($login_user) > 0) {
        $login_user = $login_user[0];
        if (isset($_REQUEST['password'])) {
          if (! verify_password($_REQUEST['password'], $login_user['Passwort'], $login_user['UID'])) {
            $ok = false;
            error(_("Your password is incorrect.  Please try it again."));
          }
        } else {
          $ok = false;
          error(_("Please enter a password."));
        }
      } else {
        $ok = false;
        error(_("No user was found with that Nickname. Please try again. If you are still having problems, ask an Dispatcher."));
      }
    } else {
      $ok = false;
      error(_("Please enter a nickname."));
    }

    if ($ok) {
      $_SESSION['uid'] = $login_user['UID'];
      $_SESSION['locale'] = $login_user['Sprache'];

      redirect(page_link_to('news'));
    }
  }

  if (in_array('register', $privileges)) {
    $register_hint = join('', array(
        '<p>' . _("Please sign up, if you want to help us!") . '</p>',
        buttons(array(
            button(page_link_to('register'), register_title() . ' &raquo;')
        ))
    ));
  } else {
    $register_hint = join('', array(
        error(_('Registration is disabled.'), true)
    ));
  }

  return page_with_title(login_title(), array(
      msg(),
      '<div class="row"><div class="col-md-6">',
      form(array(
          form_text('nick', _("Nick"), $nick),
          form_password('password', _("Password")),
          form_submit('submit', _("Login")),
          buttons(array(
              button(page_link_to('user_password_recovery'), _("I forgot my password"))
          )),
          info(_("Please note: You have to activate cookies!"), true)
      )),
      '</div>',
      '<div class="col-md-6">',
      '<h2>' . register_title() . '</h2>',
      $register_hint,
      '<h2>' . _("What can I do?") . '</h2>',
      '<p>' . _("Please read about the jobs you can do to help us.") . '</p>',
      buttons(array(
          button(page_link_to('angeltypes') . '&action=about', _("Teams/Job description") . ' &raquo;')
      )),
      '</div></div>'
  ));
}
/**
 * Generates a list of Languages with their ISO Codes
 */
function languages() {
  $xml = simplexml_load_file("https://www.facebook.com/translations/FacebookLocales.xml");
  foreach($xml->xpath("/locales/locale") as $item) {
    $representation = $item->codes->code->standard->representation;
    if ($representation != "en_PI" || $representation != "en_UD")
      $locale["$representation"] = $item->englishName;
  }
  return $locale;
}
?>
