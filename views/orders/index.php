<?php
$tpl->set('title', 'Orders');
$tpl->place('header');

$menuitems[] = array('orders.php?action=add', 'Add new order', 'add.png');
#$menuitems[] = array('stock.php', 'New delivery', 'package_green.png');
$tpl->set('menuitems', $menuitems);

$colour = '<span style="color:#%s;">&bull;</span>';
?>

<div class="grid_12">

<?php $tpl->place('menu'); ?>
<br />

<table class="list" id="consumables">
	<thead>
		<tr class="heading">
			<th>Date</th>
			<th>Items</th>
			<th>Delivered</th>
			<th>Operations</th>
		</tr>
	</thead>

	<tbody>

	<?php
	foreach($orders as $o){
		echo '<tr>';
		
		// Order Date
		echo '<td class="col">' . $o->order_date . '</td>';

		// Lists items from other sql2 query
		echo '<td class="col">';
		//foreach($orders as $o) {
		echo $o->item_qty . " x " . $o->item_name . '<br>';
	//}
		echo '</td>';

		// Delivery

		echo '<td class="col">';
		if($o->status == 0){
			echo 'On Order';
		}
		if($o->status == 1){
			echo 'Delivered';
		}
		echo '</td>';
		


		// Operations

		echo '<td>';
		unset($actions);
		if($o->status == 0){
		$actions[] = array('orders.php?action=update&id=' . $o->id, 'Complete', 'delivery.png');
	}
		$actions[] = array('orders.php?action=edit&id=' . $o->id, 'Edit', 'edit.png');
		$actions[] = array('orders.php?action=delete&id=' . $o->id, 'Delete', 'delete.png');
		$tpl->set('menuitems', $actions);
		$tpl->place('menu');
		echo '</td>';
		echo '</tr>';
	}
	?>

	</tbody>

</table>

</div>

<script type="text/javascript">

function update_qty_total() {
	var total = 0;

	$(".js-consumable-qty:visible").each(function() {
		total = total + parseInt($(this).text());
	});

	$(".js-qty-total").text(" (" + total + ")");
}


	$('#consumables-filtering').focus();

	update_qty_total();
});
</script>

<?php
$tpl->place('footer')
?>