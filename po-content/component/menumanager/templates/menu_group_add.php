<?php
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:index.html');
} else {
?>
<h2>Add Menu Group</h2>
<form method="post" action="route.php?mod=menumanager&act=addmenugroup">
	<p>
		<label for="menu-group-title">Group Title</label>
		<input type="text" name="title" id="menu-group-title">
	</p>
</form>
<?php } ?>