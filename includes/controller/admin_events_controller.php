<?php

function admin_events_title() {
  return _("Create Events");
}

function admin_events() {

  global $user;
  $timezone_identifiers = DateTimeZone::listIdentifiers();
  $timezone = $user['timezone'];
  date_default_timezone_set ("$timezone_identifiers[$timezone]");

  $name = "";
  $description = "";
  $organizer = "";
  $start_date = DateTime::createFromFormat("Y-m-d H:i", date("Y-m-d") . " 00:00")->getTimestamp();
  $end_date = $start_date;
  $start_time = DateTime::createFromFormat("H:i", date("H:i") )->getTimestamp();
  $end_time = $start_time;
  $venue = "";

  if (isset($_REQUEST['submit'])) {
    $ok = true;

    if (isset($_REQUEST['name']))
      $name = strip_request_item('name');

  if (isset($_REQUEST['start_date']) && $tmp = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['start_date'])))
      $start_date = $tmp->getTimestamp();
  else {
      $ok = false;
      error(_('Please select a start date.'));
    }
    if (isset($_REQUEST['end_date']) && $tmp = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['end_date'])))
      $end_date = $tmp->getTimestamp();
    else {
      $ok = false;
      error(_('Please select an end date.'));
    }
    if (isset($_REQUEST['start_time']) && $tmp = DateTime::createFromFormat("H:i", trim($_REQUEST['start_time'])))
      $start_time = $tmp->getTimestamp();
    else {
      $ok = false;
      error(_('Please select an start time.'));
    }
    if (isset($_REQUEST['end_time']) && $tmp = DateTime::createFromFormat("H:i", trim($_REQUEST['end_time'])))
      $end_time = $tmp->getTimestamp();
    else {
      $ok = false;
      error(_('Please select an end time.'));
    }

    if (strtotime($_REQUEST['start_date']) > strtotime($_REQUEST['end_date'])) {
      $ok = false;
      error(_('The event end has to be after its start.'));
    }
    if (strtotime($_REQUEST['start_date']) == strtotime($_REQUEST['end_date'])) {
      if (strtotime($_REQUEST['start_time']) > strtotime($_REQUEST['end_time'])) {
        $ok = false;
        error(_('The events end time  has to be after its start time.'));
      }
    }
    if (strtotime($_REQUEST['start_date']) == strtotime($_REQUEST['end_date'])) {
      if (strtotime($_REQUEST['start_time']) == strtotime($_REQUEST['end_time'])) {
        $ok = false;
        error(_('The event start and end at same time.'));
      }
    }
    if (isset($_REQUEST['venue']))
      $venue = strip_request_item('venue');
    if (isset($_REQUEST['organizer']))
      $organizer = strip_request_item('organizer');
    if (isset($_REQUEST['description']))
      $description = strip_request_item_nl('description');

  }

  if ($ok) {
    event_create($name, $description, $organizer, $start_date, $start_time, $end_date, $end_time, $venue);

    success(_("New Event Created."));
    redirect(page_link_to('admin_events'));
}

  return page_with_title(admin_events_title(), array(
      msg(),
      form(array(
          form_text('name', _("Event Name"), $name),
          form_textarea('description', _("Event Description"), $description),
          form_text('organizer', _("Organizer Name"), $organizer),
          form_date('start_date', _("Start Date"), $start_date),
          form_text('start_time', _("Start Time"), date("H:i", $start_time)),
          form_date('end_date', _("End Date"), $end_date),
          form_text('end_time', _("End Time"), date("H:i", $end_time)),
          form_text('venue', _("Venue"), $venue),
          form_submit('submit', _("Save"))
      ))
  ));

}

?>