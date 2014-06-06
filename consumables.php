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
	// Get the consumables
	$consumables = Consumable::findAll($sort, $dir);

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

		try {

			// Pick up all tags
			$tags = fRequest::get('consumable_types[]', 'integer[]', array());
			$tags[] = fRequest::get('supply_type', 'integer?');
			$tags = array_filter($tags, 'strlen');

			// Populate and save consumable object from form values
			$c->populate();
			$c->linkModels();
			$c->associateTags($tags);
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

	// Get list of models
	$models = Model::getSimple($db);

	// Get consumable types
	if (feature('consumable_types'))
	{
		$consumable_types = Tag::get_by_type('consumable_type');
	}

	// Get supply types
	if (feature('supply_types'))
	{
		$supply_types = Tag::get_by_type('supply_type');
	}

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

			// Pick up all tags
			$tags = fRequest::get('consumable_types[]', 'integer[]', array());
			$tags[] = fRequest::get('supply_type', 'integer?');
			$tags = array_filter($tags, 'strlen');

			// Update consumable object from POST data and save
			$c->populate();
			$c->linkModels();
			$c->associateTags($tags);
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

	// Get list of models
	$models = Model::getSimple($db);

	// Get consumable types
	if (feature('consumable_types'))
	{
		$consumable_types = Tag::get_by_type('consumable_type');
	}

	// Get supply types
	if (feature('supply_types'))
	{
		$supply_types = Tag::get_by_type('supply_type');
	}

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