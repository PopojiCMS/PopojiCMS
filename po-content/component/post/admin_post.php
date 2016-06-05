<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_post.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada post.
 * This is a php file for handling admin process for post.
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

class Post extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index post.
	 *
	 * This function use for index post.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['component_name'], '
						<div class="btn-title pull-right">
							<a href="admin.php?mod=post&act=addnew" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> '.$GLOBALS['_']['addnew'].'</a>
							<a href="admin.php?mod=post&act=import" class="btn btn-info btn-sm"><i class="fa fa-download"></i> '.$GLOBALS['_']['post_import'].'</a>
						</div>
					');?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=post&act=multidelete', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => 'Id', 'options' => 'style="width:30px;"'),
								array('title' => $GLOBALS['_']['post_category'], 'options' => 'style="width:150px;"'),
								array('title' => $GLOBALS['_']['post_title'], 'options' => ''),
								array('title' => $GLOBALS['_']['post_active'], 'options' => 'class="no-sort" style="width:30px;"'),
								array('title' => $GLOBALS['_']['post_action'], 'options' => 'class="no-sort" style="width:80px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-post', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('post');?>
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
		if (!$this->auth($_SESSION['leveluser'], 'post', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'post';
		$primarykey = 'id_post';
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
			array('db' => 'p.'.$primarykey, 'dt' => '2', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					$post_cats = $this->podb->from('post_category')
						->where('id_post', $d)
						->fetchAll();
					$cats = '';
					foreach($post_cats as $post_cat) {
						$cat_desc = $this->podb->from('category_description')
							->where('id_category', $post_cat['id_category'])
							->fetch();
						$cats .= $cat_desc['title']." - ";
					}
					return rtrim($cats, " - ");
				}
			),
			array('db' => 'pd.title', 'dt' => '3', 'field' => 'title',
				'formatter' => function($d, $row, $i){
					if ($row['active'] == 'Y') {
						$sactive = "<i class='fa fa-eye'></i> {$GLOBALS['_']['post_active']}";
					} else {
						$sactive = "<i class='fa fa-eye-slash'></i> {$GLOBALS['_']['post_not_active']}";
					}
					if ($row['headline'] == 'Y') {
						$headline = "<i class='fa fa-star text-warning'></i> {$GLOBALS['_']['post_headline']}";
					} else {
						$headline = "<i class='fa fa-star'></i> {$GLOBALS['_']['post_not_headline']}";
					}
					return "".$d."<br /><i><a href='".WEB_URL."detailpost/".$row['seotitle']."' target='_blank'>".WEB_URL."detailpost/".$row['seotitle']."</a></i><br /><br />
					<div class='btn-group btn-group-xs'>
                        <a class='btn btn-xs btn-default' style='font-size:11px;'><i class='fa fa-user'></i> {$GLOBALS['_']['post_by']} ".$row['nama_lengkap']."</a>
						<a class='btn btn-xs btn-default tbl-subscribe' id='".$row['id_post']."' style='font-size:11px;'><i class='fa fa-rss'></i> {$GLOBALS['_']['post_subscribe']}</a>
						<a class='btn btn-xs btn-default' href='route.php?mod=post&act=facebook&id=".$row['id_post']."' style='font-size:11px;'><i class='fa fa-facebook'></i> {$GLOBALS['_']['post_share']}</a>
						<a class='btn btn-xs btn-default' href='route.php?mod=post&act=twitter&id=".$row['id_post']."' style='font-size:11px;'><i class='fa fa-twitter'></i> {$GLOBALS['_']['post_share']}</a>
                        <a class='btn btn-xs btn-default' style='font-size:11px;'>".$sactive."</a>
						<a class='btn btn-xs btn-default' id='seth".$row['id_post']."' data-headline='".$row['headline']."' style='font-size:11px;'>".$headline."</a>
					</div>";
				}
			),
			array('db' => 'p.active', 'dt' => '4', 'field' => 'active'),
			array('db' => 'p.'.$primarykey, 'dt' => '5', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							<a class='btn btn-xs btn-warning setheadline' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['post_headline']}'><i class='fa fa-star'></i></a>
							<a href='admin.php?mod=post&act=edit&id=".$d."' class='btn btn-xs btn-default' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_1']}'><i class='fa fa-pencil'></i></a>
							<a class='btn btn-xs btn-danger alertdel' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>
						</div>\n
					</div>\n";
				}
			),
			array('db' => 'p.seotitle', 'dt' => '', 'field' => 'seotitle'),
			array('db' => 'p.headline', 'dt' => '', 'field' => 'headline'),
			array('db' => 'u.nama_lengkap', 'dt' => '', 'field' => 'nama_lengkap')
		);
		$joinquery = "FROM post AS p JOIN post_description AS pd ON (pd.id_post = p.id_post) JOIN users AS u ON (u.id_user = p.editor)";
		if ($_SESSION['leveluser'] == '1' || $_SESSION['leveluser'] == '2') {
			$extrawhere = "pd.id_language = '1'";
		} else {
			$extrawhere = "pd.id_language = '1' AND p.editor = '".$_SESSION['iduser']."'";
		}
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns, $joinquery, $extrawhere));
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman add post.
	 *
	 * This function is used to display and process add post.
	 *
	*/
	public function addnew()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($_POST['seotitle'] != "") {
				$seotitle = $_POST['seotitle'];
			} else {
				$seotitle = $this->postring->seo_title($this->postring->valid($_POST['post'][1]['title'], 'xss'));
			}
			if ($_SESSION['leveluser'] == '1' OR $_SESSION['leveluser'] == '2') {
				$active = "Y";
			} else {
				$active = "N";
			}
			$post = array(
				'seotitle' => $seotitle,
				'tag' => $this->postring->valid($_POST['tag'], 'xss'),
				'picture' => $_POST['picture'],
				'picture_description' => $_POST['picture_description'],
				'date' => $_POST['publishdate'],
				'time' => $_POST['publishtime'],
				'publishdate' => $_POST['publishdate']." ".$_POST['publishtime'],
				'editor' => $_SESSION['iduser'],
				'comment' => $this->postring->valid($_POST['comment'], 'xss'),
				'active' => $active
			);
			$query_post = $this->podb->insertInto('post')->values($post);
			$query_post->execute();
			$expl = explode(",", $this->postring->valid($_POST['tag'], 'xss'));
			$total = count($expl);
			for($i=0; $i<$total; $i++){
				$last_tag = $this->podb->from('tag')
					->where('tag_seo', $expl[$i])
					->limit(1)
					->fetch();
				$query_tag = $this->podb->update('tag')
					->set(array('count' => $last_tag['count']+1))
					->where('tag_seo', $expl[$i]);
				$query_tag->execute();
			}
			$last_post = $this->podb->from('post')
				->orderBy('id_post DESC')
				->limit(1)
				->fetch();
			$id_categorys = $_POST['id_category'];
			if (!empty($_POST['id_category'])) {
				foreach($id_categorys as $id_category){
					$category = array(
						'id_post' => $last_post['id_post'],
						'id_category' => $id_category,
					);
					$query_category = $this->podb->insertInto('post_category')->values($category);
					$query_category->execute();
				}
			}
			foreach ($_POST['post'] as $id_language => $value) {
				$post_description = array(
					'id_post' => $last_post['id_post'],
					'id_language' => $id_language,
					'title' => $this->postring->valid($value['title'], 'xss'),
					'content' => stripslashes(htmlspecialchars($value['content'],ENT_QUOTES))
				);
				$query_post_description = $this->podb->insertInto('post_description')->values($post_description);
				$query_post_description->execute();
			}
			$this->poflash->success($GLOBALS['_']['post_message_1'], 'admin.php?mod=post');
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['post_addnew']);?>
				</div>
			</div>
			<div class="row">
				<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=post&act=addnew', 'autocomplete' => 'off'));?>
					<div class="col-md-8" id="left-post">
						<div class="row">
							<div class="col-md-12">
								<div class="pull-right">
									<a href="javascript:void(0)" class="btn btn-xs btn-default" id="hide-right">&nbsp;<i class="fa fa-angle-right"></i>&nbsp;</a>
								</div>
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
										<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_title_2'], 'name' => 'post['.$lang['id_language'].'][title]', 'id' => 'title-'.$lang['id_language'], 'mandatory' => true, 'options' => 'required'));?>
										<div class="form-group">
											<label><?=$GLOBALS['_']['post_content'];?> <span class="text-danger">*</span></label>
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
											<textarea class="form-control" id="po-wysiwyg-<?=$lang['id_language'];?>" name="post[<?=$lang['id_language'];?>][content]" style="height:600px;"></textarea>
										</div>
									</div>
									<?php $noctab++;} ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4" id="right-post">
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_seotitle'], 'name' => 'seotitle', 'id' => 'seotitle', 'mandatory' => true, 'options' => 'required', 'help' => 'Permalink : '.WEB_URL.'detailpost/<span id="permalink"></span>'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="id_category"><?=$GLOBALS['_']['post_category'];?> <span class="text-danger">*</span></label>
									<div class="box-category">
										<?=$this->generate_checkbox(0, 'add');?>
									</div>
									<div class="category-option">
										<div class="pull-left"><a href="admin.php?mod=category&act=addnew" target="_blank"><i class="fa fa-plus"></i> <?=$GLOBALS['_']['post_add_category'];?></a></div>
										<div class="pull-right"><a href="javascript:void(0)" id="category-refresh" data-id="0"><i class="fa fa-refresh"></i> <?=$GLOBALS['_']['post_refresh_category'];?></a></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_tag'], 'name' => 'tag', 'id' => 'tag', 'mandatory' => false, 'options' => ''));?>
								<div class="tag-option">
									<div class="text-left"><a href="admin.php?mod=tag&act=addnew" target="_blank"><i class="fa fa-plus"></i> <?=$GLOBALS['_']['post_add_tag'];?></a></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_picture'], 'name' => 'picture', 'id' => 'picture'), $inputgroup = true, $inputgroupopt = array('href' => '../'.DIR_INC.'/js/filemanager/dialog.php?type=1&field_id=picture', 'id' => 'browse-file', 'class' => 'btn-success', 'options' => '', 'title' => $GLOBALS['_']['action_7'].' '.$GLOBALS['_']['post_picture']));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputTextarea(array('label' => $GLOBALS['_']['post_picture_description'], 'name' => 'picture_description', 'id' => 'picture_description', 'class' => 'mceNoEditor', 'options' => 'rows="3" cols=""'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_date'], 'name' => 'publishdate', 'id' => 'publishdate', 'value' => date('Y-m-d'), 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_time'], 'name' => 'publishtime', 'id' => 'publishtime', 'value' => date('h:i:s'), 'mandatory' => true, 'options' => 'required'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php
									$radioitem = array(
										array('name' => 'comment', 'id' => 'comment', 'value' => 'Y', 'options' => 'checked', 'title' => 'Y'),
										array('name' => 'comment', 'id' => 'comment', 'value' => 'N', 'options' => '', 'title' => 'N')
									);
									echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['post_comment'], 'mandatory' => true), $radioitem, $inline = true);
								?>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->formAction();?>
							</div>
						</div>
					</div>
				<?=$this->pohtml->formEnd();?>
			</div>
		</div>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit post.
	 *
	 * This function is used to display and process edit post.
	 *
	*/
	public function edit()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($_POST['seotitle'] != "") {
				$seotitle = $_POST['seotitle'];
			} else {
				$seotitle = $this->postring->seo_title($this->postring->valid($_POST['post'][1]['title'], 'xss'));
			}
			if ($_SESSION['leveluser'] == '1' OR $_SESSION['leveluser'] == '2') {
				$active = $this->postring->valid($_POST['active'], 'xss');
			} else {
				$active = "N";
			}
			$post = array(
				'seotitle' => $seotitle,
				'tag' => $this->postring->valid($_POST['tag'], 'xss'),
				'picture' => $_POST['picture'],
				'picture_description' => $_POST['picture_description'],
				'date' => $_POST['publishdate'],
				'time' => $_POST['publishtime'],
				'publishdate' => $_POST['publishdate']." ".$_POST['publishtime'],
				'comment' => $this->postring->valid($_POST['comment'], 'xss'),
				'active' => $active
			);
			$query_post = $this->podb->update('post')
				->set($post)
				->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
			$query_post->execute();
			$expl = explode(",", $this->postring->valid($_POST['tag'], 'xss'));
			$total = count($expl);
			for($i=0; $i<$total; $i++){
				$last_tag = $this->podb->from('tag')
					->where('tag_seo', $expl[$i])
					->limit(1)
					->fetch();
				$query_tag = $this->podb->update('tag')
					->set(array('count' => $last_tag['count']+1))
					->where('tag_seo', $expl[$i]);
				$query_tag->execute();
			}
			$query_del_cats = $this->podb->deleteFrom('post_category')->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
			$query_del_cats->execute();
			$id_categorys = $_POST['id_category'];
			if (!empty($_POST['id_category'])) {
				foreach($id_categorys as $id_category){
					$category = array(
						'id_post' => $this->postring->valid($_POST['id'], 'sql'),
						'id_category' => $id_category,
					);
					$query_category = $this->podb->insertInto('post_category')->values($category);
					$query_category->execute();
				}
			}
			foreach ($_POST['post'] as $id_language => $value) {
				$othlang_post = $this->podb->from('post_description')
					->where('id_post', $this->postring->valid($_POST['id'], 'sql'))
					->where('id_language', $id_language)
					->count();
				if ($othlang_post > 0) {
					$post_description = array(
						'title' => $this->postring->valid($value['title'], 'xss'),
						'content' => stripslashes(htmlspecialchars($value['content'],ENT_QUOTES))
					);
					$query_post_description = $this->podb->update('post_description')
						->set($post_description)
						->where('id_post_description', $this->postring->valid($value['id'], 'sql'));
				} else {
					$post_description = array(
						'id_post' => $this->postring->valid($_POST['id'], 'sql'),
						'id_language' => $id_language,
						'title' => $this->postring->valid($value['title'], 'xss'),
						'content' => stripslashes(htmlspecialchars($value['content'],ENT_QUOTES))
					);
					$query_post_description = $this->podb->insertInto('post_description')->values($post_description);
				}
				$query_post_description->execute();
			}
			$this->poflash->success($GLOBALS['_']['post_message_2'], 'admin.php?mod=post');
		}
		$id = $this->postring->valid($_GET['id'], 'sql');
		if ($_SESSION['leveluser'] != '1' || $_SESSION['leveluser'] != '2') {
			$current_post = $this->podb->from('post')
				->select('post_description.title')
				->leftJoin('post_description ON post_description.id_post = post.id_post')
				->where('post.id_post', $id)
				->limit(1)
				->fetch();
		} else {
			$current_post = $this->podb->from('post')
				->select('post_description.title')
				->leftJoin('post_description ON post_description.id_post = post.id_post')
				->where('post.id_post', $id)
				->where('post.editor', $_SESSION['iduser'])
				->limit(1)
				->fetch();
		}
		if (empty($current_post)) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['post_edit']);?>
				</div>
			</div>
			<div class="row">
				<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=post&act=edit&id='.$current_post['id_post'], 'autocomplete' => 'off'));?>
					<div class="col-md-8" id="left-post">
						<?=$this->pohtml->inputHidden(array('name' => 'id', 'value' => $current_post['id_post'], 'options' => 'id="id_post"'));?>
						<div class="row">
							<div class="col-md-12">
								<div class="pull-right">
									<a href="javascript:void(0)" class="btn btn-xs btn-default" id="hide-right">&nbsp;<i class="fa fa-angle-right"></i>&nbsp;</a>
								</div>
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
										$paglang = $this->podb->from('post_description')
											->where('post_description.id_post', $current_post['id_post'])
											->where('post_description.id_language', $lang['id_language'])
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
										<?=$this->pohtml->inputHidden(array('name' => 'post['.$lang['id_language'].'][id]', 'value' => $paglang['id_post_description']));?>
										<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_title_2'], 'name' => 'post['.$lang['id_language'].'][title]', 'id' => 'title-'.$lang['id_language'], 'value' => $paglang['title'], 'mandatory' => true, 'options' => 'required'));?>
										<div class="form-group">
											<label><?=$GLOBALS['_']['post_content'];?> <span class="text-danger">*</span></label>
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
											<textarea class="form-control" id="po-wysiwyg-<?=$lang['id_language'];?>" name="post[<?=$lang['id_language'];?>][content]" style="height:600px;"><?=$content_after;?></textarea>
										</div>
									</div>
									<?php $noctab++;} ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4" id="right-post">
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_seotitle'], 'name' => 'seotitle', 'id' => 'seotitle', 'value' => $current_post['seotitle'], 'mandatory' => true, 'options' => 'required', 'help' => 'Permalink : '.WEB_URL.'detailpost/<span id="permalink">'.$current_post['seotitle'].'</span>'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="id_category"><?=$GLOBALS['_']['post_category'];?> <span class="text-danger">*</span></label>
									<div class="box-category">
										<?=$this->generate_checkbox(0, 'update', $current_post['id_post']);?>
									</div>
									<div class="category-option">
										<div class="pull-left"><a href="admin.php?mod=category&act=addnew" target="_blank"><i class="fa fa-plus"></i> <?=$GLOBALS['_']['post_add_category'];?></a></div>
										<div class="pull-right"><a href="javascript:void(0)" id="category-refresh" data-id="<?=$current_post['id_post'];?>"><i class="fa fa-refresh"></i> <?=$GLOBALS['_']['post_refresh_category'];?></a></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_tag'], 'name' => 'tag', 'id' => 'tag', 'value' => $current_post['tag'], 'mandatory' => false, 'options' => ''));?>
								<div class="tag-option">
									<div class="text-left"><a href="admin.php?mod=tag&act=addnew" target="_blank"><i class="fa fa-plus"></i> <?=$GLOBALS['_']['post_add_tag'];?></a></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" id="image-box">
									<div class="row">
										<?php if ($current_post['picture'] == '') { ?>
											<div class="col-md-12"><label><?=$GLOBALS['_']['post_picture_2'];?></label></div>
											<div class="col-md-12">
												<a href="data:image/gif;base64,R0lGODdhyACWAOMAAO/v76qqqubm5t3d3bu7u7KystXV1cPDw8zMzAAAAAAAAAAAAAAAAAAAAAAAAAAAACwAAAAAyACWAAAE/hDISau9OOvNu/9gKI5kaZ5oqq5s675wLM90bd94ru987//AoHBILBqPyKRyyWw6n9CodEqtWq/YrHbL7Xq/4LB4TC6bz+i0es1uu9/wuHxOr9vv+Lx+z+/7/4CBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3TAMFBQO4LAUBAQW+K8DCxCoGu73IzSUCwQECAwQBBAIVCMAFCBrRxwDQwQLKvOHV1xbUwQfYEwIHwO3BBBTawu2BA9HGwcMT1b7Vw/Dt3z563xAIrHCQnzsAAf0F6ybhwDdwgAx8OxDQgASN/sKUBWNmwQDIfwBAThRoMYDHCRYJGAhI8eRMf+4OFrgZgCKgaB4PHqg4EoBQbxgBROtlrJu4ofYm0JMQkJk/mOMkTA10Vas1CcakJrXQ1eu/sF4HWhB3NphYlNsmxOWKsWtZtASTdsVb1mhEu3UDX3RLFyVguITzolQKji/GhgXNvhU7OICgsoflJr7Qd2/isgEPGGAruTTjnSZTXw7c1rJpznobf2Y9GYBjxIsJYQbXstfRDJ1luz6t2TDvosSJSpMw4GXG3TtT+hPpEoPJ6R89B7AaUrnolgWwnUQQEKVOAy199mlonPDfr3m/GeUHFjBhAf0SUh28+P12QOIIgDbcPdwgJV+Arf0jnwTwsHOQT/Hs1BcABObjDAcTXhiCOGppKAJI6nnIwQGiKZSViB2YqB+KHtxjjXMsxijjjDTWaOONOOao44489ujjj0AGKeSQRBZp5JFIJqnkkkw26eSTUEYp5ZRUVmnllVhmqeWWXHbp5ZdghinmmGSW6UsEADs=" target="_blank"><?=$GLOBALS['_']['post_picture_3'];?></a>
												<p><i><?=$GLOBALS['_']['post_picture_4'];?></i></p>
											</div>
										<?php } else { ?>
											<div class="col-md-12"><label><?=$GLOBALS['_']['post_picture_5'];?></label></div>
											<div class="col-md-12">
												<a href="../po-content/uploads/<?=$current_post['picture'];?>" target="_blank"><?=$GLOBALS['_']['post_picture_6'];?></a>
												<p>
													<i><?=$GLOBALS['_']['post_picture_4'];?></i>
													<button type="button" class="btn btn-xs btn-danger pull-right del-image" id="<?=$current_post['id_post'];?>"><i class="fa fa-trash-o"></i></button>
												</p>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_picture'], 'name' => 'picture', 'id' => 'picture', 'value' => $current_post['picture']), $inputgroup = true, $inputgroupopt = array('href' => '../'.DIR_INC.'/js/filemanager/dialog.php?type=1&field_id=picture', 'id' => 'browse-file', 'class' => 'btn-success', 'options' => '', 'title' => $GLOBALS['_']['action_7'].' '.$GLOBALS['_']['post_picture']));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputTextarea(array('label' => $GLOBALS['_']['post_picture_description'], 'name' => 'picture_description', 'id' => 'picture_description', 'class' => 'mceNoEditor', 'value' => $current_post['picture_description'], 'options' => 'rows="3" cols=""'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_date'], 'name' => 'publishdate', 'id' => 'publishdate', 'value' => $current_post['date'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['post_time'], 'name' => 'publishtime', 'id' => 'publishtime', 'value' => $current_post['time'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php
									if ($current_post['comment'] == 'N') {
										$radioitem = array(
											array('name' => 'comment', 'id' => 'comment', 'value' => 'Y', 'options' => '', 'title' => 'Y'),
											array('name' => 'comment', 'id' => 'comment', 'value' => 'N', 'options' => 'checked', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['post_comment'], 'mandatory' => true), $radioitem, $inline = true);
									} else {
										$radioitem = array(
											array('name' => 'comment', 'id' => 'comment', 'value' => 'Y', 'options' => 'checked', 'title' => 'Y'),
											array('name' => 'comment', 'id' => 'comment', 'value' => 'N', 'options' => '', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['post_comment'], 'mandatory' => true), $radioitem, $inline = true);
									}
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php
									if ($current_post['active'] == 'N') {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => '', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => 'checked', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['post_active'], 'mandatory' => true), $radioitem, $inline = true);
									} else {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => 'checked', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => '', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['post_active'], 'mandatory' => true), $radioitem, $inline = true);
									}
								?>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->formAction();?>
							</div>
						</div>
					</div>
				<?=$this->pohtml->formEnd();?>
			</div>
		</div>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<div class="block-header">
						<h4><?=$GLOBALS['_']['post_gallery'];?></h4>
					</div>
				</div>
			</div>
			<div class="row">
				<?php
					$gallerys = $this->podb->from('post_gallery')->where('id_post', $current_post['id_post'])->orderBy('id_post_gallery DESC')->fetchAll();
					$count_gallerys = $this->podb->from('post_gallery')->where('id_post', $current_post['id_post'])->orderBy('id_post_gallery DESC')->count();
					if ($count_gallerys > 0) {
				?>
				<div class="col-md-12">
					<div class="row">
					<?php
						foreach($gallerys as $gallery){
					?>
						<div class="col-md-3" id="col-gal-<?=$gallery['id_post_gallery'];?>">
							<div class="widget">
								<div class="theme_box" style="background-image:url('../<?=DIR_CON;?>/uploads/medium/medium_<?=$gallery['picture'];?>');">
									<ul>
										<li><a href="../<?=DIR_CON;?>/uploads/<?=$gallery['picture'];?>" data-toggle="tooltip" title="<?=$GLOBALS['_']['action_11'];?>" target="_blank"><i class="fa fa-eye bg-warning"></i></a></li>
										<li><a href="javascript:void(0)" class="btn-remove-gal" id="<?=$gallery['id_post_gallery'];?>" data-toggle="tooltip" title="<?=$GLOBALS['_']['action_2'];?>"><i class="fa fa-times bg-danger"></i></a></li>
									</ul>
								</div><!-- Admin Follow -->
							</div><!-- Widget -->
						</div>
					<?php } ?>
					</div>
				</div>
				<p>&nbsp;</p>
				<?php } ?>
				<div class="col-md-12">
					<div id="postgallery" class="dropzone dz-clickable"></div>
				</div>
			</div>
		</div>
		<div id="alertdelimg" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="post" action="route.php?mod=post&act=edit&id='.<?=$current_post['id_post'];?>" autocomplete="off">
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
	 * Fungsi ini digunakan untuk menampilkan halaman import.
	 *
	 * This function is used to display import page.
	 *
	*/
	public function import()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['post_import']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'admin.php?mod=post&act=processimport', 'enctype' => true, 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-12">
								<?php
									$item = array(
										array('value' => 'popojicms', 'title' => 'PopojiCMS'),
										array('value' => 'wordpress', 'title' => 'WordPress')
									);
								?>
								<?=$this->pohtml->inputSelect(array('id' => 'from', 'label' => $GLOBALS['_']['post_import_from'], 'name' => 'from', 'mandatory' => true), $item);?>
								<div class="form-group">
									<label><?=$GLOBALS['_']['post_file'];?> <i>(.xml)</i> <span class="text-danger">*</span></label>
									<input name="fupload" id="fupload" type="file" /><br />
									<p><i><span class="text-danger">*</span> <?=$GLOBALS['_']['post_import_help'];?></i></p>
								</div>
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
	 * Fungsi ini digunakan untuk memproses halaman import.
	 *
	 * This function is used to process import page.
	 *
	*/
	public function processimport()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			?>
			<div class="block-content">
				<div class="row">
					<div class="col-md-12">
						<?=$this->pohtml->headTitle($GLOBALS['_']['post_import'], '
							<div class="btn-title pull-right">
								<a href="admin.php?mod=post" class="btn btn-success btn-sm"><i class="fa fa-book"></i> '.$GLOBALS['_']['post_back_to_post'].'</a>
								<a href="admin.php?mod=post&act=import" class="btn btn-info btn-sm"><i class="fa fa-download"></i> '.$GLOBALS['_']['post_import'].'</a>
							</div>
						');?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php
						if (!empty($_FILES['fupload']['tmp_name'])) {
							$exp = explode('.', $_FILES['fupload']['name']);
							$xmlfile = $this->postring->seo_title($exp[0]).'-'.rand(000000,999999).'-popoji.'.end($exp);
							if (($_FILES["fupload"]["size"] < 10000000) && in_array(end($exp), array('xml'))) {
								if (file_exists('../'.DIR_CON.'/uploads/'.$xmlfile)) {
									echo '<div class="alert alert-danger" role="alert">'.$GLOBALS['_']['post_message_7'].'</div>';
								} else {
									move_uploaded_file($_FILES['fupload']['tmp_name'], '../'.DIR_CON.'/uploads/'.$xmlfile);
									$importfile = simplexml_load_file('../'.DIR_CON.'/uploads/'.$xmlfile);		
									$xi=0;
									$total_xml = count($importfile->channel->item);
									?>
									<script>
										$(document).ready(function() {
											$.ajax({
												type: "POST",
												url: "route.php?mod=post&act=progressbar",
												cache: false,
												success: function(data){
													$('#progress_bar').attr('aria-valuenow', data);
													$('#progress_bar').css('width', data+'%');
													$('#progress_bar').html(data+'%');
												}
											});
										});
									</script>
									<div class="progress" style="height: 20px;">
										<div id="progress_bar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="<?=$total_xml;?>" style="width: 0%; line-height: 20px;"></div>
									</div>
									<?php
									if ($this->postring->valid($_POST['from'], 'xss') == 'popojicms') {
										foreach ($importfile->channel->item as $item) {
											$current_seotitle = $this->podb->from('post')
												->where('seotitle', $item->seotitle)
												->count();
											if ($current_seotitle < 1) {
												$imageurl = $item->picture;
												if ($imageurl != 'none') {
													$namefile = explode('/', $imageurl);
													file_put_contents('../'.DIR_CON.'/uploads/'.end($namefile), file_get_contents($imageurl));
													$original = new PoSimpleImage('../'.DIR_CON.'/uploads/'.end($namefile));
													$original->resize(900, 600)->save();
													$medium = new PoSimpleImage('../'.DIR_CON.'/uploads/'.end($namefile));
													$medium->resize(640, 426)->save('../'.DIR_CON.'/uploads/medium/medium_'.end($namefile));
													$thumb = new PoSimpleImage('../'.DIR_CON.'/uploads/'.end($namefile));
													$thumb->resize(122, 91)->save('../'.DIR_CON.'/thumbs/'.end($namefile));
													$datapic = array(
														'picture' => end($namefile)
													);
												} else {
													$datapic = array();
												}

												$data = array(
													'seotitle' => $item->seotitle,
													'tag' => $item->tag,
													'date' => $item->date,
													'time' => $item->time,
													'publishdate' => $item->date.' '.$item->time,
													'editor' => $_SESSION['iduser'],
													'active' => $item->active
												);
												$datafinal = array_merge($data, $datapic);
												$query_post = $this->podb->insertInto('post')->values($datafinal);
												$query_post->execute();

												$expl = explode(",", $item->tag);
												$total = count($expl);
												for($i=0; $i<$total; $i++){
													$last_tag = $this->podb->from('tag')
														->where('tag_seo', $expl[$i])
														->limit(1)
														->fetch();
													if ($last_tag > 0) {
														$query_tag = $this->podb->update('tag')
															->set(array('count' => $last_tag['count']+1))
															->where('tag_seo', $expl[$i]);
														$query_tag->execute();
													} else {
														$query_tag = $this->podb->insertInto('tag')->values(
															array(
																'title' => str_replace('-', ' ', $expl[$i]),
																'tag_seo' => $expl[$i],
																'count' => '1'
															)
														);
														$query_tag->execute();
													}
												}

												$last_post = $this->podb->from('post')
													->orderBy('id_post DESC')
													->limit(1)
													->fetch();

												$count_cat_seotitle = $this->podb->from('category')
													->where('seotitle', $this->postring->seo_title($item->category))
													->count();
												if ($count_cat_seotitle > 0) {
													$current_cat_seotitle = $this->podb->from('category')
														->where('seotitle', $this->postring->seo_title($item->category))
														->limit(1)
														->fetch();
													$id_category = $current_cat_seotitle['id_category'];
												} else {
													$category = array(
														'id_parent' => '0',
														'seotitle' => $this->postring->seo_title($item->category),
														'active' => 'Y'
													);
													$query_category = $this->podb->insertInto('category')->values($category);
													$query_category->execute();
													$last_category = $this->podb->from('category')
														->orderBy('id_category DESC')
														->limit(1)
														->fetch();
													$category_description = array(
														'id_category' => $last_category['id_category'],
														'id_language' => '1',
														'title' =>  $this->postring->valid($item->category[0], 'xss'),
													);
													$query_category_description = $this->podb->insertInto('category_description')->values($category_description);
													$query_category_description->execute();
													$id_category = $last_category['id_category'];
												}

												$post_category = array(
													'id_post' => $last_post['id_post'],
													'id_category' => $id_category,
												);
												$query_post_category = $this->podb->insertInto('post_category')->values($post_category);
												$query_post_category->execute();

												$post_description = array(
													'id_post' => $last_post['id_post'],
													'id_language' => '1',
													'title' => $this->postring->valid($item->title, 'xss'),
													'content' => $item->content
												);
												$query_post_description = $this->podb->insertInto('post_description')->values($post_description);
												$query_post_description->execute();
											}
											$xi++;
											$progress_bar = ($xi / $total_xml) * 100;
											$_SESSION['progress_bar'] = floor($progress_bar);
										}
									} elseif ($this->postring->valid($_POST['from'], 'xss') == 'wordpress') {
										foreach ($importfile->channel->item as $item) {
											if ($item->children('wp', true)->post_type == 'post') {
												$current_seotitle = $this->podb->from('post')
													->where('seotitle', $this->postring->seo_title($item->title))
													->count();
												if ($current_seotitle < 1) {
													$imageurl = $this->search_attachment_wp('../'.DIR_CON.'/uploads/'.$xmlfile, $item->children('wp', true)->post_id);
													if ($imageurl != 'none') {
														$namefile = explode('/', $imageurl);
														file_put_contents('../'.DIR_CON.'/uploads/'.end($namefile), file_get_contents($imageurl));
														$original = new PoSimpleImage('../'.DIR_CON.'/uploads/'.end($namefile));
														$original->resize(900, 600)->save();
														$medium = new PoSimpleImage('../'.DIR_CON.'/uploads/'.end($namefile));
														$medium->resize(640, 426)->save('../'.DIR_CON.'/uploads/medium/medium_'.end($namefile));
														$thumb = new PoSimpleImage('../'.DIR_CON.'/uploads/'.end($namefile));
														$thumb->resize(122, 91)->save('../'.DIR_CON.'/thumbs/'.end($namefile));
														$datapic = array(
															'picture' => end($namefile)
														);
													} else {
														$datapic = array();
													}

													$wpdatetime = explode(' ', $item->children('wp', true)->post_date);
													$data = array(
														'seotitle' => $this->postring->seo_title($item->title),
														'date' => $wpdatetime[0],
														'time' => $wpdatetime[1],
														'publishdate' => $wpdatetime[0].' '.$wpdatetime[1],
														'editor' => $_SESSION['iduser'],
														'comment' => ($item->children('wp', true)->comment_status == 'open' ? 'Y' : 'N'),
														'active' => ($item->children('wp', true)->status == 'publish' ? 'Y' : 'N')
													);
													$datafinal = array_merge($data, $datapic);
													$query_post = $this->podb->insertInto('post')->values($datafinal);
													$query_post->execute();

													$last_post = $this->podb->from('post')
														->orderBy('id_post DESC')
														->limit(1)
														->fetch();

													$count_cat_seotitle = $this->podb->from('category')
														->where('seotitle', $this->postring->seo_title($item->category[0]))
														->count();
													if ($count_cat_seotitle > 0) {
														$current_cat_seotitle = $this->podb->from('category')
															->where('seotitle', $this->postring->seo_title($item->category[0]))
															->limit(1)
															->fetch();
														$id_category = $current_cat_seotitle['id_category'];
													} else {
														$category = array(
															'id_parent' => '0',
															'seotitle' => $this->postring->seo_title($item->category[0]),
															'active' => 'Y'
														);
														$query_category = $this->podb->insertInto('category')->values($category);
														$query_category->execute();
														$last_category = $this->podb->from('category')
															->orderBy('id_category DESC')
															->limit(1)
															->fetch();
														$category_description = array(
															'id_category' => $last_category['id_category'],
															'id_language' => '1',
															'title' =>  $this->postring->valid($item->category[0], 'xss'),
														);
														$query_category_description = $this->podb->insertInto('category_description')->values($category_description);
														$query_category_description->execute();
														$id_category = $last_category['id_category'];
													}

													$post_category = array(
														'id_post' => $last_post['id_post'],
														'id_category' => $id_category,
													);
													$query_post_category = $this->podb->insertInto('post_category')->values($post_category);
													$query_post_category->execute();

													$post_description = array(
														'id_post' => $last_post['id_post'],
														'id_language' => '1',
														'title' => $this->postring->valid($item->title, 'xss'),
														'content' => stripslashes(htmlspecialchars($item->children("content", true),ENT_QUOTES))
													);
													$query_post_description = $this->podb->insertInto('post_description')->values($post_description);
													$query_post_description->execute();
												}
											}
											$xi++;
											$progress_bar = ($xi / $total_xml) * 100;
											$_SESSION['progress_bar'] = floor($progress_bar);
										}
									} else {
										echo '<div class="alert alert-danger" role="alert">'.$GLOBALS['_']['post_message_7'].'</div>';
									}
									unlink('../'.DIR_CON.'/uploads/'.$xmlfile);
								}
							} else {
								echo '<div class="alert alert-danger" role="alert">'.$GLOBALS['_']['post_message_7'].'</div>';
							}
						} else {
							echo '<div class="alert alert-danger" role="alert">'.$GLOBALS['_']['post_message_7'].'</div>';
						}
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'admin.php?mod=post&act=processimport', 'enctype' => true, 'autocomplete' => 'off'));?>
							<div class="row">
								<div class="col-md-12">
									<?php
										$item = array(
											array('value' => 'popojicms', 'title' => 'PopojiCMS'),
											array('value' => 'wordpress', 'title' => 'WordPress')
										);
									?>
									<?=$this->pohtml->inputSelect(array('id' => 'from', 'label' => $GLOBALS['_']['post_import_from'], 'name' => 'from', 'mandatory' => true), $item);?>
									<div class="form-group">
										<label><?=$GLOBALS['_']['post_file'];?> <i>(.xml)</i> <span class="text-danger">*</span></label>
										<input name="fupload" id="fupload" type="file" /><br />
										<p><i><span class="text-danger">*</span> <?=$GLOBALS['_']['post_import_help'];?></i></p>
									</div>
									<?=$this->pohtml->formAction();?>
								</div>
							</div>
						<?=$this->pohtml->formEnd();?>
					</div>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Fungsi ini digunakan untuk memproses headline post.
	 *
	 * This function is used to process headline post.
	 *
	*/
	public function setheadline()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'update')) {
			if ($_SESSION['leveluser'] != '1' || $_SESSION['leveluser'] != '2') {
				echo $this->pohtml->error();
				exit;
			}
		}
		if (!empty($_POST)) {
			$post = array(
				'headline' => $this->postring->valid($_POST['headline'], 'xss')
			);
			$query_post = $this->podb->update('post')
				->set($post)
				->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
			$query_post->execute();
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus post.
	 *
	 * This function is used to display and process delete post.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($_SESSION['leveluser'] != '1' || $_SESSION['leveluser'] != '2') {
				$query_desc = $this->podb->deleteFrom('post_description')->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
				$query_desc->execute();
				$query_cats = $this->podb->deleteFrom('post_category')->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
				$query_cats->execute();
				$query_gals = $this->podb->deleteFrom('post_gallery')->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
				$query_gals->execute();
				$query_pag = $this->podb->deleteFrom('post')->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
				$query_pag->execute();
				$this->poflash->success($GLOBALS['_']['post_message_3'], 'admin.php?mod=post');
			} else {
				$current_post = $this->podb->from('post')
					->where('id_post', $this->postring->valid($_POST['id'], 'sql'))
					->where('editor', $_SESSION['iduser'])
					->limit(1)
					->fetch();
				if (empty($current_post)) {
					$this->poflash->error($GLOBALS['_']['post_message_6'], 'admin.php?mod=post');
				} else {
					$query_desc = $this->podb->deleteFrom('post_description')->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
					$query_desc->execute();
					$query_cats = $this->podb->deleteFrom('post_category')->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
					$query_cats->execute();
					$query_gals = $this->podb->deleteFrom('post_gallery')->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
					$query_gals->execute();
					$query_pag = $this->podb->deleteFrom('post')->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
					$query_pag->execute();
					$this->poflash->success($GLOBALS['_']['post_message_3'], 'admin.php?mod=post');
				}
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi post.
	 *
	 * This function is used to display and process multi delete post.
	 *
	*/
	public function multidelete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					if ($_SESSION['leveluser'] != '1' || $_SESSION['leveluser'] != '2') {
						$query_desc = $this->podb->deleteFrom('post_description')->where('id_post', $this->postring->valid($item['deldata'], 'sql'));
						$query_desc->execute();
						$query_cats = $this->podb->deleteFrom('post_category')->where('id_post', $this->postring->valid($item['deldata'], 'sql'));
						$query_cats->execute();
						$query_gals = $this->podb->deleteFrom('post_gallery')->where('id_post', $this->postring->valid($item['deldata'], 'sql'));
						$query_gals->execute();
						$query_pag = $this->podb->deleteFrom('post')->where('id_post', $this->postring->valid($item['deldata'], 'sql'));
						$query_pag->execute();
					} else {
						$current_post = $this->podb->from('post')
							->where('id_post', $this->postring->valid($item['deldata'], 'sql'))
							->where('editor', $_SESSION['iduser'])
							->limit(1)
							->fetch();
						if (!empty($current_post)) {
							$query_desc = $this->podb->deleteFrom('post_description')->where('id_post', $this->postring->valid($item['deldata'], 'sql'));
							$query_desc->execute();
							$query_cats = $this->podb->deleteFrom('post_category')->where('id_post', $this->postring->valid($item['deldata'], 'sql'));
							$query_cats->execute();
							$query_gals = $this->podb->deleteFrom('post_gallery')->where('id_post', $this->postring->valid($item['deldata'], 'sql'));
							$query_gals->execute();
							$query_pag = $this->podb->deleteFrom('post')->where('id_post', $this->postring->valid($item['deldata'], 'sql'));
							$query_pag->execute();
						}
					}
				}
				$this->poflash->success($GLOBALS['_']['post_message_3'], 'admin.php?mod=post');
			} else {
				$this->poflash->error($GLOBALS['_']['post_message_6'], 'admin.php?mod=post');
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
		if (!$this->auth($_SESSION['leveluser'], 'post', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$post = array(
				'picture' => ''
			);
			$query_post = $this->podb->update('post')
				->set($post)
				->where('id_post', $this->postring->valid($_POST['id'], 'sql'));
			$query_post->execute();
		}
	}

	/**
	 * Fungsi ini digunakan untuk mengambil data kategori dari database.
	 *
	 * This function is used to get category data from database.
	 *
	*/
	public function get_category()
	{
		if (!$this->auth($_SESSION['leveluser'], 'category', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($this->postring->valid($_POST['id'], 'sql') == '0') {
				echo $this->generate_checkbox(0, 'add');
			} else {
				echo $this->generate_checkbox(0, 'update', $this->postring->valid($_POST['id'], 'sql'));
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk mengambil data tag dari database.
	 *
	 * This function is used to get tag data from database.
	 *
	*/
	public function get_tag()
	{
		if (!$this->auth($_SESSION['leveluser'], 'tag', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$search = $this->postring->valid($_POST['search'], 'xss');
			$tags = $this->podb->from('tag')
				->where('title LIKE "%'.$search.'%"')
				->orderBy('id_tag ASC')
				->fetchAll();
			header('Content-Type: application/json');
			echo json_encode($tags);
		}
	}

	/**
	 * Fungsi ini digunakan untuk memproses tambah gambar galeri.
	 *
	 * This function is used to processed add image gallery.
	 *
	*/
	public function addgallery()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$upload = new PoUpload($_FILES['file']);
			if ($upload->uploaded) {
				$id_post = $this->postring->valid($_POST['id_post'], 'sql');
				$file_name = reset((explode('.', $this->postring->valid($_FILES['file']['name'], 'xss'))));
				$upload->file_new_name_body = $file_name;
				$upload->image_convert = 'jpg';
				$upload->process('../'.DIR_CON.'/uploads/');
				if ($upload->processed) {
					$upload_med = new PoUpload($_FILES['file']);
					$upload_med->file_new_name_body = 'medium_'.$file_name;
					$upload_med->image_convert = 'jpg';
					$upload_med->image_resize = true;
					$medium_size = explode('x', $this->posetting[12]['value']);
					$upload_med->image_x = $medium_size[0];
					$upload_med->image_y = $medium_size[1];
					$upload_med->image_ratio = true;
					$upload_med->process('../'.DIR_CON.'/uploads/medium/');
						$post_gal = array(
							'id_post' => $id_post,
							'picture' => $upload->file_dst_name
						);
						$post_gal_query = $this->podb->insertInto('post_gallery')->values($post_gal);
						$post_gal_query->execute();
						$upload->clean();
						$upload_med->clean();
				}
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk memproses hapus gambar galeri terpilih.
	 *
	 * This function is used to process delete selected image gallery.
	 *
	*/
	public function deletegallery()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$query_gal = $this->podb->deleteFrom('post_gallery')->where('id_post_gallery', $this->postring->valid($_POST['id'], 'sql'));
			$query_gal->execute();
		}
	}

	/**
	 * Fungsi ini digunakan untuk menulis post di facebook.
	 *
	 * This function use for create post in facebook.
	 *
	*/
	public function facebook()
	{
		if (!$this->auth($_SESSION['leveluser'], 'oauth', 'read')) {
			if ($_SESSION['leveluser'] != '1' || $_SESSION['leveluser'] != '2') {
				echo $this->pohtml->error();
				exit;
			}
		}
		if (!empty($_GET['id'])) {
			require_once '../'.DIR_CON.'/component/oauth/facebook/Facebook/autoload.php';

			$currentOauthfb = $this->podb->from('oauth')->fetchAll();
			$appIdOauthfb = $currentOauthfb[0]['oauth_key'];
			$secretOauthfb = $currentOauthfb[0]['oauth_secret'];
			$idOauthfb = $currentOauthfb[0]['oauth_id'];
			$tokenOauthfb = $currentOauthfb[0]['oauth_token1'];
			$fbtypeOauthfb = $currentOauthfb[0]['oauth_fbtype'];

			$fb = new Facebook\Facebook([
				'app_id'  => $appIdOauthfb,
				'app_secret' => $secretOauthfb,
				'default_graph_version' => 'v2.5'
			]);

			$helper = $fb->getRedirectLoginHelper();

			try {
				$accessToken = $helper->getAccessToken();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}

			if (isset($accessToken)) {
				$oAuth2Client = $fb->getOAuth2Client();

				if (!$accessToken->isLongLived()) {
					try {
						$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
					}
					catch (Facebook\Exceptions\FacebookSDKException $e) {
						echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
						exit;
					}
				}

				$current_post = $this->podb->from('post')
					->select('post_description.title')
					->leftJoin('post_description ON post_description.id_post = post.id_post')
					->where('post.id_post', $this->postring->valid($_GET['id'], 'sql'))
					->limit(1)
					->fetch();
				$paglang = $this->podb->from('post_description')
					->where('post_description.id_post', $current_post['id_post'])
					->where('post_description.id_language', '1')
					->fetch();
				if($fbtypeOauthfb == "user"){
					$response = $fb->post('/me/feed',
						[
							"message" => $paglang['title'],
							"link" => WEB_URL."detailpost/".$current_post['seotitle'],
							"picture" => "http://www.example.net/images/example.png",
							"name" => $paglang['title'],
							"caption" => trim(trim(WEB_URL, "http://"), "/"),
							"description" => $this->postring->cuthighlight('post', $paglang['content'], '500')
						],
						$accessToken
					);
				} else {
					$response = $fb->post('/'.$idOauthfb.'/feed',
						[
							"message" => $paglang['title'],
							"link" => WEB_URL."detailpost/".$current_post['seotitle'],
							"picture" => "http://www.example.net/images/example.png",
							"name" => $paglang['title'],
							"caption" => trim(trim(WEB_URL, "http://"), "/"),
							"description" => $this->postring->cuthighlight('post', $paglang['content'], '500')
						],
						$accessToken
					);
				}
				$this->poflash->success($GLOBALS['_']['post_oauth_message_3'], 'admin.php?mod=post');
			} else {
				$loginUrl = $helper->getLoginUrl(WEB_URL.DIR_ADM.'/route.php?mod=post&act=facebook&id=1', ['public_profile', 'email', 'manage_pages', 'publish_actions']);
				header('location:'.$loginUrl);
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menulis post di twitter.
	 *
	 * This function use for create post in twitter.
	 *
	*/
	public function twitter()
	{
		if (!$this->auth($_SESSION['leveluser'], 'oauth', 'read')) {
			if ($_SESSION['leveluser'] != '1' || $_SESSION['leveluser'] != '2') {
				echo $this->pohtml->error();
				exit;
			}
		}
		if (!empty($_GET['id'])) {
			require_once '../'.DIR_CON.'/component/oauth/twitter/Twitter/twitteroauth.php';

			$currentOauthtw = $this->podb->from('oauth')->fetchAll();
			$conkeyOauthtw = $currentOauthtw[1]['oauth_key'];
			$consecretOauthtw = $currentOauthtw[1]['oauth_secret'];
			$idOauthtw = $currentOauthtw[1]['oauth_id'];
			$tokenOauthtw = $currentOauthtw[1]['oauth_token1'];
			$tokensecretOauthtw = $currentOauthtw[1]['oauth_token2'];

			define('CONSUMER_KEY', ''.$conkeyOauthtw.'');
			define('CONSUMER_SECRET', ''.$consecretOauthtw.'');
			define('OAUTH_CALLBACK', ''.WEB_URL.DIR_ADM.'/admin.php?mod=post');

			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $tokenOauthtw, $tokensecretOauthtw);

			$current_post = $this->podb->from('post')
				->select('post_description.title')
				->leftJoin('post_description ON post_description.id_post = post.id_post')
				->where('post.id_post', $this->postring->valid($_GET['id'], 'sql'))
				->limit(1)
				->fetch();
			$paglang = $this->podb->from('post_description')
				->where('post_description.id_post', $current_post['id_post'])
				->where('post_description.id_language', '1')
				->fetch();

			$params = array(
				"status" => $paglang['title'].", Link : ".WEB_URL."detailpost/".$current_post['seotitle']
			);
			$status = $connection->post('statuses/update', $params);
			if (200 == $connection->http_code) {
				$this->poflash->success($GLOBALS['_']['post_oauth_message_1'], 'admin.php?mod=post');
			} else {
				$this->poflash->error($GLOBALS['_']['post_oauth_message_2'], 'admin.php?mod=post');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk mengirim email ke para pelanggan.
	 *
	 * This function use for send email to subscribers.
	 *
	*/
	public function subscribe()
	{
		if (!$this->auth($_SESSION['leveluser'], 'post', 'create')) {
			if ($_SESSION['leveluser'] != '1' || $_SESSION['leveluser'] != '2') {
				echo $this->pohtml->error();
				exit;
			}
		}
		if (!empty($_POST['id'])) {
			$current_post = $this->podb->from('post')
				->select('post_description.title')
				->leftJoin('post_description ON post_description.id_post = post.id_post')
				->where('post.id_post', $this->postring->valid($_POST['id'], 'sql'))
				->limit(1)
				->fetch();
			$paglang = $this->podb->from('post_description')
				->where('post_description.id_post', $current_post['id_post'])
				->where('post_description.id_language', '1')
				->fetch();
			$subscribes = $this->podb->from('subscribe')->fetchAll();
			foreach($subscribes as $subscribe){
				$message = "<html>
					<body>
						Hi ".$subscribe['email']."<br />
						We have the latest updates for you!<br />
						Please click on the link below to begin reading :<br />
						<a href='".WEB_URL."/detailpost/".$current_post['seotitle']."'>".$paglang['title']."</a><br /><br />
						Thank you for subscribing,<br />
						".$this->posetting[0]['value']."
					</body>
				</html>";
				if ($this->posetting[23]['value'] != 'SMTP') {
					$email = new PoEmail;
					$send = $email
						->to($subscribe['email'])
						->subject("Website Update - ".$paglang['title'])
						->message($message)
						->from($this->posetting[5]['value'], $this->posetting[0]['value'])
						->mail();
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
					$this->pomail->addAddress($subscribe['email']);
					$this->pomail->Subject = 'Website Update - '.$paglang['title'];
					$this->pomail->msgHTML($message);
					$this->pomail->send();
				}
			}
			echo "200";
		}
	}

	/**
	 * Fungsi ini digunakan untuk menggenerate checkbox kategori.
	 *
	 * This function use for generate category checkbox
	 *
	*/
	public function generate_checkbox($id, $type, $id_post = null)
	{
		if ($type == 'add') {
			return $this->generate_child($id, "0");
		} else {
			return $this->generate_child_update($id, $id_post, "0");
		}
	}

	/**
	 * Fungsi ini digunakan untuk menggenerate child checkbox kategori.
	 *
	 * This function use for generate category child checkbox.
	 *
	*/
	public function generate_child($id, $exp)
	{
		$i = 1;
		$html = "";
		$indent = str_repeat("\t\t", $i);
		$catfuns = $this->podb->from('category')
			->select('category_description.title')
			->leftJoin('category_description ON category_description.id_category = category.id_category')
			->where('category.id_parent', $id)
			->where('category_description.id_language', '1')
			->orderBy('category.id_category ASC')
			->fetchAll();
		$catfunnum = $this->podb->from('category')->where('id_parent', $id)->orderBy('id_category ASC')->count();
		if ($catfunnum > 0) {
			$html .= "\n\t".$indent."";
			$html .= "<ul class=\"list-unstyled\">";
			$i++;
			foreach ($catfuns as $catfun) {
				$explus = $exp + 20;
				$child = $this->generate_child($catfun['id_category'], $explus."px");
				$html .= "\n\t".$indent."";
				if ($child) {
					$i--;
					$html .= "<li><input type=\"checkbox\" name=\"id_category[]\" value='".$catfun['id_category']."' style='margin-left:".$exp.";' /> ";
					$html .= $catfun['title'];
					$html .= $child;
					$html .= "\n\t".$indent."";
				} else {
					$html .= "<li><input type=\"checkbox\" name=\"id_category[]\" value='".$catfun['id_category']."' style='margin-left:".$exp.";' /> ";
					$html .= $catfun['title'];
				}
				$html .= '</li>';
			}
			$html .= "\n$indent</ul>";
			return $html;
		} else {
			return false;
		}
	}

	/**
	 * Fungsi ini digunakan untuk menggenerate update child checkbox kategori.
	 *
	 * This function use for generate category child update checkbox.
	 *
	*/
	public function generate_child_update($id, $id_post, $exp)
	{
		$i = 1;
		$html = "";
		$postcat = array();
		$indent = str_repeat("\t\t", $i);
		$catfuns = $this->podb->from('category')
			->select('category_description.title')
			->leftJoin('category_description ON category_description.id_category = category.id_category')
			->where('category.id_parent', $id)
			->where('category_description.id_language', '1')
			->orderBy('category.id_category ASC')
			->fetchAll();
		$post_cats = $this->podb->from('post_category')
			->where('id_post', $id_post)
			->fetchAll();
		foreach($post_cats as $post_cat){
			$postcat[] = $post_cat['id_category'];
		}
		$catfunnum = $this->podb->from('category')->where('id_parent', $id)->orderBy('id_category ASC')->count();
		if ($catfunnum > 0) {
			$html .= "\n\t".$indent."";
			$html .= "<ul class=\"list-unstyled\">";
			$i++;
			foreach ($catfuns as $catfun) {
				if (in_array($catfun['id_category'], $postcat)) {
					$checked = 'checked';
				} else {
					$checked = '';
				}
				$explus = $exp + 20;
				$child = $this->generate_child_update($catfun['id_category'], $id_post, $explus."px");
				$html .= "\n\t".$indent."";
				if ($child) {
					$i--;
					$html .= "<li><input type=\"checkbox\" name=\"id_category[]\" value='".$catfun['id_category']."' style='margin-left:".$exp.";' ".$checked." /> ";
					$html .= $catfun['title'];
					$html .= $child;
					$html .= "\n\t".$indent."";
				} else {
					$html .= "<li><input type=\"checkbox\" name=\"id_category[]\" value='".$catfun['id_category']."' style='margin-left:".$exp.";' ".$checked." /> ";
					$html .= $catfun['title'];
				}
				$html .= '</li>';
			}
			$html .= "\n$indent</ul>";
			return $html;
		} else {
			return false;
		}
	}

	/**
	 * Fungsi ini digunakan untuk mencari attachment gambar wordpress.
	 *
	 * This function use for searching image attachment in wordpress.
	 *
	*/
	public function search_attachment_wp($xml, $id)
	{
		$dom = new DOMDocument();
		$dom->loadXML(file_get_contents($xml));
		$xpath = new DOMXpath($dom);
		$xpath->registerNamespace('wp', 'http://wordpress.org/export/1.2/');
		$attachment_url = 'none';
		foreach($xpath->evaluate('//item') as $item) {
			if ($xpath->evaluate('number(.//wp:post_parent["'.$id.'"])', $item) == $id) {
				$attachment_url = $xpath->evaluate('string(.//wp:post_parent["'.$id.'"]/../wp:attachment_url)', $item);
			}
		}
		return $attachment_url;
	}

	/**
	 * Fungsi ini digunakan untuk membuat progres bar.
	 *
	 * This function use for create progress bar.
	 *
	*/
	public function progressbar()
	{
		if ($_SESSION['progress_bar'] < 95) {
			echo $_SESSION['progress_bar'];
		} else {
			echo "100";
			unset($_SESSION['progress_bar']);
		}
	}

}