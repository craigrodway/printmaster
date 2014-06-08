<?php
$tpl->set('title', 'Tags');
$tpl->place('header');
?>

<div class="grid_12">

	<form action="<?php echo fURL::get() ?>" method="post">

		<table class="list" id="tags">
			<thead>
			<tr class="heading">
				<th>Name</th>
				<th>Colour</th>
			</tr>
			</thead>

			<tbody>
			<?php
			foreach ($tags as $t) {

				echo '<tr>';

				echo '<td>';
				echo '<strong>' . $t->prepareTitle() . '</strong>';
				echo '</td>';

				echo '<td>';
				echo '<input type="text" name="colour[' . $t->getId() . ']" value="#' . $t->prepareColour() . '" id="colour" class="text-input js-colourpicker" size="10" max_length="6" tabindex="3" autocomplete="off"  />';
				echo '</td>';

				echo '</tr>';
			}
			?>
			</tbody>

		</table>

		<button type="submit" name="next" class="btn btn_pos" value="index">Save</button>

	</form>

</div>

<script type="text/javascript">
$(document).ready(function(){
	$.fn.colorPicker.defaults.colors = [
		"FCE94F", "EDD400", "C4A000", "FCAF3E", "F57900", "CE5C00",
		"E9B96E", "C17D11", "8F5902", "8AE234", "73D216", "4E9A06",
		"729FCF", "3465A4", "204A87", "AD7FA8", "75507B", "5C3566",
		"EF2929", "CC0000", "A40000", "EEEEEC", "BABDB6", "2E3436",
		"C7D2E1", "0096D6", "F53527"
	];

	$('.js-colourpicker').colorPicker();

});
</script>

<?php
$tpl->place('footer')
?>