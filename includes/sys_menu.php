<?php

function page_link_to($page) {
  if ($page == "")
    return '?';
  return '?p=' . $page;
}

function page_link_to_absolute($page) {
  return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . preg_replace("/\?.*$/", '', $_SERVER['REQUEST_URI']) . page_link_to($page);
}

/**
 * Renders the header toolbar containing search, login/logout, user and settings links.
 */
function header_toolbar() {
  global $p, $privileges, $user, $enable_tshirt_size, $max_freeloadable_shifts;
  $settings = array();
  $no_migrated = "";
  if (test_import()) {
    $settings = Settings();
    $no_migrated = $settings[0]['table_migrated'];
  }
  $toolbar_items = array();

  if (isset($user))
    $toolbar_items[] = toolbar_item_link(page_link_to('shifts') . '&amp;action=next', 'time', User_shift_state_render($user));

  if (! isset($user) && in_array('register', $privileges) && $no_migrated == 1)
    $toolbar_items[] = toolbar_item_link(page_link_to('register'), 'plus', register_title(), $p == 'register');

  if (in_array('login', $privileges) && $no_migrated == 1)
    $toolbar_items[] = toolbar_item_link(page_link_to('login'), 'log-in', login_title(), $p == 'login');

  if (isset($user) && in_array('user_messages', $privileges))
    $toolbar_items[] = toolbar_item_link(page_link_to('user_messages'), 'envelope', user_unread_messages());

  $hints = [];
  if (isset($user)) {
    $hint_class = 'info';
    $glyphicon = 'info-sign';
    // Archangel Note for unanswered questions
    if ($p != "admin_questions") {
      $new_questions = admin_new_questions();
      if ($new_questions != "")
        $hints[] = $new_questions;
    }

    $unconfirmed_hint = user_angeltypes_unconfirmed_hint();
    if ($unconfirmed_hint != '')
      $hints[] = $unconfirmed_hint;

    if (! isset($user['planned_departure_date']) || $user['planned_departure_date'] == null)
      $hints[] = info(_("Please enter your planned date of departure on your settings page to give us a feeling for teardown capacities."), true);

    $driver_license_required = user_driver_license_required_hint();
    if ($driver_license_required != '')
      $hints[] = $driver_license_required;

    if (User_is_freeloader($user)) {
      $hints[] = error(sprintf(_("You freeloaded at least %s shifts. Shift signup is locked. Please go to heavens desk to be unlocked again."), $max_freeloadable_shifts), true);
      $hint_class = 'danger';
      $glyphicon = 'warning-sign';
    }

    // Note for angels who have not yet arrived
    if ($user['Gekommen'] == 0) {
      $hints[] = error(_("You are not marked as arrived. Please go to heaven's desk, get your angel badge and/or tell them that you arrived already."), true);
      $hint_class = 'danger';
      $glyphicon = 'warning-sign';
    }

    if ($enable_tshirt_size && $user['Size'] == "") {
      $hints[] = error(_("You need to specify a tshirt size in your settings!"), true);
      $hint_class = 'danger';
      $glyphicon = 'warning-sign';
    }

    if ($user['DECT'] == "") {
      $hints[] = error(_("You need to specify a DECT phone number in your settings! If you don't have a DECT phone, just enter \"-\"."), true);
      $hint_class = 'danger';
      $glyphicon = 'warning-sign';
    }
  }
  if (count($hints) > 0)
    $toolbar_items[] = toolbar_popover($glyphicon . ' text-' . $hint_class, '', $hints, 'bg-' . $hint_class);

  $user_submenu = make_langselect();
  $user_submenu[] = toolbar_item_divider();
  if (in_array('user_myshifts', $privileges))
    $toolbar_items[] = toolbar_item_link(page_link_to('users') . '&amp;action=view', ' icon-icon_angel', $user['Nick'], $p == 'users');

  if (in_array('user_settings', $privileges))
    $user_submenu[] = toolbar_item_link(page_link_to('user_settings'), 'list-alt', settings_title(), $p == 'user_settings');

  if (in_array('logout', $privileges))
    $user_submenu[] = toolbar_item_link(page_link_to('logout'), 'log-out', logout_title(), $p == 'logout');

  if (count($user_submenu) > 0)
    $toolbar_items[] = toolbar_dropdown('', '', $user_submenu);

  return toolbar($toolbar_items, true);
}

function make_navigation() {
  global $p, $privileges;

  $menu = array();
  $pages = array(
      "news" => news_title(),
      "user_meetings" => meetings_title(),
      "user_shifts" => shifts_title(),
      "angeltypes" => angeltypes_title(),
      "user_questions" => questions_title()
  );

  foreach ($pages as $page => $title)
    if (in_array($page, $privileges))
      $menu[] = toolbar_item_link(page_link_to($page), '', $title, $page == $p);

  $admin_menu = array();
  $admin_pages = array(
      "admin_arrive" => admin_arrive_title(),
      "admin_active" => admin_active_title(),
      "admin_user" => admin_user_title(),
      "admin_free" => admin_free_title(),
      "admin_questions" => admin_questions_title(),
      "shifttypes" => shifttypes_title(),
      "admin_shifts" => admin_shifts_title(),
      "admin_rooms" => admin_rooms_title(),
      "admin_groups" => admin_groups_title(),
      "admin_import" => admin_import_title(),
      "admin_log" => admin_log_title(),
      "admin_export" => admin_export_title(),
      "admin_settings" => admin_settings_title(),
      "admin_cgroups" => admin_cgroups_title(),
      "admin_events" => admin_events_title()
  );

  foreach ($admin_pages as $page => $title)
    if (in_array($page, $privileges))
      $admin_menu[] = toolbar_item_link(page_link_to($page), '', $title, $page == $p);

  if (count($admin_menu) > 0)
    $menu[] = toolbar_dropdown('', _("Admin"), $admin_menu);

  return toolbar($menu);
}

function make_navigation_for($name, $pages) {
  global $privileges, $p;

  $menu = "";
  foreach ($pages as $page)
    if (in_array($page, $privileges))
      $menu .= '<li' . ($page == $p ? ' class="selected"' : '') . '><a href="' . page_link_to($page) . '">' . $title . '</a></li>';

  if ($menu != "")
    $menu = '<nav class="container"><h4>' . $name . '</h4><ul class="content">' . $menu . '</ul></nav>';
  return $menu;
}

function make_menu() {
  return make_navigation();
}

?>
