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


class Consumable extends fActiveRecord{
	
	
	var $err;
    
	
	protected function configure(){
    }
	
	
	
	
	/**
	 * Get a single consumable
	 */
	static public function getOne($id){
		
		if($id == NULL){
			return FALSE;
		}
		
		global $db;
		
		$sql = "SELECT 
				consumables.*, 
				( round( ( (consumables.qty) / (SELECT MAX(qty) FROM consumables) ) * 100 ) ) AS qty_percent,
				GROUP_CONCAT(CAST(CONCAT(manufacturers.name, ' ', models.name) AS CHAR) SEPARATOR ', ') AS model
				FROM consumables
				LEFT JOIN consumables_models ON consumables.id = consumables_models.consumable_id
				LEFT JOIN models ON consumables_models.model_id = models.id
				LEFT JOIN manufacturers ON models.manufacturer_id = manufacturers.id
				WHERE consumables.id = %i
				LIMIT 1";
				
		$query = $db->query($sql, $id)->asObjects();
		$result = $query->fetchRow();
		return $result;
		
	}
	
	
	
	
	/**
	 * Update quantity
	 */
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
			$query = $db->execute($sql, $qty, $this->getId());
		} catch(fSQLException $e){
			$this->err = $e->getMessage();
			return FALSE;
		}
		
		return TRUE;
		
	}
	
	
	
	
	/**
	 * Get colour status of a consumable based on supplied quantity
	 *
	 * @param	int		Quantity
	 * @return	string	Hexadecimal representation of a colour (OK, Warning, None)
	 */
	static public function getQtyStatus($qty){
		
		global $status;
		
		// Loop through possible statuses until our quantity is found, then return colour value
		foreach($status as $k => $v){
			if($qty >= $k){ return $v[1]; }
		}
		
	}
	
	
	
	
	/**
	 * Get available consumables associated with models
	 */
	public static function getForModels(&$db){
		
		$sql = "SELECT
				consumables.*, models.name AS model, models.id AS model_id
				FROM consumables
				LEFT JOIN consumables_models ON consumables.id = consumables_models.consumable_id
				LEFT JOIN models ON consumables_models.model_id = models.id";
				
		$consumables = $db->query($sql)->asObjects();
		
		$models = array();
		
		foreach($consumables as $c){
			
			$thismodel = 'model_' . $c->model_id;
			
			$colours = array();
			if($c->col_c){ $colours[] = 'c'; }
			if($c->col_y){ $colours[] = 'y'; }
			if($c->col_m){ $colours[] = 'm'; }
			if($c->col_k){ $colours[] = 'k'; }
			
			$models[$thismodel][] = array(
				'id' => $c->id,
				'name' => $c->name,
				'colours' => $colours,
				'qty' => $c->qty
			);
			
			//{"id": 1, "name": "C9720A", "colour": "c", "qty": 5},
		}
		
		return $models;
		
	}
	
	
	
	
	/**
	 * Get array of printer model IDs associated with 'this' consumable
	 *
	 * @return	array	1-dimensional array containing model IDs
	 */
	public function getModels(){
		
		// Initialise array
		$ids = array();
		// Get models for this consumable
		$models = $this->buildModels();
		// Loop through models and put their IDs into an array
		foreach($models as $m){
			$ids[] = $m->getId();
		}
		// Return array of model IDs
		return $ids;
		
	}
	
	
	
	
	/**
	 * Install a consumable to a printer
	 */
	public function installTo($printer){
		
		global $db;
		
		#printf('Installing consumable %s to printer %s.', $this->getName(), $printer->getName());
		
		#fCore::dump($printer);
		
		// Validation checks
		
		// 1. Check printer model is compatible
		$models = $this->getModels();
		if(!in_array($printer->getModelId(), $models)){
			$this->err = sprintf('Consumable %s not compatible with printer %s.', 
				$this->getName(), $printer->getName());
			return FALSE;
		}
		
		// 2. Check quantity
		if($this->getQty() < 1){
			$this->err = sprintf('No stock of the consumable %s.', $this->getName());
			return FALSE;
		}
		
		// Finished validation
		
		// Add 'event'
		try {
			
			$e = new Event();
			$e->setPrinterId($printer->getId());
			$e->setConsumableId($this->getId());
			$e->setDate(date('Y-m-d H:i:s'));
			$e->store();
			
		} catch(fExpectedException $e){
			#fMessaging::create('error', fURL::get(), $e->getMessage());	
			$this->err = $e->getMessage();
			return FALSE;
		} catch(fSQLException $e){
			#fMessaging::create('error', fURL::get(), 'Database error: ' . $e->getMessage());
			$this->err = $e->getMessage();
			return FALSE;
		}
		
		// Decrease stock
		try {
			$sql = 'UPDATE consumables SET qty = qty - 1 WHERE id = %i LIMIT 1';
			$query = $db->execute($sql, $this->getId());
		} catch(fSQLException $e){
			$this->err = $e->getMessage();
			return FALSE;
		}
		
		
		// Return true
		#echo 'Done!';
		return TRUE;
		
		
	}
	
	
}