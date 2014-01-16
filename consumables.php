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
 * Default action - show list of consumables
 */
if ($action == 'list') {

	// Set the users to be sortable by name or email, defaulting to name
	$sort = fCRUD::getSortColumn(array('consumables.name', 'consumables.qty', 'consumables.cost'));
	// Set the sorting to default to ascending
	$dir  = fCRUD::getSortDirection('asc');
	// Redirect the user if one of the values was loaded from the session
	fCRUD::redirectWithLoadedValues();

	// Get recordset object from tables
	$sql = "SELECT
			consumables.*,
			( round( ( (consumables.qty) / (SELECT MAX(qty) FROM consumables) ) * 100 ) ) AS qty_percent,
			GROUP_CONCAT(CAST(CONCAT(manufacturers.name, ' ', models.name) AS CHAR) SEPARATOR ', ') AS model
			FROM consumables
			LEFT JOIN consumables_models ON consumables.id = consumables_models.consumable_id
			LEFT JOIN models ON consumables_models.model_id = models.id
			LEFT JOIN manufacturers ON models.manufacturer_id = manufacturers.id
			GROUP BY consumables.id
			ORDER BY $sort $dir";
	$consumables = $db->query($sql)->asObjects();

	#$consumables = fRecordSet::build('Consumable', NULL, array($sort => $dir));



	#$models = fRecordSet::build('Model', NULL, array('name' => 'asc'));
	#$models->precreateManufacturers();

	// Include page to show table
	include 'views/consumables/index.php';

}




/**
 * Add a new consumable
 */
if ($action == 'add') {

	// Create new
	$c = new Consumable();

	// Try to get form values and save object if requested via POST method
	if (fRequest::isPost()) {

		try{

			// Try to validate options
			$validator = new fValidation();
			$validator->addOneOrMoreRule('col_c', 'col_y', 'col_m', 'col_k');
			$validator->overrideFieldName(array(
				'col_c' => 'Colour (Cyan)',
				'col_y' => 'Colour (Yellow)',
				'col_m' => 'Colour (Magenta)',
				'col_k' => 'Colour (Black)',
			));
			$validator->validate();

			// Populat and save consumable object from form values
			$c->populate();
			$c->linkModels();
			$c->store();

			// Set status message
			fMessaging::create('affected', fURL::get(), $c->getName());
			fMessaging::create('success', fURL::get(), 'The consumable ' . $c->getName() . ' was successfully added.');

			// Redirect
			$next = fRequest::getValid('next', array('index', 'add'));
			if ($next === 'index')
			{
				fURL::redirect(fURL::get());
			}
			elseif ($next === 'add')
			{
				fURL::redirect(fURL::get() . '?action=add');
			}

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

	include 'views/consumables/addedit.php';

}




/**
 * Edit a consumable
 */
if ($action == 'edit') {

	// Get ID
	$id = fRequest::get('id', 'integer');

	try {

		// Get consumable via ID
		$c = new Consumable($id);

		if (fRequest::isPost()) {

			// Try to validate options
			$validator = new fValidation();
			$validator->addOneOrMoreRule('col_c', 'col_y', 'col_m', 'col_k');
			$validator->overrideFieldName(array(
				'col_c' => 'Colour (Cyan)',
				'col_y' => 'Colour (Yellow)',
				'col_m' => 'Colour (Magenta)',
				'col_k' => 'Colour (Black)',
			));
			$validator->validate();

			// Update consumable object from POST data and save
			$c->populate();
			$c->linkModels();
			$c->store();

			// Messaging
			fMessaging::create('affected', fURL::get(), $c->getId());
			fMessaging::create('success', fURL::get(), 'The consumable ' . $c->getName() . ' was successfully updated.');
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

	include 'views/consumables/addedit.php';

}


/**
 * Delete a consumable
 */
if($action == 'delete'){

	// Get ID
	$id = fRequest::get('id', 'integer');

	try {

		$c = new Consumable($id);

		if (fRequest::isPost()) {

			$c->delete();

			fMessaging::create('success', fURL::get(), 'The consumable ' . $c->getName() . ' was successfully deleted.');
			fURL::redirect(fURL::get());

		}

	} catch(fNotFoundException $e) {

		fMessaging::create('error', fURL::get(), 'The consumable requested, ID ' . $id . ', could not be found.');
		fURL::redirect($manage_url);

	} catch(fExpectedException $e) {

		fMessaging::create('error', fURL::get(), $e->getMessage());

	} catch(fSQLException $e) {

		fMessaging::create('error', fURL::get(), 'Database error: ' . $e->getMessage());

	}

	include 'views/consumables/delete.php';

}


?>