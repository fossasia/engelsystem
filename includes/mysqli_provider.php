<?php

/**
 * Close connection.
 */
function sql_close() {
  global $sql_connection;

  return $sql_connection->close();
}

/**
 * Return NULL if given value is null.
 */
function sql_null($value = null) {
  return $value == null ? 'NULL' : ("'" . sql_escape($value) . "'");
}

/**
 * Start new transaction.
 */
function sql_transaction_start() {
  global $sql_nested_transaction_level;

  if ($sql_nested_transaction_level ++ == 0)
    return sql_query("BEGIN");
  else
    return true;
}

/**
 * Commit transaction.
 */
function sql_transaction_commit() {
  global $sql_nested_transaction_level;

  if (-- $sql_nested_transaction_level == 0)
    return sql_query("COMMIT");
  else
    return true;
}

/**
 * Stop transaction, revert database.
 */
function sql_transaction_rollback() {
  global $sql_nested_transaction_level;

  if (-- $sql_nested_transaction_level == 0)
    return sql_query("ROLLBACK");
  else
    return true;
}

/**
 * Logs an sql error.
 *
 * @param string $message
 * @return false
 */
function sql_error($message) {
  sql_close();

  $message = trim($message) . "\n";
  $message = debug_string_backtrace() . "\n";

  error_log('mysql_provider error: ' . $message);

  return false;
}

/**
 * Connect to mysql server.
 *
 * @param string $host
 *          Host
 * @param string $user
 *          Username
 * @param string $pass
 *          Password
 * @param string $db
 *          DB to select
 * @return mysqli The connection handler
 */
function sql_connect($host, $user, $pass, $db) {
  global $sql_connection;

  $sql_connection = new mysqli($host, $user, $pass, $db);
  if ($sql_connection->connect_errno) {
      error("Unable to connect to MySQL: " . $sql_connection->connect_error);
    return sql_error("Unable to connect to MySQL: " . $sql_connection->connect_error);
  }

  $result = $sql_connection->query("SET CHARACTER SET utf8;");
  if (! $result)
    return sql_error("Unable to set utf8 character set (" . $sql_connection->errno . ") " . $sql_connection->error);

  $result = $sql_connection->set_charset('utf8');
  if (! $result)
    return sql_error("Unable to set utf8 names (" . $sql_connection->errno . ") " . $sql_connection->error);

  return $sql_connection;
}

/**
 * Change the selected db in current mysql-connection.
 *
 * @param
 *          $db_name
 * @return bool true on success, false on error
 */
function sql_select_db($db_name) {
  global $sql_connection;
  if (! $sql_connection->select_db($db_name))
    return sql_error("No database selected.");
  return true;
}

/**
 * MySQL SELECT query
 *
 * @param string $query
 * @return Result array or false on error
 */
function sql_select($query) {
  global $sql_connection;

  $result = $sql_connection->query($query);
  if ($result) {
    $data = array();
    while ($line = $result->fetch_assoc())
      array_push($data, $line);
    return $data;
  } else
    return sql_error("MySQL-query error: " . $query . " (" . $sql_connection->errno . ") " . $sql_connection->error);
}

/**
 * MySQL execute a query
 *
 * @param string $query
 * @return mysqli_result boolean resource or false on error
 */
function sql_query($query) {
  global $sql_connection;

  $result = $sql_connection->query($query);
  if ($result) {
    return $result;
  } else
    return sql_error("MySQL-query error: " . $query . " (" . $sql_connection->errno . ") " . $sql_connection->error);
}

/**
 * Returns last inserted id.
 *
 * @return int
 */
function sql_id() {
  global $sql_connection;
  return $sql_connection->insert_id;
}

/**
 * Escape a string for a sql query.
 *
 * @param string $query
 * @return string
 */
function sql_escape($query) {
  global $sql_connection;
  return $sql_connection->real_escape_string($query);
}

/**
 * Convert a boolean for mysql-queries.
 *
 * @param boolean $boolean
 * @return string
 */
function sql_bool($boolean) {
  return $boolean == true ? 'TRUE' : 'FALSE';
}

/**
 * Count query result lines.
 *
 * @param string $query
 * @return int Count of result lines
 */
function sql_num_query($query) {
  global $sql_connection;
  return sql_query($query)->num_rows;
}

function sql_select_single_col($query) {
  $result = sql_select($query);
  return array_map('array_shift', $result);
}

function sql_select_single_cell($query) {
  return array_shift(array_shift(sql_select($query)));
}

?>
