printMaster
===========

PrintMaster is a quick and easy-to-use web-based printer and consumable management system for 
IT departments.


Features
--------

* List all printers (linkable to web IP interface)
* Manage consumable stock levels
* Log consumable installations


Requirements
------------

* Web server (Apache, IIS etc)
* PHP 5.1+
* MySQL


Installation
------------

1. Make a new virtual host or subdirectory at the webserver
2. Copy all Print Master files to the folder you just created
3. Create a new database and username in MySQL
4. Import the *printmaster.sql* file into the new database
5. Edit the *$db* line in *inc/init.php* to reflect your database details
6. Invent your own security (.htaccess, integrated Windows authentication...)


Getting started
---------------

1. Add manufacturers
2. Add printer models
3. Add consuambles
4. Add printers


TODO
----

* Tidy up some miscellaneous bits of code
* Add option on Printer list to install a consumable
* Allow bulk updating of consumable stock
* SNMP for printers?