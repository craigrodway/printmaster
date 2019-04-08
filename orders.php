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
$action = fRequest::getValid('action', array('list', 'add', 'edit', 'delete', 'update'));




/**
 * Default action - show list of consumables
 */
if ($action == 'list') {

	// Set the users to be sortable by name or email, defaulting to name
	$sort = fCRUD::getSortColumn(array('orders.order_date'));
	// Set the sorting to default to ascending
	$dir  = fCRUD::getSortDirection('desc');
	// Redirect the user if one of the values was loaded from the session
	fCRUD::redirectWithLoadedValues();

	// Get recordset object from tables

	$sql = "SELECT
			orders.*,
			consumables.name AS item_name
			FROM
			orders
			LEFT JOIN
			consumables
			ON orders.item_id = consumables.id
			ORDER BY
			orders.order_date DESC

	";
			$orders = $db->query($sql)->asObjects();

	$sql2 = "SELECT
			orders.*
			FROM
			orders
			GROUP BY
			orders.order_date
			ORDER BY
			orders.order_date DESC
			LIMIT 1
	";
			$orderlist = $db->query($sql2)->asObjects();

	
	include 'views/orders/index.php';

}




/**
 * Add a new consumable
 */
if ($action == 'add') {

	// Create new
	$c = new Order();
	// Try to get form values and save object if requested via POST method
	if (fRequest::isPost()) {

		try{

			// Populat and save consumable object from form values
			$c->populate();
			//$c->linkModels();
			$c->store();
			//$i->linkModels();

			// Set status message
			#fMessaging::create('affected', fURL::get(), $c->id());
			#fMessaging::create('success', fURL::get(), 'Order ' . $c->id() . ' was successfully added.');

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
	$orderitem = Order::getConsumables($db);
	include 'views/orders/addedit.php';

}




/**
 * Edit a consumable
 */
if ($action == 'edit') {

	// Get ID
	$id = fRequest::get('id', 'integer');

	try {

		// Get consumable via ID
		$c = new Order($id);

		if (fRequest::isPost()) {

			// Update consumable object from POST data and save
			$c->populate();
			//$c->linkModels();
			//$c->linkTags();
			$c->store();

			// Messaging
			fMessaging::create('affected', fURL::get(), $c->getId());
			fMessaging::create('success', fURL::get(), 'Order ' . $c->getId() . ' was successfully updated.');
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
	$orderitem = Order::getConsumables($db);

	include 'views/orders/addedit.php';

}

/**
 * Update quantity
 */
if ($action == 'update') {
	
	// Get ID
	$id = fRequest::get('id', 'integer');
	
	// $redirect = fRequest::get('redirect', 'string');
	
		try {
			
			$o = new Order($id);
			if(fRequest::isPost()){

			// Update consumable
			$updatestock = $o->getItem_qty();
			$o->increaseStockBy($updatestock);
			$o->setDelivered();

			fMessaging::create('success', fURL::get(), 'The order ' . $o->getId() . ' was marked as delivered.');
			fURL::redirect(fURL::get());
			}
			
		#die(var_export($updated));

		// Check status of installation
		
		}
		catch (fValidationException $e) {
			fMessaging::create('error', fURL::get(), $e->getMessage());
		} catch(fExpectedException $e) {
			fMessaging::create('error', fURL::get(), $e->getMessage());
		}
		include 'views/orders/update.php';
	}
/**
 * Delete an order
 */
if($action == 'delete'){

	// Get ID
	$id = fRequest::get('id', 'integer');

	try {

		$o = new Order($id);

		if (fRequest::isPost()) {

			$o->delete();

			fMessaging::create('success', fURL::get(), 'The order ' . $o->getId() . ' was successfully deleted.');
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

	include 'views/orders/delete.php';

}


?>