<?php
$tpl->set('title', ($action == 'add') ? 'Add consumable' : 'Edit consumable: ' . $c->getName());
$tpl->place('header');
?>

<div class="grid_12">
	
	<form action="<?php echo fURL::get() ?>" method="post">
	<input type="hidden" name="action" value="<?php echo $action ?>" />
	<input type="hidden" name="id" value="<?php echo $c->getId() ?>" />
		
		<table class="form">
		
			<tr>
				<td class="caption"><label for="name">Name</label></td>
				<td class="input">
					<input id="name" type="text" name="name" value="<?php echo $c->getName() ?>" 
					maxlength="<?php echo $c->inspectName('max_length') ?>" />
				</td>
			</tr>
			
			<tr>
				<td class="caption"><label for="qty">In stock</label></td>
				<td class="input">
					<input type="hidden" name="qty" value="<?php echo $c->getQty() ?>" />
					<input id="qty" type="text" name="qty" value="<?php echo $c->getQty() ?>" 
						maxlength="10" size="1" <?php if($action != 'add'){ echo 'disabled="disabled"'; } ?> />
				</td>
			</tr>
		
			<tr>
				<td class="caption"><label>For printer models</label></td>
				<td class="input">
				<?php
				$cmodels = $c->getModels();
#				fCore::expose($cmodels);
				?>
				<?php foreach($models as $m): ?>
					<label class="check">
						<input type="checkbox" 
							id="<?php echo $m->id ?>" 
							name="models::id[]" 
							value="<?php echo $m->id ?>"  
							<?php echo (in_array($m->id, $cmodels)) ? 'checked="checked"' : '' ?> />
						<?php echo $m->mfr_name . ' ' . $m->model_name ?></label>
				<?php endforeach; ?>
				</td>
			</tr>
			
			<tr>
				<td class="caption"><label for="colour">Colours</label></td>
				<td class="input">
					
					<input type="hidden" name="col_c" value="" />
					<input type="hidden" name="col_y" value="" />
					<input type="hidden" name="col_m" value="" />
					<input type="hidden" name="col_k" value="" />
					
					<label class="check">
						<input type="checkbox" id="col_c" name="col_c" value="1" <?php fHTML::showChecked($c->getCol_c(), 1) ?> />
						<span style="color:#0066B3; font-family:Webdings; font-size:125%;">n</span></label>
					
					<label class="check">
						<input type="checkbox" id="col_y" name="col_y" value="1" <?php fHTML::showChecked($c->getCol_y(), 1) ?> /> 
						<span style="color:#FFCC00; font-family:Webdings; font-size:125%;">n</span></label>
						
					<label class="check">
						<input type="checkbox" id="col_c" name="col_m" value="1" <?php fHTML::showChecked($c->getCol_m(), 1) ?> /> 
						<span style="color:#CC0099; font-family:Webdings; font-size:125%;">n</span></label>
					
					<label class="check">
						<input type="checkbox" id="col_c" name="col_k" value="1" <?php fHTML::showChecked($c->getCol_k(), 1) ?> /> 
						<span style="color:#000; font-family:Webdings; font-size:125%;">n</span></label>
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