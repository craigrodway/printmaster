<?php
$tpl->set('title', ($action == 'add') ? 'Add printer' : 'Edit printer: ' . $p->getName());
$tpl->place('header');
?>

<div class="grid_12">

	<form action="<?php echo fURL::get() ?>" method="post">
	<input type="hidden" name="action" value="<?php echo $action ?>" />
	<input type="hidden" name="id" value="<?php echo $p->getId() ?>" />

		<table class="form">

			<tr>
				<td class="caption"><label for="model_id">Model</label></td>
				<td class="input"><select name="model_id"><?php
					foreach($models as $m){
						$selected = ($m->id == $p->getModelId()) ? ' selected="selected"' : '';
						printf('<option value="%d"%s>%s</option>',
							$m->id,
							$selected,
							$m->mfr_name . ' ' . $m->model_name
						);
					}
				?></select></td>
			</tr>

			<tr>
				<td class="caption"><label for="name">Name</label></td>
				<td class="input">
					<input id="name" type="text" name="name" value="<?php echo $p->getName() ?>"
					maxlength="<?php echo $p->inspectName('max_length') ?>" />
				</td>
			</tr>

			<tr>
				<td class="caption"><label for="location">Location</label></td>
				<td class="input">
					<input id="location" type="text" name="location" value="<?php echo $p->getLocation() ?>"
					maxlength="<?php echo $p->inspectName('max_length') ?>" />
				</td>
			</tr>

			<tr>
				<td class="caption"><label for="department">Department</label></td>
				<td class="input">
					<input id="department" type="text" name="department" value="<?php echo $p->getDepartment() ?>"
					maxlength="<?php echo $p->inspectDepartment('max_length') ?>" />
				</td>
			</tr>

			<tr>
				<td class="caption"><label for="ipaddress">IP Address</label></td>
				<td class="input">
					<input id="ipaddress" type="text" name="ipaddress" value="<?php echo $p->getIpaddress() ?>"
					maxlength="<?php echo $p->inspectIpaddress('max_length') ?>" />
				</td>
			</tr>

			<tr>
				<td class="caption"><label for="server">Print server</label></td>
				<td class="input">
					<input id="server" type="text" name="server" value="<?php echo $p->getServer() ?>"
					maxlength="<?php echo $p->inspectServer('max_length') ?>" />
				</td>
			</tr>

			<tr>
				<td class="caption"><label for="serial">Serial number</label></td>
				<td class="input">
					<input id="serial" type="text" name="serial" value="<?php echo $p->getSerial() ?>"
					maxlength="<?php echo $p->inspectSerial('max_length') ?>" />
				</td>
			</tr>

			<?php if (feature('costs')): ?>
			<tr>
				<td class="caption middle"><label for="cost">Purchase cost</label></td>
				<td class="input">
					<span class="currency"><?php echo config_item('currency') ?></span>
					<input id="cost" type="text" name="cost" value="<?php echo $p->getCost() ?>" maxlength="10" size="10" />
				</td>
			</tr>
			<?php endif; ?>

			<tr>
				<td class="caption middle"><label for="cost">Purchase date</label></td>
				<td class="input">
					<input type="text" class="text-input datepicker" name="purchase_date" value="<?php echo $p->getPurchaseDate() ?>" maxlength="10" size="15">
				</td>
			</tr>

			<tr>
				<td class="caption"><label for="notes">Notes</label></td>
				<td class="input">
					<textarea id="notes" name="notes" rows="6" cols="40"><?php echo $p->getNotes() ?></textarea>
				</td>
			</tr>

			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" class="btn btn_pos"
					value="<?php echo ($action == 'add') ? 'Add' : 'Save'; ?>" />

					<?php if($action == 'edit'): ?>
					<a href="printers.php?action=delete&id=<?php echo $p->getId() ?>" class="btn btn_neg">Delete</a>
					<?php endif; ?>
				</td>
			</tr>

		</table>

	</form>

</div>

<script type="text/javascript">
$(document).ready(function(){
	$(".datepicker").Zebra_DatePicker({ direction: false });
});
</script>

<?php
$tpl->place('footer');
?>