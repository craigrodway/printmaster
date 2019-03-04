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


class Order extends fActiveRecord{
	
	
	var $err;
    
	
	protected function configure(){
    }
	
	
	
	
	/**
	 * Get a single order
	 */
	static public function getOne($id){
		
		
		global $db;
		
		$sql = "SELECT 
				orders.*
				FROM orders
				WHERE orders.id = %i
				GROUP BY orders.id
				LIMIT 1";
				
		$query = $db->query($sql)->asObjects();
		$result = $query->fetchRow();
		return $result;
		
	}
	public static function getConsumables(&$db){
		
		// Get simple list of models
		$sql = 'SELECT consumables.*,
				consumables.id AS consumable_id,
				consumables.name AS consumable_name,
				consumables.qty AS current_qty
				FROM consumables
				ORDER BY consumables.qty ASC, consumables.name ASC'; 
		$consumables = $db->query($sql)->asObjects();
		return $consumables;
		
	}
	public function increaseStockBy($qty){
		
		global $db;
		
		// Test if quantity is OK (number above 0)
		if(!is_numeric($qty) /* OR $qty < 1 */ ){
			$this->err = 'Quantity is not a valid number';
			return FALSE;
		}
		
		// Increase stock
		try {
			$sql = 'UPDATE consumables SET qty = qty + %i WHERE id = %i LIMIT 1';
			$query = $db->execute($sql, $qty, $this->getItemId());
		} catch(fSQLException $e){
			$this->err = $e->getMessage();
			return FALSE;
		}
		
		return TRUE;
		
	}
	public function setDelivered(){
		
		global $db;
		
		
		// Set Delivered
		try {
			$status = 1;
			$sql2 = 'UPDATE orders SET status = %i WHERE id = %i LIMIT 1';
			$query2 = $db->execute($sql2, $status, $this->getId());
		} catch(fSQLException $e){
			$this->err = $e->getMessage();
			return FALSE;
		}
		
		return TRUE;
		
	}
}