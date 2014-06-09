<?php
$tpl->set('title', 'Tags');
$tpl->place('header');
?>

<div class="grid_12">

	<form action="<?php echo fURL::get() ?>" method="post">

		<table class="list" id="tags">
			<thead>
			<tr class="heading">
				<th style="width: 64px">Colour</th>
				<th>Name</th>
				<th></th>
			</tr>
			</thead>

			<tbody>
				<?php
				foreach ($tags as $t) {

					echo '<tr>';

					echo '<td>';
					echo '<input type="text" name="tags[' . $t->getId() . '][colour]" value="#' . $t->prepareColour() . '" id="colour_' . $t->getId() . '" class="text-input js-colourpicker" size="10" max_length="6" autocomplete="off"  />';
					echo '</td>';

					echo '<td>';
					echo '<input type="text" name="tags[' . $t->getId() . '][title]" value="' . $t->encodeTitle() . '" id="title_' . $t->getId() . '" class="text-input js-title" size="20" max_length="128"  />';
					echo '</td>';

					echo '<td class="delete">';
					unset($actions);
					$actions[] = array(fURL::get() . '#' . $t->getId(), 'Delete', 'delete.png');
					$tpl->set('menuitems', $actions);
					$tpl->place('menu');
					echo '</td>';

					echo '</tr>';
				}
				?>

				<tr>
					<td>
						<input type="text" name="tags[-1][colour]" value="" id="colour_-1" class="text-input js-colourpicker" size="10" max_length="6" autocomplete="off"  />
					</td>
					<td>
						<input type="text" name="tags[-1][title]" value="" id="title_-1" class="text-input" size="20" max_length="128"  />
					</td>
					<td></td>
				</tr>

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

	$("table#tags").on("click", ".delete a", function(e) {
		e.preventDefault();
		//alert("delete");
		var $tr = $(this).parents("tr"),
			$input = $tr.find("input.js-title");

		$input.val("");
		$tr.remove();
	});

});
</script>

<?php
$tpl->place('footer')
?>