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
			<?php if (feature('supply_types')): ?>
			<th>&nbsp;</th>
			<?php endif; ?>
			<th>Colour</th>
			<th><?php echo fCRUD::printSortableColumn('consumables.name', 'Name') ?></th>
			<?php if (feature('costs') || feature('chargeback')): ?>
			<th class="right"><?php echo fCRUD::printSortableColumn('consumables.cost', 'Cost') ?></th>
			<?php endif; ?>
			<th colspan="2"><?php echo fCRUD::printSortableColumn('consumables.qty', 'Quantity') ?> <span class="js-qty-total"></span></th>
			<th>Printer models</th>
			<th>Printers</th>
			<th>Operations</th>
		</tr>
	</thead>

	<tbody>

	<?php
	foreach ($consumables as $c) {

		echo '<tr>';

		if (feature('supply_types')) {
			echo '<td style="width:20px;text-align:center;" class="col">';
			$supply_tags = $c->getTags('supply_type');
			if ($supply_tags->count() == 1) {
				$supply_tag = $supply_tags->getRecord(0);
				echo '<img style="vertical-align:middle" src="web/img/tags/' . $supply_tag->getId() . '.png" class="js-tooltip" title="' . $supply_tag->encodeTitle() . '">';
			}
			echo '</td>';
		}

		echo '<td style="width:100px;text-align:left;" class="col">';
		if($c->getColC()){ printf($colour, '0066B3'); }
		if($c->getColY()){ printf($colour, 'FFCC00'); }
		if($c->getColM()){ printf($colour, 'CC0099'); }
		if($c->getColK()){ printf($colour, '000'); }
		echo '</td>';

		echo '<td class="name">' . $c->prepareName() . '</td>';

		if (feature('costs') || feature('chargeback'))
		{
			echo '<td class="consumable_cost_col right">';

			if (feature('chargeback'))
			{
				echo ($c->getChargeback() == 1 ? '<img src="web/img/money.png" width="16" height="16" alt="Chargeback" class="chargeback_img" />' : '&nbsp;');
			}

			echo ($c->getCost() ? '<span class="consumable_cost_value">' . config_item('currency') . $c->prepareCost() . '</span>' : '');
			echo '</td>';
		}

		$qtycol = $c->getQtyStatus();
		$qtyinfo = '<span style="background:#%s;padding:3px 6px;-webkit-border-radius:4px;font-weight:bold;color:#000;">%d</span>';

		echo '<td width="20"><span class="js-consumable-qty">' . (int) $c->prepareQty() . '</span></td>';

		$bar = '<td width="120"><div class="progress-container"><div style="width: %d%%; background: #%s;"></div></div></td>';
		printf($bar, $c->getQtyLevel(), $qtycol);

		echo '<td>';
		$models = $c->buildModels();
		$model_list = array();
		$c->printerCount = 0;
		foreach ($models as $model) {
			$model_list[] = $model->prepareName();
			$c->printerCount = (int) $c->printerCount + $model->countPrinters();
		}
		echo implode(', ', $model_list);
		echo '</td>';

		echo '<td>' . $c->printerCount . '</td>';

		echo '<td>';
		unset($actions);
		$actions[] = array('consumables.php?action=edit&id=' . $c->getId(), 'Edit', 'edit.png');
		$actions[] = array('consumables.php?action=delete&id=' . $c->getId(), 'Delete', 'delete.png');
		// Only allow installation if there is stock
		if($c->getQty() > 0){
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