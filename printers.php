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
$action = fRequest::getValid('action', array('list', 'add', 'edit', 'delete'));




/**
 * Default action - show list of printers
 */
if ($action == 'list') {
	
	// Set the users to be sortable by name or email, defaulting to name
	$sort = fCRUD::getSortColumn(array(
		'printers.name', 'models.name', 'printers.ipaddress', 
		'printers.server', 'printers.location', 'models.colour'
	));
	// Set the sorting to default to ascending
	$dir  = fCRUD::getSortDirection('asc');
	// Redirect the user if one of the values was loaded from the session
	fCRUD::redirectWithLoadedValues();
	
	// Get recordset object from tables
	$sql = "SELECT 
				printers.*, 
				manufacturers.name AS mfr_name, 
				models.name AS model_name,
				models.colour,
				GROUP_CONCAT(CAST(CONCAT(manufacturers.name, ' ', models.name) AS CHAR) SEPARATOR ', ') AS model,
				(
					SELECT SUM(qty) FROM consumables
					LEFT JOIN consumables_models ON consumables.id = consumables_models.consumable_id
					WHERE consumables_models.model_id = printers.model_id
				) AS stock
			FROM printers
			LEFT JOIN models ON printers.model_id = models.id
			LEFT JOIN manufacturers ON models.manufacturer_id = manufacturers.id
			GROUP BY printers.id
			ORDER BY $sort $dir";
	$printers = $db->query($sql)->asObjects();
	
	#$consumables = fRecordSet::build('Consumable', NULL, array($sort => $dir));
	
	
	
	#$models = fRecordSet::build('Model', NULL, array('name' => 'asc'));
	#$models->precreateManufacturers();
	
	// Include page to show table
	include 'views/printers/index.php';
	
}




/**
 * Add a new printer
 */
if ($action == 'add') {
	
	// Create new 
	$p = new Printer();
	
	// Try to get form values and save object if requested via POST method
	if (fRequest::isPost()) {
		
		try{
		
			// Populate and save printer object from form values
			$p->populate();
			$p->store();
			
			// Set status message
			fMessaging::create('affected', fURL::get(), $p->getName());
			fMessaging::create('success', fURL::get(), 'The printer ' . $p->getName() . ' was successfully added.');
			
			// Redirect
			fURL::redirect(fURL::get());
			
		} catch (fValidationException $e) {
			fMessaging::create('error', fURL::get(), $e->getMessage());
		} catch(fExpectedException $e) {
			fMessaging::create('error', fURL::get(), $e->getMessage());
		}	
		
	}
	
	// Get manufacturers also for drop-down box
	#$manufacturers = fRecordSet::build('Manufacturer', NULL, array('name' => 'asc'));
	
	// Get list of models
	$models = Model::getSimple($db);
	
	include 'views/printers/addedit.php';
	
}




/**
 * Edit a printer
 */
if ($action == 'edit') {
	
	// Get ID
	$id = fRequest::get('id', 'integer');
	
	try {
		
		// Get consumable via ID
		$p = new Printer($id);
		
		if (fRequest::isPost()) {
			
			// Update printer object from POST data and save
			$p->populate();
			$p->store();
			
			// Messaging
			fMessaging::create('affected', fURL::get(), $p->getId());
			fMessaging::create('success', fURL::get(), 'The printer ' . $p->getName() . ' was successfully updated.');
			fURL::redirect(fURL::get());	
			
		}
	
	} catch (fNotFoundException $e) {
		
		fMessaging::create('error', fURL::get(), 'The consumable requested, ID ' . $id . ', could not be found.');	
		fURL::redirect(fURL::get());
		
	} catch (fExpectedException $e) {
		
		fMessaging::create('error', fURL::get(), $e->getMessage());	
		
	}
	
	// Get manufacturers also for drop-down box
	#$manufacturers = fRecordSet::build('Manufacturer', NULL, array('name' => 'asc'));
	
	// Get list of models
	$models = Model::getSimple($db);
	
	include 'views/printers/addedit.php';
	
}


/**
 * Delete a printer
 */
if($action == 'delete'){

	// Get ID
	$id = fRequest::get('id', 'integer');
	
	try {
		
		$p = new Printer($id);
		
		if (fRequest::isPost()) {
			
			$p->delete();
			
			fMessaging::create('success', fURL::get(), 'The printer ' . $p->getName() . ' was successfully deleted.');
			fURL::redirect(fURL::get());
		
		}
	
	} catch(fNotFoundException $e) {
		
		fMessaging::create('error', fURL::get(), 'The printer requested, ID ' . $id . ', could not be found.');
		fURL::redirect($manage_url);
	
	} catch(fExpectedException $e) {
		
		fMessaging::create('error', fURL::get(), $e->getMessage());	
		
	} catch(fSQLException $e) {
		
		fMessaging::create('error', fURL::get(), 'Database error: ' . $e->getMessage());
		
	}
	
	include 'views/printers/delete.php';

}


?>