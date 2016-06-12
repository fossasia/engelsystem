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


ALTER TABLE `User`
  ADD UNIQUE (email),
  ADD `current_city` varchar(255) DEFAULT NULL,
  ADD `twitter` varchar(255) DEFAULT NULL,
  ADD `facebook` varchar(255) DEFAULT NULL,
  ADD `github` varchar(255) DEFAULT NULL,
  ADD `organization` varchar(255) DEFAULT NULL,
  ADD `organization_web` varchar(255) DEFAULT NULL;

-- -----------------------------------------------------------------------------  

ALTER TABLE `Shifts` 
ADD `start_time` int(11) NOT NULL,
ADD `end_time` int(11) NOT NULL;

----------------------------------------------------------------------------------
