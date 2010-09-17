<?php
$menuitems = $this->get('menuitems');
$menuclass = $this->get('menuclass', 'linkbar');
?>



<ul class="<?php echo $menuclass ?>">
<?php foreach($menuitems as $item): ?>	
	
	<li>
		<?php if($item[0] == NULL): ?>
		<span style="background-image:url(web/img/<?php echo $item[2] ?>)">
			<?php echo $item[1] ?>
		</span>
		<?php else: ?>
		<a href="<?php echo $item[0] ?>" style="background-image:url(web/img/<?php echo $item[2] ?>)">
			<?php echo $item[1] ?>
		</a>
		<?php endif; ?>
	</li>
	
<?php endforeach; ?>
</ul>
