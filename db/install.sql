-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 25. Aug 2015 um 15:29
-- Server Version: 5.6.12
-- PHP-Version: 5.5.3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `AngelTypes`
--

DROP TABLE IF EXISTS `AngelTypes`;
CREATE TABLE IF NOT EXISTS `AngelTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `restricted` int(1) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `GroupPrivileges`
--

DROP TABLE IF EXISTS `GroupPrivileges`;
CREATE TABLE IF NOT EXISTS `GroupPrivileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `privilege_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`,`privilege_id`),
  KEY `privilege_id` (`privilege_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=259 ;

--
-- Daten für Tabelle `GroupPrivileges`
--

INSERT INTO `GroupPrivileges` (`id`, `group_id`, `privilege_id`) VALUES
(85, -7, 10),
(87, -7, 18),
(86, -7, 21),
(216, -6, 5),
(212, -6, 6),
(207, -6, 7),
(211, -6, 12),
(208, -6, 13),
(210, -6, 14),
(214, -6, 16),
(209, -6, 21),
(213, -6, 28),
(206, -6, 31),
(215, -6, 33),
(257, -6, 38),
(219, -5, 14),
(221, -5, 25),
(220, -5, 33),
(241, -4, 5),
(238, -4, 14),
(240, -4, 16),
(237, -4, 19),
(242, -4, 25),
(235, -4, 27),
(239, -4, 28),
(236, -4, 32),
(258, -3, 31),
(247, -2, 3),
(246, -2, 4),
(255, -2, 8),
(252, -2, 9),
(254, -2, 11),
(248, -2, 15),
(251, -2, 17),
(256, -2, 24),
(253, -2, 26),
(245, -2, 30),
(244, -2, 34),
(249, -2, 35),
(243, -2, 36),
(250, -2, 37),
(88, -1, 1),
(23, -1, 2),
(24, -1, 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Groups`
--

DROP TABLE IF EXISTS `Groups`;
CREATE TABLE IF NOT EXISTS `Groups` (
  `Name` varchar(35) NOT NULL,
  `UID` int(11) NOT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `Groups`
--

INSERT INTO `Groups` (`Name`, `UID`) VALUES
('6-Developer', -7),
('5-Bürokrat', -6),
('4-Team Coordinator', -5),
('3-Shift Coordinator', -4),
('Shirt-Manager', -3),
('2-Engel', -2),
('1-Gast', -1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `LogEntries`
--

DROP TABLE IF EXISTS `LogEntries`;
CREATE TABLE IF NOT EXISTS `LogEntries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  `nick` text NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Messages`
--

DROP TABLE IF EXISTS `Messages`;
CREATE TABLE IF NOT EXISTS `Messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Datum` int(11) NOT NULL,
  `SUID` int(11) NOT NULL DEFAULT '0',
  `RUID` int(11) NOT NULL DEFAULT '0',
  `isRead` char(1) NOT NULL DEFAULT 'N',
  `Text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Datum` (`Datum`),
  KEY `SUID` (`SUID`),
  KEY `RUID` (`RUID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Fuers interen Communikationssystem' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `NeededAngelTypes`
--

DROP TABLE IF EXISTS `NeededAngelTypes`;
CREATE TABLE IF NOT EXISTS `NeededAngelTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `angel_type_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`,`angel_type_id`),
  KEY `shift_id` (`shift_id`),
  KEY `angel_type_id` (`angel_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `News`
--

DROP TABLE IF EXISTS `News`;
CREATE TABLE IF NOT EXISTS `News` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Datum` int(11) NOT NULL,
  `Betreff` varchar(150) NOT NULL DEFAULT '',
  `Text` text NOT NULL,
  `UID` int(11) NOT NULL DEFAULT '0',
  `Treffen` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `NewsComments`
--

DROP TABLE IF EXISTS `NewsComments`;
CREATE TABLE IF NOT EXISTS `NewsComments` (
  `ID` bigint(11) NOT NULL AUTO_INCREMENT,
  `Refid` int(11) NOT NULL DEFAULT '0',
  `Datum` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Text` text NOT NULL,
  `UID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Refid` (`Refid`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Privileges`
--

DROP TABLE IF EXISTS `Privileges`;
CREATE TABLE IF NOT EXISTS `Privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `desc` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Daten für Tabelle `Privileges`
--

INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES
(1, 'start', 'Startseite für Gäste/Nicht eingeloggte User'),
(2, 'login', 'Logindialog'),
(3, 'news', 'Anzeigen der News-Seite'),
(4, 'logout', 'User darf sich ausloggen'),
(5, 'register', 'Einen neuen Engel registerieren'),
(6, 'admin_rooms', 'Räume administrieren'),
(7, 'admin_angel_types', 'Engel Typen administrieren'),
(8, 'user_settings', 'User profile settings'),
(9, 'user_messages', 'Writing and reading messages from user to user'),
(10, 'admin_groups', 'Manage usergroups and their rights'),
(11, 'user_questions', 'Let users ask questions'),
(12, 'admin_questions', 'Answer user''s questions'),
(13, 'admin_faq', 'Edit FAQs'),
(14, 'admin_news', 'Administrate the news section'),
(15, 'news_comments', 'User can comment news'),
(16, 'admin_user', 'Administrate the angels'),
(17, 'user_meetings', 'Lists meetings (news)'),
(18, 'admin_language', 'Translate the system'),
(19, 'admin_log', 'Display recent changes'),
(20, 'user_wakeup', 'User wakeup-service organization'),
(21, 'admin_import', 'Import rooms and shifts from pentabarf'),
(22, 'credits', 'View credits'),
(23, 'faq', 'View FAQ'),
(24, 'user_shifts', 'Signup for shifts'),
(25, 'user_shifts_admin', 'Signup other angels for shifts.'),
(26, 'user_myshifts', 'Allow angels to view their own shifts and cancel them.'),
(27, 'admin_arrive', 'Mark angels when they arrive.'),
(28, 'admin_shifts', 'Create shifts'),
(30, 'ical', 'iCal shift export'),
(31, 'admin_active', 'Mark angels as active and if they got a t-shirt.'),
(32, 'admin_free', 'Show a list of free/unemployed angels.'),
(33, 'admin_user_angeltypes', 'Confirm restricted angel types'),
(34, 'atom', ' Atom news export'),
(35, 'shifts_json_export', 'Export shifts in JSON format'),
(36, 'angeltypes', 'View angeltypes'),
(37, 'user_angeltypes', 'Join angeltypes.'),
(38, 'shifttypes', 'Administrate shift types');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Questions`
--

DROP TABLE IF EXISTS `Questions`;
CREATE TABLE IF NOT EXISTS `Questions` (
  `QID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UID` int(11) NOT NULL DEFAULT '0',
  `Question` text NOT NULL,
  `AID` int(11) DEFAULT NULL,
  `Answer` text,
  PRIMARY KEY (`QID`),
  KEY `UID` (`UID`),
  KEY `AID` (`AID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Fragen und Antworten' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Room`
--

DROP TABLE IF EXISTS `Room`;
CREATE TABLE IF NOT EXISTS `Room` (
  `RID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(35) NOT NULL DEFAULT '',
  `Man` text,
  `FromPentabarf` char(1) NOT NULL DEFAULT 'N',
  `show` char(1) NOT NULL DEFAULT 'Y',
  `Number` int(11) DEFAULT NULL,
  PRIMARY KEY (`RID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ShiftEntry`
--

DROP TABLE IF EXISTS `ShiftEntry`;
CREATE TABLE IF NOT EXISTS `ShiftEntry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SID` int(11) NOT NULL DEFAULT '0',
  `TID` int(11) NOT NULL DEFAULT '0',
  `UID` int(11) NOT NULL DEFAULT '0',
  `Comment` text,
  `freeload_comment` text,
  `freeloaded` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `TID` (`TID`),
  KEY `UID` (`UID`),
  KEY `SID` (`SID`,`TID`),
  KEY `freeloaded` (`freeloaded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Shifts`
--

DROP TABLE IF EXISTS `Shifts`;
CREATE TABLE IF NOT EXISTS `Shifts` (
  `SID` int(11) NOT NULL AUTO_INCREMENT,
  `title` text,
  `shifttype_id` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `RID` int(11) NOT NULL DEFAULT '0',
  `URL` text,
  `PSID` int(11) DEFAULT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `created_at_timestamp` int(11) NOT NULL,
  `edited_by_user_id` int(11) DEFAULT NULL,
  `edited_at_timestamp` int(11) NOT NULL,
  PRIMARY KEY (`SID`),
  UNIQUE KEY `PSID` (`PSID`),
  KEY `RID` (`RID`),
  KEY `shifttype_id` (`shifttype_id`),
  KEY `created_by_user_id` (`created_by_user_id`),
  KEY `edited_by_user_id` (`edited_by_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=133 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ShiftTypes`
--

DROP TABLE IF EXISTS `ShiftTypes`;
CREATE TABLE IF NOT EXISTS `ShiftTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `angeltype_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `angeltype_id` (`angeltype_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `ShiftTypes`
--

INSERT INTO `ShiftTypes` (`id`, `name`, `angeltype_id`, `description`) VALUES
(4, 'Schichttyp1', NULL, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `User`
--

DROP TABLE IF EXISTS `User`;
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
  `api_key` varchar(32) NOT NULL,
  `got_voucher` int(11) NOT NULL,
  `arrival_date` int(11) DEFAULT NULL,
  `planned_arrival_date` int(11) NOT NULL,
  `planned_departure_date` int(11) DEFAULT NULL,
  
  PRIMARY KEY (`UID`),
  UNIQUE KEY `Nick` (`Nick`),
  KEY `api_key` (`api_key`),
  KEY `password_recovery_token` (`password_recovery_token`),
  KEY `force_active` (`force_active`),
  KEY `arrival_date` (`arrival_date`,`planned_arrival_date`),
  KEY `planned_departure_date` (`planned_departure_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `User`
--

INSERT INTO `User` (`UID`, `Nick`, `Name`, `Vorname`, `Alter`, `Telefon`, `DECT`, `Handy`, `email`, `email_shiftinfo`, `jabber`, `Size`, `Passwort`, `password_recovery_token`, `Gekommen`, `Aktiv`, `force_active`, `Tshirt`, `color`, `Sprache`, `Menu`, `lastLogIn`, `CreateDate`, `Art`, `kommentar`, `Hometown`, `api_key`, `got_voucher`, `arrival_date`, `planned_arrival_date`, `planned_departure_date`) VALUES
(1, 'admin', 'Gates', 'Bill', 42, '', '-', '', 'admin@example.com', 1, '', 'XL', '$6$rounds=5000$hjXbIhoRTH3vKiRa$Wl2P2iI5T9iRR.HHu/YFHswBW0WVn0yxCfCiX0Keco9OdIoDK6bIAADswP6KvMCJSwTGdV8PgA8g8Xfw5l8BD1', NULL, 1, 1, 0, 1, 2, 'de_DE.UTF-8', 'L', 1439759300, '0000-00-00 00:00:00', '', '', '', '038850abdd1feb264406be3ffa746235', 3, 1439490478, 1436964455, 1440161255);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `UserAngelTypes`
--

DROP TABLE IF EXISTS `UserAngelTypes`;
CREATE TABLE IF NOT EXISTS `UserAngelTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `angeltype_id` int(11) NOT NULL,
  `confirm_user_id` int(11) DEFAULT NULL,
  `coordinator` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`angeltype_id`,`confirm_user_id`),
  KEY `angeltype_id` (`angeltype_id`),
  KEY `confirm_user_id` (`confirm_user_id`),
  KEY `coordinator` (`coordinator`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `UserGroups`
--

DROP TABLE IF EXISTS `UserGroups`;
CREATE TABLE IF NOT EXISTS `UserGroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Daten für Tabelle `UserGroups`
--

INSERT INTO `UserGroups` (`id`, `uid`, `group_id`) VALUES
(3, 1, -7),
(4, 1, -6),
(12, 1, -5),
(2, 1, -4),
(1, 1, -2);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `GroupPrivileges`
--
ALTER TABLE `GroupPrivileges`
  ADD CONSTRAINT `groupprivileges_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `Groups` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groupprivileges_ibfk_2` FOREIGN KEY (`privilege_id`) REFERENCES `Privileges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `Messages`
--
ALTER TABLE `Messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`SUID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`RUID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `NeededAngelTypes`
--
ALTER TABLE `NeededAngelTypes`
  ADD CONSTRAINT `neededangeltypes_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `Room` (`RID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `neededangeltypes_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `Shifts` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `neededangeltypes_ibfk_3` FOREIGN KEY (`angel_type_id`) REFERENCES `AngelTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `News`
--
ALTER TABLE `News`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `NewsComments`
--
ALTER TABLE `NewsComments`
  ADD CONSTRAINT `newscomments_ibfk_1` FOREIGN KEY (`Refid`) REFERENCES `News` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `newscomments_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `Questions`
--
ALTER TABLE `Questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`AID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ShiftEntry`
--
ALTER TABLE `ShiftEntry`
  ADD CONSTRAINT `shiftentry_ibfk_1` FOREIGN KEY (`SID`) REFERENCES `Shifts` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shiftentry_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shiftentry_ibfk_3` FOREIGN KEY (`TID`) REFERENCES `AngelTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `Shifts`
--
ALTER TABLE `Shifts`
  ADD CONSTRAINT `shifts_ibfk_1` FOREIGN KEY (`RID`) REFERENCES `Room` (`RID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shifts_ibfk_2` FOREIGN KEY (`shifttype_id`) REFERENCES `ShiftTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shifts_ibfk_3` FOREIGN KEY (`created_by_user_id`) REFERENCES `User` (`UID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `shifts_ibfk_4` FOREIGN KEY (`edited_by_user_id`) REFERENCES `User` (`UID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ShiftTypes`
--
ALTER TABLE `ShiftTypes`
  ADD CONSTRAINT `shifttypes_ibfk_1` FOREIGN KEY (`angeltype_id`) REFERENCES `AngelTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `UserAngelTypes`
--
ALTER TABLE `UserAngelTypes`
  ADD CONSTRAINT `userangeltypes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userangeltypes_ibfk_2` FOREIGN KEY (`angeltype_id`) REFERENCES `AngelTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userangeltypes_ibfk_3` FOREIGN KEY (`confirm_user_id`) REFERENCES `User` (`UID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints der Tabelle `UserGroups`
--
ALTER TABLE `UserGroups`
  ADD CONSTRAINT `usergroups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `Groups` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usergroups_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
