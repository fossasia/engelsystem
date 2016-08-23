<?php

function admin_settings_title() {
  return _("Settings");
}

function admin_settings() {

  $ok = false;
  $msg = "";
  $event_name = "";
  $buildup_start_date = "";
  $event_start_date = "";
  $event_end_date = "";
  $teardown_end_date = "";
  $event_welcome_msg = "";
  $settings_source = Settings();
  if (count($settings_source) == 1) {
    $event_name = $settings_source[0]['event_name'];
    $buildup_start_date = $settings_source[0]['buildup_start_date'];
    $event_start_date = $settings_source[0]['event_start_date'];
    $event_end_date = $settings_source[0]['event_end_date'];
    $teardown_end_date = $settings_source[0]['teardown_end_date'];
    $event_welcome_msg = $settings_source[0]['event_welcome_msg'];
  }
  if (isset($_REQUEST['submit'])) {
    $ok = true;

    if (isset($_REQUEST['event_name']))
      $event_name = strip_request_item('event_name');

    if (isset($_REQUEST['buildup_start_date']) && $_REQUEST['buildup_start_date'] != '') {
      if (DateTime::createFromFormat("Y-m-d", trim($_REQUEST['buildup_start_date']))) {
        $buildup_start_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['buildup_start_date']))->getTimestamp();
      } else {
          $ok = false;
          $msg .= error(_("Please enter buildup start date."), true);
        }
    }

    if (isset($_REQUEST['event_start_date']) && $_REQUEST['event_start_date'] != '') {
      if (DateTime::createFromFormat("Y-m-d", trim($_REQUEST['event_start_date']))) {
        $event_start_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['event_start_date']))->getTimestamp();
      } else {
        $ok = false;
        $msg .= error(_("Please enter event start date."), true);
        }
    }

    if (isset($_REQUEST['event_end_date']) && $_REQUEST['event_end_date'] != '') {
      if (DateTime::createFromFormat("Y-m-d", trim($_REQUEST['event_end_date']))) {
        $event_end_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['event_end_date']))->getTimestamp();
      } else {
          $ok = false;
          $msg .= error(_("Please enter event end date."), true);
        }
    }

    if (isset($_REQUEST['teardown_end_date']) && $_REQUEST['teardown_end_date'] != '') {
      if (DateTime::createFromFormat("Y-m-d", trim($_REQUEST['teardown_end_date']))) {
        $teardown_end_date = DateTime::createFromFormat("Y-m-d", trim($_REQUEST['teardown_end_date']))->getTimestamp();
      } else {
        $ok = false;
        $msg .= error(_("Please enter teardown end date."), true);
      }
    }

    if (strtotime($_REQUEST['buildup_start_date']) <= strtotime($_REQUEST['event_start_date'])) {
      if (strtotime($_REQUEST['event_start_date']) <= strtotime($_REQUEST['event_end_date'])) {
        if (strtotime($_REQUEST['event_end_date']) <= strtotime($_REQUEST['teardown_end_date'])) {
          $ok = true;
        }
        else {
          $ok = false;
          $msg .= error(_("Please check the order of dates corrrect order is event end date <= teardown end date"));
        }
      }
      else {
        $ok = false;
        $msg .= error(_("Please check the order of dates corrrect order is event start date <= event end date"));
      }
    }
    else {
      $ok = false;
      $msg .= error(_("Please check the order of dates corrrect order is buildup start date <= event start date"));
    }

    if (isset($_REQUEST['event_welcome_msg']))
      $event_welcome_msg = strip_request_item('event_welcome_msg');
  }
  if ($ok) {
    if (count($settings_source) == 1)
      Settings_update($event_name, $buildup_start_date, $event_start_date, $event_end_date, $teardown_end_date, $event_welcome_msg);
    else
      Settings_create($event_name, $buildup_start_date, $event_start_date, $event_end_date, $teardown_end_date, $event_welcome_msg);

    success(_("Settings saved."));
    redirect(page_link_to('admin_settings'));
  }
  return page_with_title(admin_settings_title(), array(
      $msg,
      msg(),
      div('row', array(
        div('col-md-12', array(
              form(array(
                form_info('', _("Here you can change event information.")),
                form_text('event_name', _("Event Name"), $event_name),
                form_date('buildup_start_date', _("Buildup date"), $buildup_start_date, time()),
                form_date('event_start_date', _("Event start date"), $event_start_date, time()),
                form_date('event_end_date', _("Event end date"), $event_end_date, time()),
                form_date('teardown_end_date', _("Teardown end date"), $teardown_end_date, time()),
                form_info('', _("Here you can write your display message for registration:")),
                form_text('event_welcome_msg', _("Event Welcome Message"), $event_welcome_msg),
                form_submit('submit', _("Save"))
              ))
          ))
      ))
  ));
}
?>
