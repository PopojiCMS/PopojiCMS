<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_gallery.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman galeri.
 * This is a php file for handling admin process for gallery page.
 *
*/

/**
 * Fungsi ini digunakan untuk mencegah file ini diakses langsung tanpa melalui router.
 *
 * This function use for prevent this file accessed directly without going through a router.
 *
*/
if (!defined('CONF_STRUCTURE')) {
	header('location:index.html');
	exit;
}

/**
 * Fungsi ini digunakan untuk mencegah file ini diakses langsung tanpa login akses terlebih dahulu.
 *
 * This function use for prevent this file accessed directly without access login first.
 *
*/
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:index.php');
	exit;
}

class Gallery extends PoCore
{

	/**
	 * Fungsi ini digunakan untuk menginisialisasi class utama.
	 *
	 * This function use to initialize the main class.
	 *
	*/
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman index galeri.
	 *
	 * This function use for index gallery page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['component_name'], '
						<div class="btn-title pull-right">
							<a href="admin.php?mod=gallery&act=addnew" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> '.$GLOBALS['_']['addnew'].'</a>
							<a href="admin.php?mod=gallery&act=album" class="btn btn-success btn-sm"><i class="fa fa-book"></i> '.$GLOBALS['_']['gallery_album'].'</a>
						</div>
					');?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=gallery&act=multidelete', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => 'Id', 'options' => 'style="width:30px;"'),
								array('title' => $GLOBALS['_']['gallery_album'], 'options' => ''),
								array('title' => $GLOBALS['_']['gallery_title'], 'options' => ''),
								array('title' => $GLOBALS['_']['gallery_action'], 'options' => 'class="no-sort" style="width:50px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-gallery', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('gallery');?>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan data json pada tabel.
	 *
	 * This function use for display json data in table.
	 *
	*/
	public function datatable()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'gallery';
		$primarykey = 'id_gallery';
		$columns = array(
			array('db' => 'g.'.$primarykey, 'dt' => '0', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<input type='checkbox' id='titleCheckdel' />\n
						<input type='hidden' class='deldata' name='item[".$i."][deldata]' value='".$d."' disabled />\n
					</div>\n";
				}
			),
			array('db' => 'g.'.$primarykey, 'dt' => '1', 'field' => $primarykey),
			array('db' => 'a.title as alb_title', 'dt' => '2', 'field' => 'alb_title'),
			array('db' => 'g.title as gal_title', 'dt' => '3', 'field' => 'gal_title',
				'formatter' => function($d, $row, $i){
					return "<a href='../".DIR_CON."/uploads/".$row['picture']."' target='_blank'>".$d."</a>";
				}
			),
			array('db' => 'g.'.$primarykey, 'dt' => '4', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							<a href='admin.php?mod=gallery&act=edit&id=".$d."' class='btn btn-xs btn-default' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_1']}'><i class='fa fa-pencil'></i></a>
							<a class='btn btn-xs btn-danger alertdel' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>
						</div>\n
					</div>\n";
				}
			),
			array('db' => 'g.picture', 'dt' => '', 'field' => 'picture'),
		);
		$joinquery = "FROM gallery AS g JOIN album AS a ON (a.id_album = g.id_album)";
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns, $joinquery));
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman tambah galeri.
	 *
	 * This function is used to display and process add gallery page.
	 *
	*/
	public function addnew()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$gallery = array(
				'id_album' => $this->postring->valid($_POST['id_album'], 'sql'),
				'title' => $this->postring->valid($_POST['title'], 'xss'),
				'content' => stripslashes(htmlspecialchars($_POST['content'],ENT_QUOTES)),
				'picture' => $_POST['picture']
			);
			$query_gallery = $this->podb->insertInto('gallery')->values($gallery);
			$query_gallery->execute();
			$this->poflash->success($GLOBALS['_']['gallery_message_1'], 'admin.php?mod=gallery');
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['gallery_addnew']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=gallery&act=addnew', 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-5">
								<div class="input-group">
									<?php
										$albs = $this->podb->from('album')
											->where('active', 'Y')
											->orderBy('id_album DESC')
											->fetchAll();
										echo $this->pohtml->inputSelectNoOpt(array('id' => 'id_album', 'label' => $GLOBALS['_']['gallery_album'], 'name' => 'id_album', 'mandatory' => true));
										foreach($albs as $alb){
											echo '<option value="'.$alb['id_album'].'">'.$alb['title'].'</option>';
										}
										echo $this->pohtml->inputSelectNoOptEnd();
									?>
									<span class="input-group-btn" style="padding-top:25px !important;">
										<a href="admin.php?mod=gallery&act=addnewalbum" class="btn btn-success"><?=$GLOBALS['_']['addnew'];?></a>
									</span>
								</div>
							</div>
							<div class="col-md-7">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['gallery_title'], 'name' => 'title', 'id' => 'title', 'mandatory' => true, 'options' => 'required'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="message"><?=$GLOBALS['_']['gallery_content'];?></label>
									<textarea id="po-wysiwyg" name="content" class="form-control" style="width:100%; height:300px;"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['gallery_picture'], 'name' => 'picture', 'id' => 'picture', 'mandatory' => true, 'options' => 'required',), $inputgroup = true, $inputgroupopt = array('href' => '../'.DIR_INC.'/js/filemanager/dialog.php?type=1&field_id=picture', 'id' => 'browse-file', 'class' => 'btn-success', 'options' => '', 'title' => $GLOBALS['_']['action_7'].' '.$GLOBALS['_']['gallery_picture']));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->formAction();?>
							</div>
						</div>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit galeri.
	 *
	 * This function is used to display and process edit gallery.
	 *
	*/
	public function edit()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$gallery = array(
				'id_album' => $this->postring->valid($_POST['id_album'], 'sql'),
				'title' => $this->postring->valid($_POST['title'], 'xss'),
				'content' => stripslashes(htmlspecialchars($_POST['content'],ENT_QUOTES)),
				'picture' => $_POST['picture']
			);
			$query_gallery = $this->podb->update('gallery')
				->set($gallery)
				->where('id_gallery', $this->postring->valid($_POST['id'], 'sql'));
			$query_gallery->execute();
			$this->poflash->success($GLOBALS['_']['gallery_message_2'], 'admin.php?mod=gallery');
		}
		$id = $this->postring->valid($_GET['id'], 'sql');
		$current_gallery = $this->podb->from('gallery')
			->select('album.title AS album_title')
			->leftJoin('album ON album.id_album = gallery.id_album')
			->where('gallery.id_gallery', $id)
			->limit(1)
			->fetch();
		if (empty($current_gallery)) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['gallery_edit']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=gallery&act=edit', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'id', 'value' => $current_gallery['id_gallery']));?>
						<div class="row">
							<div class="col-md-5">
								<div class="input-group">
									<?php
										$albs = $this->podb->from('album')
											->where('active', 'Y')
											->orderBy('id_album DESC')
											->fetchAll();
										echo $this->pohtml->inputSelectNoOpt(array('id' => 'id_album', 'label' => $GLOBALS['_']['gallery_album'], 'name' => 'id_album', 'mandatory' => true));
										echo '<option value="'.$current_gallery['id_album'].'">'.$GLOBALS['_']['gallery_select'].' - '.$current_gallery['album_title'].'</option>';
										foreach($albs as $alb){
											echo '<option value="'.$alb['id_album'].'">'.$alb['title'].'</option>';
										}
										echo $this->pohtml->inputSelectNoOptEnd();
									?>
									<span class="input-group-btn" style="padding-top:25px !important;">
										<a href="admin.php?mod=gallery&act=addnewalbum" class="btn btn-success"><?=$GLOBALS['_']['addnew'];?></a>
									</span>
								</div>
							</div>
							<div class="col-md-7">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['gallery_title'], 'name' => 'title', 'id' => 'title', 'value' => $current_gallery['title'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="message"><?=$GLOBALS['_']['gallery_content'];?></label>
									<textarea id="po-wysiwyg" name="content" class="form-control" style="width:100%; height:300px;"><?=html_entity_decode($current_gallery['content']);?></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['gallery_picture'], 'name' => 'picture', 'id' => 'picture', 'value' => $current_gallery['picture'], 'mandatory' => true, 'options' => 'required',), $inputgroup = true, $inputgroupopt = array('href' => '../'.DIR_INC.'/js/filemanager/dialog.php?type=1&field_id=picture', 'id' => 'browse-file', 'class' => 'btn-success', 'options' => '', 'title' => $GLOBALS['_']['action_7'].' '.$GLOBALS['_']['gallery_picture']));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->formAction();?>
							</div>
						</div>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus galeri.
	 *
	 * This function is used to display and process delete gallery page.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$query = $this->podb->deleteFrom('gallery')->where('id_gallery', $this->postring->valid($_POST['id'], 'sql'));
			$query->execute();
			$this->poflash->success($GLOBALS['_']['gallery_message_3'], 'admin.php?mod=gallery');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi galeri.
	 *
	 * This function is used to display and process multi delete gallery page.
	 *
	*/
	public function multidelete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					$query = $this->podb->deleteFrom('gallery')->where('id_gallery', $this->postring->valid($item['deldata'], 'sql'));
					$query->execute();
				}
				$this->poflash->success($GLOBALS['_']['gallery_message_3'], 'admin.php?mod=gallery');
			} else {
				$this->poflash->error($GLOBALS['_']['gallery_message_6'], 'admin.php?mod=gallery');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman index album.
	 *
	 * This function use for index album page.
	 *
	*/
	public function album()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['component_name_album'], '
						<div class="btn-title pull-right">
							<a href="admin.php?mod=gallery&act=addnewalbum" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> '.$GLOBALS['_']['addnew'].'</a>
							<a href="admin.php?mod=gallery" class="btn btn-success btn-sm"><i class="fa fa-picture-o"></i> '.$GLOBALS['_']['gallery_back'].'</a>
						</div>
					');?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=gallery&act=multideletealbum', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => 'Id', 'options' => 'style="width:30px;"'),
								array('title' => $GLOBALS['_']['gallery_title'], 'options' => ''),
								array('title' => $GLOBALS['_']['gallery_active'], 'options' => 'class="no-sort" style="width:50px;"'),
								array('title' => $GLOBALS['_']['gallery_action'], 'options' => 'class="no-sort" style="width:50px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-album', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('gallery', 'deletealbum');?>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan data json pada tabel.
	 *
	 * This function use for display json data in table.
	 *
	*/
	public function datatable2()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'album';
		$primarykey = 'id_album';
		$columns = array(
			array('db' => $primarykey, 'dt' => '0', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<input type='checkbox' id='titleCheckdel' />\n
						<input type='hidden' class='deldata' name='item[".$i."][deldata]' value='".$d."' disabled />\n
					</div>\n";
				}
			),
			array('db' => $primarykey, 'dt' => '1', 'field' => $primarykey),
			array('db' => 'title', 'dt' => '2', 'field' => 'title'),
			array('db' => 'active', 'dt' => '3', 'field' => 'active',
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>".$d."</div>\n";
				}
			),
			array('db' => $primarykey, 'dt' => '4', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							<a href='admin.php?mod=gallery&act=editalbum&id=".$d."' class='btn btn-xs btn-default' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_1']}'><i class='fa fa-pencil'></i></a>
							<a class='btn btn-xs btn-danger alertdel' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>
						</div>\n
					</div>\n";
				}
			)
		);
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns));
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman tambah album.
	 *
	 * This function is used to display and process add album page.
	 *
	*/
	public function addnewalbum()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$album = array(
				'title' => $this->postring->valid($_POST['title'], 'xss'),
				'seotitle' => $this->postring->seo_title($this->postring->valid($_POST['title'], 'xss'))
			);
			$query_album = $this->podb->insertInto('album')->values($album);
			$query_album->execute();
			$this->poflash->success($GLOBALS['_']['gallery_album_message_1'], 'admin.php?mod=gallery&act=album');
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['gallery_album_addnew']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=gallery&act=addnewalbum', 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['gallery_title'], 'name' => 'title', 'id' => 'title', 'mandatory' => true, 'options' => 'required'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->formAction();?>
							</div>
						</div>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit album.
	 *
	 * This function is used to display and process edit album.
	 *
	*/
	public function editalbum()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$album = array(
				'title' => $this->postring->valid($_POST['title'], 'xss'),
				'seotitle' => $this->postring->seo_title($this->postring->valid($_POST['title'], 'xss')),
				'active' => $this->postring->valid($_POST['active'], 'xss')
			);
			$query_album = $this->podb->update('album')
				->set($album)
				->where('id_album', $this->postring->valid($_POST['id'], 'sql'));
			$query_album->execute();
			$this->poflash->success($GLOBALS['_']['gallery_album_message_2'], 'admin.php?mod=gallery&act=album');
		}
		$id = $this->postring->valid($_GET['id'], 'sql');
		$current_album = $this->podb->from('album')
			->where('id_album', $id)
			->limit(1)
			->fetch();
		if (empty($current_album)) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['gallery_album_edit']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=gallery&act=editalbum', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'id', 'value' => $current_album['id_album']));?>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['gallery_title'], 'name' => 'title', 'id' => 'title', 'value' => $current_album['title'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php
									if ($current_album['active'] == 'N') {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => '', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => 'checked', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['gallery_active'], 'mandatory' => true), $radioitem, $inline = true);
									} else {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => 'checked', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => '', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['gallery_active'], 'mandatory' => true), $radioitem, $inline = true);
									}
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->formAction();?>
							</div>
						</div>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus album.
	 *
	 * This function is used to display and process delete album page.
	 *
	*/
	public function deletealbum()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$query = $this->podb->deleteFrom('album')->where('id_album', $this->postring->valid($_POST['id'], 'sql'));
			$query->execute();
			$this->poflash->success($GLOBALS['_']['gallery_album_message_3'], 'admin.php?mod=gallery&act=album');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi album.
	 *
	 * This function is used to display and process multi delete album page.
	 *
	*/
	public function multideletealbum()
	{
		if (!$this->auth($_SESSION['leveluser'], 'gallery', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					$query = $this->podb->deleteFrom('album')->where('id_album', $this->postring->valid($item['deldata'], 'sql'));
					$query->execute();
				}
				$this->poflash->success($GLOBALS['_']['gallery_album_message_3'], 'admin.php?mod=gallery&act=album');
			} else {
				$this->poflash->error($GLOBALS['_']['gallery_album_message_6'], 'admin.php?mod=gallery&act=album');
			}
		}
	}

}