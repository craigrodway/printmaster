<?php
$tpl->set('title', 'Printers');
$tpl->place('header');

$menuitems[] = array('printers.php?action=add', 'Add new printer', 'add.png');
$tpl->set('menuitems', $menuitems);
?>

<div class="grid_12">

<?php $tpl->place('menu'); ?>
<br />

<p class="noprint">
	<label for="search"><strong>Find printer:</strong></label>
	<input type="text" name="search" id="search" size="30" autocomplete="off" />
</p>

<?php #fCore::expose($printers); ?>

<table class="list" id="printers">
	<thead>
	<tr class="heading">
		<th filter-type="ddl"><?php echo fCRUD::printSortableColumn('models.colour', 'Colour') ?></th>
		<th><?php echo fCRUD::printSortableColumn('printers.name', 'Name') ?></th>
		<th><?php echo fCRUD::printSortableColumn('models.name', 'Model') ?></th>
		<th><?php echo fCRUD::printSortableColumn('printers.ipaddress', 'IP Address') ?></th>
		<th filter-type="ddl"><?php echo fCRUD::printSortableColumn('printers.server', 'Print server') ?></th>
		<th><?php echo fCRUD::printSortableColumn('printers.location', 'Location') ?></th>
		<th filter="false">Operations</th>
	</tr>
	</thead>
	
	<tbody>
	<?php
	foreach($printers as $p){
		
		echo '<tr>';
		
		$img = ($p->colour == 1) ? 'colour.png' : 'mono.png';
		$txt = ($p->colour == 1) ? 'C' : 'M';
		echo '<td style="width:48px;text-align:center;">';
		echo '<span style="display:none;">' . $txt . '</span>';
		echo '<img src="web/img/' . $img . '" width="16" height="16" />';
		echo '</td>';
		
		printf('<td><strong><a href="%s">%s</a></strong></td>',
			'printers.php?action=edit&id=' . $p->id,
			$p->name
		);
		
		echo '<td>' . $p->model . '</td>';
		
		#echo '<td><strong>' . $p->name . '</strong><br /><span>' . $p->model . '</span></td>';
		
		if(!empty($p->ipaddress)){
			printf('<td><a href="http://%1$s/" class="ext" target="_blank">%1$s</a></td>', $p->ipaddress);
		} else {
			echo '<td>&nbsp;</td>';
		}
		
		echo '<td>' . $p->server . '</td>';
		
		echo '<td>' . $p->location . '</td>';
		
		echo '<td class="operations">';
		unset($actions);
		$actions[] = array('reports.php?printer_id=' . $p->id, 'Report', 'report_magnify.png');
		// Only allow consumable installation if there is stock
		if($p->stock > 0){
			$text = sprintf('Install consumable (%d) &rarr;', $p->stock);
			#$actions[] = array('install.php?printer_id=' . $p->id, $text, 'printer_add.png');
		} else {
			$actions[] = array(NULL, '<strong style="color:#c00">No consumables!</strong>', 'error.png');
		}
		$tpl->set('menuitems', $actions);
		$tpl->place('menu');
		#echo $nostock;
		echo '</td>';
		
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
	$('#printers').tableFilter(options);
	$('#search').focus();
});
</script>

<?php
$tpl->place('footer')
?>