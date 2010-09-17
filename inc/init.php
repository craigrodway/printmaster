<?php
include(dirname(__FILE__) . '/config.php');

// Set page template
$tpl = new fTemplating(DOC_ROOT . '/views/template');
$tpl->set('header', 'header.php');
$tpl->set('footer', 'footer.php');
$tpl->set('menu', 'menu.php');

// Configure database
$db = new fDatabase('mysql', 'printmaster', 'root', '', 'localhost');
fORMDatabase::attach($db);

// Configure session
fSession::setPath(DOC_ROOT . '/session');
fSession::setLength('1 hour');
fSession::open();