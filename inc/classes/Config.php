<?php

class Config {


	protected $data;


	public function get($key = '', $default = FALSE)
	{
		return $this->element($key, $this->data, $default);
	}


	public function set($key = '', $value = '')
	{
		$this->data[$key] = $value;
	}


	/**
	 * Is a certain feature enabled?
	 */
	public function feature($key = '', $default = FALSE)
	{
		$features = $this->get('features', array());
		return element($key, $features, $default);
	}


	public function __get($key = '')
	{
		return $this->get($key);
	}


	public function __set($key = '', $value = '')
	{
		return $this->set($key, $value);
	}


	private function element($key = '', $array = array(), $default = FALSE)
	{
		if (array_key_exists($key, $array))
		{
			return $array[$key];
		}
		else
		{
			return $default;
		}
	}


}