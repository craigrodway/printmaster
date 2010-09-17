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
$action = fRequest::getValid('action', array('install'));

$redirect = fRequest::get('redirect', 'string');

// Get IDs
$consumable_id = fRequest::get('consumable_id', 'integer?');
$printer_id = fRequest::get('printer_id', 'integer?');

if($action == 'install'){
	
	// Install consumable into printer
	
	try{
		
		// Get objects matching the printer/consumable
		$printer = new Printer($printer_id);
		$consumable = new Consumable($consumable_id);
		
		// Try to install it
		$installed = $consumable->installTo($printer);
		
		// Check status of installation
		if($installed == FALSE){
			fMessaging::create('error', $redirect, $consumable->err);
			fURL::redirect($redirect);
		} else {
			fMessaging::create('success', $redirect, sprintf(
				'The consumable %s has been installed in to %s!',
				$consumable->getName(),
				$printer->getName()
			));
			fURL::redirect($redirect);
		}
		
	} catch (fNotFoundException $e) {
		
		fMessaging::create('error', $redirect, 'The requested object with ID ' . $id . ', could not be found.');	
		fURL::redirect($redirect);
	
	}
}
