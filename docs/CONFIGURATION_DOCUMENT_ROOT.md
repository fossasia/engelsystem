# Instructions to configure document root in ubuntu

We must make sure to point our apache2 document root to the Engelsystem directory to prevent any user from accessing anything other than the public/ directory for security reasons. Do this by modifying the apache2 configuration file

### Changing apache2 document root

The default document root is set in the 000-default.conf file that is under /etc/apache2/sites-available folder.

$ ```sudo nano /etc/apache2/sites-available/000-default.conf```

While the file is opened change DocumentRoot /var/www/ with your new folder
e.g DocumentRoot /var/www/html/engelsystem/public

or you can execute the following command in your terminal

$ ```sudo sed -i -e 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/engelsystem\/public/g' /etc/apache2/sites-available/000-default.conf```

### Set the right Apache folder Permissions

$ ```sudo chown -R www-data /var/www/html/engelsystem/```

### Restart Apache
$ ``` sudo service apache2 restart ```

After following these steps engelsystem can be viewed at **localhost/ or at  http://[i.p.address]**
