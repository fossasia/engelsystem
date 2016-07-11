# Installation eines frischen Engelsystems
[![Build Status](https://travis-ci.org/fossasia/engelsystem.svg?branch=Travis_config)](https://travis-ci.org/fossasia/engelsystem)

[![Code Climate](https://codeclimate.com/github/fossasia/engelsystem/badges/gpa.svg)](https://codeclimate.com/github/fossasia/engelsystem)
[![Build Status](https://travis-ci.org/fossasia/engelsystem.svg?branch=documentation)](https://travis-ci.org/fossasia/engelsystem)

## Mindestvorrausetzungen (bzw. getestet unter):
 * PHP 5.4.x (cgi-fcgi)
 * MySQL-Server 5.5.x
 * Webserver mit PHP-Anbindung, z.B. lighttpd, nginx oder Apache

## Vorgehen:
 * Klonen des `master` inkl. submodules in lokales Verzeichnis: `git clone --recursive https://github.com/fossasia/engelsystem.git`
 * Der Webserver muss Schreibrechte auf das Verzeichnis `import` bekommen, für alle anderen Dateien reichen Leserechte.
 * Der Webserver muss auf `public` als http-root zeigen.

 * Empfehlung: Dirlisting sollte deaktiviert sein.
 * Es muss eine MySQL-Datenbank angelegt werden und ein User existieren, der alle Rechte auf dieser Datenbank besitzt.
 * Es muss die db/install.sql und die db/update.sql importiert/ausgeführt werden.
 * Erstelle bei Bedarf eine config/config.php, die die Werte (z.B. DB-Zugang) aus der config/config.default.php überschreibt.
 * Engelsystem im Browser aufrufen, Anmeldung mit admin:asdfasdf vornehmen und Admin-Passwort ändern.

Das Engelsystem ist jetzt einsatzbereit.

## Session Einstellungen:
 * Einstellungen für Cookies und Sessions bitte in der PHP Config des Servers vornehmen.
 * Sowohl Apache als auch nginx bieten Möglichkeiten für verschiedene Konfigurationen pro VirtualHost an

Fehler bitte auf Github melden: https://github.com/fossasia/engelsystem/issues

--

# Installation of Engelsystem
[![Code Climate](https://codeclimate.com/github/fossasia/engelsystem/badges/gpa.svg)](https://codeclimate.com/github/fossasia/engelsystem)
[![Build Status](https://travis-ci.org/fossasia/engelsystem.svg?branch=documentation)](https://travis-ci.org/fossasia/engelsystem)
## Requirements:
 * PHP 5.4.x (cgi-fcgi)
 * MySQL-Server 5.5.x
 * Webserver, i.e. lighttpd, nginx, or Apache

## Directions:
 * Clone the master branch with the submodules: `git clone --recursive https://github.com/fossasia/engelsystem.git`
 * Webserver must have write access to the 'import' directory and read access for all other directories
 * Webserver must be public.

 * Recommended: Directory Listing should be disabled.
 * There must a be MySQL database created with a user who has full rights to that database.
 * It must be created by the db/install.sql and db/update.sql files.
 * If necessary, create a config/config.php to override values from config/config.default.php.
 * In the browser, login with credentials admin:asdfasdf and change the password.

Engelsystem can now be used.

## Session Settings:
 * Make sure the config allows for sessions.
 * Both Apache and Nginx allow for different VirtualHost configurations.

Report Bugs: https://github.com/fossasia/engelsystem/issues
