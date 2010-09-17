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


class Model extends fActiveRecord{
    
	
	protected function configure(){
    }
	
	
	
	
	/**
	 * Get 'simple' list of models - ID + name of manufacturer and model
	 *
	 * @param	object	Database object reference
	 * @return	object	List of models
	 */
	public static function getSimple(&$db){
		
		// Get simple list of models
		$sql = 'SELECT models.id, models.name AS model_name, manufacturers.name AS mfr_name
				FROM models
				LEFT JOIN manufacturers ON models.manufacturer_id = manufacturers.id
				ORDER BY manufacturers.name ASC, models.name ASC';
		$models = $db->query($sql)->asObjects();
		return $models;
		
	}
	
	
}