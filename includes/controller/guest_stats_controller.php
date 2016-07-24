<?php

function guest_stats() {
  global $api_key;

  if(isset($_REQUEST['api_key'])) {
    if($_REQUEST['api_key'] == $api_key) {
      $stats = array();

      list($user_count) = user_count();
      $stats['user_count'] = $user_count['user_count'];

      list($arrived_user_count) = user_count_arrived();
      $stats['arrived_user_count'] = $arrived_user_count['user_count'];

      $done_shifts_seconds = user_done_shifts();
      $stats['done_work_hours'] = round($done_shifts_seconds / (60*60), 0);

      $users_in_action_source = user_action_source();
      $stats['users_in_action'] = count($users_in_action_source);

      header("Content-Type: application/json");
      die(json_encode($stats));
    } else die(json_encode(array('error' => "Wrong api_key.")));
  } else die(json_encode(array('error' => "Missing parameter api_key.")));
}
?>
