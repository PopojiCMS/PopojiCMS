<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_contact.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman kontak.
 * This is a php file for handling admin process for contact page.
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

class contact extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index kontak.
	 *
	 * This function use for index contact page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'contact', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['component_name']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=contact&act=multidelete', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => 'Id', 'options' => 'style="width:30px;"'),
								array('title' => $GLOBALS['_']['contact_name'], 'options' => ''),
								array('title' => $GLOBALS['_']['contact_email'], 'options' => ''),
								array('title' => $GLOBALS['_']['contact_subject'], 'options' => ''),
								array('title' => $GLOBALS['_']['contact_action'], 'options' => 'class="no-sort" style="width:120px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-contact', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('contact');?>
		<div id="viewdata" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 id="modal-title"><?=$GLOBALS['_']['contact_dialog_title_1'];?></h4>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" aria-hidden="true"><i class="fa fa-sign-out"></i> <?=$GLOBALS['_']['action_10'];?></button>
					</div>
				</div>
			</div>
		</div>
		<div id="alertreply" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=contact&act=reply', 'autocomplete' => 'off'));?>
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 id="modal-title"><?=$GLOBALS['_']['contact_dialog_title_2'];?></h4>
						</div>
						<div class="modal-body">
							<?=$this->pohtml->inputHidden(array('name' => 'name', 'value' => '', 'options' => 'id="name"'));?>
							<?=$this->pohtml->inputHidden(array('name' => 'email', 'value' => '', 'options' => 'id="email"'));?>
							<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['contact_subject'], 'name' => 'subject', 'id' => 'subject', 'mandatory' => true, 'options' => 'required'));?>
							<div class="form-group">
								<label for="message"><?=$GLOBALS['_']['contact_message'];?> <span class="text-danger">*</span></label>
								<textarea id="message" name="message" class="form-control textarea-editor" style="width:100%; height:300px;"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> <?=$GLOBALS['_']['action_5'];?></button>
							<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> <?=$GLOBALS['_']['action_6'];?></button>
						</div>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
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
		if (!$this->auth($_SESSION['leveluser'], 'contact', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'contact';
		$primarykey = 'id_contact';
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
			array('db' => 'name', 'dt' => '2', 'field' => 'name'),
			array('db' => 'email', 'dt' => '3', 'field' => 'email'),
			array('db' => 'subject', 'dt' => '4', 'field' => 'subject'),
			array('db' => $primarykey, 'dt' => '5', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					if ($row['status'] == 'Y') {
						$readdata = "<a class='btn btn-xs btn-success readdata' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['contact_notread']}'><i class='fa fa-circle-o' id='read".$d."'></i></a>";
					} else {
						$readdata = "<a class='btn btn-xs btn-success readdata' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['contact_read']}'><i class='fa fa-circle' id='read".$d."'></i></a>";
					}
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							".$readdata."
							<a class='btn btn-xs btn-warning viewdata' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['contact_view']}'><i class='fa fa-eye'></i></a>
							<a class='btn btn-xs btn-primary alertreply' id='".$d."' data-name='".$row['name']."' data-email='".$row['email']."' data-subject='".$row['subject']."' data-toggle='tooltip' title='{$GLOBALS['_']['contact_reply']}'><i class='fa fa-reply'></i></a>
							<a class='btn btn-xs btn-danger alertdel' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>
						</div>\n
					</div>\n";
				}
			),
			array('db' => 'status', 'dt' => '', 'field' => 'status')
		);
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns));
	}

	/**
	 * Fungsi ini digunakan untuk mengganti status kontak.
	 *
	 * This function use for change contact status.
	 *
	*/
	public function readdata()
	{
		if (!$this->auth($_SESSION['leveluser'], 'contact', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$current_contact = $this->podb->from('contact')
				->where('id_contact', $this->postring->valid($_POST['id'], 'sql'))
				->limit(1)
				->fetch();
			if ($current_contact['status'] == 'Y') {
				$status = 'N';
			} else {
				$status = 'Y';
			}
			$query_contact = $this->podb->update('contact')
				->set(array('status' => $status))
				->where('id_contact', $this->postring->valid($_POST['id'], 'sql'));
			$query_contact->execute();
			echo $status;
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan kontak per id.
	 *
	 * This function use for display contact contain id.
	 *
	*/
	public function viewdata()
	{
		if (!$this->auth($_SESSION['leveluser'], 'contact', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$current_contact = $this->podb->from('contact')
				->where('id_contact', $this->postring->valid($_POST['id'], 'sql'))
				->limit(1)
				->fetch();
			echo $current_contact['message'];
		}
	}

	/**
	 * Fungsi ini digunakan untuk mengirim balasan email.
	 *
	 * This function use for send email reply.
	 *
	*/
	public function reply()
	{
		if (!$this->auth($_SESSION['leveluser'], 'contact', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($this->posetting[23]['value'] != 'SMTP') {
				$email = new PoEmail;
				$send = $email
					->to($this->postring->valid($_POST['name'], 'xss')." <".$this->postring->valid($_POST['email'], 'xss').">")
					->subject($this->postring->valid($_POST['subject'], 'xss'))
					->message($this->postring->valid($_POST['message'], 'xss'))
					->from($this->posetting[5]['value'], $this->posetting[0]['value'])
					->mail();
				if ($send) {
					$this->poflash->success($GLOBALS['_']['contact_message_1'], 'admin.php?mod=contact');
				} else {
					$this->poflash->error($GLOBALS['_']['contact_message_3'], 'admin.php?mod=contact');
				}
			} else {
				$this->pomail->isSMTP();
				$this->pomail->SMTPDebug = 0;
				$this->pomail->Debugoutput = 'html';
				$this->pomail->Host = $this->posetting[24]['value'];
				$this->pomail->Port = $this->posetting[27]['value'];
				$this->pomail->SMTPAuth = true;
				$this->pomail->Username = $this->posetting[25]['value'];;
				$this->pomail->Password = $this->posetting[26]['value'];
				$this->pomail->setFrom($this->posetting[5]['value'], $this->posetting[0]['value']);
				$this->pomail->addAddress($this->postring->valid($_POST['email'], 'xss'), $this->postring->valid($_POST['name'], 'xss'));
				$this->pomail->Subject = $this->postring->valid($_POST['subject'], 'xss');
				$this->pomail->msgHTML($this->postring->valid($_POST['message'], 'xss'));
				if ($this->pomail->send()) {
					$this->poflash->success($GLOBALS['_']['contact_message_1'], 'admin.php?mod=contact');
				} else {
					$this->poflash->error($GLOBALS['_']['contact_message_3'], 'admin.php?mod=contact');
				}
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus kontak.
	 *
	 * This function is used to display and process delete contact page.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'contact', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$query = $this->podb->deleteFrom('contact')->where('id_contact', $this->postring->valid($_POST['id'], 'sql'));
			$query->execute();
			$this->poflash->success($GLOBALS['_']['contact_message_2'], 'admin.php?mod=contact');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi kontak.
	 *
	 * This function is used to display and process multi delete contact page.
	 *
	*/
	public function multidelete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'contact', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					$query = $this->podb->deleteFrom('contact')->where('id_contact', $this->postring->valid($item['deldata'], 'sql'));
					$query->execute();
				}
				$this->poflash->success($GLOBALS['_']['contact_message_2'], 'admin.php?mod=contact');
			} else {
				$this->poflash->error($GLOBALS['_']['contact_message_4'], 'admin.php?mod=contact');
			}
		}
	}

}