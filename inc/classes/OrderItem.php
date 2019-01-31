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


class OrderItem extends fActiveRecord{
	
	
	var $err;
    
	
	protected function configure(){
    }
	
	
	
	
	/**
	 * Get a single order
	 */
	static public function getOne($id){
		
		
		global $db;
		
		$sql = "SELECT 
				order_items.*
				FROM order_items
				WHERE order_items.id = %i
				GROUP BY order_items.id
				LIMIT 1";
				
		$query = $db->query($sql)->asObjects();
		$result = $query->fetchRow();
		return $result;
		
	}
	
}