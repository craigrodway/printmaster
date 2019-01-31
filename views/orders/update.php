<?php
$tpl->set('title', 'Update');
$tpl->place('header');
?>

<div class="grid_12">
	
	<form action="<?php echo fURL::get() ?>" method="post">
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="id" value="<?php echo $o->getId() ?>" />
	<input type="hidden" name="status" value="1" />
	
	<p>Are you sure you want to complete this order?</p>
	
	<input type="submit" class="btn btn_neg" value="Complete" />
	<a class="btnlink" href="orders.php">Cancel</a>
	
</div>

<?php
$tpl->place('footer');
?>