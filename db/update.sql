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
  `start_date` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `venue` varchar(255) NOT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `Name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
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
  `event_welcome_msg` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- Added privilege for Admin Settings
INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (39, 'admin_settings_controller', 'Admin Settings');
INSERT INTO `GroupPrivileges` (`id`, `group_id`, `privilege_id`) VALUES (218, -4, 39);

-- -----------------------------------------------------------------------------------
-- Added privilege for the Import/Export User data
INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (40, 'admin_export_controller', 'Import and Export user data');
INSERT INTO `GroupPrivileges` (`id`, `group_id`, `privilege_id`) VALUES (337, -4, 40);
