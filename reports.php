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
include_once('inc/core.php');

// Get action from query string
$action = fRequest::getValid('action', array('list'));
$printer_id = fCRUD::getSearchValue('printer_id');
$date_from = fCRUD::getSearchValue('date_from');
$date_to = fCRUD::getSearchValue('date_to');



/**
 * Default action - show report of consumable installation
 */
if ($action == 'list') {

	// Set the users to be sortable by name or email, defaulting to name
	$sort = fCRUD::getSortColumn(array(
		'events.date', 'models.name', 'printers.name', 'consumables.name', 'events.cost'
	));

	// Set the sorting to default to ascending
	$dir  = fCRUD::getSortDirection('desc');

	// Redirect the user if one of the values was loaded from the session
	fCRUD::redirectWithLoadedValues();

	// Determine search parameters
	$sql_where = '';

	if ($printer_id)
	{
		$printer = new Printer($printer_id);
		$sql_where .= ' AND events.printer_id = ' . $db->escape('integer', $printer_id);
	}

	if ($date_from)
	{
		$sql_where .= ' AND DATE(events.date) >= ' . $db->escape('date', $date_from);
	}

	if ($date_to)
	{
		$sql_where .= ' AND DATE(events.date) <= ' . $db->escape('date', $date_to);
	}

	// Get recordset object from tables
	$sql = "SELECT
				CAST(CONCAT(manufacturers.name, ' ', models.name) AS CHAR) AS model,
				printers.name AS printer_name,
				printers.ipaddress,
				consumables.name AS consumable_name,
				consumables.col_c, consumables.col_y, consumables.col_m, consumables.col_k,
				consumables.chargeback,
				events.*
			FROM events
			LEFT JOIN consumables ON events.consumable_id = consumables.id
			LEFT JOIN printers ON events.printer_id = printers.id
			LEFT JOIN models ON printers.model_id = models.id
			LEFT JOIN manufacturers ON models.manufacturer_id = manufacturers.id
			WHERE 1 = 1
			$sql_where
			ORDER BY $sort $dir";

	$events = $db->query($sql)->asObjects();

	// Get list of printers for dropdown box
	$printers = Printer::getSimple($db);

	// Include page to show table
	include 'views/reports/index.php';

}


?>