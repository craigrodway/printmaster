<?php
$tpl->set('title', 'Delete printer manufacturer: ' . $m->getName());
$tpl->place('header');
?>

<div class="grid_12">
	
	<form action="<?php echo fURL::get() ?>" method="post">
	<input type="hidden" name="action" value="delete" />
	<input type="hidden" name="id" value="<?php echo $m->getId() ?>" />
	
	<p>Are you sure you want to delete this manufacturer?</p>
	
	<input type="submit" class="btn btn_neg" value="Delete" />
	<a class="btnlink" href="manufacturers.php">No thanks</a>
	
</div>

<?php
$tpl->place('footer');
?>