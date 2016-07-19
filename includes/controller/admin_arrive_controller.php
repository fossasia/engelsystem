<?php

function admin_arrive_title() {
  return _("Arrived angels");
}

function admin_arrive() {
  $msg = "";
  $search = "";
  if (isset($_REQUEST['search']))
    $search = strip_request_item('search');

  if (isset($_REQUEST['reset']) && preg_match("/^[0-9]*$/", $_REQUEST['reset'])) {
    $id = $_REQUEST['reset'];
    $user_source = User($id);
    if ($user_source != null) {
      User_update_unset_Gokemon($id);
      engelsystem_log("User set to not arrived: " . User_Nick_render($user_source));
      success(_("Reset done. Angel has not arrived."));
      redirect(user_link($user_source));
    } else
      $msg = error(_("Angel not found."), true);
  } elseif (isset($_REQUEST['arrived']) && preg_match("/^[0-9]*$/", $_REQUEST['arrived'])) {
    $id = $_REQUEST['arrived'];
    $user_source = User($id);
    if ($user_source != null) {
      User_update_set_Gokemon($id);
      engelsystem_log("User set has arrived: " . User_Nick_render($user_source));
      success(_("Angel has been marked as arrived."));
      redirect(user_link($user_source));
    } else
      $msg = error(_("Angel not found."), true);
  }

  $users = Users();
  $arrival_count_at_day = [];
  $planned_arrival_count_at_day = [];
  $planned_departure_count_at_day = [];
  $table = "";
  $users_matched = [];
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
    if ($usr['planned_departure_date'] != null)
      $usr['rendered_planned_departure_date'] = date('Y-m-d', $usr['planned_departure_date']);
    else
      $usr['rendered_planned_departure_date'] = '-';
    $usr['rendered_planned_arrival_date'] = date('Y-m-d', $usr['planned_arrival_date']);
    $usr['rendered_arrival_date'] = $usr['arrival_date'] > 0 ? date('Y-m-d', $usr['arrival_date']) : "-";
    $usr['arrived'] = $usr['Gekommen'] == 1 ? _("yes") : "";
    $usr['actions'] = $usr['Gekommen'] == 1 ? '<a href="' . page_link_to('admin_arrive_controller') . '&reset=' . $usr['UID'] . '&search=' . $search . '">' . _("reset") . '</a>' : '<a href="' . page_link_to('admin_arrive_controller') . '&arrived=' . $usr['UID'] . '&search=' . $search . '">' . _("arrived") . '</a>';

    if ($usr['arrival_date'] > 0) {
      $day = date('Y-m-d', $usr['arrival_date']);
      if (! isset($arrival_count_at_day[$day]))
        $arrival_count_at_day[$day] = 0;
      $arrival_count_at_day[$day] ++;
    }

    if ($usr['planned_arrival_date'] != null) {
      $day = date('Y-m-d', $usr['planned_arrival_date']);
      if (! isset($planned_arrival_count_at_day[$day]))
        $planned_arrival_count_at_day[$day] = 0;
      $planned_arrival_count_at_day[$day] ++;
    }

    if ($usr['planned_departure_date'] != null && $usr['Gekommen'] == 1) {
      $day = date('Y-m-d', $usr['planned_departure_date']);
      if (! isset($planned_departure_count_at_day[$day]))
        $planned_departure_count_at_day[$day] = 0;
      $planned_departure_count_at_day[$day] ++;
    }

    $users_matched[] = $usr;
  }

  ksort($arrival_count_at_day);
  ksort($planned_arrival_count_at_day);
  ksort($planned_departure_count_at_day);

  $arrival_at_day = [];
  $arrival_sum = 0;
  foreach ($arrival_count_at_day as $day => $count) {
    $arrival_sum += $count;
    $arrival_at_day[$day] = [
        'day' => $day,
        'count' => $count,
        'sum' => $arrival_sum
    ];
  }

  $planned_arrival_sum_at_day = [];
  $planned_arrival_sum = 0;
  foreach ($planned_arrival_count_at_day as $day => $count) {
    $planned_arrival_sum += $count;
    $planned_arrival_at_day[$day] = [
        'day' => $day,
        'count' => $count,
        'sum' => $planned_arrival_sum
    ];
  }

  $planned_departure_at_day = [];
  $planned_departure_sum = 0;
  foreach ($planned_departure_count_at_day as $day => $count) {
    $planned_departure_sum += $count;
    $planned_departure_at_day[$day] = [
        'day' => $day,
        'count' => $count,
        'sum' => $planned_departure_sum
    ];
  }

  return page_with_title(admin_arrive_title(), array(
      msg(),
      form(array(
          form_text('search', _("Search"), $search),
          form_submit('submit', _("Search"))
      )),
      table(array(
          'nick' => _("Nickname"),
          'rendered_planned_arrival_date' => _("Planned arrival"),
          'arrived' => _("Arrived?"),
          'rendered_arrival_date' => _("Arrival date"),
          'rendered_planned_departure_date' => _("Planned departure"),
          'actions' => ""
      ), $users_matched),
      div('row', [
          div('col-md-4', [
              heading(_("Planned arrival statistics"), 2),
              bargraph('planned_arrives', 'day', [
                  'count' => _("arrived"),
                  'sum' => _("arrived sum")
              ], [
                  'count' => '#090',
                  'sum' => '#888'
              ], $planned_arrival_at_day),
              table([
                  'day' => _("Date"),
                  'count' => _("Count"),
                  'sum' => _("Sum")
              ], $planned_arrival_at_day)
          ]),
          div('col-md-4', [
              heading(_("Arrival statistics"), 2),
              bargraph('arrives', 'day', [
                  'count' => _("arrived"),
                  'sum' => _("arrived sum")
              ], [
                  'count' => '#090',
                  'sum' => '#888'
              ], $arrival_at_day),
              table([
                  'day' => _("Date"),
                  'count' => _("Count"),
                  'sum' => _("Sum")
              ], $arrival_at_day)
          ]),
          div('col-md-4', [
              heading(_("Planned departure statistics"), 2),
              bargraph('planned_departures', 'day', [
                  'count' => _("arrived"),
                  'sum' => _("arrived sum")
              ], [
                  'count' => '#090',
                  'sum' => '#888'
              ], $planned_departure_at_day),
              table([
                  'day' => _("Date"),
                  'count' => _("Count"),
                  'sum' => _("Sum")
              ], $planned_departure_at_day)
          ])
      ])
  ));
}
?>
