<?php

function admin_import_title() {
  return _("Frab import");
}

function admin_import() {
  global $rooms_import;
  global $user;
  $html = "";

  $step = "input";
  if (isset($_REQUEST['step']) && in_array($step, [
      'input',
      'check',
      'import'
  ]))
    $step = $_REQUEST['step'];

  if ($test_handle = fopen('../import/tmp', 'w')) {
    fclose($test_handle);
    unlink('../import/tmp');
  } else {
    error(_('Webserver has no write-permission on import directory.'));
  }

  $import_file = '../import/import_' . $user['UID'] . '.xml';
  $shifttype_id = null;
  $add_minutes_start = 15;
  $add_minutes_end = 15;

  $shifttypes_source = ShiftTypes();
  if ($shifttypes_source === false)
    engelsystem_error('Unable to load shifttypes.');
  $shifttypes = [];
  foreach ($shifttypes_source as $shifttype)
    $shifttypes[$shifttype['id']] = $shifttype['name'];

  switch ($step) {
    case 'input':
      $ok = false;

      if (isset($_REQUEST['submit'])) {
        $ok = true;

        if (isset($_REQUEST['shifttype_id']) && isset($shifttypes[$_REQUEST['shifttype_id']]))
          $shifttype_id = $_REQUEST['shifttype_id'];
        else {
          $ok = false;
          error(_('Please select a shift type.'));
        }

        if (isset($_REQUEST['add_minutes_start']) && is_numeric(trim($_REQUEST['add_minutes_start'])))
          $add_minutes_start = trim($_REQUEST['add_minutes_start']);
        else {
          $ok = false;
          error(_("Please enter an amount of minutes to add to a talk's begin."));
        }

        if (isset($_REQUEST['add_minutes_end']) && is_numeric(trim($_REQUEST['add_minutes_end'])))
          $add_minutes_end = trim($_REQUEST['add_minutes_end']);
        else {
          $ok = false;
          error(_("Please enter an amount of minutes to add to a talk's end."));
        }

        if (isset($_FILES['xcal_file']) && ($_FILES['xcal_file']['error'] == 0)) {
          if (move_uploaded_file($_FILES['xcal_file']['tmp_name'], $import_file)) {
            libxml_use_internal_errors(true);
            if (simplexml_load_file($import_file) === false) {
              $ok = false;
              error(_('No valid xml/xcal file provided.'));
              unlink($import_file);
            }
          } else {
            $ok = false;
            error(_('File upload went wrong.'));
          }
        } else {
          $ok = false;
          error(_('Please provide some data.'));
        }
      }
      if ($ok) {
        redirect(page_link_to('admin_import') . "&step=check&shifttype_id=" . $shifttype_id . "&add_minutes_end=" . $add_minutes_end . "&add_minutes_start=" . $add_minutes_start);
      } else {
        $html .= div('well well-sm text-center', [
            _('Frab import')
        ]) . div('well well-sm text-left', [
            _('File Upload') . mute(glyph('arrow-right')) . mute(_('Validation')) . mute(glyph('arrow-right')) . mute(_('Import'))
        ]) . div('row', [
            div(' col-md-6', [
                form(array(
                    form_info('', _("This import will create/update/delete rooms and shifts by given FRAB-export file. The needed file format is xcal.")),
                    form_select('shifttype_id', _('Shifttype'), $shifttypes, $shifttype_id),
                    form_spinner('add_minutes_start', _("Add minutes to start"), $add_minutes_start),
                    form_spinner('add_minutes_end', _("Add minutes to end"), $add_minutes_end),
                    form_file('xcal_file', _("xcal-File (.xcal)")),
                    form_submit('submit', _("Import"))
                ))
            ])
        ]);
      }
      break;

    case 'check':
      if (! file_exists($import_file)) {
        error(_('Missing import file.'));
        redirect(page_link_to('admin_import'));
      }

      if (isset($_REQUEST['shifttype_id']) && isset($shifttypes[$_REQUEST['shifttype_id']]))
        $shifttype_id = $_REQUEST['shifttype_id'];
      else {
        error(_('Please select a shift type.'));
        redirect(page_link_to('admin_import'));
      }

      if (isset($_REQUEST['add_minutes_start']) && is_numeric(trim($_REQUEST['add_minutes_start'])))
        $add_minutes_start = trim($_REQUEST['add_minutes_start']);
      else {
        error(_("Please enter an amount of minutes to add to a talk's begin."));
        redirect(page_link_to('admin_import'));
      }

      if (isset($_REQUEST['add_minutes_end']) && is_numeric(trim($_REQUEST['add_minutes_end'])))
        $add_minutes_end = trim($_REQUEST['add_minutes_end']);
      else {
        error(_("Please enter an amount of minutes to add to a talk's end."));
        redirect(page_link_to('admin_import'));
      }

      list($rooms_new, $rooms_deleted) = prepare_rooms($import_file);
      list($events_new, $events_updated, $events_deleted) = prepare_events($import_file, $shifttype_id, $add_minutes_start, $add_minutes_end);

      $html .= div('well well-sm text-center', [
          '<span class="text-success">' . _('File Upload') . glyph('ok-circle') . '</span>' . mute(glyph('arrow-right')) . _('Validation') . mute(glyph('arrow-right')) . mute(_('Import'))
      ]) . form([
          div('row', [
              div('col-sm-6', [
                  '<h3>' . _("Rooms to create") . '</h3>',
                  table(_("Name"), $rooms_new)
              ]),
              div('col-sm-6', [
                  '<h3>' . _("Rooms to delete") . '</h3>',
                  table(_("Name"), $rooms_deleted)
              ])
          ]),
          '<h3>' . _("Shifts to create") . '</h3>',
          table(array(
              'day' => _("Day"),
              'start' => _("Start"),
              'end' => _("End"),
              'shifttype' => _('Shift type'),
              'title' => _("Title"),
              'room' => _("Room")
          ), shifts_printable($events_new, $shifttypes)),
          '<h3>' . _("Shifts to update") . '</h3>',
          table(array(
              'day' => _("Day"),
              'start' => _("Start"),
              'end' => _("End"),
              'shifttype' => _('Shift type'),
              'title' => _("Title"),
              'room' => _("Room")
          ), shifts_printable($events_updated, $shifttypes)),
          '<h3>' . _("Shifts to delete") . '</h3>',
          table(array(
              'day' => _("Day"),
              'start' => _("Start"),
              'end' => _("End"),
              'shifttype' => _('Shift type'),
              'title' => _("Title"),
              'room' => _("Room")
          ), shifts_printable($events_deleted, $shifttypes)),
          form_submit('submit', _("Import"))
      ], page_link_to('admin_import') . '&step=import&shifttype_id=' . $shifttype_id . "&add_minutes_end=" . $add_minutes_end . "&add_minutes_start=" . $add_minutes_start);
      break;

    case 'import':
      if (! file_exists($import_file)) {
        error(_('Missing import file.'));
        redirect(page_link_to('admin_import'));
      }

      if (! file_exists($import_file))
        redirect(page_link_to('admin_import'));

      if (isset($_REQUEST['shifttype_id']) && isset($shifttypes[$_REQUEST['shifttype_id']]))
        $shifttype_id = $_REQUEST['shifttype_id'];
      else {
        error(_('Please select a shift type.'));
        redirect(page_link_to('admin_import'));
      }

      if (isset($_REQUEST['add_minutes_start']) && is_numeric(trim($_REQUEST['add_minutes_start'])))
        $add_minutes_start = trim($_REQUEST['add_minutes_start']);
      else {
        error(_("Please enter an amount of minutes to add to a talk's begin."));
        redirect(page_link_to('admin_import'));
      }

      if (isset($_REQUEST['add_minutes_end']) && is_numeric(trim($_REQUEST['add_minutes_end'])))
        $add_minutes_end = trim($_REQUEST['add_minutes_end']);
      else {
        error(_("Please enter an amount of minutes to add to a talk's end."));
        redirect(page_link_to('admin_import'));
      }

      list($rooms_new, $rooms_deleted) = prepare_rooms($import_file);
      foreach ($rooms_new as $room) {
        $result = Room_create($room, true, true);
        if ($result === false)
          engelsystem_error('Unable to create room.');
        $rooms_import[trim($room)] = sql_id();
      }
      foreach ($rooms_deleted as $room)
        delete_room_by_name ($room);

      list($events_new, $events_updated, $events_deleted) = prepare_events($import_file, $shifttype_id, $add_minutes_start, $add_minutes_end);
      foreach ($events_new as $event) {
        $result = Shift_create($event);
        if ($result === false)
          engelsystem_error('Unable to create shift.');
      }

      foreach ($events_updated as $event) {
        $result = Shift_update_by_psid($event);
        if ($result === false)
          engelsystem_error('Unable to update shift.');
      }

      foreach ($events_deleted as $event) {
        $result = Shift_delete_by_psid($event['PSID']);
        if ($result === false)
          engelsystem_error('Unable to delete shift.');
      }

      engelsystem_log("Pentabarf import done");

      unlink($import_file);

      $html .= div('well well-sm text-center', [
          '<span class="text-success">' . _('File Upload') . glyph('ok-circle') . '</span>' . mute(glyph('arrow-right')) . '<span class="text-success">' . _('Validation') . glyph('ok-circle') . '</span>' . mute(glyph('arrow-right')) . '<span class="text-success">' . _('Import') . glyph('ok-circle') . '</span>'
      ]) . success(_("It's done!"), true);
      break;
    default:
      redirect(page_link_to('admin_import'));
  }

  return page_with_title(admin_import_title(), [
      msg(),
      $html
  ]);
}
function prepare_rooms($file) {
  global $rooms_import;
  $data = read_xml($file);

  // Load rooms from db for compare with input
  $rooms = Room_by_FromPentabarf();
  $rooms_db = array();
  $rooms_import = array();
  foreach ($rooms as $room) {
    $rooms_db[] = (string) $room['Name'];
    $rooms_import[$room['Name']] = $room['RID'];
  }

  $events = $data->vcalendar->vevent;
  $rooms_pb = array();
  foreach ($events as $event) {
    $rooms_pb[] = (string) $event->location;
    if (! isset($rooms_import[trim($event->location)]))
      $rooms_import[trim($event->location)] = trim($event->location);
  }
  $rooms_pb = array_unique($rooms_pb);

  $rooms_new = array_diff($rooms_pb, $rooms_db);
  $rooms_deleted = array_diff($rooms_db, $rooms_pb);

  return array(
      $rooms_new,
      $rooms_deleted
  );
}
function prepare_events($file, $shifttype_id, $add_minutes_start, $add_minutes_end) {
  global $rooms_import;
  $data = read_xml($file);

  $rooms = Room_all();
  $rooms_db = array();
  foreach ($rooms as $room)
    $rooms_db[$room['Name']] = $room['RID'];

  $events = $data->vcalendar->vevent;
  $shifts_pb = array();
  foreach ($events as $event) {
    $event_pb = $event->children("http://pentabarf.org");
    $event_id = trim($event_pb->{
      'event-id' });
    $shifts_pb[$event_id] = array(
        'shifttype_id' => $shifttype_id,
        'start' => DateTime::createFromFormat("Ymd\THis", $event->dtstart)->getTimestamp() - $add_minutes_start * 60,
        'end' => DateTime::createFromFormat("Ymd\THis", $event->dtend)->getTimestamp() + $add_minutes_end * 60,
        'RID' => $rooms_import[trim($event->location)],
        'title' => trim($event->summary),
        'URL' => trim($event->url),
        'PSID' => $event_id
    );
  }

  $shifts = Shifts_by_start();
  $shifts_db = array();
  foreach ($shifts as $shift)
    $shifts_db[$shift['PSID']] = $shift;

  $shifts_new = [];
  $shifts_updated = [];
  foreach ($shifts_pb as $shift)
    if (! isset($shifts_db[$shift['PSID']]))
      $shifts_new[] = $shift;
    else {
      $tmp = $shifts_db[$shift['PSID']];
      if ($shift['shifttype_id'] != $tmp['shifttype_id'] || $shift['title'] != $tmp['title'] || $shift['start'] != $tmp['start'] || $shift['end'] != $tmp['end'] || $shift['RID'] != $tmp['RID'] || $shift['URL'] != $tmp['URL'])
        $shifts_updated[] = $shift;
    }

  $shifts_deleted = array();
  foreach ($shifts_db as $shift)
    if (! isset($shifts_pb[$shift['PSID']]))
      $shifts_deleted[] = $shift;

  return array(
      $shifts_new,
      $shifts_updated,
      $shifts_deleted
  );
}
function read_xml($file) {
  global $xml_import;
  if (! isset($xml_import))
    $xml_import = simplexml_load_file($file);
  return $xml_import;
}
function shifts_printable($shifts, $shifttypes) {
  global $rooms_import;
  $rooms = array_flip($rooms_import);

  uasort($shifts, 'shift_sort');

  $shifts_printable = array();
  foreach ($shifts as $shift)
    $shifts_printable[] = array(
        'day' => date("l, Y-m-d", $shift['start']),
        'start' => date("H:i", $shift['start']),
        'shifttype' => ShiftType_name_render([
            'id' => $shift['shifttype_id'],
            'name' => $shifttypes[$shift['shifttype_id']]
        ]),
        'title' => shorten($shift['title']),
        'end' => date("H:i", $shift['end']),
        'room' => $rooms[$shift['RID']]
    );
  return $shifts_printable;
}

function shift_sort($a, $b) {
  return ($a['start'] < $b['start']) ? - 1 : 1;
}
?>
