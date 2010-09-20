PrintMaster
===========

PrintMaster is a quick and easy-to-use web-based printer and consumable management system for 
IT departments.

Licensed under GPLv3.

<<<<<<< HEAD
**[Download ZIP](http://github.com/craigrodway/printmaster/zipball/master)**

=======
>>>>>>> origin/master

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


Installation
------------

1. Create directory on webserver to store files (configure as virtual host or subdir)
2. Copy all Print Master files to the folder you just created
3. Create a new directory called **session** where you extracted the files and make it writeable
4. Create a new username and database in MySQL
5. Import the *printmaster.sql* file into the new database
6. Edit the *$db* line in *inc/init.php* to reflect your database details
7. Invent your own security (.htaccess, integrated Windows authentication...)


Getting started
---------------

1. Add manufacturers (or import **printmaster-manufacturers.sql** for most common)
2. Add printer models
3. Add consuambles
4. Add printers


TODO
----

* Tidy up some miscellaneous bits of code
* Add option on Printer list to install a consumable
* Allow bulk updating of consumable stock
* Stock levels (OK, Low, Critical) configurable at consumable level, not global
* SNMP for printers?