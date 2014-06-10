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


include_once('inc/core.php');

// Do not show page title beneath menu (two columns have own titles)
$tpl->set('hidetitle', TRUE);

// Set page title
$tpl->set('title', 'Home');
$tpl->place('header');




/**
 * Consumable stock
 */

// Get consumables
$consumables = Consumable::findAll('consumables.name', 'asc');




/**
 * Quick add
 */

 // List of printers
$printers = Printer::getSimple($db);
// List of models and which consumables are available
$models_consumables = Consumable::getForModels($db);
$models_consumables_json = fJSON::encode($models_consumables);
?>

	<br />

	<div class="grid_5 suffix_1">
		<?php include 'views/home/quickadd.php'; ?>
	</div>

	<div class="grid_6">
		<?php include 'views/home/stock.php'; ?>
	</div>

<?php
$tpl->place('footer');
?>