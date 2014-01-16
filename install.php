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
$action = fRequest::getValid('action', array('install'));

$redirect = fRequest::get('redirect', 'string');

// Get IDs
$consumable_id = fRequest::get('consumable_id', 'integer?');
$consumable_ids = fRequest::get('consumable_ids', 'integer[]?');
$printer_id = fRequest::get('printer_id', 'integer?');

if($action == 'install'){

	// Install consumable into printer

	try{

		// Get objects matching the printer/consumable
		$printer = new Printer($printer_id);

		if ( ! empty($consumable_ids))
		{
			$errors = 0;

			foreach ($consumable_ids as $consumable_id)
			{
				// Get consumable and try to install it
				$consumable = new Consumable($consumable_id);
				if ( ! $consumable->installTo($printer))
				{
					$errors++;
				}
			}

			$installed = ($errors === 0);
			$success = sprintf('%d consumables have been installed in to %s!', (count($consumable_ids) - $errors), $printer->getName());
		}
		else
		{
			// Get consumable and try to install it
			$consumable = new Consumable($consumable_id);
			$installed = $consumable->installTo($printer);
			$success = sprintf('The consumable %s has been installed in to %s!',
				$consumable->getName(), $printer->getName());
		}

		// Check status of installation
		if($installed == FALSE){
			fMessaging::create('error', $redirect, $consumable->err);
			fURL::redirect($redirect);
		} else {
			fMessaging::create('success', $redirect, $success);
			fURL::redirect($redirect);
		}

	} catch (fNotFoundException $e) {

		fMessaging::create('error', $redirect, 'The requested object with ID ' . $id . ', could not be found.');
		fURL::redirect($redirect);

	}
}
