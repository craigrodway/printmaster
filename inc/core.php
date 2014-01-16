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

/*
Core initialisation script to be included on all pages.
Sets up DB, paths etc.
*/


// Do a check to see which init method we're using.
if (file_exists(dirname(__FILE__) . '/init.php'))
{
	// Old file still exists. Use that instead.
	require_once('init.php');

	// Initialise configuration
	$config = new Config();
	$config->features = new Config();

	// Fallback to keep existing features enabled
	$config->currency = CURRENCY;
	$config->features->costs = TRUE;
}
else
{
	// New method (1.4.0+)

	// Paths and URLs
	define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
	define('URL_ROOT', str_replace('\\', '/', substr(DOC_ROOT, strlen(realpath($_SERVER['DOCUMENT_ROOT']))) . '/'));

	spl_autoload_register('printmaster_autoload');

	// Initialise configuration
	$config = new Config();
	$config->features = new Config();

	// Include base config file
	require_once(dirname(__FILE__) . '/config/config.default.php');

	// Include custom config file
	if (file_exists(DOC_ROOT . '/inc/config/config.php')) {
		require_once(dirname(__FILE__) . '/config/config.php');
	}


	// Debugging assistance
	fCore::enableDebugging(config_item('debug', FALSE));
	//fCore::enableErrorHandling(DOC_ROOT . '/error.log');
	//fCore::enableExceptionHandling(DOC_ROOT . '/exceptions.log');
	fCore::enableErrorHandling('html');
	fCore::enableExceptionHandling('html');

	// Set page template
	$tpl = new fTemplating(DOC_ROOT . '/views/template');
	$tpl->set('header', 'header.php');
	$tpl->set('footer', 'footer.php');
	$tpl->set('menu', 'menu.php');

	// @TODO remove these when the thresholds become configurable.
	global $status;
	$status[3] = array('OK', '73D216');			// 3+ OK
	$status[2] = array('Low', 'EDD400');		// 2+ Warning
	$status[0] = array('Critical', 'CC0000');	// 0: Empty - bad.

	// Set up database connection
	$db = new fDatabase('mysql', config_item('db_name'), config_item('db_user'), config_item('db_pass'), config_item('db_host'), config_item('db_port'));
	fORMDatabase::attach($db);

	// Configure session
	fSession::setPath(config_item('session_path'));
	fSession::setLength(config_item('session_length', '1 hour'));
	fSession::open();
}




/**
 * Automatically includes classes
 *
 * @throws Exception
 *
 * @param  string $class_name  Name of the class to load
 * @return void
 */
function printmaster_autoload($class){

	$flourish_file = DOC_ROOT . '/inc/flourish/' . $class . '.php';

	if (file_exists($flourish_file)) {
		return require $flourish_file;
	}

	$file = DOC_ROOT . '/inc/classes/' . $class . '.php';

	if (file_exists($file)) {
		return require $file;
	}

	throw new Exception('The class ' . $class_name . ' could not be loaded');
}




/**
 * Get a configuration item, or $default if not present
 *
 * @param string $name		Name of config item to get
 * @param mixed $default		Value to return if $name does not exist
 * @return mixed		Config item value if present, or $default
 */
function config_item($name = '', $default = FALSE)
{
	global $config;
	return $config->get($name, $default);
}




/**
 * Get the active status of a PrintMaster feature
 *
 * @param string $name		Name of feature
 * @param mixed $default		What to return if the value hasn't been set
 * @return bool		True/False
 */
function feature($name = '', $default = FALSE)
{
	global $config;
	return (bool) $config->features->get($name, $default);
}


/* End of file: /inc/core.php */