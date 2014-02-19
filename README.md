PrintMaster
===========

PrintMaster is a quick and easy-to-use web-based printer and consumable management system for
IT departments.

Licensed under GPLv3.

**[Download ZIP](http://github.com/craigrodway/printmaster/zipball/master)**


Features
--------

* List all printers (linkable to web IP interface)
* Manage consumable stock levels
* Log consumable installations


Screenshot
----------

[![alt text][1_img]][1_url]

[1_url]: http://picasaweb.google.co.uk/craig.rodway/PrintMaster?authkey=Gv1sRgCOPWjoHyx_Xw7gE&feat=directlink
[1_img]: http://lh5.ggpht.com/_hb6RYM32rvs/TJSVTvlqnBI/AAAAAAAADEU/A7IXMPrzme8/s400/dashboard.png


Requirements
------------

* Web server (Apache, IIS etc)
* PHP 5.1+
* MySQL


Installation (1.4.0+)
---------------------

1. Create directory on webserver to store files (configure as virtual host or subdir)
2. Copy all PrintMaster files to the folder you just created
3. Change the permissions on the `session` folder to be writeable
4. Create a new username and database in MySQL
5. Import the `printmaster.sql` file into the new database
6. Copy `inc/config/config.default.php` to `inc/config/config.php`.
7. Edit `inc/config/config.php` and add your database details.
8. Access **upgrade.php** in a browser to update the database to the latest version. E.g. **http://server/printmaster/upgrade.php**
9. Invent your own security (.htaccess, integrated Windows authentication...)


Getting started
---------------

1. Add manufacturers (or import **printmaster-manufacturers.sql** for most common)
2. Add printer models
3. Add consuambles
4. Add printers


TODO
----

Development takes place on the **[PrintMaster Trello board](https://trello.com/b/5mErSQXR/printmaster)**.