<?php

/**
 * Available T-Shirt sizes
 */
$tshirt_sizes = array(
    '' => _("Please select..."),
    'S' => "S",
    'M' => "M",
    'L' => "L",
    'XL' => "XL",
    '2XL' => "2XL",
    '3XL' => "3XL",
    '4XL' => "4XL",
    '5XL' => "5XL",
    'S-G' => "S Girl",
    'M-G' => "M Girl",
    'L-G' => "L Girl",
    'XL-G' => "XL Girl"
);

/**
 * Gui for deleting user with password field.
 */
function User_delete_view($user) {
  return page_with_title(sprintf(_("Delete %s"), User_Nick_render($user)), [
      msg(),
      buttons([
          button(user_edit_link($user), glyph('chevron-left') . _("back"))
      ]),
      error(_("Do you really want to delete the user including all his shifts and every other piece of his data?"), true),
      form([
          form_password('password', _("Your password")),
          form_submit('submit', _("Delete"))
      ])
  ]);
}

/**
 * View for editing the number of given vouchers
 */
function User_edit_vouchers_view($user) {
  return page_with_title(sprintf(_("%s's vouchers"), User_Nick_render($user)), [
      msg(),
      buttons([
          button(user_link($user), glyph('chevron-left') . _("back"))
      ]),
      info(sprintf(_("Angel should receive at least  %d vouchers."), User_get_eligable_voucher_count($user)), true),
      form([
          form_spinner('vouchers', _("Number of vouchers given out"), $user['got_voucher']),
          form_submit('submit', _("Save"))
      ], page_link_to('users') . '&action=edit_vouchers&user_id=' . $user['UID'])
  ]);
}

function Users_view($users, $order_by, $arrived_count, $active_count, $force_active_count, $freeloads_count, $tshirts_count, $voucher_count) {
  $search = "";
  $users_matched = [];
  if (isset($_REQUEST['search']))
    $search = strip_request_item('search');
  if ($search == "")
    $tokens = [];
  else
    $tokens = explode(" ", $search);
  foreach ($users as $usr) {
    if (count($tokens) > 0) {
      $match = false;
      $index = join(" ", $usr);
      foreach ($tokens as $t)
        if (stristr($index, trim($t))) {
          $match = true;
          break;
        }
      if (! $match)
        continue;
    }

    $usr['nick'] = User_Nick_render($usr);
    $usr['Gekommen'] = glyph_bool($usr['Gekommen']);
    $usr['got_voucher'] = $usr['got_voucher'];
    $usr['Aktiv'] = glyph_bool($usr['Aktiv']);
    $usr['force_active'] = glyph_bool($usr['force_active']);
    $usr['Tshirt'] = glyph_bool($usr['Tshirt']);
    $usr['lastLogIn'] = date(_('m/d/Y h:i a'), $usr['lastLogIn']);
    $usr['actions'] = table_buttons(array(
        button_glyph(page_link_to('admin_user') . '&id=' . $usr['UID'], 'edit', 'btn-xs')
    ));
    $users_matched[] = $usr;
  }

  $users_matched[] = array(
      'Nick' => '<strong>' . _('Sum') . '</strong>',
      'Gekommen' => $arrived_count,
      'got_voucher' => $voucher_count,
      'Aktiv' => $active_count,
      'force_active' => $force_active_count,
      'freeloads' => $freeloads_count,
      'Tshirt' => $tshirts_count,
      'actions' => '<strong>' . count($users) . '</strong>'
  );

  return page_with_title(_('All users'), array(
      msg(),
      form(array(
          form_text('search', _("Search"), $search),
          form_submit('submit', _("Search"))
      )),
      buttons(array(
          button(page_link_to('register'), glyph('plus') . _('New user'))
      )),
      table(array(
          'Nick' => Users_table_header_link('Nick', _('Nick'), $order_by),
          'Vorname' => Users_table_header_link('Vorname', _('Prename'), $order_by),
          'Name' => Users_table_header_link('Name', _('Name'), $order_by),
          'email' => Users_table_header_link('email', _('Email'), $order_by),
          'Handy' => Users_table_header_link('Handy', _('Mobile'), $order_by),
          'DECT' => Users_table_header_link('DECT', _('DECT'), $order_by),
          'Gekommen' => Users_table_header_link('Gekommen', _('Arrived'), $order_by),
          'got_voucher' => Users_table_header_link('got_voucher', _('Voucher'), $order_by),
          'freeloads' => _('Freeloads'),
          'Aktiv' => Users_table_header_link('Aktiv', _('Active'), $order_by),
          'force_active' => Users_table_header_link('force_active', _('Forced'), $order_by),
          'Tshirt' => Users_table_header_link('Tshirt', _('T-Shirt'), $order_by),
          'Size' => Users_table_header_link('Size', _('Size'), $order_by),
          'facebook' => Users_table_header_link('facebook', _('Facebook'), $order_by),
          'github' => Users_table_header_link('github', _('Github'), $order_by),
          'twitter' => Users_table_header_link('twitter', _('Twitter'), $order_by),
          'organization' => Users_table_header_link('organization', _('Organization'), $order_by),
          'lastLogIn' => Users_table_header_link('lastLogIn', _('Last login'), $order_by),
          'actions' => ''
      ), $users_matched)
  ));
}

function Users_table_header_link($column, $label, $order_by) {
  return '<a href="' . page_link_to('users') . '&OrderBy=' . $column . '">' . $label . ($order_by == $column ? ' <span class="caret"></span>' : '') . '</a>';
}

function User_shift_state_render($user) {
  $upcoming_shifts = ShiftEntries_upcoming_for_user($user);
  if ($upcoming_shifts === false)
    return false;

  if (count($upcoming_shifts) == 0)
    return '<span class="text-success">' . _("Free") . '</span>';

  if ($upcoming_shifts[0]['start'] > time())
    if ($upcoming_shifts[0]['start'] - time() > 3600)
      return '<span class="text-success moment-countdown" data-timestamp="' . $upcoming_shifts[0]['start'] . '">' . _("Next shift %c") . '</span>';
    else
      return '<span class="text-warning moment-countdown" data-timestamp="' . $upcoming_shifts[0]['start'] . '">' . _("Next shift %c") . '</span>';

  $halfway = ($upcoming_shifts[0]['start'] + $upcoming_shifts[0]['end']) / 2;

  if (time() < $halfway)
    return '<span class="text-danger moment-countdown" data-timestamp="' . $upcoming_shifts[0]['start'] . '">' . _("Shift starts %c") . '</span>';
  else
    return '<span class="text-danger moment-countdown" data-timestamp="' . $upcoming_shifts[0]['end'] . '">' . _("Shift ends %c") . '</span>';
}

function User_view($user_source, $admin_user_privilege, $freeloader, $user_angeltypes, $user_groups, $shifts, $its_me) {
  global $LETZTES_AUSTRAGEN, $privileges;
  $user_name = htmlspecialchars($user_source['Vorname']) . " " . htmlspecialchars($user_source['Name']);
  $myshifts_table = array();
  $timesum = 0;
  foreach ($shifts as $shift) {
    $shift_info = '<a href="' . shift_link($shift) . '">' . $shift['name'] . '</a>';
    if ($shift['title'])
      $shift_info .= '<br /><a href="' . shift_link($shift) . '">' . $shift['title'] . '</a>';
    foreach ($shift['needed_angeltypes'] as $needed_angel_type) {
      $shift_info .= '<br><b>' . $needed_angel_type['name'] . ':</b>';
      
      $shift_entries = array();
      foreach ($needed_angel_type['users'] as $user_shift) {
        $member = User_Nick_render($user_shift);
        if ($user_shift['freeloaded'])
          $member = '<strike>' . $member . '</strike>';
        
        $shift_entries[] = $member;
      }
      $shift_info .= join(", ", $shift_entries);
    }
    
    $myshift = array(
        'date' => date("Y-m-d", $shift['start']),
        'time' => date("H:i", $shift['start']) . ' - ' . date("H:i", $shift['end']),
        'room' => $shift['Name'],
        'shift_info' => $shift_info,
        'comment' => $shift['Comment']
    );
    
    if ($shift['freeloaded']) {
      if (in_array("user_shifts_admin", $privileges))
        $myshift['comment'] .= '<br /><p class="error">' . _("Freeloaded") . ': ' . $shift['freeload_comment'] . '</p>';
      else
        $myshift['comment'] .= '<br /><p class="error">' . _("Freeloaded") . '</p>';
    }
    
    $myshift['actions'] = [
        button(shift_link($shift), glyph('eye-open') . _('view'), 'btn-xs')
    ];
    if ($its_me || in_array('user_shifts_admin', $privileges))
      $myshift['actions'][] = button(page_link_to('user_myshifts') . '&edit=' . $shift['id'] . '&id=' . $user_source['UID'], glyph('edit') . _('edit'), 'btn-xs');
    if (($shift['start'] > time() + $LETZTES_AUSTRAGEN * 3600) || in_array('user_shifts_admin', $privileges))
      $myshift['actions'][] = button(page_link_to('user_myshifts') . ((! $its_me) ? '&id=' . $user_source['UID'] : '') . '&cancel=' . $shift['id'], glyph('trash') . _('sign off'), 'btn-xs');
    $myshift['actions'] = table_buttons($myshift['actions']);
    
    if ($shift['freeloaded'])
      $timesum += (- 2 * ($shift['end'] - $shift['start']));
    else
      $timesum += ($shift['end'] - $shift['start']);
    $myshifts_table[] = $myshift;
  }
  if (count($myshifts_table) > 0)
    $myshifts_table[] = array(
        'date' => '<b>' . _("Sum:") . '</b>',
        'time' => "<b>" . round($timesum / 3600, 1) . " h</b>",
        'room' => "",
        'shift_info' => "",
        'comment' => "",
        'actions' => ""
    );
  
  return page_with_title('<span class="icon-icon_angel"></span> ' . htmlspecialchars($user_source['Nick']) . ' <small>' . $user_name . '</small>', array(
      msg(),
      div('row', array(
          div('col-md-3', array(
              '<h1>',
              '<span class="glyphicon glyphicon-phone"></span>',
              $user_source['DECT'],
              '</h1>'
          )),
          div('col-md-3', array(
              '<h4>' . _("User state") . '</h4>',
              ($admin_user_privilege && $freeloader) ? '<span class="text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> ' . _("Freeloader") . '</span><br />' : '',
              $user_source['Gekommen'] ? User_shift_state_render($user_source) . '<br />' : '',
              $admin_user_privilege || $its_me ? ($user_source['Gekommen'] ? '<span class="text-success"><span class="glyphicon glyphicon-home"></span> ' . sprintf(_("Arrived at %s"), date('Y-m-d', $user_source['arrival_date'])) . '</span>' : '<span class="text-danger">' . sprintf(_("Not arrived (Planned: %s)"), date('Y-m-d', $user_source['planned_arrival_date'])) . '</span>') : ($user_source['Gekommen'] ? '<span class="text-success"><span class="glyphicon glyphicon-home"></span> ' . _("Arrived") . '</span>' : '<span class="text-danger">' . _("Not arrived") . '</span>'),
              $admin_user_privilege ? ($user_source['got_voucher'] > 0 ? '<br /><span class="text-success">' . glyph('cutlery') . sprintf(ngettext("Got %s voucher", "Got %s vouchers", $user_source['got_voucher']), $user_source['got_voucher']) . '</span><br />' : '<br /><span class="text-danger">' . _("Got no vouchers") . '</span><br />') : '',
              ($user_source['Gekommen'] && $admin_user_privilege && $user_source['Aktiv']) ? ' <span class="text-success">' . _("Active") . '</span>' : '',
              ($user_source['Gekommen'] && $admin_user_privilege && $user_source['Tshirt']) ? ' <span class="text-success">' . _("T-Shirt") . '</span>' : ''
          )),
          div('col-md-3', array(
              '<h4>' . _("Angeltypes") . '</h4>',
              User_angeltypes_render($user_angeltypes)
          )),
          div('col-md-3', array(
              '<h4>' . _("Rights") . '</h4>',
              User_groups_render($user_groups)
          ))
      )),
      div('row', array(
          div('col-md-6', array(
              '<h4>' . _("Native Language") . '</h4>',
              Native_language_render($user_source)
          )),
          div('col-md-6', array(
              '<h4>' . _("Other Languages") . '</h4>',
              Other_languages_render($user_source)
          ))
      )),
      div('row space-top', array(
          div('col-md-12', array(
              buttons(array(
                  $admin_user_privilege ? button(page_link_to('admin_user') . '&id=' . $user_source['UID'], glyph("edit") . _("edit")) : '',
                  $admin_user_privilege ? button(user_driver_license_edit_link($user_source), glyph("road") . _("driving license")) : '',
                  ($admin_user_privilege && ! $user_source['Gekommen']) ? button(page_link_to('admin_arrive') . '&arrived=' . $user_source['UID'], _("arrived")) : '',
                  $admin_user_privilege ? button(page_link_to('users') . '&action=edit_vouchers&user_id=' . $user_source['UID'], glyph('cutlery') . _('Edit vouchers')) : '',
                  $its_me ? button(page_link_to('user_settings'), glyph('list-alt') . _("Settings")) : '',
                  $its_me ? button(page_link_to('ical') . '&key=' . $user_source['api_key'], glyph('calendar') . _("iCal Export")) : '',
                  $its_me ? button(page_link_to('shifts_json_export') . '&key=' . $user_source['api_key'], glyph('export') . _("JSON Export")) : '',
                  $its_me ? button(page_link_to('user_myshifts') . '&reset', glyph('repeat') . _('Reset API key')) : ''
              ))
          ))
      )),
      ($its_me || $admin_user_privilege) ? '<h2>' . _("Shifts") . '</h2>' : '',
      ($its_me || $admin_user_privilege) ? table(array(
          'date' => _("Day"),
          'time' => _("Time"),
          'room' => _("Location"),
          'shift_info' => _("Name &amp; workmates"),
          'comment' => _("Comment"),
          'actions' => _("Action")
      ), $myshifts_table) : '',
      $its_me ? info(glyph('info-sign') . _("Your night shifts between 2 and 8 am count twice."), true) : '',
      $its_me && count($shifts) == 0 ? error(sprintf(_("Go to the <a href=\"%s\">shifts table</a> to sign yourself up for some shifts."), page_link_to('user_shifts')), true) : ''
  ));
}

/**
 * View for password recovery step 1: E-Mail
 */
function User_password_recovery_view() {
  return page_with_title(user_password_recovery_title(), array(
      msg(),
      _("We will send you an e-mail with a password recovery link. Please use the email address you used for registration."),
      form(array(
          form_text('email', _("E-Mail"), ""),
          form_submit('submit', _("Recover"))
      )) 
  ));
}

/**
 * View for password recovery step 2: New password
 */
function User_password_set_view() {
  return page_with_title(user_password_recovery_title(), array(
      msg(),
      _("Please enter a new password."),
      form(array(
          form_password('password', _("Password")),
          form_password('password2', _("Confirm password")),
          form_submit('submit', _("Save"))
      )) 
  ));
}

function User_angeltypes_render($user_angeltypes) {
  $output = array();
  foreach ($user_angeltypes as $angeltype) {
    $class = "";
    if ($angeltype['restricted'] == 1)
      if ($angeltype['confirm_user_id'] != null)
        $class = 'text-success';
      else
        $class = 'text-warning';
    else
      $class = 'text-success';
    $output[] = '<a href="' . angeltype_link($angeltype['id']) . '" class="' . $class . '">' . ($angeltype['coordinator'] ? glyph('education') : '') . $angeltype['name'] . '</a>';
  }
  return join('<br />', $output);
}

function User_groups_render($user_groups) {
  $output = array();
  foreach ($user_groups as $group) {
    $output[] = substr($group['Name'], 2);
  }
  return join('<br />', $output);
}

/**
 * Render a user nickname.
 *
 * @param User $user_source
 * @return string
 */
function User_Nick_render($user_source) {
  return '<a class="' . ($user_source['Gekommen'] ? '' : 'text-muted') . '" href="' . page_link_to('users') . '&amp;action=view&amp;user_id=' . $user_source['UID'] . '"><span class="icon-icon_angel"></span> ' . htmlspecialchars($user_source['Nick']) . '</a>';
}
/**
 * Render the Native Language
 */
function Native_language_render($user_source) {
  $xml = simplexml_load_file("https://www.facebook.com/translations/FacebookLocales.xml");
  foreach($xml->xpath("/locales/locale") as $item)
  {
    $representation = $item->codes->code->standard->representation;
    if ($representation == $user_source['native_lang']) {
      return htmlspecialchars($item->englishName);
    }
  }
}
/**
 * Render Other Languages
 */
function Other_languages_render($user_source) {
  $other_langs = explode(",", $user_source['other_langs']);
  $xml = simplexml_load_file("https://www.facebook.com/translations/FacebookLocales.xml");
  $list = "";
  foreach($xml->xpath("/locales/locale") as $item)
  {
    $representation = $item->codes->code->standard->representation;
    foreach($other_langs as $other_lang) {
      if ($other_lang == $representation) {
        $list .= $item->englishName . ', ';
      }
    }
  }
  $pos = strrpos($list, ',');
  if($pos !== false){
    $list = substr_replace($list, '', $pos, strlen(','));
  }
  return htmlspecialchars($list);
}

?>
