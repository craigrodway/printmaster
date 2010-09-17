<?php
/*
Copyright (C) 2010 Craig A Rodway.

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


// Include initialisation file
include_once('inc/init.php');

// Get action from query string
$action = fRequest::getValid('action', array('list'));




/**
 * Default action - show report of consumable installation
 */
if ($action == 'list') {
	
	// Set the users to be sortable by name or email, defaulting to name
	$sort = fCRUD::getSortColumn(array(
		'events.date', 'models.name', 'printers.name', 'consumables.name'
	));
	// Set the sorting to default to ascending
	$dir  = fCRUD::getSortDirection('desc');
	// Redirect the user if one of the values was loaded from the session
	fCRUD::redirectWithLoadedValues();
	
	// Get recordset object from tables
	$sql = "SELECT
				CAST(CONCAT(manufacturers.name, ' ', models.name) AS CHAR) AS model,
				printers.name AS printer_name,
				printers.ipaddress,
				consumables.name AS consumable_name,
				consumables.col_c, consumables.col_y, consumables.col_m, consumables.col_k, 
				events.*
			FROM events
			LEFT JOIN consumables ON events.consumable_id = consumables.id
			LEFT JOIN printers ON events.printer_id = printers.id
			LEFT JOIN models ON printers.model_id = models.id
			LEFT JOIN manufacturers ON models.manufacturer_id = manufacturers.id
			{where}
			ORDER BY $sort $dir";
	
	// Get potential printer ID
	$printer_id = fRequest::get('printer_id', 'integer?');
	
	if($printer_id == NULL){
		$sql = str_replace('{where}', '', $sql);
		$events = $db->query($sql)->asObjects();
	} else {
		$sql_where = 'WHERE events.printer_id = %i';
		$sql = str_replace('{where}', $sql_where, $sql);
		$events = $db->query($sql, $printer_id)->asObjects();
		$printer = new Printer($printer_id);
	}
	
	// Include page to show table
	include 'views/reports/index.php';
	
}


?>