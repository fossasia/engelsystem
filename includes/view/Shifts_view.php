<?php

function Shift_editor_info_render($shift) {
  $info = [];
  if ($shift['created_by_user_id'] != null)
    $info[] = sprintf(glyph('plus') . _("created at %s by %s"), date('Y-m-d H:i', $shift['created_at_timestamp']), User_Nick_render(User($shift['created_by_user_id'])));
  if ($shift['edited_by_user_id'] != null)
    $info[] = sprintf(glyph('pencil') . _("edited at %s by %s"), date('Y-m-d H:i', $shift['edited_at_timestamp']), User_Nick_render(User($shift['edited_by_user_id'])));
  return join('<br />', $info);
}

function Shift_signup_button_render($shift, $angeltype, $user_angeltype = null, $user_shifts = null) {
  global $user;
  
  if ($user_angeltype == null) {
    $user_angeltype = UserAngelType_by_User_and_AngelType($user, $angeltype);
    if ($user_angeltype === false)
      engelsystem_error('Unable to load user angeltype.');
  }
  
  if (Shift_signup_allowed($shift, $angeltype, $user_angeltype, $user_shifts))
    return button(page_link_to('user_shifts') . '&shift_id=' . $shift['SID'] . '&type_id=' . $angeltype['id'], _('Sign up'));
  elseif ($user_angeltype == null)
    return button(page_link_to('angeltypes') . '&action=view&angeltype_id=' . $angeltype['id'], sprintf(_('Become %s'), $angeltype['name']));
  else
    return '';
}

function Shift_view($shift, $shifttype, $room, $shift_admin, $angeltypes_source, $user_shift_admin, $admin_rooms, $admin_shifttypes, $user_shifts, $signed_up) {
  $parsedown = new Parsedown();
  
  $angeltypes = [];
  foreach ($angeltypes_source as $angeltype)
    $angeltypes[$angeltype['id']] = $angeltype;
  
  $needed_angels = '';
  foreach ($shift['NeedAngels'] as $needed_angeltype) {
    $class = 'progress-bar-warning';
    if ($needed_angeltype['taken'] == 0)
      $class = 'progress-bar-danger';
    if ($needed_angeltype['taken'] >= $needed_angeltype['count'])
      $class = 'progress-bar-success';
    $needed_angels .= '<div class="list-group-item">';
    
    $needed_angels .= '<div class="pull-right">' . Shift_signup_button_render($shift, $angeltypes[$needed_angeltype['TID']]) . '</div>';
    
    $needed_angels .= '<h3>' . AngelType_name_render($angeltypes[$needed_angeltype['TID']]) . '</h3>';
    $needed_angels .= progress_bar(0, $needed_angeltype['count'], min($needed_angeltype['taken'], $needed_angeltype['count']), $class, $needed_angeltype['taken'] . ' / ' . $needed_angeltype['count']);
    
    $angels = [];
    foreach ($shift['ShiftEntry'] as $shift_entry) {
      if ($shift_entry['TID'] == $needed_angeltype['TID']) {
        $entry = User_Nick_render(User($shift_entry['UID']));
        if ($shift_entry['freeloaded'])
          $entry = '<strike>' . $entry . '</strike>';
        if ($user_shift_admin) {
          $entry .= ' <div class="btn-group">';
          $entry .= button_glyph(page_link_to('user_myshifts') . '&edit=' . $shift_entry['id'] . '&id=' . $shift_entry['UID'], 'pencil', 'btn-xs');
          $entry .= button_glyph(page_link_to('user_shifts') . '&entry_id=' . $shift_entry['id'], 'trash', 'btn-xs');
          $entry .= '</div>';
        }
        $angels[] = $entry;
      }
    }
    
    $needed_angels .= join(', ', $angels);
    
    $needed_angels .= '</div>';
  }
  
  return page_with_title($shift['name'] . ' <small class="moment-countdown" data-timestamp="' . $shift['start'] . '">%c</small>', [
      
      msg(),
      Shift_collides($shift, $user_shifts) ? info(_('This shift collides with one of your shifts.'), true) : '',
      $signed_up ? info(_('You are signed up for this shift.'), true) : '',
      ($shift_admin || $admin_shifttypes || $admin_rooms) ? buttons([
          $shift_admin ? button(shift_edit_link($shift), glyph('pencil') . _('edit')) : '',
          $shift_admin ? button(shift_delete_link($shift), glyph('trash') . _('delete')) : '',
          $admin_shifttypes ? button(shifttype_link($shifttype), $shifttype['name']) : '',
          $admin_rooms ? button(room_link($room), glyph('map-marker') . $room['Name']) : '' 
      ]) : '',
      div('row', [
          div('col-sm-3 col-xs-6', [
              '<h4>' . _('Title') . '</h4>',
              '<p class="lead">' . ($shift['URL'] != '' ? '<a href="' . $shift['URL'] . '">' . $shift['title'] . '</a>' : $shift['title']) . '</p>' 
          ]),
          div('col-sm-3 col-xs-6', [
              '<h4>' . _('Start') . '</h4>',
              '<p class="lead' . (time() >= $shift['start'] ? ' text-success' : '') . '">',
              glyph('calendar') . date('Y-m-d', $shift['start']),
              '<br />',
              glyph('time') . date('H:i', $shift['start']),
              '</p>' 
          ]),
          div('col-sm-3 col-xs-6', [
              '<h4>' . _('End') . '</h4>',
              '<p class="lead' . (time() >= $shift['end'] ? ' text-success' : '') . '">',
              glyph('calendar') . date('Y-m-d', $shift['end']),
              '<br />',
              glyph('time') . date('H:i', $shift['end']),
              '</p>' 
          ]),
          div('col-sm-3 col-xs-6', [
              '<h4>' . _('Location') . '</h4>',
              '<p class="lead">' . glyph('map-marker') . $room['Name'] . '</p>' 
          ]) 
      ]),
      div('row', [
          div('col-sm-6', [
              '<h2>' . _('Needed angels') . '</h2>',
              '<div class="list-group">' . $needed_angels . '</div>' 
          ]),
          div('col-sm-6', [
              '<h2>' . _('Description') . '</h2>',
              $parsedown->parse($shifttype['description']) 
          ]) 
      ]),
      $shift_admin ? Shift_editor_info_render($shift) : '' 
  ]);
}

/**
 * Calc shift length in format 12:23h.
 *
 * @param Shift $shift          
 */
function shift_length($shift) {
  $length = floor(($shift['end'] - $shift['start']) / (60 * 60)) . ":";
  $length .= str_pad((($shift['end'] - $shift['start']) % (60 * 60)) / 60, 2, "0", STR_PAD_LEFT) . "h";
  return $length;
}
?>
