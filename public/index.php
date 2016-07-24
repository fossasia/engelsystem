<?php
require_once realpath(__DIR__ . '/../includes/engelsystem_provider.php');

$free_pages = array(
    'stats',
    'shifts_json_export_all',
    'user_password_recovery',
    'api',
    'credits',
    'angeltypes',
    'users',
    'user_driver_licenses',
    'ical',
    'shifts_json_export',
    'shifts',
    'atom',
    'login'
);

// Desired page/function
$p = "";
if (! isset($_REQUEST['p']))
  $_REQUEST['p'] = isset($user) ? "news" : "login";
if (isset($_REQUEST['p']) && preg_match("/^[a-z0-9_]*$/i", $_REQUEST['p']) && (in_array($_REQUEST['p'], $free_pages) || in_array($_REQUEST['p'], $privileges))) {
  $p = $_REQUEST['p'];

  $title = $p;
  $content = "";

  if ($p == "api") {
    require_once realpath(__DIR__ . '/../includes/controller/api.php');
    error("Api disabled temporily.");
    redirect(page_link_to('login'));
    api_controller();
  } elseif ($p == "ical") {
    require_once realpath(__DIR__ . '/../includes/controller/user_ical_controller.php');
    user_ical();
  } elseif ($p == "atom") {
    require_once realpath(__DIR__ . '/../includes/controller/user_atom_controller.php');
    user_atom();
  } elseif ($p == "shifts_json_export") {
    require_once realpath(__DIR__ . '/../includes/controller/shifts_controller.php');
    shifts_json_export_controller();
  } elseif ($p == "shifts_json_export_all") {
    require_once realpath(__DIR__ . '/../includes/controller/shifts_controller.php');
    shifts_json_export_all_controller();
  } elseif ($p == "stats") {
    require_once realpath(__DIR__ . '/../includes/controller/guest_stats_controller.php');
    guest_stats();
  } elseif ($p == "user_password_recovery") {
    require_once realpath(__DIR__ . '/../includes/controller/users_controller.php');
    $title = user_password_recovery_title();
    $content = user_password_recovery_controller();
  } elseif ($p == "angeltypes") {
    list($title, $content) = angeltypes_controller();
  } elseif ($p == "shifts") {
    list($title, $content) = shifts_controller();
  } elseif ($p == "users") {
    list($title, $content) = users_controller();
  } elseif ($p == "user_angeltypes") {
    list($title, $content) = user_angeltypes_controller();
  } elseif ($p == "user_driver_licenses") {
    list($title, $content) = user_driver_licenses_controller();
  } elseif ($p == "shifttypes") {
    list($title, $content) = shifttypes_controller();
  } elseif ($p == "news") {
    $title = news_title();
    $content = user_news();
  } elseif ($p == "news_comments") {
    require_once realpath(__DIR__ . '/../includes/controller/user_news_controller.php');
    $title = user_news_comments_title();
    $content = user_news_comments();
  } elseif ($p == "user_meetings") {
    $title = meetings_title();
    $content = user_meetings();
  } elseif ($p == "user_myshifts") {
    $title = myshifts_title();
    $content = user_myshifts();
  } elseif ($p == "user_shifts") {
    $title = shifts_title();
    $content = user_shifts();
  } elseif ($p == "user_messages") {
    $title = messages_title();
    $content = user_messages();
  } elseif ($p == "user_questions") {
    $title = questions_title();
    $content = user_questions();
  } elseif ($p == "user_settings") {
    $title = settings_title();
    $content = user_settings();
  } elseif ($p == "login") {
    $title = login_title();
    $content = guest_login();
  } elseif ($p == "register") {
    $title = register_title();
    $content = guest_register();
  } elseif ($p == "logout") {
    $title = logout_title();
    $content = guest_logout();
  } elseif ($p == "admin_questions_controller") {
    $title = admin_questions_title();
    $content = admin_questions();
  } elseif ($p == "admin_user_controller") {
    $title = admin_user_title();
    $content = admin_user();
  } elseif ($p == "admin_arrive_controller") {
    $title = admin_arrive_title();
    $content = admin_arrive();
  } elseif ($p == "admin_active_controller") {
    $title = admin_active_title();
    $content = admin_active();
  } elseif ($p == "admin_free_controller") {
    $title = admin_free_title();
    $content = admin_free();
  } elseif ($p == "admin_news_controller") {
    require_once realpath(__DIR__ . '/../includes/controller/admin_news_controller.php');
    $content = admin_news();
  } elseif ($p == "admin_rooms_controller") {
    $title = admin_rooms_title();
    $content = admin_rooms();
  } elseif ($p == "admin_groups_controller") {
    $title = admin_groups_title();
    $content = admin_groups();
  } elseif ($p == "admin_language") {
    require_once realpath(__DIR__ . '/../includes/pages/admin_language.php');
    $content = admin_language();
  } elseif ($p == "admin_import_controller") {
    $title = admin_import_title();
    $content = admin_import();
  } elseif ($p == "admin_shifts_controller") {
    $title = admin_shifts_title();
    $content = admin_shifts();
  } elseif ($p == "admin_log_controller") {
    $title = admin_log_title();
    $content = admin_log();
  } elseif ($p == "admin_settings_controller") {
    $title = admin_settings_title();
    $content = admin_settings();
  } elseif ($p == "admin_export_controller") {
    $title = admin_export_title();
    $content = admin_export();
  } elseif ($p == "credits") {
    require_once realpath(__DIR__ . '/../includes/controller/guest_credits_controller.php');
    $title = credits_title();
    $content = guest_credits();
  } else {
    require_once realpath(__DIR__ . '/../includes/controller/guest_start_controller.php');
    $content = guest_start();
  }
} else {
  // If already logged in , show - authorization page
  if (isset($user)) {
    $title = _("No Access");
    $content = _("You don't have permission to view this page. You probably have to sign in or register in order to gain access!");
  } else {
    // Otherwise lead to the login page
    redirect(page_link_to("login"));
  }
}

echo template_render('../templates/layout.html', array(
    'theme' => isset($user) ? $user['color'] : $default_theme,
    'title' => $title,
    'atom_link' => ($p == 'news' || $p == 'user_meetings') ? '<link href="' . page_link_to('atom') . (($p == 'user_meetings') ? '&amp;meetings=1' : '') . '&amp;key=' . $user['api_key'] . '" type="application/atom+xml" rel="alternate" title="Atom Feed">' : '',
    'menu' => make_menu(),
    'content' => msg() . $content,
    'header_toolbar' => header_toolbar(),
    'faq_url' => $faq_url,
    'contact_email' => $contact_email,
    'locale' => locale()
));

?>
