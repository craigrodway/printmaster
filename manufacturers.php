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
$action = fRequest::getValid('action', array('list', 'add', 'edit', 'delete'));




/**
 * Default action - show list of manufacturers
 */
if ($action == 'list') {

	// Get recordset object from table, ordered by name
	$manufacturers = fRecordSet::build('Manufacturer', NULL, array('name' => 'asc'));
	// Include page to show table
	include 'views/manufacturers/index.php';

}




/**
 * Add a new manufacturer
 */
if ($action == 'add') {

	// Create new
	$m = new Manufacturer();

	// Try to get form values and save object if requested via POST method
	if (fRequest::isPost()) {

		try{
			// Populat and save manufacturer object from form values
			$m->populate();
			$m->store();

			// Set status message
			fMessaging::create('affected', fURL::get(), $m->getName());
			fMessaging::create('success', fURL::get(), 'The manufacturer ' . $m->getName() . ' was successfully added.');

			// Redirect
			fURL::redirect(fURL::get());

		} catch(fExpectedException $e) {
			fMessaging::create('error', fURL::get(), $e->getMessage());
		}

	}

	include 'views/manufacturers/addedit.php';

}




/**
 * Edit a manufacturer
 */
if ($action == 'edit') {

	// Get ID
	$id = fRequest::get('id', 'integer');

	try {

		// Get manufacturer via ID
		$m = new Manufacturer($id);

		if (fRequest::isPost()) {

			// Update manufacturer object from POST data and save
			$m->populate();
			$m->store();

			// Messaging
			fMessaging::create('affected', fURL::get(), $m->getId());
			fMessaging::create('success', fURL::get(), 'The manufacturer ' . $m->getName() . ' was successfully updated.');
			fURL::redirect(fURL::get());

		}

	} catch (fNotFoundException $e) {

		fMessaging::create('error', fURL::get(), 'The manufacturer requested, ID ' . $id . ', could not be found.');
		fURL::redirect(fURL::get());

	} catch (fExpectedException $e) {

		fMessaging::create('error', fURL::get(), $e->getMessage());

	}

	include 'views/manufacturers/addedit.php';

}


/**
 * Delete a manufacturer
 */
if($action == 'delete'){

	// Get ID
	$id = fRequest::get('id', 'integer');

	try {

		$m = new Manufacturer($id);

		if (fRequest::isPost()) {

			$m->delete();

			fMessaging::create('success', fURL::get(), 'The manufacturer ' . $m->getName() . ' was successfully deleted.');
			fURL::redirect(fURL::get());

		}

	} catch(fNotFoundException $e) {

		fMessaging::create('error', fURL::get(), 'The manufacturer requested, ID ' . $id . ', could not be found.');
		fURL::redirect($manage_url);

	} catch(fExpectedException $e) {

		fMessaging::create('error', fURL::get(), $e->getMessage());

	} catch(fSQLException $e) {

		fMessaging::create('error', fURL::get(), 'Database error: ' . $e->getMessage());

	}

	include 'views/manufacturers/delete.php';

}

?>