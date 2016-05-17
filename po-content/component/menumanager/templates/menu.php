<link href="../<?=DIR_INC;?>/css/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../<?=DIR_INC;?>/js/menu/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../<?=DIR_INC;?>/js/menu/iutil.js"></script>
<script type="text/javascript" src="../<?=DIR_INC;?>/js/menu/idrag.js"></script>
<script type="text/javascript" src="../<?=DIR_INC;?>/js/menu/idrop.js"></script>
<script type="text/javascript" src="../<?=DIR_INC;?>/js/menu/isortables.js"></script>
<script type="text/javascript" src="../<?=DIR_INC;?>/js/menu/inestedsortable.js"></script>
<script type="text/javascript" src="../<?=DIR_INC;?>/js/menu/menu.js"></script>
<script>
	var current_group_id = <?php echo $group_id; ?>;
</script>
<?php
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:index.html');
} else {
?>
	<div class="block-content">
		<div class="row">
			<div class="col-md-12">
				<div class="block-header">
					<h3>Menu Manager</h3>
					<ol class="list-inline list-unstyled">
						<li><a href="admin.php?mod=home">Home</a></li>
						<li>/</li>
						<li class="active">Menu Manager</li>
					</ol>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 ul-menu">
				<ul id="menu-group">
					<?php foreach ($menu_groups as $menu_group) : ?>
					<li id="group-<?php echo $menu_group['id']; ?>">
						<a href="<?php echo site_url('menu&amp;group_id=' . $menu_group['id']); ?>">
							<?php echo $menu_group['title']; ?>
						</a>
					</li>
					<?php endforeach; ?>
					<li id="add-group"><a href="#" title="Add Menu Group">+</a></li>
				</ul>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-8 ul-menu">
				<form method="post" id="form-menu" action="route.php?mod=menumanager&act=savepositionmenu">
					<div class="ns-row" id="ns-header">
						<div class="ns-actions">Actions</div>
						<div class="ns-active">Active</div>
						<div class="ns-class">Class</div>
						<div class="ns-url">URL</div>
						<div class="ns-title">Title</div>
					</div>
					<?php echo $menu_ul; ?>
					<div id="ns-footer">
						<button type="submit" class="btn btn-sm btn-primary" id="btn-save-menu">Update Menu</button>
					</div>
				</form>
				<p>&nbsp;</p>
			</div>
			<div class="col-md-4">
				<div class="box info">
					<h2>Info</h2>
					<section>
						<p>Drag the menu list to re-order, and click <b>Update Menu</b> to save the position.</p>
						<p>To add a menu, use the <b>Add Menu</b> form below.</p>
					</section>
				</div>
				<div class="box">
					<h2>Current Menu Group</h2>
					<section>
						<span id="edit-group-input"><?php echo $group_title; ?></span>
						(ID: <b><?php echo $group_id; ?></b>)
						<div style="margin-top:5px;">
							<a id="edit-group" class="btn btn-sm btn-primary" href="#">Edit Group</a>
							<?php if ($group_id > 1) : ?>
							&middot; <a id="delete-group" class="btn btn-sm btn-danger" href="#">Delete Group</a>
							<?php endif; ?>
						</div>
					</section>
				</div>
				<div class="box">
					<h2>Add Menu</h2>
					<section>
						<form class="form-bordered" id="form-add-menu" method="post" action="route.php?mod=menumanager&act=addmenu">
							<div class="form-group">
								<label for="menu-title">Title</label>
								<input class="form-control input-sm" type="text" name="title" id="menu-title" />
							</div>
							<div class="form-group">
								<label for="menu-url">URL</label>
								<input class="form-control input-sm" type="text" name="url" id="menu-url" />
							</div>
							<div class="form-group">
								<label for="menu-class">Class</label>
								<input class="form-control input-sm" type="text" name="class" id="menu-class" />
							</div>
							<div class="form-group">
								<input type="hidden" name="group_id" value="<?php echo $group_id; ?>" />
								<button id="add-menu" type="submit" class="btn btn-sm btn-primary">Add Menu</button>
							</div>
						</form>
					</section>
				</div>
			</div>
		</div>
	</div>
	<div id="loading">
		<img src="../<?=DIR_INC;?>/images/menu/ajax-loader.gif" alt="Loading">
		Processing...
	</div>
<?php } ?>