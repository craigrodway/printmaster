<?php
$tpl->set('title', 'Printer models');
$tpl->place('header');

$menuitems[] = array('models.php?action=add', 'Add new printer model', 'add.png');
$tpl->set('menuitems', $menuitems);
?>

<div class="grid_12">

<?php $tpl->place('menu'); ?>
<br />

<table class="list" id="models">
	<thead>
		<tr class="heading">
			<th><?php echo fCRUD::printSortableColumn('models.colour', 'Colour') ?></th>
			<th><?php echo fCRUD::printSortableColumn('manufacturers.name', 'Manufacturer') ?></th>
			<th><?php echo fCRUD::printSortableColumn('models.name', 'Name') ?></th>
			<th><?php echo fCRUD::printSortableColumn('printer_count', '# Printers') ?></th>
			<th>Operations</th>
		</tr>
	</thead>

	<tbody>
	<?php
	foreach($models as $m){

		echo '<tr>';

		$img = ($m->colour == 1) ? 'colour.png' : 'mono.png';
		echo '<td style="width:48px;text-align:center;">';
		echo '<img src="web/img/' . $img . '" width="16" height="16" />';
		echo '</td>';

		echo '<td>' . $m->mfr_name . '</td>';

		echo '<td class="name">' . $m->name . '</td>';

		if ( (int) $m->printer_count > 0)
		{
			$actions = array(
				array(
					'printers.php?model_id=' . $m->id, 'View printers (' . $m->printer_count . ')', 'printer.png',
				)
			);
			$tpl->set('menuitems', $actions);
			echo '<td>';
			$tpl->place('menu');
			echo '</td>';
		}
		else
		{
			echo '<td></td>';
		}

		echo '<td>';
		unset($actions);
		$actions[] = array('models.php?action=edit&id=' . $m->id, 'Edit', 'edit.png');
		$actions[] = array('models.php?action=delete&id=' . $m->id, 'Delete', 'delete.png');

		$tpl->set('menuitems', $actions);
		$tpl->place('menu');
		echo '</td>';

		echo '</tr>';
	}
	?>
	</tbody>

</table>

</div>

<script type="text/javascript">
$(document).ready(function(){
	$("table#models").addTableFilter({
		labelText: "Find model:",
		size: 30
	});
	$('#models-filtering').focus();
});
</script>

<?php
$tpl->place('footer')
?>