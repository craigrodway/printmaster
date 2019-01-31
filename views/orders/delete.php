<?php
$tpl->set('title', 'Delete');
$tpl->place('header');
?>

<div class="grid_12">
	
	<form action="<?php echo fURL::get() ?>" method="post">
	<input type="hidden" name="action" value="delete" />
	<input type="hidden" name="id" value="<?php echo $o->getId() ?>" />
	
	<p>Are you sure you want to delete this order?</p>
	
	<input type="submit" class="btn btn_neg" value="Delete" />
	<a class="btnlink" href="orders.php">No thanks</a>
	
</div>

<?php
$tpl->place('footer');
?>