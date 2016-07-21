<?php

function guest_stats() {
  global $api_key;

  if(isset($_REQUEST['api_key'])) {
    if($_REQUEST['api_key'] == $api_key) {
      $stats = array();

      list($user_count) = sql_select("SELECT count(*) as `user_count` FROM `User`");
      $stats['user_count'] = $user_count['user_count'];

      list($arrived_user_count) = sql_select("SELECT count(*) as `user_count` FROM `User` WHERE `Gekommen`=1");
      $stats['arrived_user_count'] = $arrived_user_count['user_count'];

      $done_shifts_seconds = sql_select_single_cell("SELECT SUM(`Shifts`.`end` - `Shifts`.`start`) FROM `ShiftEntry` JOIN `Shifts` USING (`SID`) WHERE `Shifts`.`end` < UNIX_TIMESTAMP()");
      $stats['done_work_hours'] = round($done_shifts_seconds / (60*60), 0);

      $users_in_action_source = sql_select("SELECT `Shifts`.`start`, `Shifts`.`end` FROM `ShiftEntry` JOIN `Shifts` ON `Shifts`.`SID`=`ShiftEntry`.`SID` WHERE UNIX_TIMESTAMP() BETWEEN `Shifts`.`start` AND `Shifts`.`end`");
      $stats['users_in_action'] = count($users_in_action_source);

      header("Content-Type: application/json");
      die(json_encode($stats));
    } else die(json_encode(array('error' => "Wrong api_key.")));
  } else die(json_encode(array('error' => "Missing parameter api_key.")));
}
?>
