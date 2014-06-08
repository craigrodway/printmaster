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
$model_id = fRequest::get('model_id', 'integer');




/**
 * Default action - show list of printers
 */
if ($action == 'list') {

	// Set the users to be sortable by name or email, defaulting to name
	$sort = fCRUD::getSortColumn(Printer::$sort);
	// Set the sorting to default to ascending
	$dir  = fCRUD::getSortDirection('asc');
	// Redirect the user if one of the values was loaded from the session
	fCRUD::redirectWithLoadedValues();

	// Filter
	$where = array();

	if ($model_id)
	{
		// Filter by model ID
		$where['printers.model_id='] = $model_id;
	}

	$printers = Printer::findAll($where, $sort, $dir);

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

			// Tags
			if (feature('tags'))
			{
				$post_tags = fRequest::get('tags[]', 'string[]', array());
				$tags = Tag::parse_from_post($post_tags);
				$p->associateTags($tags);
			}

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

	if (feature('tags'))
	{
		$tags = Tag::get_by_type('custom');
	}

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