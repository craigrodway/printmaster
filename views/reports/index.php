<?php
if(isset($printer)){
	$tpl->set('title', 'Consumable installation report for ' . $printer->getName());
} else {
	$tpl->set('title', 'Consumable installation reports');
}
$tpl->place('header');

$colour = '<span style="color:#%s;">&bull;</span>';
?>

<div class="grid_12">


	<div class="filter">

		<form method="get" action="<?php echo fURL::get() ?>">

			<div class="grid_3 alpha">
				<label>Date from:</label>
				<input type="text" class="text-input datepicker" name="date_from" value="<?php echo fCRUD::getSearchValue('date_from') ?>">
			</div>
			<div class="grid_3">
				<label>Date to:</label>
				<input type="text" class="text-input datepicker" name="date_to" value="<?php echo fCRUD::getSearchValue('date_to') ?>">
			</div>
			<div class="grid_3">
				<label>Printer:</label>
				<select name="printer_id">
					<option value="" <?php echo ($printer_id ? '' : 'selected="selected"') ?>>(Any)</option>
					<?php
					foreach ($printers as $printer)
					{
						$selected = ($printer_id == $printer->id ? 'selected="selected"' : '');
						echo '<option value="' . $printer->id . '" ' . $selected . '>' . $printer->name . '</option>';
					}
					?>
				</select>
			</div>
			<div class="grid_2 omega buttons">
				<label>&nbsp;</label>
				<input type="submit" name="submit" value="Filter" class="btn btn_misc">
				<a href="<?php echo fURL::get() ?>?reset" class="btn btn_neg">Cancel</a>
			</div>

			<div class="clear"></div>

		</form>

	</div>


	<table class="list" id="events">
		<thead>
		<tr class="heading">
			<th filter="false">Colour</th>
			<th><?php echo fCRUD::printSortableColumn('consumables.name', 'Consumable') ?></th>
			<th><?php echo fCRUD::printSortableColumn('printers.name', 'Printer') ?></th>
			<th><?php echo fCRUD::printSortableColumn('models.name', 'Model') ?></th>
			<th filter="false"><?php echo fCRUD::printSortableColumn('events.date', 'Date') ?></th>
			<?php if (feature('costs') || feature('chargeback')): ?>
			<th class="right"><?php echo fCRUD::printSortableColumn('events.cost', 'Cost') ?></th>
			<?php endif; ?>
		</tr>
		</thead>

		<tbody>
		<?php

		$total_cost = 0;

		foreach($events as $e){

			// Add up total cost
			if ($e->cost)
			{
				$total_cost += $e->cost;
			}

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

			if (feature('costs') || feature('chargeback'))
			{
				echo '<td class="consumable_cost_col right">';

				if (feature('chargeback'))
				{
					echo ($e->chargeback == 1 ? '<img src="web/img/money.png" width="16" height="16" alt="Chargeback" class="chargeback_img" />' : '&nbsp;');
				}

				echo ($e->cost ? '<span class="consumable_cost_value">' . config_item('currency') . $e->cost . '</span>' : '');
				echo '</td>';
			}

			echo '</tr>';
		}
		?>
		</tbody>

		<?php if (feature('costs') && $total_cost > 0): ?>
		<tfoot>
			<tr class="report-total">
				<td colspan="5" class="right"><strong>Total:</strong></td>
				<td class="right"><?php echo config_item('currency') . number_format($total_cost, 2) ?></td>
			</tr>
		</tfoot>
		<?php endif; ?>

	</table>

</div>

<script type="text/javascript">
$(document).ready(function(){
	$(".datepicker").Zebra_DatePicker({ direction: false });
});
</script>

<?php
$tpl->place('footer')
?>