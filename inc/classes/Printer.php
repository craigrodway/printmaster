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


    public static $sort = array(
    	'printers.name',
    	'models.colour',
    	'models.name',
    	'printers.ipaddress',
    	'printers.server',
    	'printers.location',
    );


    public static function findAll($where = array(), $sort = 'printers.name', $dir = 'asc', $start = 0, $limit = 100) {

    	if ( ! in_array($sort, self::$sort)) {
			$sort = 'printers.name';
		}

		if ( ! in_array($dir, array('asc', 'desc'))) {
			$dir = 'asc';
		}

		return fRecordSet::build(
			__CLASS__,
			$where,
			array($sort => $dir)
		);
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


	public function getTags() {
		$tags = $this->buildTags();
		return $tags->filter(array('getType=' => 'custom'));
	}


	public function getStock() {
		$model = $this->createModel();
		$consumables = $model->buildConsumables();
		return $consumables->reduce('Consumable::total_qty');
	}


}