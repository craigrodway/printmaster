<?php
$tpl->set('title', 'Printers');
$tpl->place('header');

$menuitems[] = array('printers.php?action=add', 'Add new printer', 'add.png');
$tpl->set('menuitems', $menuitems);
?>

<div class="grid_12">

<?php $tpl->place('menu'); ?>
<br />

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
	foreach ($printers as $p) {

		$model = $p->createModel();

		$tag_str = '';
		if (feature('tags')) {
			$tags = $p->getTags();
			if ($tags->count() > 0) {
				$tag_arr = '';
				foreach ($tags as $tag) {
					$tag_arr[] = '<li class="tag">' . $tag->prepareTitle() . '</li>';
				}
				$tag_str = '<ul class="tags-inline">';
				$tag_str .= implode('', $tag_arr);
				$tag_str .= '</ul>';
			}
		}

		echo '<tr>';

		$img = ($model->getColour() == 1) ? 'colour.png' : 'mono.png';
		$txt = ($model->getColour() == 1) ? 'C' : 'M';
		echo '<td style="width:48px;text-align:center;">';
		echo '<span style="display:none;">' . $txt . '</span>';
		echo '<img src="web/img/' . $img . '" width="16" height="16" />';
		echo '</td>';

		// Name
		$url = 'printers.php?action=edit&id=' . $p->getId();
		echo '<td>';
		echo '<strong><a href="' . $url . '">' . $p->getName() . '</a></strong>';
		echo $tag_str;
		echo '</td>';

		echo '<td>' . $model->getName() . '</td>';

		#echo '<td><strong>' . $p->name . '</strong><br /><span>' . $p->model . '</span></td>';

		if ($p->getIpaddress()) {
			printf('<td><a href="http://%1$s/" class="ext" target="_blank">%1$s</a></td>', $p->getIpaddress());
		} else {
			echo '<td>&nbsp;</td>';
		}

		echo '<td>' . $p->getServer() . '</td>';

		echo '<td>' . $p->getLocation() . '</td>';

		echo '<td class="operations">';
		unset($actions);
		$actions[] = array('reports.php?printer_id=' . $p->getId(), 'Report', 'report_magnify.png');

		// Only allow consumable installation if there is stock
		$stock = $p->getStock();
		if ($stock > 0) {
			$text = sprintf('Install consumable (%d) &rarr;', $stock);
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
	$("table#printers").addTableFilter({
		labelText: "Find printer:",
		size: 30
	});
	$('#printers-filtering').focus();
});
</script>

<?php
$tpl->place('footer')
?>