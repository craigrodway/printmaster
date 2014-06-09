<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $this->prepare('title') ?> - PrintMaster</title>
<base href="<?php echo fURL::getDomain() . URL_ROOT ?>" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="web/css/reset.css" />
<link rel="stylesheet" href="web/css/text.css" />
<link rel="stylesheet" href="web/css/960.css" />
<link rel="stylesheet" href="web/_datepicker/public/css/printmaster.css" />
<link rel="stylesheet" href="web/_powertip/css/jquery.powertip-dark.min.css" />
<link rel="stylesheet" href="web/_bsmselect/css/jquery.bsmselect.css" />
<link rel="stylesheet" href="web/_rscp/colorPicker.css" />
<link rel="stylesheet" href="web/css/printmaster.css" />
<link rel="stylesheet" href="web/css/print.css" media="print" />
<script src="web/js/jquery-1.10.2.min.js"></script>
<script src="web/js/jquery.fastLiveFilter.js"></script>
<script src="web/js/jquery.table-filter.js"></script>
<script src="web/js/jquery.magnific-popup.custom-0.9.9.min.js"></script>
<script src="web/_datepicker/public/javascript/zebra_datepicker.js"></script>
<script src="web/_powertip/jquery.powertip.min.js"></script>
<script src="web/_bsmselect/js/jquery.bsmselect.js"></script>
<script src="web/_rscp/jquery.colorPicker.min.js"/></script>
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<style type="text/css">.clear{ zoom: 1; display: block; }</style>
<![endif]-->
<?php
if (feature('tags'))
{
	$tags = Tag::get_by_type('custom');
	echo '<style type="text/css">';
	$str = ".tag.id-%d { background-color: #%s; color: #%s; } \n";
	foreach ($tags as $tag) {
		printf($str, $tag->getId(), $tag->getColour(), $tag->getAltColour());
	}
	echo '</style>';
}
?>
</head>
<body>

<div id="header-fullwidth">

	<div class="container_12">

		<!-- menu -->
		<div class="grid_12" id="header">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="printers.php">Printers</a></li>
				<li><a href="manufacturers.php">Manufacturers</a></li>
				<li><a href="models.php">Models</a></li>
				<li><a href="consumables.php">Consumables</a></li>
				<li><a href="reports.php?reset">Reports</a></li>
				<?php if (feature('tags')): ?>
				<li><a href="tags.php">Tags</a></li>
				<?php endif; ?>
			</ul>
		</div>

		<div class="clear"></div>

	</div>

</div>

<!-- Main body container -->
<div class="container_12">


	<!-- Page title -->
	<?php if(!$this->get('hidetitle', FALSE)): ?>
	<div class="grid_12" id="title">
		<h1><?php echo $this->prepare('title') ?></h1>
	</div>
	<?php else: ?>
	<br>
	<?php endif; ?>


	<!-- Messaging -->
	<div class="grid_12"><?php
	fMessaging::show('error', fURL::get());
	fMessaging::show('success', fURL::get());
	?></div>