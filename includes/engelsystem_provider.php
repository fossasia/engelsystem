<?php
/**
 * This file includes all needed functions, connects to the db etc.
 */
require_once realpath(__DIR__ . '/../includes/mysqli_provider.php');

require_once realpath(__DIR__ . '/../includes/sys_auth.php');
require_once realpath(__DIR__ . '/../includes/sys_log.php');
require_once realpath(__DIR__ . '/../includes/sys_menu.php');
require_once realpath(__DIR__ . '/../includes/sys_page.php');
require_once realpath(__DIR__ . '/../includes/sys_template.php');

require_once realpath(__DIR__ . '/../includes/model/AngelType_model.php');
require_once realpath(__DIR__ . '/../includes/model/LogEntries_model.php');
require_once realpath(__DIR__ . '/../includes/model/Message_model.php');
require_once realpath(__DIR__ . '/../includes/model/NeededAngelTypes_model.php');
require_once realpath(__DIR__ . '/../includes/model/Room_model.php');
require_once realpath(__DIR__ . '/../includes/model/ShiftEntry_model.php');
require_once realpath(__DIR__ . '/../includes/model/Shifts_model.php');
require_once realpath(__DIR__ . '/../includes/model/ShiftTypes_model.php');
require_once realpath(__DIR__ . '/../includes/model/UserAngelTypes_model.php');
require_once realpath(__DIR__ . '/../includes/model/UserDriverLicenses_model.php');
require_once realpath(__DIR__ . '/../includes/model/UserGroups_model.php');
require_once realpath(__DIR__ . '/../includes/model/User_model.php');
require_once realpath(__DIR__ . '/../includes/model/Settings_model.php');
require_once realpath(__DIR__ . '/../includes/model/admin_active_model.php');
require_once realpath(__DIR__ . '/../includes/model/admin_arrive_model.php');
require_once realpath(__DIR__ . '/../includes/model/admin_export_model.php');
require_once realpath(__DIR__ . '/../includes/model/admin_free_model.php');
require_once realpath(__DIR__ . '/../includes/model/admin_groups_model.php');
require_once realpath(__DIR__ . '/../includes/model/admin_import_model.php');
require_once realpath(__DIR__ . '/../includes/model/admin_news_model.php');
require_once realpath(__DIR__ . '/../includes/model/admin_questions_model.php');
require_once realpath(__DIR__ . '/../includes/model/user_news_model.php');
require_once realpath(__DIR__ . '/../includes/model/user_atom_model.php');
require_once realpath(__DIR__ . '/../includes/model/user_questions_model.php');
require_once realpath(__DIR__ . '/../includes/model/user_myshifts_model.php');
require_once realpath(__DIR__ . '/../includes/model/user_settings_model.php');
require_once realpath(__DIR__ . '/../includes/model/user_shifts_model.php');
require_once realpath(__DIR__ . '/../includes/model/guest_stats_model.php');
require_once realpath(__DIR__ . '/../includes/model/admin_user_model.php');
require_once realpath(__DIR__ . '/../includes/model/guest_login_model.php');

require_once realpath(__DIR__ . '/../includes/view/AngelTypes_view.php');
require_once realpath(__DIR__ . '/../includes/view/Questions_view.php');
require_once realpath(__DIR__ . '/../includes/view/Rooms_view.php');
require_once realpath(__DIR__ . '/../includes/view/Shifts_view.php');
require_once realpath(__DIR__ . '/../includes/view/ShiftEntry_view.php');
require_once realpath(__DIR__ . '/../includes/view/ShiftTypes_view.php');
require_once realpath(__DIR__ . '/../includes/view/UserAngelTypes_view.php');
require_once realpath(__DIR__ . '/../includes/view/UserDriverLicenses_view.php');
require_once realpath(__DIR__ . '/../includes/view/User_view.php');

require_once realpath(__DIR__ . '/../includes/controller/angeltypes_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/rooms_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/shifts_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/shifttypes_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/users_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/user_angeltypes_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/user_driver_licenses_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_active_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_arrive_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_export_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_free_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_groups_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_import_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_log_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_questions_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_rooms_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_shifts_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_user_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/admin_settings_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/user_messages_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/user_news_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/user_questions_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/user_myshifts_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/user_shifts_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/user_settings_controller.php');
require_once realpath(__DIR__ . '/../includes/controller/guest_login_controller.php');

require_once realpath(__DIR__ . '/../includes/helper/graph_helper.php');
require_once realpath(__DIR__ . '/../includes/helper/internationalization_helper.php');
require_once realpath(__DIR__ . '/../includes/helper/message_helper.php');
require_once realpath(__DIR__ . '/../includes/helper/error_helper.php');
require_once realpath(__DIR__ . '/../includes/helper/email_helper.php');

require_once realpath(__DIR__ . '/../includes/mailer/shifts_mailer.php');
require_once realpath(__DIR__ . '/../includes/mailer/users_mailer.php');

require_once realpath(__DIR__ . '/../config/config-sample.default.php');
if (file_exists(realpath(__DIR__ . '/../config/config.php')))
  require_once realpath(__DIR__ . '/../config/config.php');

if ($maintenance_mode) {
  echo file_get_contents(__DIR__ . '/../public/maintenance.html');
  die();
}

require_once realpath(__DIR__ . '/../vendor/parsedown/Parsedown.php');

session_start();

gettext_init();

sql_connect($config['host'], $config['user'], $config['pw'], $config['db']);

load_auth();

?>
