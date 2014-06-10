<h2>Consumable stock</h2>

<p id="consumable-filter">
<strong>Show: </strong>
<label class="check"> <input type="checkbox" id="consumables-critical" rel="critical" checked="checked" /> Critical</label>
<label class="check"> <input type="checkbox" id="consumables-low" rel="low" checked="checked" /> Low</label>
<label class="check"> <input type="checkbox" id="consumables-ok" rel="ok" checked="checked" /> OK</label>
</p>

<table class="consumables list">

<?php
$colour = '<span style="color:#%s;">&bull;</span>';

foreach ($consumables as $c){

	foreach($status as $k => $v){
		if($c->getQty() >= $k){
			$qtylevel = strtolower($v[0]);
			$qtycol = $v[1];
			break;
		}
	}

	echo '<tr class="consumable-' . $qtylevel . '">';

	if (feature('supply_types')) {
		echo '<td style="width:20px;text-align:center;" class="col">';
		$supply_tags = $c->getTags('supply_type');
		if ($supply_tags->count() == 1) {
			$supply_tag = $supply_tags->getRecord(0);
			echo '<img style="vertical-align:middle" src="web/img/tags/' . $supply_tag->getId() . '.png" class="js-tooltip" title="' . $supply_tag->encodeTitle() . '">';
		}
		echo '</td>';
	}

	echo '<td style="text-align:left;" class="col">';
	if($c->getColC()){ printf($colour, '0066B3'); }
	if($c->getColY()){ printf($colour, 'FFCC00'); }
	if($c->getColM()){ printf($colour, 'CC0099'); }
	if($c->getColK()){ printf($colour, '000'); }
	echo '</td>';

	echo '<td><span class="name">' . $c->getName() . '</span></td>';

	echo '<td>';
	$models = $c->buildModels();
	$model_list = array();
	$c->printerCount = 0;
	foreach ($models as $model) {
		$model_list[] = $model->prepareName();
		$c->printerCount = (int) $c->printerCount + $model->countPrinters();
	}
	echo '<span class="for">' . implode(', ', $model_list) . '</span>';
	echo '</td>';

	$qtycol = $c->getQtyStatus();
	$qtyinfo = '<span style="background:#%s;padding:3px 6px;-webkit-border-radius:4px;font-weight:bold;color:#000;">%d</span>';
	$bar = '<td width="120"><div class="progress-container"><div style="width: %d%%; background: #%s;"></div></div></td>';
	printf($bar, $c->getQtyLevel(), $qtycol);

	echo '<td width="20"><span class="qty">' . (int) $c->prepareQty() . '</span></td>';

	// URL for updating stock
	$url = sprintf('stock.php?consumable_id=%d&redirect=%s', $c->id, fURL::get());
	echo '<td align="center" width="20" valign="middle"><a href="' . $url . '" rel="stock">';
	echo '<img src="web/img/package_green.png" width="16" height="16" alt="Enter new stock" />';
	echo '</a></td>';

	echo '</tr>';

}
?>

</table>

<script type="text/javascript">
$(document).ready(function(){
	// Filter tick-boxes. Hides rows.
	$('#consumable-filter input').click(function(){
		var row = $('table.consumables tr.consumable-' + $(this).attr("rel"));
		(!$(this).is(':checked')) ? row.hide() : row.show();
	});

	// Attach event to take delivery of consumables
	$('a[rel=stock]').magnificPopup({
		type: 'ajax',
		closeBtnInside: true,
		focus: "[name=qty]"
	});
});


</script>