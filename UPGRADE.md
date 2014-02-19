# Upgrade

See the __General instructions__ section below, and then check the version-specific entries.


### 1.4.0 - 01.2013
* Copy `inc/config/config.default.php` to `inc/config/config.php`.
* Edit `inc/config/config.php` and enter your current database information (copy from `inc/init.php`).
* Delete `inc/config.php` and `inc/init.php`.

### 1.2.1 - 22.08.2013
* Database patch only: Just visit __/printmaster/upgrade.php__ in your browser.

### 1.2.0 - 18.08.2013
* New config value: Copy the new "CURRENCY" lines from `inc/config.php` to your own `inc/config.php` file and adjust if needed.


### General instructions
* Backup files and database of current PrintMaster.
* Get archive of latest PrintMaster.
* Replace contents on server with latest PrintMaster files, __except__ `inc/config.php` and `inc/init.php`.
* Go to _upgrade.php_ in your PrintMaster installation. E.g. __http://server/printmaster/upgrade.php__.