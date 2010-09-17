<?php
$tpl->set('title', 'Delete model: ' . $m->getName());
$tpl->place('header');
?>

<div class="grid_12">
	
	<form action="<?php echo fURL::get() ?>" method="post">
	<input type="hidden" name="action" value="delete" />
	<input type="hidden" name="id" value="<?php echo $m->getId() ?>" />
	
	<p>Are you sure you want to delete this model?</p>
	
	<input type="submit" class="btn btn_neg" value="Delete" />
	<a class="btnlink" href="models.php">No thanks</a>
	
</div>

<?php
$tpl->place('footer');
?>