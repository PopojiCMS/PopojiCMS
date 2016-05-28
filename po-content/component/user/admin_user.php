<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_user.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman pengguna.
 * This is a php file for handling admin process for user page.
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

class User extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index pengguna.
	 *
	 * This function use for index user page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['component_name'], '<a href="admin.php?mod=user&act=userlevel" class="btn btn-success btn-sm btn-title pull-right"><i class="fa fa-user"></i> '.$GLOBALS['_']['component_name_user_level'].'</a>');?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=user&act=multidelete', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => 'Id', 'options' => 'style="width:30px;"'),
								array('title' => $GLOBALS['_']['user_name'], 'options' => ''),
								array('title' => $GLOBALS['_']['user_fullname'], 'options' => ''),
								array('title' => $GLOBALS['_']['user_level'], 'options' => ''),
								array('title' => $GLOBALS['_']['user_block'], 'options' => 'class="no-sort" style="width:30px;"'),
								array('title' => $GLOBALS['_']['user_action'], 'options' => 'class="no-sort" style="width:50px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-user', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('user');?>
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
		if (!$this->auth($_SESSION['leveluser'], 'user', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'users';
		$primarykey = 'id_user';
		$columns = array(
			array('db' => 'u.id_session', 'dt' => null, 'field' => 'id_session'),
			array('db' => 'u.'.$primarykey, 'dt' => '0', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<input type='checkbox' id='titleCheckdel' />\n
						<input type='hidden' class='deldata' name='item[".$i."][deldata]' value='".$d."' disabled />\n
					</div>\n";
				}
			),
			array('db' => 'u.'.$primarykey, 'dt' => '1', 'field' => $primarykey),
			array('db' => 'u.username', 'dt' => '2', 'field' => 'username'),
			array('db' => 'u.nama_lengkap', 'dt' => '3', 'field' => 'nama_lengkap'),
			array('db' => 'ul.title', 'dt' => '4', 'field' => 'title'),
			array('db' => 'u.block', 'dt' => '5', 'field' => 'block'),
			array('db' => 'u.'.$primarykey, 'dt' => '6', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					$id = array('1');
					if (in_array($row['id_user'], $id)) {
						$tbldel = "<a class='btn btn-xs btn-danger' data-toggle='tooltip' title='{$GLOBALS['_']['action_9']}'><i class='fa fa-times'></i></a>";
					} else {
						$tbldel = "<a class='btn btn-xs btn-danger alertdel' id='".$row['id_user']."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>";
					}
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							<a href='admin.php?mod=user&act=edit&id=".$row['id_session']."' class='btn btn-xs btn-default' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_1']}'><i class='fa fa-pencil'></i></a>
							$tbldel
						</div>\n
					</div>\n";
				}
			)
		);
		$joinquery = "FROM users AS u JOIN user_level AS ul ON (ul.id_level = u.level)";
		if ($_SESSION['leveluser'] == '1' || $_SESSION['leveluser'] == '2') {
			echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns, $joinquery));
		} else {
			$extraWhere = "u.id_user = '".$_SESSION['iduser']."'";
			echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns, $joinquery, $extraWhere));
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman add pengguna.
	 *
	 * This function is used to display and process add user page.
	 *
	*/
	public function addnew()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if ($_SESSION['leveluser'] != '1' && $_SESSION['leveluser'] != '2') {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$this->poval->validation_rules(array(
				'username' => 'required|max_len,50|min_len,3',
				'nama_lengkap' => 'required|max_len,255|min_len,3',
				'password' => 'required|max_len,50|min_len,6',
				'repeatpass' => 'required|max_len,50|min_len,6',
				'email' => 'required|valid_email',
				'no_telp' => 'required',
				'level' => 'required'
			));
			$this->poval->filter_rules(array(
				'username' => 'trim|sanitize_string',
				'nama_lengkap' => 'trim|sanitize_string',
				'password' => 'trim',
				'repeatpass' => 'trim',
				'email' => 'trim|sanitize_email',
				'no_telp' => 'trim',
				'level' => 'trim|sanitize_numbers'
			));
			$validated_data = $this->poval->run($_POST);
			if ($validated_data === false) {
				$this->poflash->error($GLOBALS['_']['user_message_4'], 'admin.php?mod=user&act=addnew');
			} else {
				if (md5($_POST['password']) != md5($_POST['repeatpass'])) {
					$this->poflash->error($GLOBALS['_']['user_message_4'], 'admin.php?mod=user&act=addnew');
				} else {
					$last_user = $this->podb->from('users')->limit(1)->orderBy('id_user DESC')->fetch();
					$data = array(
						'id_user' => $last_user['id_user']+1,
						'username' => $_POST['username'],
						'password' => md5($_POST['password']),
						'nama_lengkap' => $_POST['nama_lengkap'],
						'email' => $_POST['email'],
						'no_telp' => $_POST['no_telp'],
						'level' => $_POST['level'],
						'tgl_daftar' => date('Ymd'),
						'id_session' => md5($_POST['password'])
					);
					$query = $this->podb->insertInto('users')->values($data);
					$query->execute();
					$this->poflash->success($GLOBALS['_']['user_message_1'], 'admin.php?mod=user');
				}
			}
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['user_addnew']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=user&act=addnew', 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_name'], 'name' => 'username', 'id' => 'username', 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_fullname'], 'name' => 'nama_lengkap', 'id' => 'nama_lengkap', 'mandatory' => true, 'options' => 'required'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'password', 'label' => $GLOBALS['_']['user_password'], 'name' => 'password', 'id' => 'password', 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'password', 'label' => $GLOBALS['_']['user_retype_password'], 'name' => 'repeatpass', 'id' => 'repeatpass', 'mandatory' => true, 'options' => 'required'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_email'], 'name' => 'email', 'id' => 'email', 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-4">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_phone_number'], 'name' => 'no_telp', 'id' => 'no_telp', 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-4">
								<?php
									$item = array();
									$levels = $this->podb->from('user_level')->orderBy('id_level ASC')->fetchAll();
									foreach($levels as $level){
										$item[] = array('value' => $level['id_level'], 'title' => $level['title']);
									}
								?>
								<?=$this->pohtml->inputSelect(array('id' => 'level', 'label' => $GLOBALS['_']['user_level'], 'name' => 'level', 'mandatory' => true), $item);?>
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit pengguna.
	 *
	 * This function is used to display and process edit user page.
	 *
	*/
	public function edit()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$this->poval->validation_rules(array(
				'nama_lengkap' => 'required|max_len,255|min_len,3',
				'email' => 'required|valid_email',
				'no_telp' => 'required'
			));
			$this->poval->filter_rules(array(
				'nama_lengkap' => 'trim|sanitize_string',
				'email' => 'trim|sanitize_email',
				'no_telp' => 'trim'
			));
			$validated_data = $this->poval->run(array_merge($_POST, $_FILES));
			if ($validated_data === false) {
				$this->poflash->error($GLOBALS['_']['user_message_5'], 'admin.php?mod=user&act=edit&id='.$this->postring->valid($_POST['id'], 'xss'));
			} else {
				$data = array(
					'nama_lengkap' => $_POST['nama_lengkap'],
					'email' => $_POST['email'],
					'no_telp' => $_POST['no_telp'],
					'bio' => htmlspecialchars($_POST['bio'], ENT_QUOTES),
					'locktype' => $this->postring->valid($_POST['locktype'], 'xss')
				);
				if(!empty($_FILES['picture']['tmp_name'])){
					$file_exists = '../'.DIR_CON.'/uploads/user-'.$_POST['id_user'];
					if (file_exists($file_exists)){
						unlink('../'.DIR_CON.'/uploads/user-'.$_POST['id_user']);
					}
					$upload = new PoUpload($_FILES['picture']);
					if ($upload->uploaded) {
						$upload->file_new_name_body = 'user-'.$_POST['id_user'];
						$upload->image_convert = 'jpg';
						$upload->image_resize = true;
						$upload->image_x = 512;
						$upload->image_y = 512;
						$upload->image_ratio = true;
						$upload->process('../'.DIR_CON.'/uploads/');
						if ($upload->processed) {
							$datapic = array(
								'picture' => $upload->file_dst_name
							);
							$upload->clean();
						} else {
							$datapic = array();
						}
					}
				} else {
					$datapic = array();
				}
				if (!empty($_POST['newpassword'])) {
					$datapass = array(
						'password' => md5($_POST['newpassword'])
					);
				} else {
					$datapass = array();
				}
				if ($_SESSION['leveluser'] == '1' || $_SESSION['leveluser'] == '2'){
					$datalvl = array(
						'level' => $this->postring->valid($_POST['level'], 'xss'),
						'block' => $_POST['block']
					);
				} else {
					$datalvl = array();
				}
				$datafinal = array_merge($data, $datapic, $datapass, $datalvl);
				$query = $this->podb->update('users')
					->set($datafinal)
					->where('id_session', $this->postring->valid($_POST['id'], 'xss'));
				$query->execute();
				$current_user = $this->podb->from('users')
					->select('user_level.menu')
					->leftJoin('user_level ON user_level.id_level = users.level')
					->where('id_session', $this->postring->valid($_POST['id'], 'xss'))
					->limit(1)
					->fetch();
				$timeout = new PoTimeout;
				$timeout->rec_session($current_user);
				$timeout->timer();
				$this->poflash->success($GLOBALS['_']['user_message_2'], 'admin.php?mod=user');
			}
		}
		$id = $this->postring->valid($_GET['id'], 'xss');
		$current_user = $this->podb->from('users')
			->select('user_level.title')
			->leftJoin('user_level ON user_level.id_level = users.level')
			->where('users.id_session', $id)
			->limit(1)
			->fetch();
		if (empty($current_user)) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['user_edit']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=user&act=edit&id='.$current_user['id_session'], 'enctype' => true, 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'id', 'value' => $current_user['id_session']));?>
						<?=$this->pohtml->inputHidden(array('name' => 'id_user', 'value' => $current_user['id_user']));?>
						<?=$this->pohtml->inputHidden(array('name' => 'locktype', 'value' => $current_user['locktype'], 'options' => 'id="locktype"'));?>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_name'], 'name' => 'username', 'id' => 'username', 'value' => $current_user['username'], 'options' => 'disabled required', 'help' => '<small>'.$GLOBALS['_']['user_name_note'].'</small>'));?>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label><?=$GLOBALS['_']['user_password'];?></label>
									<?php if ($current_user['locktype'] == "0") { ?>
									<div class="box-password">
										<div class="input-group">
											<input class="form-control" type="password" id="newpassword" name="newpassword">
											<span class="input-group-btn">
												<button id="change-lock-type" class="btn btn-warning" type="button"><i class="fa fa-qrcode"></i> <?=$GLOBALS['_']['user_pattern'];?></button>
											</span>
										</div>
										<span class="help-block"><small><?=$GLOBALS['_']['user_password_note'];?></small></span>
									</div>
									<div class="box-password-lock" style="display:none;">
										<div class="input-group">
											<span class="btn-group">
												<button id="change-pattern" class="btn btn-warning" type="button"><i class="fa fa-qrcode"></i> <?=$GLOBALS['_']['user_pattern'];?></button>
												<button id="change-lock-type-2" class="btn btn-success" type="button"><i class="fa fa-font"></i> <?=$GLOBALS['_']['user_locktype'];?></button>
											</span>
										</div>
										<div id="patternHolder"></div>
										<span class="help-block"><small><?=$GLOBALS['_']['user_password_note_2'];?></small></span>
									</div>
									<?php } else { ?>
									<div class="box-password" style="display:none;">
										<div class="input-group">
											<input class="form-control" type="password" id="newpassword" name="newpassword">
											<span class="input-group-btn">
												<button id="change-lock-type" class="btn btn-success" type="button"><i class="fa fa-font"></i> <?=$GLOBALS['_']['user_locktype'];?></button>
											</span>
										</div>
										<span class="help-block"><small><?=$GLOBALS['_']['user_password_note'];?></small></span>
									</div>
									<div class="box-password-lock">
										<div class="input-group">
											<span class="btn-group">
												<button id="change-pattern" class="btn btn-warning" type="button"><i class="fa fa-qrcode"></i> <?=$GLOBALS['_']['user_pattern'];?></button>
												<button id="change-lock-type-2" class="btn btn-success" type="button"><i class="fa fa-font"></i> <?=$GLOBALS['_']['user_locktype'];?></button>
											</span>
										</div>
										<div id="patternHolder"></div>
										<span class="help-block"><small><?=$GLOBALS['_']['user_password_note_2'];?></small></span>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_fullname'], 'name' => 'nama_lengkap', 'id' => 'nama_lengkap', 'value' => $current_user['nama_lengkap'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_email'], 'name' => 'email', 'id' => 'email', 'value' => $current_user['email'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_phone_number'], 'name' => 'no_telp', 'id' => 'no_telp', 'value' => $current_user['no_telp'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-6">
								<?php
									$item = array();
									$levels = $this->podb->from('user_level')->orderBy('id_level ASC')->fetchAll();
									$item[] = array('value' => $current_user['level'], 'title' => $current_user['title'].' ('.$GLOBALS['_']['action_8'].')');
									foreach($levels as $level){
										$item[] = array('value' => $level['id_level'], 'title' => $level['title']);
									}
								?>
								<?=$this->pohtml->inputSelect(array('id' => 'level', 'label' => $GLOBALS['_']['user_level'], 'name' => 'level', 'mandatory' => true), $item);?>
							</div>
						</div>
						<?=$this->pohtml->inputTextarea(array('label' => $GLOBALS['_']['user_bio'], 'name' => 'bio', 'id' => 'bio', 'value' => html_entity_decode($current_user['bio']), 'options' => 'rows="8" cols="" required', 'help' => '<small>'.$GLOBALS['_']['user_bio_note'].'</small>'));?>
						<?=$this->pohtml->inputText(array('type' => 'file', 'label' => $GLOBALS['_']['user_picture'], 'name' => 'picture', 'id' => 'picture', 'help' => '<small>'.$GLOBALS['_']['user_picture_note'].'</small>'));?>
						<?php
							if ($current_user['block'] == 'N') {
								$radioitem = array(
									array('name' => 'block', 'id' => 'block', 'value' => 'Y', 'options' => '', 'title' => 'Y'),
									array('name' => 'block', 'id' => 'block', 'value' => 'N', 'options' => 'checked', 'title' => 'N')
								);
								echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['user_block'], 'mandatory' => true), $radioitem, $inline = false);
							} else {
								$radioitem = array(
									array('name' => 'block', 'id' => 'block', 'value' => 'Y', 'options' => 'checked', 'title' => 'Y'),
									array('name' => 'block', 'id' => 'block', 'value' => 'N', 'options' => '', 'title' => 'N')
								);
								echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['user_block'], 'mandatory' => true), $radioitem, $inline = false);
							}
						?>
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus pengguna.
	 *
	 * This function is used to display and process delete user page.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if ($_SESSION['leveluser'] != '1' && $_SESSION['leveluser'] != '2') {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$query = $this->podb->deleteFrom('users')->where('id_user', $this->postring->valid($_POST['id'], 'sql'));
			$query->execute();
			$this->poflash->success($GLOBALS['_']['user_message_3'], 'admin.php?mod=user');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi pengguna.
	 *
	 * This function is used to display and process multi delete user page.
	 *
	*/
	public function multidelete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if ($_SESSION['leveluser'] != '1' && $_SESSION['leveluser'] != '2') {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					$query = $this->podb->deleteFrom('users')->where('id_user', $this->postring->valid($item['deldata'], 'sql'));
					$query->execute();
				}
				$this->poflash->success($GLOBALS['_']['user_message_3'], 'admin.php?mod=user');
			} else {
				$this->poflash->error($GLOBALS['_']['user_message_6'], 'admin.php?mod=user');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman index level pengguna.
	 *
	 * This function use for index user level.
	 *
	*/
	public function userlevel()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		if ($_SESSION['leveluser'] != '1' && $_SESSION['leveluser'] != '2') {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['component_name_user_level'], '
						<div class="btn-title pull-right">
							<a href="admin.php?mod=user&act=addnewuserlevel" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> '.$GLOBALS['_']['addnew'].'</a>
							<a href="admin.php?mod=user" class="btn btn-success btn-sm"><i class="fa fa-user"></i> '.$GLOBALS['_']['component_name'].'</a>
						</div>
					');?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?php
						$columns = array(
							array('title' => 'Id', 'options' => 'style="width:30px;"'),
							array('title' => $GLOBALS['_']['user_level_name'], 'options' => ''),
							array('title' => $GLOBALS['_']['user_level_title'], 'options' => ''),
							array('title' => $GLOBALS['_']['user_level_menu'], 'options' => ''),
							array('title' => $GLOBALS['_']['user_action'], 'options' => 'class="no-sort" style="width:50px;"')
						);
					?>
					<?=$this->pohtml->createTable(array('id' => 'table-user-level', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = false);?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('user', 'deleteuserlevel');?>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan data json pada tabel level pengguna.
	 *
	 * This function use for display json data in table user level.
	 *
	*/
	public function datatable2()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		if ($_SESSION['leveluser'] != '1' && $_SESSION['leveluser'] != '2') {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'user_level';
		$primarykey = 'id_level';
		$columns = array(
			array('db' => 'ul.'.$primarykey, 'dt' => '0', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<input type='checkbox' id='titleCheckdel' />\n
						<input type='hidden' class='deldata' name='item[".$i."][deldata]' value='".$d."' disabled />\n
					</div>\n";
				}
			),
			array('db' => 'ul.'.$primarykey, 'dt' => '1', 'field' => $primarykey),
			array('db' => 'ul.level', 'dt' => '2', 'field' => 'level'),
			array('db' => 'ul.title as titleul', 'dt' => '3', 'field' => 'titleul'),
			array('db' => 'mg.title as titlemg', 'dt' => '4', 'field' => 'titlemg'),
			array('db' => 'ul.'.$primarykey, 'dt' => '5', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					$id = array('1','2','3','4');
					if (in_array($row['id_level'], $id)) {
						$tbldel = "<a class='btn btn-xs btn-danger' data-toggle='tooltip' title='{$GLOBALS['_']['action_9']}'><i class='fa fa-times'></i></a>";
					} else {
						$tbldel = "<a class='btn btn-xs btn-danger alertdel' id='".$row['id_level']."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>";
					}
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							<a href='admin.php?mod=user&act=edituserlevel&id=".$row['id_level']."' class='btn btn-xs btn-default' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_1']}'><i class='fa fa-pencil'></i></a>
							$tbldel
						</div>\n
					</div>\n";
				}
			)
		);
		$joinquery = "FROM user_level AS ul JOIN menu_group AS mg ON (mg.id = ul.menu)";
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns, $joinquery));
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan halaman tambah baru level pengguna.
	 *
	 * This function use for add new user level.
	 *
	*/
	public function addnewuserlevel()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if ($_SESSION['leveluser'] != '1' && $_SESSION['leveluser'] != '2') {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$this->poval->validation_rules(array(
				'level' => 'required|max_len,255|min_len,3',
				'title' => 'required|max_len,255|min_len,3'
			));
			$this->poval->filter_rules(array(
				'level' => 'trim',
				'title' => 'trim'
			));
			$validated_data = $this->poval->run(array_merge($_POST, $_FILES));
			if($validated_data === false) {
				$this->poflash->error($GLOBALS['_']['user_level_message_4'], 'admin.php?mod=user&act=addnewuserlevel');
			} else {
				$role = array();
				$items = $_POST['item'];
				foreach($items as $item){
					$role[] = array(
						'component' => (!empty($item['component']) ? $this->postring->valid($item['component'], 'xss') : '-'),
						'create' => (!empty($item['create']) ? '1' : '0'),
						'read' => (!empty($item['read']) ? '1' : '0'),
						'update' => (!empty($item['update']) ? '1' : '0'),
						'delete' => (!empty($item['delete']) ? '1' : '0')
					);
				}
				$itemroles = json_encode($role);
				$data = array(
					'level' => $this->postring->valid($_POST['level'], 'xss'),
					'title' => $this->postring->valid($_POST['title'], 'xss'),
					'role' => $itemroles,
					'menu' => $this->postring->valid($_POST['menu'], 'xss')
				);
				$query = $this->podb->insertInto('user_level')->values($data);
				$query->execute();
				$this->poflash->success($GLOBALS['_']['user_level_message_1'], 'admin.php?mod=user&act=userlevel');
			}
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['user_level_addnew']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=user&act=addnewuserlevel', 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-4">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_level_name'], 'name' => 'level', 'id' => 'level', 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-4">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_level_title'], 'name' => 'title', 'id' => 'title', 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-4">
								<?php
									$item = array();
									$menus = $this->podb->from('menu_group')->orderBy('id ASC')->fetchAll();
									foreach($menus as $menu){
										$item[] = array('value' => $menu['id'], 'title' => $menu['title']);
									}
								?>
								<?=$this->pohtml->inputSelect(array('id' => 'menu', 'label' => $GLOBALS['_']['user_level_menu'], 'name' => 'menu', 'mandatory' => true), $item);?>
							</div>
							<div class="col-md-12 table-responsive" id="table-role">
								<div class="form-group">
									<label for="role"><?=$GLOBALS['_']['user_level_role'];?> <span class="text-danger">*</span></label>
									<?=$this->pohtml->tableStart(array('id' => 'table-role', 'class' => 'table table-striped table-bordered'));?>
										<thead>
											<tr>
												<th class="text-center"><?=$GLOBALS['_']['component'];?></th>
												<th class="text-center"><?=$GLOBALS['_']['user_level_create'];?></th>
												<th class="text-center"><?=$GLOBALS['_']['user_level_read'];?></th>
												<th class="text-center"><?=$GLOBALS['_']['user_level_update'];?></th>
												<th class="text-center"><?=$GLOBALS['_']['user_level_delete'];?></th>
											</tr>
										</thead>
										<tbody>
											<?php
												$nodir = 0;
												$listdir = new PoDirectory();
												$listdirs = $listdir->listDir('../'.DIR_CON.'/component/');
												sort($listdirs);
												foreach($listdirs as $listdir) {
													if ($listdir != 'index.html') {
											?>
											<tr>
												<td class="text-center"><input type="hidden" name="item[<?=$nodir;?>][component]" value="<?=$listdir;?>" /><?=$listdir;?></td>
												<td class="text-center"><input type="checkbox" name="item[<?=$nodir;?>][create]" value="1" /></td>
												<td class="text-center"><input type="checkbox" name="item[<?=$nodir;?>][read]" value="1" /></td>
												<td class="text-center"><input type="checkbox" name="item[<?=$nodir;?>][update]" value="1" /></td>
												<td class="text-center"><input type="checkbox" name="item[<?=$nodir;?>][delete]" value="1" /></td>
											</tr>
											<?php } $nodir++; } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="5" class="text-center">
													<input type="checkbox" id="checkallrole" /> <?=$GLOBALS['_']['action_3'];?>
												</td>
											</tr>
										</tfoot>
									<?=$this->pohtml->tableEnd();?>
								</div>
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
	 * Fungsi ini digunakan untuk menampilkan halaman edit level pengguna.
	 *
	 * This function use for edit user level.
	 *
	*/
	public function edituserlevel()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if ($_SESSION['leveluser'] != '1' && $_SESSION['leveluser'] != '2') {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$this->poval->validation_rules(array(
				'level' => 'required|max_len,255|min_len,3',
				'title' => 'required|max_len,255|min_len,3'
			));
			$this->poval->filter_rules(array(
				'level' => 'trim',
				'title' => 'trim'
			));
			$validated_data = $this->poval->run(array_merge($_POST, $_FILES));
			if($validated_data === false) {
				$this->poflash->error($GLOBALS['_']['user_level_message_5'], 'admin.php?mod=user&act=addnewuserlevel');
			} else {
				$role = array();
				$items = $_POST['item'];
				foreach($items as $item){
					$role[] = array(
						'component' => (!empty($item['component']) ? $this->postring->valid($item['component'], 'xss') : '-'),
						'create' => (!empty($item['create']) ? '1' : '0'),
						'read' => (!empty($item['read']) ? '1' : '0'),
						'update' => (!empty($item['update']) ? '1' : '0'),
						'delete' => (!empty($item['delete']) ? '1' : '0')
					);
				}
				$itemroles = json_encode($role);
				$data = array(
					'level' => $this->postring->valid($_POST['level'], 'xss'),
					'title' => $this->postring->valid($_POST['title'], 'xss'),
					'role' => $itemroles,
					'menu' => $this->postring->valid($_POST['menu'], 'xss')
				);
				$query = $this->podb->update('user_level')
					->set($data)
					->where('id_level', $this->postring->valid($_POST['id'], 'xss'));
				$query->execute();
				$this->poflash->success($GLOBALS['_']['user_level_message_2'], 'admin.php?mod=user&act=userlevel');
			}
		}
		$id = $this->postring->valid($_GET['id'], 'xss');
		$current_user_level = $this->podb->from('user_level')
			->where('id_level', $id)
			->limit(1)
			->fetch();
		if (empty($current_user_level)) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['user_level_edit']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=user&act=edituserlevel', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'id', 'value' => $current_user_level['id_level']));?>
						<div class="row">
							<div class="col-md-4">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_level_name'], 'name' => 'level', 'id' => 'level', 'value' => $current_user_level['level'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-4">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['user_level_title'], 'name' => 'title', 'id' => 'title', 'value' => $current_user_level['title'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-4">
								<?php
									$item = array();
									$menus = $this->podb->from('menu_group')->orderBy('id ASC')->fetchAll();
									$menusel = $this->podb->from('menu_group')->where('id', $current_user_level['menu'])->limit(1)->fetch();
									$item[] = array('value' => $current_user_level['menu'], 'title' => $menusel['title'].' ('.$GLOBALS['_']['action_8'].')');
									foreach($menus as $menu){
										$item[] = array('value' => $menu['id'], 'title' => $menu['title']);
									}
								?>
								<?=$this->pohtml->inputSelect(array('id' => 'menu', 'label' => $GLOBALS['_']['user_level_menu'], 'name' => 'menu', 'mandatory' => true), $item);?>
							</div>
							<div class="col-md-12 table-responsive" id="table-role">
								<div class="form-group">
									<label for="role"><?=$GLOBALS['_']['user_level_role'];?> <span class="text-danger">*</span></label>
									<?=$this->pohtml->tableStart(array('id' => 'table-role', 'class' => 'table table-striped table-bordered'));?>
										<thead>
											<tr>
												<th class="text-center"><?=$GLOBALS['_']['component'];?></th>
												<th class="text-center"><?=$GLOBALS['_']['user_level_create'];?></th>
												<th class="text-center"><?=$GLOBALS['_']['user_level_read'];?></th>
												<th class="text-center"><?=$GLOBALS['_']['user_level_update'];?></th>
												<th class="text-center"><?=$GLOBALS['_']['user_level_delete'];?></th>
											</tr>
										</thead>
										<tbody>
											<?php
												$nodir = 0;
												$listdir = new PoDirectory();
												$listdirs = $listdir->listDir('../'.DIR_CON.'/component/');
												$roles = json_decode($current_user_level['role'], true);
												$results = array();
												$itemfusion = array();
												$itemfusion2 = array();
												if (empty($roles)) {
													foreach($listdirs as $listdir) {
														if ($listdir != 'index.html') {
															$results[] = array(
																'component' => $listdir,
																'create' => '',
																'read' => '',
																'update' => '',
																'delete' => '',
															);
														}
													}
												} else {
													foreach($roles as $key => $role) {
														if(!$this->postring->search_array($role['component'], $listdirs)) {
															unset($roles[$key]);
														}
														$itemfusion2 = $roles;
													}
													foreach($listdirs as $listdir) {
														if ($listdir != 'index.html') {
															if(!$this->postring->search_array($listdir, $itemfusion2)) {
																$itemfusion[] = array(
																	'component' => $listdir,
																	'create' => '',
																	'read' => '',
																	'update' => '',
																	'delete' => '',
																);
															}
														}
													}
													$results = array_merge($itemfusion2, $itemfusion);
												}
												foreach($results as $result) {
											?>
											<tr>
												<td class="text-center"><input type="hidden" name="item[<?=$nodir;?>][component]" value="<?=$result['component'];?>" /><?=$result['component'];?></td>
												<td class="text-center"><input type="checkbox" name="item[<?=$nodir;?>][create]" value="1" <?=(($result['create'] == '1') ? 'checked' : '');?> /></td>
												<td class="text-center"><input type="checkbox" name="item[<?=$nodir;?>][read]" value="1" <?=(($result['read'] == '1') ? 'checked' : '');?> /></td>
												<td class="text-center"><input type="checkbox" name="item[<?=$nodir;?>][update]" value="1" <?=(($result['update'] == '1') ? 'checked' : '');?> /></td>
												<td class="text-center"><input type="checkbox" name="item[<?=$nodir;?>][delete]" value="1" <?=(($result['delete'] == '1') ? 'checked' : '');?> /></td>
											</tr>
											<?php $nodir++; } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="5" class="text-center">
													<input type="checkbox" id="checkallrole" /> <?=$GLOBALS['_']['action_3'];?>
												</td>
											</tr>
										</tfoot>
									<?=$this->pohtml->tableEnd();?>
								</div>
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus level pengguna.
	 *
	 * This function is used to display and process delete user level page.
	 *
	*/
	public function deleteuserlevel()
	{
		if (!$this->auth($_SESSION['leveluser'], 'user', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if ($_SESSION['leveluser'] != '1' && $_SESSION['leveluser'] != '2') {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$id = array('1','2','3','4');
			if (in_array($this->postring->valid($_POST['id'], 'sql'), $id)) {
				$this->poflash->error($GLOBALS['_']['user_level_message_6'], 'admin.php?mod=user&act=userlevel');
			} else {
				$query = $this->podb->deleteFrom('user_level')->where('id_level', $this->postring->valid($_POST['id'], 'sql'));
				$query->execute();
				$this->poflash->success($GLOBALS['_']['user_level_message_3'], 'admin.php?mod=user&act=userlevel');
			}
		}
	}

}