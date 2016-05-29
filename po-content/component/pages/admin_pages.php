<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_pages.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman.
 * This is a php file for handling admin process for pages.
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

class Pages extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index halaman.
	 *
	 * This function use for index pages.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'pages', 'read')) {
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
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=pages&act=multidelete', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => 'Id', 'options' => 'style="width:30px;"'),
								array('title' => $GLOBALS['_']['pages_title'], 'options' => ''),
								array('title' => $GLOBALS['_']['pages_active'], 'options' => 'class="no-sort" style="width:30px;"'),
								array('title' => $GLOBALS['_']['pages_action'], 'options' => 'class="no-sort" style="width:50px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-pages', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('pages');?>
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
		if (!$this->auth($_SESSION['leveluser'], 'pages', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'pages';
		$primarykey = 'id_pages';
		$columns = array(
			array('db' => 'p.'.$primarykey, 'dt' => '0', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<input type='checkbox' id='titleCheckdel' />\n
						<input type='hidden' class='deldata' name='item[".$i."][deldata]' value='".$d."' disabled />\n
					</div>\n";
				}
			),
			array('db' => 'p.'.$primarykey, 'dt' => '1', 'field' => $primarykey),
			array('db' => 'pd.title', 'dt' => '2', 'field' => 'title',
				'formatter' => function($d, $row, $i){
					return "".$d."<br /><i><a href='".WEB_URL."pages/".$this->postring->seo_title($d)."' target='_blank'>".WEB_URL."pages/".$this->postring->seo_title($d)."</a></i>";
				}
			),
			array('db' => 'p.active', 'dt' => '3', 'field' => 'active'),
			array('db' => 'p.'.$primarykey, 'dt' => '4', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							<a href='admin.php?mod=pages&act=edit&id=".$d."' class='btn btn-xs btn-default' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_1']}'><i class='fa fa-pencil'></i></a>
							<a class='btn btn-xs btn-danger alertdel' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>
						</div>\n
					</div>\n";
				}
			)
		);
		$joinquery = "FROM pages AS p JOIN pages_description AS pd ON (pd.id_pages = p.id_pages)";
		$extrawhere = "pd.id_language = '1'";
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns, $joinquery, $extrawhere));
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman add halaman.
	 *
	 * This function is used to display and process add pages.
	 *
	*/
	public function addnew()
	{
		if (!$this->auth($_SESSION['leveluser'], 'pages', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($_POST['seotitle'] != "") {
				$seotitle = $_POST['seotitle'];
			} else {
				$seotitle = $this->postring->seo_title($this->postring->valid($_POST['pages'][1]['title'], 'xss'));
			}
			if ($_SESSION['leveluser'] == '1' OR $_SESSION['leveluser'] == '2') {
				$active = "Y";
			} else {
				$active = "N";
			}
			$pages = array(
				'seotitle' => $seotitle,
				'picture' => $_POST['picture'],
				'active' => $active
			);
			$query_pages = $this->podb->insertInto('pages')->values($pages);
			$query_pages->execute();
			$last_pages = $this->podb->from('pages')
				->orderBy('id_pages DESC')
				->limit(1)
				->fetch();
			foreach ($_POST['pages'] as $id_language => $value) {
				$pages_description = array(
					'id_pages' => $last_pages['id_pages'],
					'id_language' => $id_language,
					'title' => $this->postring->valid($value['title'], 'xss'),
					'content' => stripslashes(htmlspecialchars($value['content'],ENT_QUOTES))
				);
				$query_pages_description = $this->podb->insertInto('pages_description')->values($pages_description);
				$query_pages_description->execute();
			}
			$this->poflash->success($GLOBALS['_']['pages_message_1'], 'admin.php?mod=pages');
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['pages_addnew']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=pages&act=addnew', 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-12">
								<?php
									$notab = 1;
									$noctab = 1;
									$langs = $this->podb->from('language')->where('active', 'Y')->orderBy('id_language ASC')->fetchAll();
								?>
								<ul class="nav nav-tabs">
									<?php foreach($langs as $lang) { ?>
									<li <?php echo ($notab == '1' ? 'class="active"' : ''); ?>><a href="#tab-content-<?=$lang['id_language'];?>" data-toggle="tab"><img src="../<?=DIR_INC;?>/images/flag/<?=$lang['code'];?>.png" /> <?=$lang['title'];?></a></li>
									<?php $notab++;} ?>
								</ul>
								<div class="tab-content">
									<?php foreach($langs as $lang) { ?>
									<div class="tab-pane <?php echo ($noctab == '1' ? 'active' : ''); ?>" id="tab-content-<?=$lang['id_language'];?>">
										<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['pages_title_2'], 'name' => 'pages['.$lang['id_language'].'][title]', 'id' => 'title-'.$lang['id_language'], 'mandatory' => true, 'options' => 'required'));?>
										<div class="form-group">
											<label><?=$GLOBALS['_']['pages_content'];?> <span class="text-danger">*</span></label>
											<div class="row" style="margin-top:-30px;">
												<div class="col-md-12">
													<div class="pull-right">
														<div class="input-group">
															<span class="btn-group">
																<a class="btn btn-sm btn-default tiny-visual" data-lang="<?=$lang['id_language'];?>">Visual</a>
																<a class="btn btn-sm btn-success tiny-text" data-lang="<?=$lang['id_language'];?>">Text</a>
															</span>
														</div>
													</div>
												</div>
											</div>
											<textarea class="form-control" id="po-wysiwyg-<?=$lang['id_language'];?>" name="pages[<?=$lang['id_language'];?>][content]" style="height:500px;"></textarea>
										</div>
									</div>
									<?php $noctab++;} ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['pages_seotitle'], 'name' => 'seotitle', 'id' => 'seotitle', 'mandatory' => true, 'options' => 'required', 'help' => 'Permalink : '.WEB_URL.'pages/<span id="permalink"></span>'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['pages_picture'], 'name' => 'picture', 'id' => 'picture'), $inputgroup = true, $inputgroupopt = array('href' => '../'.DIR_INC.'/js/filemanager/dialog.php?type=1&field_id=picture', 'id' => 'browse-file', 'class' => 'btn-success', 'options' => '', 'title' => $GLOBALS['_']['action_7'].' '.$GLOBALS['_']['pages_picture']));?>
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit halaman.
	 *
	 * This function is used to display and process edit pages.
	 *
	*/
	public function edit()
	{
		if (!$this->auth($_SESSION['leveluser'], 'pages', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($_POST['seotitle'] != "") {
				$seotitle = $_POST['seotitle'];
			} else {
				$seotitle = $this->postring->seo_title($this->postring->valid($_POST['pages'][1]['title'], 'xss'));
			}
			if ($_SESSION['leveluser'] == '1' OR $_SESSION['leveluser'] == '2') {
				$active = $this->postring->valid($_POST['active'], 'xss');
			} else {
				$active = "N";
			}
			$pages = array(
				'seotitle' => $seotitle,
				'picture' => $_POST['picture'],
				'active' => $active
			);
			$query_pages = $this->podb->update('pages')
				->set($pages)
				->where('id_pages', $this->postring->valid($_POST['id'], 'sql'));
			$query_pages->execute();
			foreach ($_POST['pages'] as $id_language => $value) {
				$othlang_pages = $this->podb->from('pages_description')
					->where('id_pages', $this->postring->valid($_POST['id'], 'sql'))
					->where('id_language', $id_language)
					->count();
				if ($othlang_pages > 0) {
					$pages_description = array(
						'title' => $this->postring->valid($value['title'], 'xss'),
						'content' => stripslashes(htmlspecialchars($value['content'],ENT_QUOTES))
					);
					$query_pages_description = $this->podb->update('pages_description')
						->set($pages_description)
						->where('id_pages_description', $this->postring->valid($value['id'], 'sql'));
				} else {
					$pages_description = array(
						'id_pages' => $this->postring->valid($_POST['id'], 'sql'),
						'id_language' => $id_language,
						'title' => $this->postring->valid($value['title'], 'xss'),
						'content' => stripslashes(htmlspecialchars($value['content'],ENT_QUOTES))
					);
					$query_pages_description = $this->podb->insertInto('pages_description')->values($pages_description);
				}
				$query_pages_description->execute();
			}
			$this->poflash->success($GLOBALS['_']['pages_message_2'], 'admin.php?mod=pages');
		}
		$id = $this->postring->valid($_GET['id'], 'sql');
		$current_pages = $this->podb->from('pages')
			->select('pages_description.title')
			->leftJoin('pages_description ON pages_description.id_pages = pages.id_pages')
			->where('pages.id_pages', $id)
			->limit(1)
			->fetch();
		if (empty($current_pages)) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['pages_edit']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=pages&act=edit&id='.$current_pages['id_pages'], 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'id', 'value' => $current_pages['id_pages']));?>
						<div class="row">
							<div class="col-md-12">
								<?php
									$notab = 1;
									$noctab = 1;
									$langs = $this->podb->from('language')->where('active', 'Y')->orderBy('id_language ASC')->fetchAll();
								?>
								<ul class="nav nav-tabs">
									<?php foreach($langs as $lang) { ?>
									<li <?php echo ($notab == '1' ? 'class="active"' : ''); ?>><a href="#tab-content-<?=$lang['id_language'];?>" data-toggle="tab"><img src="../<?=DIR_INC;?>/images/flag/<?=$lang['code'];?>.png" /> <?=$lang['title'];?></a></li>
									<?php $notab++;} ?>
								</ul>
								<div class="tab-content">
									<?php foreach($langs as $lang) { ?>
									<div class="tab-pane <?php echo ($noctab == '1' ? 'active' : ''); ?>" id="tab-content-<?=$lang['id_language'];?>">
										<?php
										$paglang = $this->podb->from('pages_description')
											->where('pages_description.id_pages', $current_pages['id_pages'])
											->where('pages_description.id_language', $lang['id_language'])
											->fetch();
											$content_before = html_entity_decode($paglang['content']);
											$content_after = preg_replace_callback(
												'/(?:\<code*\>([^\<]*)\<\/code\>)/',
												create_function(
												   '$matches',
													'return \'<code>\'.stripslashes(htmlspecialchars($matches[1],ENT_QUOTES)).\'</code>\';'
												),
												$content_before
											);
										?>
										<?=$this->pohtml->inputHidden(array('name' => 'pages['.$lang['id_language'].'][id]', 'value' => $paglang['id_pages_description']));?>
										<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['pages_title_2'], 'name' => 'pages['.$lang['id_language'].'][title]', 'id' => 'title-'.$lang['id_language'], 'value' => $paglang['title'], 'mandatory' => true, 'options' => 'required'));?>
										<div class="form-group">
											<label><?=$GLOBALS['_']['pages_content'];?> <span class="text-danger">*</span></label>
											<div class="row" style="margin-top:-30px;">
												<div class="col-md-12">
													<div class="pull-right">
														<div class="input-group">
															<span class="btn-group">
																<a class="btn btn-sm btn-default tiny-visual" data-lang="<?=$lang['id_language'];?>">Visual</a>
																<a class="btn btn-sm btn-success tiny-text" data-lang="<?=$lang['id_language'];?>">Text</a>
															</span>
														</div>
													</div>
												</div>
											</div>
											<textarea class="form-control" id="po-wysiwyg-<?=$lang['id_language'];?>" name="pages[<?=$lang['id_language'];?>][content]" style="height:500px;"><?=$content_after;?></textarea>
										</div>
									</div>
									<?php $noctab++;} ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['pages_seotitle'], 'name' => 'seotitle', 'id' => 'seotitle', 'value' => $current_pages['seotitle'], 'mandatory' => true, 'options' => 'required', 'help' => 'Permalink : '.WEB_URL.'pages/<span id="permalink">'.$current_pages['seotitle'].'</span>'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" id="image-box">
									<div class="row">
										<?php if ($current_pages['picture'] == '') { ?>
											<div class="col-md-3"><label><?=$GLOBALS['_']['pages_picture_2'];?></label></div>
											<div class="col-md-9">
												<a href="data:image/gif;base64,R0lGODdhyACWAOMAAO/v76qqqubm5t3d3bu7u7KystXV1cPDw8zMzAAAAAAAAAAAAAAAAAAAAAAAAAAAACwAAAAAyACWAAAE/hDISau9OOvNu/9gKI5kaZ5oqq5s675wLM90bd94ru987//AoHBILBqPyKRyyWw6n9CodEqtWq/YrHbL7Xq/4LB4TC6bz+i0es1uu9/wuHxOr9vv+Lx+z+/7/4CBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3TAMFBQO4LAUBAQW+K8DCxCoGu73IzSUCwQECAwQBBAIVCMAFCBrRxwDQwQLKvOHV1xbUwQfYEwIHwO3BBBTawu2BA9HGwcMT1b7Vw/Dt3z563xAIrHCQnzsAAf0F6ybhwDdwgAx8OxDQgASN/sKUBWNmwQDIfwBAThRoMYDHCRYJGAhI8eRMf+4OFrgZgCKgaB4PHqg4EoBQbxgBROtlrJu4ofYm0JMQkJk/mOMkTA10Vas1CcakJrXQ1eu/sF4HWhB3NphYlNsmxOWKsWtZtASTdsVb1mhEu3UDX3RLFyVguITzolQKji/GhgXNvhU7OICgsoflJr7Qd2/isgEPGGAruTTjnSZTXw7c1rJpznobf2Y9GYBjxIsJYQbXstfRDJ1luz6t2TDvosSJSpMw4GXG3TtT+hPpEoPJ6R89B7AaUrnolgWwnUQQEKVOAy199mlonPDfr3m/GeUHFjBhAf0SUh28+P12QOIIgDbcPdwgJV+Arf0jnwTwsHOQT/Hs1BcABObjDAcTXhiCOGppKAJI6nnIwQGiKZSViB2YqB+KHtxjjXMsxijjjDTWaOONOOao44489ujjj0AGKeSQRBZp5JFIJqnkkkw26eSTUEYp5ZRUVmnllVhmqeWWXHbp5ZdghinmmGSW6UsEADs=" target="_blank"><?=$GLOBALS['_']['pages_picture_3'];?></a>
												<p><i><?=$GLOBALS['_']['pages_picture_4'];?></i></p>
											</div>
										<?php } else { ?>
											<div class="col-md-2"><label><?=$GLOBALS['_']['pages_picture_5'];?></label></div>
											<div class="col-md-10">
												<a href="../po-content/uploads/<?=$current_pages['picture'];?>" target="_blank"><?=$GLOBALS['_']['pages_picture_6'];?></a>
												<p>
													<i><?=$GLOBALS['_']['pages_picture_4'];?></i>
													<button type="button" class="btn btn-xs btn-danger pull-right del-image" id="<?=$current_pages['id_pages'];?>"><i class="fa fa-trash-o"></i></button>
												</p>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['pages_picture'], 'name' => 'picture', 'id' => 'picture', 'value' => $current_pages['picture']), $inputgroup = true, $inputgroupopt = array('href' => '../'.DIR_INC.'/js/filemanager/dialog.php?type=1&field_id=picture', 'id' => 'browse-file', 'class' => 'btn-success', 'options' => '', 'title' => $GLOBALS['_']['action_7'].' '.$GLOBALS['_']['pages_picture']));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php
									if ($current_pages['active'] == 'N') {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => '', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => 'checked', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['pages_active'], 'mandatory' => true), $radioitem, $inline = true);
									} else {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => 'checked', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => '', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['pages_active'], 'mandatory' => true), $radioitem, $inline = true);
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
		<div id="alertdelimg" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="post" action="route.php?mod=pages&act=edit&id='.<?=$current_pages['id_pages'];?>" autocomplete="off">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h3 id="modal-title"><i class="fa fa-exclamation-triangle text-danger"></i> <?=$GLOBALS['_']['dialogdel_1'];?></h3>
						</div>
						<div class="modal-body">
							<?=$GLOBALS['_']['dialogdel_2'];?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-sm btn-danger btn-del-image" id=""><i class="fa fa-trash-o"></i> <?=$GLOBALS['_']['dialogdel_3'];?></button>
							<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-sign-out"></i> <?=$GLOBALS['_']['dialogdel_4'];?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus halaman.
	 *
	 * This function is used to display and process delete pages.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'pages', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$query_desc = $this->podb->deleteFrom('pages_description')->where('id_pages', $this->postring->valid($_POST['id'], 'sql'));
			$query_desc->execute();
			$query_pag = $this->podb->deleteFrom('pages')->where('id_pages', $this->postring->valid($_POST['id'], 'sql'));
			$query_pag->execute();
			$this->poflash->success($GLOBALS['_']['pages_message_3'], 'admin.php?mod=pages');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi halaman.
	 *
	 * This function is used to display and process multi delete pages.
	 *
	*/
	public function multidelete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'pages', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					$query_desc = $this->podb->deleteFrom('pages_description')->where('id_pages', $this->postring->valid($item['deldata'], 'sql'));
					$query_desc->execute();
					$query_pag = $this->podb->deleteFrom('pages')->where('id_pages', $this->postring->valid($item['deldata'], 'sql'));
					$query_pag->execute();
				}
				$this->poflash->success($GLOBALS['_']['pages_message_3'], 'admin.php?mod=pages');
			} else {
				$this->poflash->error($GLOBALS['_']['pages_message_6'], 'admin.php?mod=pages');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus gambar terpilih.
	 *
	 * This function is used to display and process delete selected image.
	 *
	*/
	public function delimage()
	{
		if (!$this->auth($_SESSION['leveluser'], 'pages', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$pages = array(
				'picture' => ''
			);
			$query_pages = $this->podb->update('pages')
				->set($pages)
				->where('id_pages', $this->postring->valid($_POST['id'], 'sql'));
			$query_pages->execute();
		}
	}

}