<?php

function import_tables() {
  global $DB_HOST, $DB_PASSWORD, $DB_NAME, $DB_USER;
  $import_install = '../db/install.sql';
  $import_update = '../db/update.sql';
  $command_install = 'mysql -h' .$DB_HOST .' -u' .$DB_USER .' -p' .$DB_PASSWORD .' ' .$DB_NAME .' < ' .$import_install;
  $command_update = 'mysql -h' .$DB_HOST .' -u' .$DB_USER .' -p' .$DB_PASSWORD .' ' .$DB_NAME .' < ' .$import_update;
  $output = array();
  exec($command_install, $output, $worked_install);
  exec($command_update, $output, $worked_update);

  switch ($worked_install && $worked_update) {
      case 0:
          return true;
      case 1:
          return false;
  }
}

function test_import() {
  global $DB_NAME;
  $sql = "SHOW TABLES FROM $DB_NAME";
  $result = sql_query($sql);
  return $result;
}

?>
