#Installation of Engelsystem on an online server

##Steps:

*  clone the repository (the recursive parameter allows us to clone the submodules):                           git clone --recursive [*https://github.com/engelsystem/engelsystem.git*](https://github.com/engelsystem/engelsystem.git)

*  Compress the downloaded repository in .zip format

*  Upload the compressed file to the server either using the file manager of the CPanel (or Vista Panel) provided by the hosting server or use any FTP client (E.g. FileZilla, etc.)

*  Extract the components in the directory (public\_html)

*  Next, configure your Engelsystem database:



1.  Create a database for the Engelsystem (give any name to it)

2.  Open phpMyAdmin on the server to create tables in the database

3.  In phpMyAdmin select the database created and click on the import tab to import the tables and schema for the Engelsystem

4.  Import the “install.sql” and “update.sql” to finish configuring the database for the Engelsystem



*  We must make sure to point our Apache document root to the Engelsystem directory to prevent any user from accessing anything other than the public/ directory for security reasons. Do this by modifying the Apache configuration file using the SSH access and edit the following file:

    \#vim /var/cpanel/userdata/USERNAME/DOMAINNAME.COM

    Change USERNAME with the CPanel username and DOMAINNAME with the primary domainname.

*  After editing file, search for text *documentroot* and change the path as:

    documentroot: /home/USERNAME/public\_html/\[Engelsystem directory\]/public

*  After making changed, we need to rebuid Apache configuration file and restart Apache server. Use the following command to do it.

    \# /scripts/rebuildhttpdconf

    \# service httpd restart
    The changes will be permanently updated. Check your site for reflecting changes. For more help on changing the documentroot in CPanel, please visit [here](http://tecadmin.net/how-to-change-document-root-of-primary-domain-in-cpanel/) .

If you made it this far without any issues, **congratulations!** You have successfully set up Engelsystem on your domain and can use it to manage your event.

##Session Settings:

-   Make sure the config allows for sessions.

-   Both Apache and Nginx allow for different VirtualHost configurations.

For more information on deploying the system please visit, https://codefungsoc2k16.wordpress.com/2016/05/20/deploy-engelsystem-on-your-localserver/
