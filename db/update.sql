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

ALTER TABLE `AngelTypes` ADD `requires_driver_license` BOOLEAN NOT NULL;

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
INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (39, 'admin_settings', 'Settings Page for Admin');

INSERT INTO `GroupPrivileges` (`id`, `group_id`, `privilege_id`) VALUES (218, -4, 39);