<?php

// Consumable status
global $status;
$status[3] = array('OK', '73D216');			// 3+ OK
$status[2] = array('Low', 'EDD400');		// 2+ Warning
$status[0] = array('Critical', 'CC0000');	// 0: Empty - bad.

// Currency
define('CURRENCY', '&pound;');


// Paths and URLs
define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
define('URL_ROOT', str_replace('\\', '/', substr(DOC_ROOT, strlen(realpath($_SERVER['DOCUMENT_ROOT']))) . '/'));


// Error reporting
#error_reporting(E_ALL);
fCore::enableDebugging(FALSE);
#fCore::enableErrorHandling(DOC_ROOT . '/error.log');
#fCore::enableExceptionHandling(DOC_ROOT . '/exceptions.log');

fCore::enableErrorHandling('html');
fCore::enableExceptionHandling('html');


/**
 * Automatically includes classes
 * 
 * @throws Exception
 * 
 * @param  string $class_name  Name of the class to load
 * @return void
 */
function __autoload($class){
    
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