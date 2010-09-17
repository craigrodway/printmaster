<?php
$tpl->set('title', 'Printer manufacturers');
$tpl->place('header');

$menuitems[] = array('manufacturers.php?action=add', 'Add new manufacturer', 'add.png');
$tpl->set('menuitems', $menuitems);
?>

<div class="grid_12">

<?php $tpl->place('menu'); ?>
<br />

<table class="list">
	<tr class="heading">
		<th>Name</th>
		<th># Models</th>
		<th>Operations</th>
	</tr>
	
	<?php
	#fCore::expose($manufacturers);
	foreach($manufacturers as $m){
		
		echo '<tr>';
		
		echo '<td class="name">' . $m->getName() . '</td>';
		
		echo '<td>';
		echo $m->countModels();
		echo '</td>';
		
		echo '<td>';
		unset($actions);
		$actions[] = array('manufacturers.php?action=edit&id=' . $m->getId(), 'Edit', 'edit.png');
		$actions[] = array('manufacturers.php?action=delete&id=' . $m->getId(), 'Delete', 'delete.png');
		$tpl->set('menuitems', $actions);
		$tpl->place('menu');
		echo '</td>';
		
		echo '</tr>';
	}
	?>
	
</table>

</div>

<?php
$tpl->place('footer')
?>