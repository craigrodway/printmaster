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


class Printer extends fActiveRecord{
    
	
	protected function configure(){
    }
	
	
	
	
	/**
	 * Get 'simple' list of printers for ID and name
	 *
	 * @param	object	Database object reference
	 * @return	object	List of printers
	 */
	public static function getSimple(&$db){
		
		// Get simple list of printers
		$sql = "SELECT 
					printers.*,
					CAST(CONCAT(manufacturers.name, ' ', models.name) AS CHAR) AS model,
					models.colour
				FROM printers
				LEFT JOIN models ON printers.model_id = models.id
				LEFT JOIN manufacturers ON models.manufacturer_id = manufacturers.id
				GROUP BY printers.id
				ORDER BY printers.name ASC";
		$printers = $db->query($sql)->asObjects();
		return $printers;
		
	}
	
	
}