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

-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `User` (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `Nick` varchar(23) NOT NULL DEFAULT '',
  `Name` varchar(23) NOT NULL DEFAULT '',
  `Vorname` varchar(23) NOT NULL DEFAULT '',
  `Alter` int(4) DEFAULT NULL,
  `Telefon` varchar(40) DEFAULT NULL,
  `DECT` varchar(5) DEFAULT NULL,
  `Handy` varchar(40) DEFAULT NULL,
  `email` varchar(123) DEFAULT NULL,
  `email_shiftinfo` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'User wants to be informed by mail about changes in his shifts',
  `jabber` varchar(200) DEFAULT NULL,
  `Size` varchar(4) DEFAULT NULL,
  `Passwort` varchar(128) DEFAULT NULL,
  `password_recovery_token` varchar(32) DEFAULT NULL,
  `Gekommen` tinyint(4) NOT NULL DEFAULT '0',
  `Aktiv` tinyint(4) NOT NULL DEFAULT '0',
  `force_active` tinyint(1) NOT NULL,
  `Tshirt` tinyint(4) DEFAULT '0',
  `color` tinyint(4) DEFAULT '10',
  `Sprache` char(64) NOT NULL,
  `Menu` char(1) NOT NULL DEFAULT 'L',
  `lastLogIn` int(11) NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Art` varchar(30) DEFAULT NULL,
  `kommentar` text,
  `Hometown` varchar(255) NOT NULL DEFAULT '',
  `current_city` varchar(255) DEFAULT NULL,
  `api_key` varchar(32) NOT NULL,
  `got_voucher` int(11) NOT NULL,
  `arrival_date` int(11) DEFAULT NULL,
  `planned_arrival_date` int(11) NOT NULL,
  `planned_departure_date` int(11) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `organization_web` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`UID`),
  UNIQUE KEY `Nick` (`Nick`),
  KEY `api_key` (`api_key`),
  KEY `password_recovery_token` (`password_recovery_token`),
  KEY `force_active` (`force_active`),
  KEY `arrival_date` (`arrival_date`,`planned_arrival_date`),
  KEY `planned_departure_date` (`planned_departure_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

ALTER TABLE `User` ADD UNIQUE (email) ;

ALTER TABLE `Shifts` 
ADD `start_time` int(11) NOT NULL,
ADD `end_time` int(11) NOT NULL;
