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


class Tag extends fActiveRecord {


	protected function configure()
	{
	}




	/**
	 * Get 'simple' list of models - ID + name of manufacturer and model
	 *
	 * @param	object	Database object reference
	 * @return	object	List of models
	 */
	public static function get_by_type($type = '')
	{
		return fRecordSet::build('Tag',
			array('type=' => $type),
			array('title' => 'asc')
		);
	}




	public static function parse_from_post($post_tags = array())
	{
		$tags = array();

		if (empty($post_tags)) return $tags;

		foreach ($post_tags as $key => $value)
		{
			if ( (int) $value !== 0)
			{
				$tags[] = $value;
			}
			else
			{
				$tag = new Tag();
				$tag->setTitle(trim($value));
				$tag->setType('custom');
				$tag->store();
				$tags[] = $tag->getId();
			}
		}

		return $tags;
	}


}