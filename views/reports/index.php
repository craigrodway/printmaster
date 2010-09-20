<?php
if(isset($printer)){
	$tpl->set('title', 'Consumable installation reports for ' . $printer->getName());
} else {
	$tpl->set('title', 'Consumable installation reports');
}
$tpl->place('header');

$colour = '<span style="color:#%s;">&bull;</span>';
?>

<div class="grid_12">

<p class="noprint">
	<label for="search"><strong>Find:</strong></label>
	<input type="text" name="search" id="search" size="30" autocomplete="off" />
</p>

<table class="list" id="events">
	<thead>
	<tr class="heading">
		<th filter="false">Colour</th>
		<th><?php echo fCRUD::printSortableColumn('consumables.name', 'Consumable') ?></th>
		<th><?php echo fCRUD::printSortableColumn('printers.name', 'Printer') ?></th>
		<th><?php echo fCRUD::printSortableColumn('models.name', 'Model') ?></th>
		<th filter="false"><?php echo fCRUD::printSortableColumn('events.date', 'Date') ?></th>
	</tr>
	</thead>
	
	<tbody>
	<?php
	foreach($events as $e){
		
		echo '<tr>';
		
		/* $img = ($p->colour == 1) ? 'colour.png' : 'mono.png';
		$txt = ($p->colour == 1) ? 'C' : 'M';
		echo '<td style="width:48px;text-align:center;">';
		echo '<span style="display:none;">' . $txt . '</span>';
		echo '<img src="web/img/' . $img . '" width="16" height="16" />';
		echo '</td>'; */
		
		echo '<td class="col" width="80">';
		if($e->col_c){ printf($colour, '0066B3'); }
		if($e->col_y){ printf($colour, 'FFCC00'); }
		if($e->col_m){ printf($colour, 'CC0099'); }
		if($e->col_k){ printf($colour, '000'); }
		echo '</td>';
		
		printf('<td><strong><a href="consumables.php?action=edit&id=%d">%s</a></strong>',
			$e->consumable_id, $e->consumable_name
		);
		
		printf('<td><strong><a href="%s">%s</a></strong></td>',
			'printers.php?action=edit&id=' . $e->printer_id,
			$e->printer_name
		);
		
		echo '<td>' . $e->model . '</td>';
		
		$date = date('D jS F Y, H:i', strtotime($e->date));
		$date = preg_replace('/(\d+)(st|nd|rd|th)/', '$1<sup>$2</sup>', $date);
		echo '<td>' . $date . '</td>';
		
		echo '</tr>';
	}
	?>
	</tbody>
	
</table>

</div>

<script type="text/javascript">
$(document).ready(function(){
	var options = {
		additionalFilterTriggers: [$('#search')],
		filterDelay: 1
	};
	$('#events').tableFilter(options);
	$('#search').focus();
});
</script>

<?php
$tpl->place('footer')
?>