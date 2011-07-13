<?php

/**
 * ProcessWire 2.x Admin Markup Template
 *
 * Copyright 2010 by Ryan Cramer
 *
 *
 */

$searchForm = $user->hasPermission('ProcessPageSearch') ? $modules->get('ProcessPageSearch')->renderSearchForm() : '';
$bodyClass = $input->get->modal ? 'modal' : '';
if(!isset($content)) $content = '';

$config->styles->prepend($config->urls->adminTemplates . "styles/main.css");
$config->styles->append($config->urls->adminTemplates . "styles/ui.css");
$config->scripts->append($config->urls->adminTemplates . "scripts/main.js");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />

	<title><?php echo strip_tags($page->get("browser_title|headline|title|name")); ?> &bull; ProcessWire</title>

	<script type="text/javascript">
		<?php

		$jsConfig = $config->js();
		$jsConfig['debug'] = $config->debug;
		$jsConfig['urls'] = array(
			'root' => $config->urls->root,
			'admin' => $config->urls->admin,
			'modules' => $config->urls->modules,
			'core' => $config->urls->core,
			'files' => $config->urls->files,
			'templates' => $config->urls->templates,
			'adminTemplates' => $config->urls->adminTemplates,
			);
		?>

		var config = <?php echo json_encode($jsConfig); ?>;
	</script>

	<?php foreach($config->styles->unique() as $file) echo "\n\t<link type='text/css' href='$file' rel='stylesheet' />"; ?>


	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="<?php echo $config->urls->adminTemplates; ?>styles/ie.css" />
	<![endif]-->

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo $config->urls->adminTemplates; ?>styles/ie7.css" />
	<![endif]-->

	<?php foreach($config->scripts->unique() as $file) echo "\n\t<script type='text/javascript' src='$file'></script>"; ?>

</head>
<body<?php if($bodyClass) echo " class='$bodyClass'"; ?>>


<div id="container">
	<div id="sidebar">

		<?php echo $searchForm; ?>
		<ul id='nav'>
			<?php include($config->paths->templatesAdmin . "topnav.inc"); ?>
		</ul>

		<?php if(!$user->isGuest()): ?>
		<div id='userinfo'>
			<h2><?php echo $user->name?></h2>
			<?php if($user->hasPermission('profile-edit')): ?>
			<a class='action' href='<?php echo $config->urls->admin; ?>profile/'>Profile</a> /
			<?php endif; ?>
			<a class='action' href='<?php echo $config->urls->admin; ?>login/logout/'>Logout</a>
		</div>
		<?php endif; ?>
		<?php
			$last_modified = $pages->find('limit=5, sort=-modified');
		?>
		<?php if(!$user->isGuest()): ?>
		<div id='last_modified'>
			<h2>Latest updates</h2>
			<ul>
			<?php foreach($last_modified as $p){
				if ($p->editable()) {
					echo "<li><a href='".$config->urls->admin."page/edit/?id={$p->id}'><span class='date'>". date('j.n', $p->modified) ."</span> " . $p->title . " <span>(" . $p->modifiedUser->title .  ")</span></a></li>";
				}
			} ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
	<div id="content">

		<?php if(!$user->isGuest()): ?>
		<ul id='breadcrumb' class='nav'>
			<?php
			foreach($this->fuel('breadcrumbs') as $breadcrumb) {
				$title = htmlspecialchars(strip_tags($breadcrumb->title));
				echo "\n\t\t\t<li><a href='{$breadcrumb->url}'>{$title} </a></li>";
			}
			?>

		</ul>
		<?php endif; ?>

		<h1 id='title'><?php echo strip_tags($this->fuel->processHeadline ? $this->fuel->processHeadline : $page->get("title|name")); ?></h1>


		<?php if(count($notices)) include($config->paths->adminTemplates . "notices.inc"); ?>

		<?php if(trim($page->summary)) echo "<h2>{$page->summary}</h2>"; ?>
		<?php if($page->body) echo $page->body; ?>


		<?php echo $content?>



		<?php if($config->debug && $this->user->isSuperuser()) include($config->paths->adminTemplates . "debug.inc"); ?>
		<p id="footer">
		ProcessWire <?php echo $config->version; ?> &copy; <?php echo date("Y"); ?> by Ryan Cramer
		</p>

	</div>

	<div style="clear: both;"></div>

</div>






</body>
</html>
