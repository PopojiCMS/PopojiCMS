<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_comment.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman komentar.
 * This is a php file for handling admin process for comment page.
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

class Comment extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index komentar.
	 *
	 * This function use for index comment page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'comment', 'read')) {
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
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=comment&act=multidelete', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => 'Id', 'options' => 'style="width:30px;"'),
								array('title' => $GLOBALS['_']['comment_name'], 'options' => ''),
								array('title' => $GLOBALS['_']['comment_date'], 'options' => 'style="width:150px;"'),
								array('title' => $GLOBALS['_']['comment_publish'], 'options' => 'style="width:50px;"'),
								array('title' => $GLOBALS['_']['comment_action'], 'options' => 'class="no-sort" style="width:150px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-comment', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('comment');?>
		<div id="viewdata" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 id="modal-title"><?=$GLOBALS['_']['comment_dialog_title_1'];?></h4>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" aria-hidden="true"><i class="fa fa-sign-out"></i> <?=$GLOBALS['_']['action_10'];?></button>
					</div>
				</div>
			</div>
		</div>
		<div id="alertreply" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=comment&act=reply', 'autocomplete' => 'off'));?>
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 id="modal-title"><?=$GLOBALS['_']['comment_dialog_title_2'];?></h4>
						</div>
						<div class="modal-body">
							<?=$this->pohtml->inputHidden(array('name' => 'id_parent', 'value' => '', 'options' => 'id="id_parent"'));?>
							<?=$this->pohtml->inputHidden(array('name' => 'id_post', 'value' => '', 'options' => 'id="id_post"'));?>
							<div class="form-group" style="border-bottom:none; padding-bottom:0px;">
								<label for="comment"><?=$GLOBALS['_']['comment_name'];?> <span class="text-danger">*</span></label>
								<textarea id="comment" name="comment" class="form-control text-comment" style="width:100%; height:100px;" required></textarea>
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
		if (!$this->auth($_SESSION['leveluser'], 'comment', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'comment';
		$primarykey = 'id_comment';
		$columns = array(
			array('db' => 'c.'.$primarykey, 'dt' => '0', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<input type='checkbox' id='titleCheckdel' />\n
						<input type='hidden' class='deldata' name='item[".$i."][deldata]' value='".$d."' disabled />\n
					</div>\n";
				}
			),
			array('db' => 'c.'.$primarykey, 'dt' => '1', 'field' => $primarykey),
			array('db' => 'c.name', 'dt' => '2', 'field' => 'name',
				'formatter' => function($d, $row, $i){
					return $d."<br />\n
						<span style='font-size:12px;'>".$row['email']." - <a href='".$this->postring->addhttp($row['url'])."' target='_blank'>Website</a></span><br />\n
						Post : <a href='".WEB_URL."detailpost/".$row['seotitle']."' target='_blank'>".$row['title']."</a>\n
					\n";
				}
			),
			array('db' => 'c.date', 'dt' => '3', 'field' => 'date',
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						".date('d M Y', strtotime($d))." - ".date('h:m', strtotime($row['time']))."\n
					</div>\n";
				}
			),
			array('db' => 'c.active', 'dt' => '4', 'field' => 'active',
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<span id='publish-span".$row['id_comment']."'>".$d."</span>\n
					</div>\n";
				}
			),
			array('db' => 'c.'.$primarykey, 'dt' => '5', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					if ($row['status'] == 'Y') {
						$publishdata = "<a class='btn btn-xs btn-info publishdata' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['comment_act_notpublish']}'><i class='fa fa-star' id='publish".$d."'></i></a>";
					} else {
						$publishdata = "<a class='btn btn-xs btn-info publishdata' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['comment_act_publish']}'><i class='fa fa-star' id='publish".$d."'></i></a>";
					}
					if ($row['status'] == 'Y') {
						$readdata = "<a class='btn btn-xs btn-success readdata' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['comment_notread']}'><i class='fa fa-circle-o' id='read".$d."'></i></a>";
					} else {
						$readdata = "<a class='btn btn-xs btn-success readdata' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['comment_read']}'><i class='fa fa-circle' id='read".$d."'></i></a>";
					}
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							".$publishdata."
							".$readdata."
							<a class='btn btn-xs btn-warning viewdata' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['comment_view']}'><i class='fa fa-eye'></i></a>
							<a class='btn btn-xs btn-primary alertreply' id='".$d."' data-post='".$row['id_post']."' data-toggle='tooltip' title='{$GLOBALS['_']['comment_reply']}'><i class='fa fa-reply'></i></a>
							<a class='btn btn-xs btn-danger alertdel' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>
						</div>\n
					</div>\n";
				}
			),
			array('db' => 'c.id_post', 'dt' => '', 'field' => 'id_post'),
			array('db' => 'c.email', 'dt' => '', 'field' => 'email'),
			array('db' => 'c.url', 'dt' => '', 'field' => 'url'),
			array('db' => 'c.time', 'dt' => '', 'field' => 'time'),
			array('db' => 'c.status', 'dt' => '', 'field' => 'status'),
			array('db' => 'p.seotitle', 'dt' => '', 'field' => 'seotitle'),
			array('db' => 'pd.title', 'dt' => '', 'field' => 'title')
		);
		$joinquery = "FROM comment AS c JOIN post AS p ON (p.id_post = c.id_post) JOIN post_description AS pd ON (pd.id_post = c.id_post AND pd.id_language = '1')";
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns, $joinquery));
	}

	/**
	 * Fungsi ini digunakan untuk mengganti status publish komentar.
	 *
	 * This function use for change comment publish status.
	 *
	*/
	public function publishdata()
	{
		if (!$this->auth($_SESSION['leveluser'], 'comment', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$current_comment = $this->podb->from('comment')
				->where('id_comment', $this->postring->valid($_POST['id'], 'sql'))
				->limit(1)
				->fetch();
			if ($current_comment['active'] == 'Y') {
				$active = 'N';
			} else {
				$active = 'Y';
			}
			$query_comment = $this->podb->update('comment')
				->set(array('active' => $active))
				->where('id_comment', $this->postring->valid($_POST['id'], 'sql'));
			$query_comment->execute();
			echo $active;
		}
	}

	/**
	 * Fungsi ini digunakan untuk mengganti status komentar.
	 *
	 * This function use for change comment status.
	 *
	*/
	public function readdata()
	{
		if (!$this->auth($_SESSION['leveluser'], 'comment', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$current_comment = $this->podb->from('comment')
				->where('id_comment', $this->postring->valid($_POST['id'], 'sql'))
				->limit(1)
				->fetch();
			if ($current_comment['status'] == 'Y') {
				$status = 'N';
			} else {
				$status = 'Y';
			}
			$query_comment = $this->podb->update('comment')
				->set(array('status' => $status))
				->where('id_comment', $this->postring->valid($_POST['id'], 'sql'));
			$query_comment->execute();
			echo $status;
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan komentar per id.
	 *
	 * This function use for display comment contain id.
	 *
	*/
	public function viewdata()
	{
		if (!$this->auth($_SESSION['leveluser'], 'comment', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$current_comment = $this->podb->from('comment')
				->where('id_comment', $this->postring->valid($_POST['id'], 'sql'))
				->limit(1)
				->fetch();
			echo $current_comment['comment'];
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
		if (!$this->auth($_SESSION['leveluser'], 'comment', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$data = array(
				'id_parent' => $this->postring->valid($_POST['id_parent'], 'sql'),
				'id_post' => $this->postring->valid($_POST['id_post'], 'sql'),
				'name' => $_SESSION['namalengkap'],
				'email' => $this->posetting[5]['value'],
				'url' => WEB_URL,
				'comment' => $this->postring->valid($_POST['comment'], 'xss'),
				'date' => date('Y-m-d'),
				'time' => date('h:m:s'),
				'active' => 'Y',
				'status' => 'Y'
			);
			$query_comment = $this->podb->insertInto('comment')->values($data);
			if ($query_comment->execute()) {
				$this->poflash->success($GLOBALS['_']['comment_message_1'], 'admin.php?mod=comment');
			} else {
				$this->poflash->error($GLOBALS['_']['comment_message_3'], 'admin.php?mod=comment');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus komentar.
	 *
	 * This function is used to display and process delete comment page.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'comment', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$query = $this->podb->deleteFrom('comment')->where('id_comment', $this->postring->valid($_POST['id'], 'sql'));
			$query->execute();
			$this->poflash->success($GLOBALS['_']['comment_message_2'], 'admin.php?mod=comment');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi komentar.
	 *
	 * This function is used to display and process multi delete comment page.
	 *
	*/
	public function multidelete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'comment', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					$query = $this->podb->deleteFrom('comment')->where('id_comment', $this->postring->valid($item['deldata'], 'sql'));
					$query->execute();
				}
				$this->poflash->success($GLOBALS['_']['comment_message_2'], 'admin.php?mod=comment');
			} else {
				$this->poflash->error($GLOBALS['_']['comment_message_4'], 'admin.php?mod=comment');
			}
		}
	}

}