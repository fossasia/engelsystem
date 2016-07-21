<?php

function myshifts_title() {
  return _("My shifts");
}

// Displays the layers that has a user
function user_myshifts() {
  global $LETZTES_AUSTRAGEN;
  global $user, $privileges;
  $timezone_identifiers = DateTimeZone::listIdentifiers();
  $timezone = $user['timezone'];
  date_default_timezone_set ("$timezone_identifiers[$timezone]");
  $msg = "";

  if (isset($_REQUEST['id']) && in_array("user_shifts_admin", $privileges) && preg_match("/^[0-9]{1,}$/", $_REQUEST['id']) && count_users_by_id($_REQUEST['id']) > 0) {
    $id = $_REQUEST['id'];
  } else {
    $id = $user['UID'];
  }

  list($shifts_user) = user_by_id($id);

  if (isset($_REQUEST['reset'])) {
    if ($_REQUEST['reset'] == "ack") {
      User_reset_api_key($user);
      success(_("Key changed."));
      redirect(page_link_to('users') . '&action=view&user_id=' . $shifts_user['UID']);
    }
    return page_with_title(_("Reset API key"), array(
        error(_("If you reset the key, the url to your iCal- and JSON-export and your atom feed changes! You have to update it in every application using one of these exports."), true),
        button(page_link_to('user_myshifts') . '&reset=ack', _("Continue"), 'btn-danger')
    ));
  } elseif (isset($_REQUEST['edit']) && preg_match("/^[0-9]*$/", $_REQUEST['edit'])) {
    $id = $_REQUEST['edit'];
    $shift = select_shifts($id, $shifts_user['UID']);
    if (count($shift) > 0) {
      $shift = $shift[0];
      $freeloaded = $shift['freeloaded'];
      $freeload_comment = $shift['freeload_comment'];

      if (isset($_REQUEST['submit'])) {
        $ok = true;
        if (in_array("user_shifts_admin", $privileges)) {
          $freeloaded = isset($_REQUEST['freeloaded']);
          $freeload_comment = strip_request_item_nl('freeload_comment');
          if ($freeloaded && $freeload_comment == '') {
            $ok = false;
            error(_("Please enter a freeload comment!"));
          }
        }

        $comment = strip_request_item_nl('comment');
        $user_source = User($shift['UID']);

        if ($ok) {
          $result = ShiftEntry_update(array(
              'id' => $id,
              'Comment' => $comment,
              'freeloaded' => $freeloaded,
              'freeload_comment' => $freeload_comment
          ));
          if ($result === false)
            engelsystem_error('Unable to update shift entry.');

          engelsystem_log("Updated " . User_Nick_render($user_source) . "'s shift " . $shift['name'] . " from " . date("Y-m-d H:i", $shift['start']) . " to " . date("Y-m-d H:i", $shift['end']) . " with comment " . $comment . ". Freeloaded: " . ($freeloaded ? "YES Comment: " . $freeload_comment : "NO"));
          success(_("Shift saved."));
          redirect(page_link_to('users') . '&action=view&user_id=' . $shifts_user['UID']);
        }
      }

      return ShiftEntry_edit_view(User_Nick_render($shifts_user), date("Y-m-d H:i", $shift['start']) . ', ' . shift_length($shift), $shift['Name'], $shift['name'], $shift['angel_type'], $shift['Comment'], $shift['freeloaded'], $shift['freeload_comment'], in_array("user_shifts_admin", $privileges));
    } else
      redirect(page_link_to('user_myshifts'));
  } elseif (isset($_REQUEST['cancel']) && preg_match("/^[0-9]*$/", $_REQUEST['cancel'])) {
    $id = $_REQUEST['cancel'];
    $shift = shiftentry_select($id, $shifts_user['UID']);
    if (count($shift) > 0) {
      $shift = $shift[0];
      if (($shift['start'] > time() + $LETZTES_AUSTRAGEN * 3600) || in_array('user_shifts_admin', $privileges)) {
        $result = ShiftEntry_delete($id);
        if ($result === false)
          engelsystem_error('Unable to delete shift entry.');
        $room = Room($shift['RID']);
        $angeltype = AngelType($shift['TID']);
        $shifttype = ShiftType($shift['shifttype_id']);

        engelsystem_log("Deleted own shift: " . $shifttype['name'] . " at " . $room['Name'] . " from " . date("Y-m-d H:i", $shift['start']) . " to " . date("Y-m-d H:i", $shift['end']) . " as " . $angeltype['name']);
        success(_("Shift canceled."));
      } else
        error(_("It's too late to sign yourself off the shift. If neccessary, ask the dispatcher to do so."));
    } else
      redirect(user_link($shifts_user));
  }

  redirect(page_link_to('users') . '&action=view&user_id=' . $shifts_user['UID']);
}
?>
