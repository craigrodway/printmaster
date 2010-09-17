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
 * Default action - show list of models
 */
if ($action == 'list') {
	
	// Set the users to be sortable by name or email, defaulting to name
	$sort = fCRUD::getSortColumn(array('models.name', 'models.colour', 'manufacturers.name'));
	// Set the sorting to default to ascending
	$dir  = fCRUD::getSortDirection('asc');
	// Redirect the user if one of the values was loaded from the session
	fCRUD::redirectWithLoadedValues();
	
	// Get recordset object from table, ordered by name
	$models = $db->query("SELECT models.*, manufacturers.name AS mfr_name FROM models
		LEFT JOIN manufacturers ON models.manufacturer_id = manufacturers.id
		ORDER BY $sort $dir"
	)->asObjects();
	
	
	#$models = fRecordSet::build('Model', NULL, array('name' => 'asc'));
	#$models->precreateManufacturers();
	
	// Include page to show table
	include 'views/models/index.php';
	
}




/**
 * Add a new model
 */
if ($action == 'add') {
	
	// Create new 
	$m = new Model();
	
	// Try to get form values and save object if requested via POST method
	if (fRequest::isPost()) {
		
		try{
			// Populat and save manufacturer object from form values
			$m->populate();
			$m->store();
			
			// Set status message
			fMessaging::create('affected', fURL::get(), $m->getName());
			fMessaging::create('success', fURL::get(), 'The model ' . $m->getName() . ' was successfully added.');
			
			// Redirect
			fURL::redirect(fURL::get());
			
		} catch(fExpectedException $e) {
			fMessaging::create('error', fURL::get(), $e->getMessage());
		}	
		
	}
	
	// Get manufacturers also for drop-down box
	$manufacturers = fRecordSet::build('Manufacturer', NULL, array('name' => 'asc'));
	
	include 'views/models/addedit.php';
	
}




/**
 * Edit a model
 */
if ($action == 'edit') {
	
	// Get ID
	$id = fRequest::get('id', 'integer');
	
	try {
		
		// Get model via ID
		$m = new Model($id);
		
		if (fRequest::isPost()) {
			
			// Update model object from POST data and save
			$m->populate();
			$m->store();
			
			// Messaging
			fMessaging::create('affected', fURL::get(), $m->getId());
			fMessaging::create('success', fURL::get(), 'The model ' . $m->getName() . ' was successfully updated.');
			fURL::redirect(fURL::get());	
			
		}
	
	} catch (fNotFoundException $e) {
		
		fMessaging::create('error', fURL::get(), 'The model requested, ID ' . $id . ', could not be found.');	
		fURL::redirect(fURL::get());
		
	} catch (fExpectedException $e) {
		
		fMessaging::create('error', fURL::get(), $e->getMessage());	
		
	}
	
	// Get manufacturers also for drop-down box
	$manufacturers = fRecordSet::build('Manufacturer', NULL, array('name' => 'asc'));
	
	include 'views/models/addedit.php';
	
}


/**
 * Delete a model
 */
if($action == 'delete'){

	// Get ID
	$id = fRequest::get('id', 'integer');
	
	try {
		
		$m = new Model($id);
		
		if (fRequest::isPost()) {
			
			$m->delete();
			
			fMessaging::create('success', fURL::get(), 'The model ' . $m->getName() . ' was successfully deleted.');
			fURL::redirect(fURL::get());
		
		}
	
	} catch(fNotFoundException $e) {
		
		fMessaging::create('error', fURL::get(), 'The model requested, ID ' . $id . ', could not be found.');
		fURL::redirect($manage_url);
	
	} catch(fExpectedException $e) {
		
		fMessaging::create('error', fURL::get(), $e->getMessage());	
		
	} catch(fSQLException $e) {
		
		fMessaging::create('error', fURL::get(), 'Database error: ' . $e->getMessage());
		
	}
	
	include 'views/models/delete.php';

}


?>