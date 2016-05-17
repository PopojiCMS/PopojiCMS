<?php
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:index.html');
} else {
?>
<h2>Edit Menu</h2>
<form method="post" action="route.php?mod=menumanager&act=savemenu">
	<p>
		<label for="edit-menu-title">Title</label>
		<input type="text" name="title" id="edit-menu-title" value="<?php echo $row[MENU_TITLE]; ?>">
	</p>
	<p>
		<label for="edit-menu-url">URL</label>
		<input type="text" name="url" id="edit-menu-url" value="<?php echo $row[MENU_URL]; ?>">
	</p>
	<p>
		<label for="edit-menu-class">Class</label>
		<input type="text" name="class" id="edit-menu-class" value="<?php echo $row[MENU_CLASS]; ?>">
	</p>
	<p>
		<label for="edit-menu-class">Active</label>
		<select name="active" id="edit-menu-active">
			<option value="<?php echo $row[MENU_ACTIVE]; ?>">Selected <?php echo $row[MENU_ACTIVE]; ?></option>
			<option value="Y">Y</option>
			<option value="N">N</option>
		</select>
	</p>
	<p>
		<label for="edit-menu-class">Target</label>
		<select name="target" id="edit-menu-active">
			<option value="<?php echo $row[MENU_TARGET]; ?>">Selected <?php echo $row[MENU_TARGET]; ?></option>
			<option value="none">none</option>
			<option value="_blank">_blank</option>
			<option value="_self">_self</option>
			<option value="_parent">_parent</option>
			<option value="_top">_top</option>
		</select>
	</p>
	<input type="hidden" name="menu_id" value="<?php echo $row[MENU_ID]; ?>">
</form>
<?php } ?>