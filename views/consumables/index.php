<?php
$tpl->set('title', 'Consumables');
$tpl->place('header');

$menuitems[] = array('consumables.php?action=add', 'Add new consumable', 'add.png');
#$menuitems[] = array('stock.php', 'New delivery', 'package_green.png');
$tpl->set('menuitems', $menuitems);

$colour = '<span style="color:#%s;">&bull;</span>';
?>

<div class="grid_12">

<?php $tpl->place('menu'); ?>
<br />

<table class="list" id="consumables">
	<thead>
		<tr class="heading">
			<th>Colour</th>
			<th><?php echo fCRUD::printSortableColumn('consumables.name', 'Name') ?></th>
			<?php if (feature('costs')): ?>
			<th class="right"><?php echo fCRUD::printSortableColumn('consumables.cost', 'Cost') ?></th>
			<?php endif; ?>
			<th colspan="2"><?php echo fCRUD::printSortableColumn('consumables.qty', 'Quantity') ?> <span class="js-qty-total"></span></th>
			<th>Printer models</th>
			<th>Operations</th>
		</tr>
	</thead>

	<tbody>

	<?php
	foreach($consumables as $c){

		echo '<tr>';

		echo '<td style="width:100px;text-align:left;" class="col">';
		if($c->col_c){ printf($colour, '0066B3'); }
		if($c->col_y){ printf($colour, 'FFCC00'); }
		if($c->col_m){ printf($colour, 'CC0099'); }
		if($c->col_k){ printf($colour, '000'); }
		echo '</td>';

		echo '<td class="name">' . $c->name . '</td>';

		if (feature('costs'))
		{
			echo '<td class="right">' . ($c->cost ? config_item('currency') . $c->cost : ''). '</td>';
		}

		$qtycol = Consumable::getQtyStatus($c->qty);
		$qtyinfo = '<span style="background:#%s;padding:3px 6px;-webkit-border-radius:4px;font-weight:bold;color:#000;">%d</span>';
#		echo '<td>' . sprintf($qtyinfo, $qtycol, $c->qty) . '</td>';

		echo '<td width="20"><span class="js-consumable-qty">' . $c->qty . '</span></td>';


		$bar = '<td width="120"><div class="progress-container"><div style="width: %d%%; background: #%s;"></div></div></td>';
		printf($bar, $c->qty_percent, $qtycol);



		echo '<td>' . $c->model . '</td>';

		echo '<td>';
		unset($actions);
		$actions[] = array('consumables.php?action=edit&id=' . $c->id, 'Edit', 'edit.png');
		$actions[] = array('consumables.php?action=delete&id=' . $c->id, 'Delete', 'delete.png');
		// Only allow installation if there is stock
		if($c->qty > 0){
			#$actions[] = array('install.php?consumable_id=' . $c->id, 'Install to printer &rarr;', 'printer_add.png');
		}
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

function update_qty_total() {
	var total = 0;

	$(".js-consumable-qty:visible").each(function() {
		total = total + parseInt($(this).text());
	});

	$(".js-qty-total").text(" (" + total + ")");
}

$(document).ready(function(){
	$("table#consumables").addTableFilter({
		labelText: "Find consumable:",
		size: 30
	}).on("filtered", function() {
		update_qty_total();
	});

	$('#consumables-filtering').focus();

	update_qty_total();
});
</script>

<?php
$tpl->place('footer')
?>