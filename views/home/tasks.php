<h2>Tasks</h2>

<?php
// Menu items
$actions[] = array('printers.php?action=add', 'Add new printer', 'printer_add.png');
$actions[] = array('consumables.php?action=new', 'New consumable stock', 'package_green.png');
$actions[] = array('reports.php', 'Printer reports', 'report_magnify.png');

$tpl->set('menuclass', 'linkbar linkbar-vertical');
$tpl->set('menuitems', $actions);
$tpl->place('menu');
?>