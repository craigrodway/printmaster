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

foreach($consumables as $c){

	// Calculate width for bar
	//$width = ( ($c->qty) / ($max_consumables) ) * 100;
	// Get colour indicator for quantity
	#$qtycol = Consumable::getQtyStatus($c->qty);

	foreach($status as $k => $v){
		if($c->qty >= $k){
			$qtylevel = strtolower($v[0]);
			$qtycol = $v[1];
			break;
		}
	}

	echo '<tr class="consumable-' . $qtylevel . '">';

	echo '<td class="col">';
	if($c->col_c){ printf($colour, '0066B3'); }
	if($c->col_y){ printf($colour, 'FFCC00'); }
	if($c->col_m){ printf($colour, 'CC0099'); }
	if($c->col_k){ printf($colour, '000'); }
	echo '</td>';

	echo '<td><span class="name">' . $c->name . '</span></td>';

	echo '<td><span class="for">' . $c->model . '</span></td>';

	$bar = '<td width="110"><div class="progress-container"><div style="width: %d%%; background: #%s"></div></div></td>';
	printf($bar, $c->qty_percent, $qtycol);

	echo '<td align="center" width="20"><span class="qty">' . $c->qty . '</span></td>';

	// URL for updating stock
	$url = sprintf('stock.php?consumable_id=%d&redirect=%s', $c->id, fURL::get());
	echo '<td align="center" width="20" valign="middle"><a href="' . $url . '" rel="stock">';
	echo '<img src="web/img/package_green.png" width="16" height="16" alt="Enter new stock" />';
	echo '</a></td>';
	// Extra order stuff

	$ordered = "";
	if($c->order_status == '0'){
		$ordered = "<span>". $c->order_qty . " </span><a href='orders.php?action=update&id=" . $c->order_id . "'>" ."<img src='web/img/delivery.png'></a>";
	}
	echo '<td width="30">' . $ordered . '</td>';

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