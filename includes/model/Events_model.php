<?php

/**
 * Delete an Event.
 *
 * @param event $event
 */
function event_delete($event) {
  return sql_query("
      DELETE FROM `Events`
      WHERE `event_id`='" . sql_escape($event['event_id']) . "'
      LIMIT 1");
}

/**
 * Update Event.
 *
 * @param int $event_id
 * @param string $name
 * @param string $description
 * @param string $organizer
 * @param datetime $start_date
 * @param datetime $end_date
 * @param string $venue
 */
function event_update($event_id, $name, $description, $organizer, $start_date, $start_time, $end_date, $end_time, $venue) {
  return sql_query("
      UPDATE `Events` SET
      `name`='" . sql_escape($name) . "',
      `description`='" . sql_escape($description) . "',
      `organizer`='" . sql_escape($organizer) . "',
      `start_date`='" . sql_escape($start_date) . "',
      `start_time`='" . sql_escape($start_time) . "',
      `end_date`='" . sql_escape($end_date) . "',
      `end_time`='" . sql_escape($end_time) . "',
      `venue`='" . sql_escape($venue) . "'
      WHERE `id`='" . sql_escape($event_id) . "'
      LIMIT 1");
}

/**
 * Create an event.
 *
 * @param int $event_id
 * @param string $name
 * @param string $description
 * @param string $organizer
 * @param datetime $start_date
 * @param datetime $end_date
 * @param string $venue
 */
function event_create($name, $description, $organizer, $start_date, $start_time, $end_date, $end_time, $venue) {
  $result = sql_query("
      INSERT INTO `Events` SET
      `name`='" . sql_escape($name) . "',
      `description`='" . sql_escape($description) . "',
      `organizer`='" . sql_escape($organizer) . "',
      `start_date`='" . sql_escape($start_date) . "',
      `start_time`='" . sql_escape($start_time) . "',
      `end_date`='" . sql_escape($end_date) . "',
      `end_time`='" . sql_escape($end_time) . "',
      `venue`='" . sql_escape($venue));
  if ($result === false)
    return false;
  return sql_id();
}

/**
 * Validates a name for events.
 * Returns array containing validation success and validated name.
 *
 * @param string $name
 * @param event $event
 */
function event_validate_name($name, $event) {
  $name = strip_item($name);
  if ($name == "")
    return array(
        false,
        $name
    );
  if (isset($event) && isset($event['event_id']))
    return array(
        sql_num_query("
        SELECT *
        FROM `Events`
        WHERE `name`='" . sql_escape($name) . "'
        AND NOT `event_id`='" . sql_escape($event['event_id']) . "'
        LIMIT 1") == 0,
        $name
    );
  else
    return array(
        sql_num_query("
        SELECT `event_id`
        FROM `Events`
        WHERE `name`='" . sql_escape($name) . "'
        LIMIT 1") == 0,
        $name
    );
}

/**
 * Returns all events.
 */
function events() {
  return sql_select("
      SELECT *
      FROM `Events`
      ORDER BY `name`");
}

/**
 * Returns event id array
 */
function event_ids() {
  $event_source = sql_select("SELECT `event_id` FROM `Events`");
  if ($event_source === false)
    return false;
  if (count($event_source) > 0)
    return $event_source;
  return null;
}

/**
 * Returns event by id.
 *
 * @param $id event
 *          ID
 */
function event($id) {
  $event_source = sql_select("SELECT * FROM `Events` WHERE `event_id`='" . sql_escape($id) . "' LIMIT 1");
  if ($event_source === false)
    return false;
  if (count($event_source) > 0)
    return $event_source[0];
  return null;
}

/**
 * Returns event by name.
 *
 * @param $name event
 *          NAME
 */
function event_name($name) {
  $event_source = sql_select("SELECT * FROM `Events` WHERE `name`='" . sql_escape($name) . "' LIMIT 1");
  if ($event_source === false)
    return false;
  if (count($event_source) > 0)
    return $event_source[0];
  return null;
}

/**
 * Returns event by venue.
 *
 * @param $venue event
 *          VENUE
 */
function event_venue($venue) {
  $event_source = sql_select("SELECT * FROM `Events` WHERE `venue`='" . sql_escape($venue) . "' ");
  if ($event_source === false)
    return false;
  if (count($event_source) > 0)
    return $event_source[0];
  return null;
}

/**
 * Returns event by organizer.
 *
 * @param $organizer event
 *          ORGANIZER
 */
function event_organizer($organizer) {
  $event_source = sql_select("SELECT * FROM `Events` WHERE `organizer`='" . sql_escape($organizer) . "' ");
  if ($event_source === false)
    return false;
  if (count($event_source) > 0)
    return $event_source[0];
  return null;
}

?>
