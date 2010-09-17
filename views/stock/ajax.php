<div style="width: 320px;">
	
	<h3 style="margin-bottom: 4px">New consumable stock for: <?php echo $c->name ?></h3>

	<table width="100%" class="list">
		<tr>
			<td>
				<?php
				$colour = '<span style="color:#%s;" class="colour">&bull;</span>';
				if($c->col_c){ printf($colour, '0066B3'); }
				if($c->col_y){ printf($colour, 'FFCC00'); }
				if($c->col_m){ printf($colour, 'CC0099'); }
				if($c->col_k){ printf($colour, '000'); }
				?>
			</td>
			<td><?php echo $c->model ?>
			</td>
		</tr>
	</table>

	<form action="stock.php" method="post">

		<input type="hidden" name="consumable_id" value="<?php echo $c->id ?>">
		<input type="hidden" name="redirect" value="<?php echo $redirect ?>">
		
		<label for="qty"><strong>How many in this delivery?</strong></label><br>
		<input type="number" value="1" name="qty" autocomplete="off" size="3" autofocus>
		
		<br><br>
		<input type="submit" class="btn btn_pos" value="Update">

	</form>

</div>