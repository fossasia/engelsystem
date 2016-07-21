<?php
// Publicly available function to retrieve iCal export their own layers
function user_ical() {
  global $ical_shifts, $user;

  if (isset ($_REQUEST['key']) && preg_match("/^[0-9a-f]{32}$/", $_REQUEST['key']))
    $key = $_REQUEST['key'];
  else
    die("Missing key.");

  $user = User_by_api_key($key);
  if($user === false)
    die("Unable to find user.");
  if($user == null)
    die("Key invalid.");
  if(!in_array('ical', privileges_for_user($user['UID'])))
    die("No privilege for ical.");

  if (isset ($_REQUEST['export']) && $_REQUEST['export'] == 'user_shifts') {
    require_once realpath(__DIR__ . '/user_shifts.php');
    view_user_shifts();
  } else {
    $ical_shifts = sql_select("
        SELECT `ShiftTypes`.`name`, `Shifts`.*, `Room`.`Name` as `room_name`
        FROM `ShiftEntry`
        INNER JOIN `Shifts` ON (`ShiftEntry`.`SID` = `Shifts`.`SID`)
        JOIN `ShiftTypes` ON (`ShiftTypes`.`id` = `Shifts`.`shifttype_id`)
        INNER JOIN `Room` ON (`Shifts`.`RID` = `Room`.`RID`)
        WHERE `UID`='" . sql_escape($user['UID']) . "'
        ORDER BY `start`");
  }

  header("Content-Type: text/calendar; charset=utf-8");
  $html = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//-//Engelsystem//DE\r\nCALSCALE:GREGORIAN\r\n";
  foreach ($ical_shifts as $shift) {
    $html .= "BEGIN:VEVENT\r\n";
    $html .= "UID:" . md5($shift['start'] . $shift['end'] . $shift['name']) . "\r\n";
    $html .= "SUMMARY:" . str_replace("\n", "\\n", $shift['name']) . " (" . str_replace("\n", "\\n", $shift['title']) . ")\r\n";
    if(isset($shift['Comment']))
      $html .= "DESCRIPTION:" . str_replace("\n", "\\n", $shift['Comment']) . "\r\n";
    $html .= "DTSTART;TZID=Europe/Berlin:" . date("Ymd\THis", $shift['start']) . "\r\n";
    $html .= "DTEND;TZID=Europe/Berlin:" . date("Ymd\THis", $shift['end']) . "\r\n";
    $html .= "LOCATION:" . $shift['room_name'] . "\r\n";
    $html .= "END:VEVENT\r\n";
  }
  $html .= "END:VCALENDAR\r\n";
  $html = trim($html, "\x0A");
  header("Content-Length: " . strlen($html));
  echo $html;
  die();
}
?>
