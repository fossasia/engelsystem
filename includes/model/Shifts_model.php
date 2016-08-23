<?php

/**
 * Check if a shift collides with other shifts (in time).
 * @param Shift $shift
 * @param array<Shift> $shifts
 */
function Shift_collides($shift, $shifts) {
  foreach ($shifts as $other_shift)
    if ($shift['SID'] != $other_shift['SID'])
      if (! ($shift['start'] >= $other_shift['end'] || $shift['end'] <= $other_shift['start']))
        return true;
  return false;
}

/**
 * Check if an angel can sign up for given shift.
 *
 * @param Shift $shift
 * @param AngelType $angeltype
 * @param array<Shift> $user_shifts
 */
function Shift_signup_allowed($shift, $angeltype, $user_angeltype = null, $user_shifts = null) {
  global $user, $privileges;

  if ($user_shifts == null) {
    $user_shifts = Shifts_by_user($user);
    if ($user_shifts === false)
      engelsystem_error('Unable to load users shifts.');
  }

  $collides = Shift_collides($shift, $user_shifts);

  if ($user_angeltype == null) {
    $user_angeltype = UserAngelType_by_User_and_AngelType($user, $angeltype);
    if ($user_angeltype === false)
      engelsystem_error('Unable to load user angeltype.');
  }

  $signed_up = false;
  foreach ($user_shifts as $user_shift)
    if ($user_shift['SID'] == $shift['SID']) {
      $signed_up = true;
      break;
    }

  $needed_angeltypes = NeededAngelTypes_by_shift($shift['SID']);
  if ($needed_angeltypes === false)
    engelsystem_error('Unable to load needed angel types.');

    // is the shift still running or alternatively is the user shift admin?
  $user_may_join_shift = true;

  // you canot join if shift is full
  foreach ($needed_angeltypes as $needed_angeltype)
    if ($needed_angeltype['angel_type_id'] == $angeltype['id']) {
      if ($needed_angeltype['taken'] >= $needed_angeltype['count'])
        $user_may_join_shift = false;
      break;
    }

    // you cannot join if user alread joined a parallel or this shift
  $user_may_join_shift &= ! $collides;

  // you cannot join if you already singed up for this shift
  $user_may_join_shift &= ! $signed_up;

  // you cannot join if user is not of this angel type
  $user_may_join_shift &= $user_angeltype != null;

  // you cannot join if you are not confirmed
  if ($angeltype['restricted'] == 1 && $user_angeltype != null)
    $user_may_join_shift &= isset($user_angeltype['confirm_user_id']);

    // you can only join if the shift is in future
  $user_may_join_shift &= time() < $shift['start'];

  // User shift admins may join anybody in every shift
  $user_may_join_shift |= in_array('user_shifts_admin', $privileges);

  return $user_may_join_shift;
}

/**
 * Delete a shift by its external id.
 */
function Shift_delete_by_psid($shift_psid) {
  return sql_query("DELETE FROM `Shifts` WHERE `PSID`='" . sql_escape($shift_psid) . "'");
}

/**
 * Delete a shift.
 */
function Shift_delete($shift_id) {
  mail_shift_delete(Shift($shift_id));

  return sql_query("DELETE FROM `Shifts` WHERE `SID`='" . sql_escape($shift_id) . "'");
}

/**
 * Update a shift.
 */
function Shift_update($shift) {
  global $user;
  $shift['name'] = ShiftType($shift['shifttype_id'])['name'];
  mail_shift_change(Shift($shift['SID']), $shift);

  return sql_query("UPDATE `Shifts` SET
      `shifttype_id`='" . sql_escape($shift['shifttype_id']) . "',
      `start`='" . sql_escape($shift['start']) . "',
      `end`='" . sql_escape($shift['end']) . "',
      `RID`='" . sql_escape($shift['RID']) . "',
      `title`=" . sql_null($shift['title']) . ",
      `URL`=" . sql_null($shift['URL']) . ",
      `PSID`=" . sql_null($shift['PSID']) . ",
      `edited_by_user_id`='" . sql_escape($user['UID']) . "',
      `edited_at_timestamp`=" . time() . "
      WHERE `SID`='" . sql_escape($shift['SID']) . "'");
}

/**
 * Update a shift by its external id.
 */
function Shift_update_by_psid($shift) {
  $shift_source = sql_select("SELECT `SID` FROM `Shifts` WHERE `PSID`=" . $shift['PSID']);
  if ($shift_source === false)
    return false;
  if (count($shift_source) == 0)
    return null;
  $shift['SID'] = $shift_source[0]['SID'];
  return Shift_update($shift);
}

/**
 * Create a new shift.
 *
 * @return new shift id or false
 */
function Shift_create($shift) {
  global $user;
  $result = sql_query("INSERT INTO `Shifts` SET
      `shifttype_id`='" . sql_escape($shift['shifttype_id']) . "',
      `start`='" . sql_escape($shift['start']) . "',
      `end`='" . sql_escape($shift['end']) . "',
      `RID`='" . sql_escape($shift['RID']) . "',
      `title`=" . sql_null($shift['title']) . ",
      `URL`=" . sql_null($shift['URL']) . ",
      `PSID`=" . sql_null($shift['PSID']) . ",
      `created_by_user_id`='" . sql_escape($user['UID']) . "',
      `created_at_timestamp`=" . time());
  if ($result === false)
    return false;
  return sql_id();
}

/**
 * Return users shifts.
 */
function Shifts_by_user($user) {
  return sql_select("
      SELECT `ShiftTypes`.`id` as `shifttype_id`, `ShiftTypes`.`name`, `ShiftEntry`.*, `Shifts`.*, `Room`.*
      FROM `ShiftEntry`
      JOIN `Shifts` ON (`ShiftEntry`.`SID` = `Shifts`.`SID`)
      JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
      JOIN `Room` ON (`Shifts`.`RID` = `Room`.`RID`)
      WHERE `UID`='" . sql_escape($user['UID']) . "'
      ORDER BY `start`
      ");
}

/**
 * TODO: $_REQUEST is not allowed in model!
 * Returns Shift id array
 */
function Shifts_filtered() {
  global $_REQUEST;
  $filter = "";

  // filterRoom (Array of integer) - Array of Room IDs (optional, for list request)
  if (isset($_REQUEST['filterRoom']) && is_array($_REQUEST['filterRoom'])) {
    foreach ($_REQUEST['filterRoom'] as $key => $value) {
      $filter .= ", `RID`='" . sql_escape($value) . "' ";
    }
  }

  // filterTask (Array of integer) - Array if Task (optional, for list request)
  if (isset($_REQUEST['filterTask']) && is_array($_REQUEST['filterTask'])) {
    foreach ($_REQUEST['filterTask'] as $key => $value) {
      // TODO $filter .= ", `RID`=" . sql_escape($value) . " ";
    }
  }

  // filterOccupancy (integer) - Occupancy state: (optional, for list request)
  // 1 occupied, 2 free, 3 occupied and free
  if (isset($_REQUEST['filterOccupancy']) && is_array($_REQUEST['filterOccupancy'])) {
    foreach ($_REQUEST['filterOccupancy'] as $key => $value) {
      // TODO $filter .= ", `RID`=" . sql_escape($value) . " ";
    }
  }

  // format filter
  if ($filter != "") {
    $filter = ' WHERE ' . substr($filter, 1);
  }

  // real request
  $shifts_source = sql_select("SELECT `SID` FROM `Shifts`" . $filter);
  if ($shifts_source === false)
    return false;
  if (count($shifts_source) > 0) {
    return $shifts_source;
  }
  return null;
}

/**
 * Returns Shift by id.
 *
 * @param $id Shift
 *          ID
 */
function Shift($id) {
  $shifts_source = sql_select("
      SELECT `Shifts`.*, `ShiftTypes`.`name`
      FROM `Shifts`
      JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
      WHERE `SID`='" . sql_escape($id) . "'");
  $shiftsEntry_source = sql_select("SELECT `id`, `TID` , `UID` , `freeloaded` FROM `ShiftEntry` WHERE `SID`='" . sql_escape($id) . "'");

  if ($shifts_source === false)
    return false;
  if (count($shifts_source) > 0) {
    $result = $shifts_source[0];

    $result['ShiftEntry'] = $shiftsEntry_source;
    $result['NeedAngels'] = [];

    $temp = NeededAngelTypes_by_shift($id);
    foreach ($temp as $e) {
      $result['NeedAngels'][] = array(
          'TID' => $e['angel_type_id'],
          'count' => $e['count'],
          'restricted' => $e['restricted'],
          'taken' => $e['taken']
      );
    }

    return $result;
  }
  return null;
}

/**
 * Returns all shifts with needed angeltypes and count of subscribed jobs.
 *
 */
function Shifts() {
  $shifts_source = sql_select("
    SELECT `ShiftTypes`.`name`, `Shifts`.*, `Room`.`RID`, `Room`.`Name` as `room_name`
    FROM `Shifts`
    JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
    JOIN `Room` ON `Room`.`RID` = `Shifts`.`RID`
    ");
  if ($shifts_source === false)
    return false;

  foreach ($shifts_source as &$shift) {
    $needed_angeltypes = NeededAngelTypes_by_shift($shift['SID']);
    if ($needed_angeltypes === false)
      return false;

    $shift['angeltypes'] = $needed_angeltypes;
  }

  return $shifts_source;
}

/**
 * Returns shifts ical for given Users.
 *
 * @param $uid ID of User
 */
function shifts_ical($uid) {
  return sql_select("
      SELECT `ShiftTypes`.`name`, `Shifts`.*, `Room`.`Name` as `room_name`
      FROM `ShiftEntry`
      INNER JOIN `Shifts` ON (`ShiftEntry`.`SID` = `Shifts`.`SID`)
      INNER JOIN `ShiftTypes` ON (`Shifts`.`shifttype_id`=`ShiftTypes`.`id`)
      INNER JOIN `Room` ON (`Shifts`.`RID` = `Room`.`RID`)
      WHERE `UID`='" . sql_escape($uid) . "'
      ORDER BY `start`");
}

/**
 * Returns all shifts for given User.
 *
 * @param $sid ID of Shifts
 * @param $uid ID of Users
 */
function shiftentry_select($sid, $uid) {
  return sql_select("
      SELECT *
      FROM `Shifts`
      INNER JOIN `ShiftEntry` USING (`SID`)
      WHERE `ShiftEntry`.`id`='" . sql_escape($sid) . "' AND `UID`='" . sql_escape($uid) . "'");
}

/**
 * Returns all shifts for the session.
 *
 * @param $session_var of Rooms
 * @param $starttime Start time of Shifts
 * @param $endtime End Time of Shifts
 */
function selects_session_shifttypes($session_var, $starttime, $endtime) {
  return sql_select("
      SELECT `ShiftTypes`.`name`, `Shifts`.*
      FROM `Shifts`
      INNER JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
      INNER JOIN `ShiftEntry` ON (`Shifts`.`SID` = `ShiftEntry`.`SID` AND `ShiftEntry`.`UID` = '" . sql_escape($user['UID']) . "')
      WHERE `Shifts`.`RID` IN (" . implode(',', $session_var) . ")
      AND `start` BETWEEN " . $starttime . " AND " . $endtime);
}

/**
 * Returns Days from Shifts .
 *
 */
function gets_days() {
  return sql_select_single_col("
      SELECT DISTINCT DATE(FROM_UNIXTIME(`start`)) AS `id`, DATE(FROM_UNIXTIME(`start`)) AS `name`
      FROM `Shifts`
      ORDER BY `start`");
}

/**
 * Delete Shifts.
 *
 * @param $sid ID of Shifts
 */
function dele_shifts($sid) {
  return sql_query("DELETE FROM `Shifts` WHERE `SID` = '" . sql_escape($sid) . "'");
}

/**
 * Return Shifts by ID
 *
 * @param $shift_id ID of Shifts
 */
function selects_shift_by_shift_ids($shift_id) {
  return sql_select("
      SELECT `ShiftTypes`.`name`, `Shifts`.*, `Room`.* FROM `Shifts`
      JOIN `Room` ON (`Shifts`.`RID` = `Room`.`RID`)
      JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
      WHERE `SID`='" . sql_escape($shift_id) . "'");
}

/**
 * Return Shifts by Start time
 *
 */
function Shifts_by_start() {
  return sql_select("SELECT * FROM `Shifts` WHERE `PSID` IS NOT NULL ORDER BY `start`");
}

?>
