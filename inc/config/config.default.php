<?php

/**
 * PrintMaster default configuration.
 *
 * Copy this file to config.php and change to match your settings.
 */


/**
 * Database configuration
 */
$config->db_host = '';
$config->db_port = '';
$config->db_user = '';
$config->db_pass = '';
$config->db_name = '';


/**
 * Currency to display for various costs
 */
$config->currency = '&pound;';


/**
 * Session configuration
 *
 * session_save_path: Path to the folder where session files are saved
 * session_length: Length of active sessions
 */
$config->session_path = DOC_ROOT . '/session';
$config->session_length = '1 hour';


/**
 * Debug mode
 *
 * This can be helpful when diagnosing problems and errors. Should usually be false.
 */
$config->debug = FALSE;


/**
 * Features to enable by default.
 *
 * To override, copy this section to your own config.php file, and set the appropriate value to true or false.
 *
 * costs: Consumable and printer costs
 * chargeback: Consumable chargeback option (Yes/No)
 * consumable_types: Consumable types
 */
$config->features->costs = TRUE;
$config->features->chargeback = TRUE;
$config->features->consumable_types = TRUE;