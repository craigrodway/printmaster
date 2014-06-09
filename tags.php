<?php
/*
Copyright (C) 2014 Craig A Rodway.

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

if (fRequest::isPost()) {

	// Update ALL the tags.

	$data = fRequest::get('tags[]', 'array', array());

	if (count($data) > 0) {

		$success = 0;

		foreach ($data as $id => $values) {

			try {

				$colour = str_replace('#', '', $values['colour']);
				$title = trim($values['title']);
				$id = (int) $id;

				// Empty new tag entry? Do nothing.
				if ($id === -1 && empty($title)) {
					continue;
				}

				if ($id > 0) {
					// Load tag with ID
					$tag = new Tag($id);
				} else {
					// Create new tag
					$tag = new Tag();
					$tag->setType('custom');
				}

				if (empty($title)) {
					// Title is empty. Remove this tag.
					$tag->delete();
				} else {
					// Update it.
					$tag->setColour($colour);
					$tag->setTitle($title);
					$tag->store();
				}

				$success++;

			} catch (fValidationException $e) {
				fMessaging::create('error', fURL::get(), $e->getMessage());
			} catch(fExpectedException $e) {
				fMessaging::create('error', fURL::get(), $e->getMessage());
			}

		}

		if ($success > 0) {
			fMessaging::create('success', fURL::get(), 'The tags have been updated successfully.');
			fURL::redirect(fURL::get());
		}

	}

}

// Get ALL the (custom) tags.
$tags = Tag::get_by_type('custom');

// Include page to show table
include 'views/tags/index.php';


/* End of file: ./tags.php */