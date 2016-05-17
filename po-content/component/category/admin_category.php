<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_category.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman kategori.
 * This is a php file for handling admin process for category page.
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

class Category extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index kategori.
	 *
	 * This function use for index category page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'category', 'read')) {
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
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=category&act=multidelete', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => 'Id', 'options' => 'style="width:30px;"'),
								array('title' => $GLOBALS['_']['category_parent'], 'options' => ''),
								array('title' => $GLOBALS['_']['category_title'], 'options' => ''),
								array('title' => $GLOBALS['_']['category_active'], 'options' => 'class="no-sort" style="width:30px;"'),
								array('title' => $GLOBALS['_']['category_action'], 'options' => 'class="no-sort" style="width:50px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-category', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('category');?>
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
		if (!$this->auth($_SESSION['leveluser'], 'category', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'category';
		$primarykey = 'id_category';
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
			array('db' => 'c.id_parent', 'dt' => '2', 'field' => 'id_parent',
				'formatter' => function($d, $row, $i){
					if ($d == '0') {
						return 'No Parent';
					} else {
						$parent = $this->podb->from('category')
							->select('category_description.title')
							->leftJoin('category_description ON category_description.id_category = category.id_category')
							->where('category.id_category', $d)
							->limit(1)
							->fetch();
						return $parent['title'];
					}
				}
			),
			array('db' => 'cd.title', 'dt' => '3', 'field' => 'title',
				'formatter' => function($d, $row, $i){
					return "".$d."<br /><i><a href='".WEB_URL."category/".$this->postring->seo_title($d)."' target='_blank'>".WEB_URL."category/".$this->postring->seo_title($d)."</a></i>";
				}
			),
			array('db' => 'c.active', 'dt' => '4', 'field' => 'active'),
			array('db' => 'c.'.$primarykey, 'dt' => '5', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							<a href='admin.php?mod=category&act=edit&id=".$d."' class='btn btn-xs btn-default' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_1']}'><i class='fa fa-pencil'></i></a>
							<a class='btn btn-xs btn-danger alertdel' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>
						</div>\n
					</div>\n";
				}
			)
		);
		$joinquery = "FROM category AS c JOIN category_description AS cd ON (cd.id_category = c.id_category)";
		$extrawhere = "cd.id_language = '1'";
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns, $joinquery, $extrawhere));
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman add kategori.
	 *
	 * This function is used to display and process add category page.
	 *
	*/
	public function addnew()
	{
		if (!$this->auth($_SESSION['leveluser'], 'category', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($_SESSION['leveluser'] == '1' OR $_SESSION['leveluser'] == '2') {
				$active = "Y";
			} else {
				$active = "N";
			}
			$category = array(
				'id_parent' => $this->postring->valid($_POST['id_parent'], 'sql'),
				'seotitle' => $this->postring->seo_title($this->postring->valid($_POST['category'][1]['title'], 'xss')),
				'picture' => $_POST['picture'],
				'active' => $active
			);
			$query_category = $this->podb->insertInto('category')->values($category);
			$query_category->execute();
			$last_category = $this->podb->from('category')
				->orderBy('id_category DESC')
				->limit(1)
				->fetch();
			foreach ($_POST['category'] as $id_language => $value) {
				$category_description = array(
					'id_category' => $last_category['id_category'],
					'id_language' => $id_language,
					'title' => $this->postring->valid($value['title'], 'xss')
				);
				$query_category_description = $this->podb->insertInto('category_description')->values($category_description);
				$query_category_description->execute();
			}
			$this->poflash->success($GLOBALS['_']['category_message_1'], 'admin.php?mod=category');
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['category_addnew']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=category&act=addnew', 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-6">
								<?php
									$cats = $this->podb->from('category')
										->select('category_description.title')
										->leftJoin('category_description ON category_description.id_category = category.id_category')
										->where('category.id_parent', '0')
										->where('category_description.id_language', '1')
										->orderBy('category.id_category ASC')
										->fetchAll();
									echo $this->pohtml->inputSelectNoOpt(array('id' => 'id_parent', 'label' => $GLOBALS['_']['category_parent'], 'name' => 'id_parent', 'mandatory' => true));
								?>
									<option value="0">No Parent</option>
									<?php
									foreach($cats as $cat){
										echo $this->generate_select($cat['id_category'], $cat['title']);
									}
									echo $this->pohtml->inputSelectNoOptEnd();
									?>
							</div>
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['category_picture'], 'name' => 'picture', 'id' => 'picture'), $inputgroup = true, $inputgroupopt = array('href' => '../'.DIR_INC.'/js/filemanager/dialog.php?type=1&field_id=picture', 'id' => 'browse-file', 'class' => 'btn-success', 'options' => '', 'title' => $GLOBALS['_']['action_7'].' '.$GLOBALS['_']['category_picture']));?>
							</div>
						</div>
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
										<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['category_title_2'], 'name' => 'category['.$lang['id_language'].'][title]', 'id' => 'title-'.$lang['id_language'], 'mandatory' => true, 'options' => 'required'));?>
									</div>
									<?php $noctab++;} ?>
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit kategori.
	 *
	 * This function is used to display and process edit category page.
	 *
	*/
	public function edit()
	{
		if (!$this->auth($_SESSION['leveluser'], 'category', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($_SESSION['leveluser'] == '1' OR $_SESSION['leveluser'] == '2') {
				$active = $this->postring->valid($_POST['active'], 'xss');
			} else {
				$active = "N";
			}
			$category = array(
				'id_parent' => $this->postring->valid($_POST['id_parent'], 'sql'),
				'seotitle' => $this->postring->seo_title($this->postring->valid($_POST['category'][1]['title'], 'xss')),
				'picture' => $_POST['picture'],
				'active' => $active
			);
			$query_category = $this->podb->update('category')
				->set($category)
				->where('id_category', $this->postring->valid($_POST['id'], 'sql'));
			$query_category->execute();
			foreach ($_POST['category'] as $id_language => $value) {
				$othlang_category = $this->podb->from('category_description')
					->where('id_category', $this->postring->valid($_POST['id'], 'sql'))
					->where('id_language', $id_language)
					->count();
				if ($othlang_category > 0) {
					$category_description = array(
						'title' => $this->postring->valid($value['title'], 'xss')
					);
					$query_category_description = $this->podb->update('category_description')
						->set($category_description)
						->where('id_category_description', $this->postring->valid($value['id'], 'sql'));
				} else {
					$category_description = array(
						'id_category' => $this->postring->valid($_POST['id'], 'sql'),
						'id_language' => $id_language,
						'title' => $this->postring->valid($value['title'], 'xss')
					);
					$query_category_description = $this->podb->insertInto('category_description')->values($category_description);
				}
				$query_category_description->execute();
			}
			$this->poflash->success($GLOBALS['_']['category_message_2'], 'admin.php?mod=category');
		}
		$id = $this->postring->valid($_GET['id'], 'sql');
		$current_category = $this->podb->from('category')
			->select('category_description.title')
			->leftJoin('category_description ON category_description.id_category = category.id_category')
			->where('category.id_category', $id)
			->limit(1)
			->fetch();
		if (empty($current_category)) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['category_edit']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=category&act=edit&id='.$current_category['id_category'], 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'id', 'value' => $current_category['id_category']));?>
						<div class="row">
							<div class="col-md-6">
								<?php
									$selcats = $this->podb->from('category')
										->select('category_description.title')
										->leftJoin('category_description ON category_description.id_category = category.id_category')
										->where('category.id_category', $current_category['id_parent'])
										->where('category_description.id_language', '1')
										->fetch();
									$cats = $this->podb->from('category')
										->select('category_description.title')
										->leftJoin('category_description ON category_description.id_category = category.id_category')
										->where('category.id_parent', '0')
										->where('category_description.id_language', '1')
										->orderBy('category.id_category ASC')
										->fetchAll();
									echo $this->pohtml->inputSelectNoOpt(array('id' => 'id_parent', 'label' => $GLOBALS['_']['category_parent'], 'name' => 'id_parent', 'mandatory' => true));
								?>
									<?php if (!empty($selcats)) { ?>
									<option value="<?=$selcats['id_category'];?>"><?=$selcats['title'];?></option>
									<?php } ?>
									<option value="0">No Parent</option>
									<?php
									foreach($cats as $cat){
										echo $this->generate_select($cat['id_category'], $cat['title']);
									}
									echo $this->pohtml->inputSelectNoOptEnd();
									?>
							</div>
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['category_picture'], 'name' => 'picture', 'id' => 'picture', 'value' => $current_category['picture']), $inputgroup = true, $inputgroupopt = array('href' => '../'.DIR_INC.'/js/filemanager/dialog.php?type=1&field_id=picture', 'id' => 'browse-file', 'class' => 'btn-success', 'options' => '', 'title' => $GLOBALS['_']['action_7'].' '.$GLOBALS['_']['category_picture']));?>
							</div>
						</div>
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
										$catlang = $this->podb->from('category_description')
											->where('category_description.id_category', $current_category['id_category'])
											->where('category_description.id_language', $lang['id_language'])
											->fetch();
										?>
										<?=$this->pohtml->inputHidden(array('name' => 'category['.$lang['id_language'].'][id]', 'value' => $catlang['id_category_description']));?>
										<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['category_title_2'], 'name' => 'category['.$lang['id_language'].'][title]', 'id' => 'title-'.$lang['id_language'], 'value' => $catlang['title'], 'mandatory' => true, 'options' => 'required'));?>
									</div>
									<?php $noctab++;} ?>
								</div>
								<?php
									if ($current_category['active'] == 'N') {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => '', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => 'checked', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['category_active'], 'mandatory' => true), $radioitem, $inline = true);
									} else {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => 'checked', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => '', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['category_active'], 'mandatory' => true), $radioitem, $inline = true);
									}
								?>
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus kategori.
	 *
	 * This function is used to display and process delete category page.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'category', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$query_desc = $this->podb->deleteFrom('category_description')->where('id_category', $this->postring->valid($_POST['id'], 'sql'));
			$query_desc->execute();
			$query_cat = $this->podb->deleteFrom('category')->where('id_category', $this->postring->valid($_POST['id'], 'sql'));
			$query_cat->execute();
			$this->poflash->success($GLOBALS['_']['category_message_3'], 'admin.php?mod=category');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi kategori.
	 *
	 * This function is used to display and process multi delete category page.
	 *
	*/
	public function multidelete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'category', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					$query_desc = $this->podb->deleteFrom('category_description')->where('id_category', $this->postring->valid($item['deldata'], 'sql'));
					$query_desc->execute();
					$query_cat = $this->podb->deleteFrom('category')->where('id_category', $this->postring->valid($item['deldata'], 'sql'));
					$query_cat->execute();
				}
				$this->poflash->success($GLOBALS['_']['category_message_3'], 'admin.php?mod=category');
			} else {
				$this->poflash->error($GLOBALS['_']['category_message_6'], 'admin.php?mod=category');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menggenerate option pada kotak select.
	 *
	 * This function use for generate option in select box.
	 *
	*/
	public function generate_select($id, $label)
	{
		$html = "<option value=\"{$id}\" style=\"font-weight:bold;\">{$label}</option>";
		$html .= $this->generate_option($id, $label, "20px");
		return ($html);
	}

	/**
	 * Fungsi ini digunakan untuk menggenerate option pada kotak select.
	 *
	 * This function use for generate option in select box.
	 *
	*/
	public function generate_option($id, $label, $exp)
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
			$i++;
			foreach ($catfuns as $catfun) {
				$explus = $exp + 20;
				$child = $this->generate_option($catfun['id_category'], $catfun['title'], $explus."px");
				$html .= "\n\t".$indent."";
				if ($child) {
					$i--;
					$html .= "<option value='".$catfun['id_category']."' style='margin-left:".$exp.";'>";
					$html .= $catfun['title'];
					$html .= $child;
					$html .= "\n\t".$indent."";
				} else {
					$html .= "<option value='".$catfun['id_category']."' style='margin-left:".$exp.";'>";
					$html .= $catfun['title'];
				}
				$html .= "</option>";
			}
		}
		return ($html);
	}

}