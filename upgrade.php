<?php
/*
Copyright (C) 2013 Craig A Rodway.

This file is part of Print Master.

Print Master is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Print Master is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Print Master.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
Database patch script for PrintMaster.

If this is the first time being run, the patch history table is created.
When it exists, it executes the required patch scripts in the ./db folder.
*/


// Include initialisation file
include_once('inc/core.php');

// Get DB schema so we can find out if the table exists.
$schema = new fSchema($db);
$tables = $schema->getTables();

// If table does not exist - create it and insert default entry.
if ( ! array_search('patch_history', $tables))
{
	echo "Initialising patch history table...<br>";

	$sql = "CREATE TABLE `patch_history` ( `num` smallint(5) unsigned NOT NULL) ENGINE='MyISAM' COLLATE 'utf8_unicode_ci'";
	$db->query($sql);

	$sql = "ALTER TABLE `patch_history` ADD PRIMARY KEY `num` (`num`)";
	$db->query($sql);

	$sql = "INSERT INTO `patch_history` SET `num` = 0";
	$db->query($sql);
}

// Get the last patch level
$sql = "SELECT MAX(num) AS num FROM patch_history";
$row = $db->query($sql)->fetchRow();
$num = (int) $row['num'];

// Next patch to install
$next = $num + 1;

echo "Current patch level: $num.<br>";

// Get the highest patch level available
$patch_dir = new fDirectory(DOC_ROOT . '/db');
$patch_files = $patch_dir->scan('patch*.sql');

foreach ($patch_files as $file)
{
	// Get patch number from filename
	preg_match('/patch([0-9]+).sql/', $file->getName(), $matches);
	$patch_num = $matches[1];

	// If it's at the right level, run it.
	if ($patch_num >= $next)
	{
		echo "Running " . $file->getName() . "... ";
		$contents = $file->read();
		try {
			$db->query($contents);
			echo "OK!<br>";
		} catch (fException $e) {
			echo "Error: " . $e->getMessage() . "<br>";
		}
	}
	else
	{
		echo "Skipping " . $file->getName() . "...<br>";
	}
}

echo '<br><br><a href="' . URL_ROOT . '">Continue to PrintMaster</a>';

/* End of file: ./upgrade.php */