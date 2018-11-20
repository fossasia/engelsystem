-- drivers license information
CREATE TABLE IF NOT EXISTS `UserDriverLicenses` (
  `user_id` int(11) NOT NULL,
  `has_car` tinyint(1) NOT NULL,
  `has_license_car` tinyint(1) NOT NULL,
  `has_license_3_5t_transporter` tinyint(1) NOT NULL,
  `has_license_7_5t_truck` tinyint(1) NOT NULL,
  `has_license_12_5t_truck` tinyint(1) NOT NULL,
  `has_license_forklift` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `UserDriverLicenses`
  ADD CONSTRAINT `userdriverlicenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Update table 'Angeltypes'
ALTER TABLE `AngelTypes` ADD `requires_driver_license` BOOLEAN NOT NULL;

-- -----------------------------------------------------------------------------
-- Update 'User' Table
ALTER TABLE `User`
  ADD UNIQUE (email),
  ADD `current_city` varchar(255) DEFAULT NULL,
  ADD `twitter` varchar(255) DEFAULT NULL,
  ADD `facebook` varchar(255) DEFAULT NULL,
  ADD `github` varchar(255) DEFAULT NULL,
  ADD `organization` varchar(255) DEFAULT NULL,
  ADD `organization_web` varchar(255) DEFAULT NULL,
  ADD `timezone` varchar(255) DEFAULT NULL,
  ADD `native_lang` varchar(5) DEFAULT NULL,
  ADD `other_langs` varchar(150) DEFAULT NULL;

-- -----------------------------------------------------------------------------
-- Events information table
CREATE TABLE IF NOT EXISTS `Events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `organizer` varchar(255) NOT NULL,
  `start_date` int(11) DEFAULT NULL,
  `start_time` int(10) NOT NULL,
  `end_time` int(10) NOT NULL,
  `end_date` int(11) DEFAULT NULL,
  `venue` varchar(255) NOT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `Name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;
-- -----------------------------------------------------------------------------
-- Alter table sturcture for Rooms, converting char(1) type to boolean
ALTER TABLE `Room`
    MODIFY COLUMN `FromPentabarf` bit(1) NOT NULL DEFAULT b'0',
    MODIFY COLUMN `show` bit(1) NOT NULL DEFAULT b'1';
-- -----------------------------------------------------------------------------
-- Welcome Message table
DROP TABLE IF EXISTS `Welcome_Message`;
CREATE TABLE IF NOT EXISTS `Welcome_Message` (
  `display_msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

INSERT INTO `Welcome_Message` (`display_msg`) VALUES ("By completing this form you're registering as a Chaos-Angel. This script will create you an account in the angel task sheduler.");

-- ---------------------------------------------------------------------------------
-- Settings table
DROP TABLE IF EXISTS `Settings`;
CREATE TABLE IF NOT EXISTS `Settings` (
  `event_name` varchar(255) DEFAULT NULL,
  `buildup_start_date` int(11) DEFAULT NULL,
  `event_start_date` int(11) DEFAULT NULL,
  `event_end_date` int(11) DEFAULT NULL,
  `teardown_end_date` int(11) DEFAULT NULL,
  `autoupdate` tinyint(1) NOT NULL DEFAULT '0',
  `event_welcome_msg` varchar(255) DEFAULT NULL,
  `table_migrated` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- Added privilege for Admin Settings
INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (39, 'admin_settings', 'Admin Settings');
INSERT INTO `GroupPrivileges` (`id`, `group_id`, `privilege_id`) VALUES (218, -4, 39);

-- -----------------------------------------------------------------------------------
-- Added privilege for the Import/Export User data
INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (40, 'admin_export', 'Import and Export user data');
INSERT INTO `GroupPrivileges` (`id`, `group_id`, `privilege_id`) VALUES (337, -4, 40);

-- -----------------------------------------------------------------------------------
-- Added privelege for the Create Groups page
INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (41, 'admin_cgroups', 'Create new groups');
INSERT INTO `GroupPrivileges` (`id`, `group_id`, `privilege_id`) VALUES (339, -4, 41);

-- -----------------------------------------------------------------------------------
-- Added privelege for the Create Events page
INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (43, 'admin_events', 'Create new Events');
INSERT INTO `GroupPrivileges` (`id`, `group_id`, `privilege_id`) VALUES (347, -4, 43);

-- ------------------------------------------------------------------------------------
-- Added Privilege for install page.
INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (42, 'install', 'Install Engelsystem');

-- ------------------------------------------------------------------------------------
-- Updated Table Room
ALTER TABLE `Room`
  ADD `e_id` int(11) NOT NULL;
