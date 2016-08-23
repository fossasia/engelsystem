<?php

/**
 * Returns Temporary table (for exporting database)
 *
 */
function create_temporary_table() {
  return sql_query("CREATE TEMPORARY TABLE `temp_tb` SELECT * FROM `User`");
}

/**
 * Drops column from temporary table to prevent sensitive information
 *
 */
function alter_table($col) {
  return sql_query("ALTER TABLE `temp_tb` DROP $col");
}

/**
 * Returns Column names of the Schema (`User`)
 * For database export
 *
 */
function select_column() {
  return sql_select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'User' ");

}

/**
 * Returns temp_tb for exporting data
 *
 */
function select_temp_tb() {
  return sql_select("SELECT * FROM `temp_tb`");
}

?>
