#Installation of Engelsystem

##Requirements:

1.Setup LAMP

> 1.1 PHP 5.4.x (cgi-fcgi)
>
> 1.2 MySQL-Server 5.5.x pr MariaDB
>
> 1.3 Webserver ( Apache/Nginx/lighttpd)

2.Install GIT

##Steps:

*    clone the repository (the recursive parameter allows us to clone the submodules):                           git clone --recursive [*https://github.com/engelsystem/engelsystem.git*](https://github.com/engelsystem/engelsystem.git)

*    Next, configure your MySQL Engelsystem database:

**        mysql -u root -p**

**        \[Enter your password\]**

**        CREATE DATABASE engelsystem;**

**        use engelsystem;**

**        source \[path to engelsystem\]/engelsystem/db/install.sql;**

**        source \[path to engelsystem\]/engelsystem/db/update.sql;**

**        exit;**

*   Go to **engelsystem/config** and copy the default config into config.php. Modify the new file to match your MySQL credentials so that the system could access the database on the localserver.

*      Move the app to your **/var/www/html/** directory by typing **mv ./engelsystem /var/www/html**

*      To login, type use the following credentials:

*Username:* **admin**

*Password:* **asdfasdf**

*   We must make sure to point our apache2 document root to the Engelsystem directory to prevent any user from accessing anything other than the public/ directory for security reasons. Do this by modifying the apache2 configuration file:

**apt-get install nano -y**

**nano /etc/apache2/sites-available/000-default.conf**

* Change **DocumentRoot /var/www/html** into **DocumentRoot /var/www/html/engelsystem/public**. Restart apache by,

> **service apache2 restart**

##Session Settings:

-   Make sure the config allows for sessions.

-   Both Apache and Nginx allow for different VirtualHost configurations.

For more information on deploying the system please visit, https://codefungsoc2k16.wordpress.com/2016/05/20/deploy-engelsystem-on-your-localserver/
