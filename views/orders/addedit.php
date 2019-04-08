<?php
$tpl->set('title', ($action == 'add') ? 'Add order' : 'Edit order');
$tpl->place('header');
?>

<div class="grid_12">

	<form action="<?php echo fURL::get() ?>" method="post">
	<input type="hidden" name="action" value="<?php echo $action ?>" />
	<input type="hidden" name="id" value="<?php echo $c->getId() ?>" />

		<table class="form">

			<tr>
				<td class="caption middle"><label for="name">Date</label></td>
				<td class="input">
					<input id="ID" type="hidden" name="ID" size=6 readonly value="<?php echo $c->getId() ?>"/>
					 <input type="text" class="text-input datepicker" size="30" name="order_date" value="<?php echo date('D jS F Y, H:i') ?>">
				</td>
			</tr>
			<tr>
				<td class="caption middle"><label for="name">Status</label></td>
				<td>
					<select name="status">
					 	<option value="0">On Order</option>
					 	<option value="1">Delivered</option>
					 </select>
				</td>
			</tr>
			<tr>
				<td class="caption"><label>Consumables</label></td>
				<?php
				# $cconsumables = $c->getConsumables();
#				fCore::expose($cmodels);
				?>
				<td class="input"><select name="item_id"><?php
					foreach($orderitem as $m){
						$selected = ($m->consumable_id == $c->getItem_Id()) ? ' selected="selected"' : '';
						printf('<option value="%d"%s>%s</option>',
							$m->consumable_id,
							$selected,
							$m->consumable_name . '  (Stock: ' . $m->current_qty .')'
						);
					}
				?></select></td>
			</tr>
			<tr>
				<td class="caption"><label>Quantity</label></td>
				<td>
				<input type="text" size="4" name="item_qty" value="<?php echo $c->getItem_qty() ?>">
			</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<button type="submit" name="next" class="btn btn_pos" value="index"><?php echo ($action == 'add') ? 'Add' : 'Save'; ?></button>

					<?php if ($action === 'add'): ?>
					<button type="submit" name="next" class="btn btn_misc" value="add">Add new + another</button>
					<?php endif; ?>
				</td>
			</tr>

		</table>
	</form>
	<!-- <?php 
		include 'views/orders/item.php';
		include 'views/orders/item.php';
	?> -->
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("input[name='name']").focus();
});
</script>

<?php
$tpl->place('footer');
?>
