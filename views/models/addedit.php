<?php
$tpl->set('title', ($action == 'add') ? 'Add printer model' : 'Edit printer model: ' . $m->getName());
$tpl->place('header');
?>

<div class="grid_12">
	
	<form action="<?php echo fURL::get() ?>" method="post">
	<input type="hidden" name="action" value="<?php echo $action ?>" />
	<input type="hidden" name="id" value="<?php echo $m->getId() ?>" />
		
		<table class="form">
		
			<tr>
				<td class="caption"><label for="manufacturer_id">Manufacturer</label></td>
				<td class="input">
					<select name="manufacturer_id" id="manufacturer_id">
					<?php
					$option = '<option value="%d">%s</option>';
					foreach($manufacturers as $mfr){
						fHTML::printOption($mfr->getName(), $mfr->getId(), $m->getManufacturerId());
					}
					?>
					</select>
				</td>
			</tr>
			
			<tr>
				<td class="caption"><label for="name">Name</label></td>
				<td class="input">
					<input id="name" type="text" name="name" value="<?php echo $m->getName() ?>" 
					maxlength="<?php echo $m->inspectName('max_length') ?>" />
				</td>
			</tr>
			
			<tr>
				<td class="caption"><label for="colour">Colour</label></td>
				<td class="input">
					<input type="hidden" name="colour" value="0" />
					<input type="checkbox" id="colour" name="colour" value="1" <?php fHTML::showChecked($m->getColour(), 1) ?> />
				</td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" class="btn btn_pos" 
					value="<?php echo ($action == 'add') ? 'Add' : 'Save'; ?>" />
				</td>
			</tr>
			
		</table>
		
	</form>
	
</div>

<?php
$tpl->place('footer');
?>