#!/usr/bin/env bash

# Authors:
#   Sreeja Kamishetty <sreeja25kamishetty@gmail.com>

# Description:
#   A post-installation bash script for engelsystem

echo "Updating your package manager enter your root password"
sudo apt-get update
sudo apt-get upgrade

echo "Installing LAMP enter your root password where ever asked"
echo "Install Apache"
sudo apt-get install -y apache2

echo "Install MySQL"
sudo apt-get install mysql-server mysql-client
sudo mysql_secure_installation

php5=$(sudo apt-cache search php5 | wc -l)
php7=$(sudo apt-cache search php7 | wc -l)
if (( $php7 != 0 ))
then
  sudo apt-get install -y libapache2-mod-php7.0 php7.0 php7.0-mysql
elif (( $php5 != 0 ))
then
  sudo apt-get install -y libapache2-mod-php5 php5 php5-mysql
fi

echo "Install git"
sudo apt-get install -y git
cd /var/www/html

echo "Cloning the github repository"
sudo git clone --recursive https://github.com/fossasia/engelsystem.git
cd engelsystem

echo "Creating database engelsystem, enter mysql root password"
# creating new database engelsystem
echo "create database engelsystem" | mysql -u root -p

echo "Edit the database name username and password in config/config.php file"
sudo cp config/config-sample.default.php config/config.php

echo "Adding folder permission for Apache"
sudo chown -R www-data /var/www/html/engelsystem/

echo "Changing the document root and restarting apache"
sudo sed -i -e 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/engelsystem\/public/g'  /etc/apache2/sites-available/000-default.conf
sudo service apache2 restart
echo "All the dependencies are installed and Engelsystem can be viewed on local server at localhost/"
