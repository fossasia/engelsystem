# Five Minute Installation of Engelsystem
### Prerequisites
Please check out the docs for more information [the documentation here](/docs/).

    1.1 PHP 5.4.x (cgi-fcgi)
    1.2 MySQL-Server 5.5.x pr MariaDB
    1.3 Webserver ( Apache/Nginx/lighttpd)

### Step 1: Download or Clone the repository
- ```$ git clone --recursive https://github.com/fossasia/engelsystem.git```

### Step 2: Create a Mysql Database
- Using the MySQL Client
- ```$ mysql -u root -p```
- ```mysql> CREATE DATABASE engelsystem;```

### Step 3: Set up config.php
- Go to **engelsystem/config** and copy MySQL-Connection Settings from default config-sample into config.php. Modify the new file to match your MySQL credentials so that the system could access the database on the localserver.

### Step 4: Upload the files
-Move the app to your /var/www/html/ directory by typing ```sudo mv ./engelsystem /var/www/html``` and we can view engelsystem on localhost/ after configuring document root [documentation here](/docs/CONFIGURATION_DOCUMENT_ROOT.md)

### Step 5: Run the Install Script
- Visit localhost/ on your browser you will be redirected to install script.

Once you have filled the information and clicked install engelsystem. We are redirected to login page where we can login with the credentials with admin rights.
