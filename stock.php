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


// Get parameters
$redirect = fRequest::get('redirect', 'string');
$consumable_id = fRequest::get('consumable_id', 'integer?');
$qty = fRequest::get('qty');
$cost = fRequest::get('cost', 'float?');

// Determine status - show page or update stock
if(fRequest::isPost() && $consumable_id != NULL){

	// Increase stock

	try{

		// Get objects matching the printer/consumable
		$consumable = new Consumable($consumable_id);

		// Update cost if present
		if ($cost)
		{
			$consumable->setCost($cost);
			$consumable->store();
		}

		// Update consumable
		$updated = $consumable->increaseStockBy($qty);

		#die(var_export($updated));

		// Check status of installation
		if($updated == FALSE){
			fMessaging::create('error', $redirect, $consumable->err);
			fURL::redirect($redirect);
		} else {
			fMessaging::create('success', $redirect, sprintf(
				'The consumable stock for %s has been updated.',
				$consumable->getName()
			));
			fURL::redirect($redirect);
		}

	} catch (fNotFoundException $e) {

		fMessaging::create('error', $redirect, 'The requested object with ID ' . $id . ', could not be found.');
		fURL::redirect($redirect);

	}


} else {

	// Get consumable object from ID
	if($consumable_id != NULL){
		$c = Consumable::getOne($consumable_id);
	}

	// No POSTed data, show form (based on request method)
	$view = (fRequest::isAjax()) ? 'ajax.php' : 'simple.php';
	include 'views/stock/' . $view;

}
