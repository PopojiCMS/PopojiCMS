<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_theme.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman tema.
 * This is a php file for handling admin process for theme page.
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

class Theme extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index tema.
	 *
	 * This function use for index theme page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'theme', 'read')) {
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
			<?php
				$themes = $this->podb->from('theme')->orderBy('id_theme desc')->fetchAll();
				foreach($themes as $theme){
			?>
				<div class="col-md-4">
					<div class="widget">
						<div class="theme_box" style="background-image:url('../<?=DIR_CON;?>/themes/<?=$theme['folder'];?>/preview.jpg');">
							<h3><?=$theme['title'];?></h3>
							<span>@<?=$theme['author'];?></span>
							<?=($theme['active'] == 'Y' ? '<label class="label label-info">'.$GLOBALS['_']['theme_active'].'</label>' : '<label class="label label-warning">'.$GLOBALS['_']['theme_notactive'].'</label>');?>
							<ul>
								<?php if (file_exists('../'.DIR_CON.'/themes/'.$theme['folder'].'/config/config.php')) { ?>
								<li><a href="admin.php?mod=theme&act=config&folder=<?=$theme['folder'];?>" data-toggle="tooltip" title="<?=$GLOBALS['_']['action_1'];?>"><i class="fa fa-pencil bg-info"></i></a></li>
								<?php } else { ?>
								<li><a href="admin.php?mod=theme&act=edit&folder=<?=$theme['folder'];?>&id=index.php" data-toggle="tooltip" title="<?=$GLOBALS['_']['action_1'];?>"><i class="fa fa-pencil bg-info"></i></a></li>
								<?php } ?>
								<li><a href="route.php?mod=theme&act=active&id=<?=$theme['id_theme'];?>" data-toggle="tooltip" title="<?=$GLOBALS['_']['action_8'];?>"><i class="fa fa-eye bg-warning"></i></a></li>
								<li><a href="javascript:void(0)" class="alertdel" id="<?=$theme['id_theme'];?>" data-toggle="tooltip" title="<?=$GLOBALS['_']['action_2'];?>"><i class="fa fa-times bg-danger"></i></a></li>
							</ul>
						</div><!-- Admin Follow -->
					</div><!-- Widget -->
				</div>
			<?php } ?>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('theme');?>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman tambah tema.
	 *
	 * This function is used to display and process add theme page.
	 *
	*/
	public function addnew()
	{
		if (!$this->auth($_SESSION['leveluser'], 'theme', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if (!empty($_FILES['fupload']['tmp_name'])) {
				$exp = explode('.', $_FILES['fupload']['name']);
				$themeName = $this->postring->seo_title($exp[0]).'-'.rand(000000,999999).'-popoji.'.$exp[1];
				if (in_array($exp[1], array('zip'))) {
					move_uploaded_file($_FILES['fupload']['tmp_name'], '../'.DIR_CON.'/uploads/'.$themeName);
					if (file_exists('../'.DIR_CON.'/themes/'.$this->postring->valid($_POST['folder'], 'xss'))) {
						unlink('../'.DIR_CON.'/uploads/'.$themeName);
						$this->poflash->error($GLOBALS['_']['theme_message_4'], 'admin.php?mod=theme');
					} else {
						$archive = new PoPclZip('../'.DIR_CON.'/uploads/'.$themeName);
						if ($archive->extract(PCLZIP_OPT_PATH, '../'.DIR_CON.'/themes/'.$this->postring->valid($_POST['folder'], 'xss')) == 0) {
							unlink('../'.DIR_CON.'/uploads/'.$themeName);
							$this->poflash->error($GLOBALS['_']['theme_message_4'], 'admin.php?mod=theme');
						}
						$data = array(
							'title' => $this->postring->valid($_POST['title'], 'xss'),
							'author' => $this->postring->valid($_POST['author'], 'xss'),
							'folder' => $this->postring->valid($_POST['folder'], 'xss')
						);
						$query_theme = $this->podb->insertInto('theme')->values($data);
						$query_theme->execute();
						unlink('../'.DIR_CON.'/uploads/'.$themeName);
						if (file_exists('../'.DIR_CON.'/themes/'.$this->postring->valid($_POST['folder'], 'xss').'/config/install.php')) {
							$this->poflash->success($GLOBALS['_']['theme_message_1'], 'admin.php?mod=theme&act=install&folder='.$this->postring->valid($_POST['folder'], 'xss'));
						} else {
							$this->poflash->success($GLOBALS['_']['theme_message_1'], 'admin.php?mod=theme');
						}
					}
				} else {
					$this->poflash->error($GLOBALS['_']['theme_message_4'], 'admin.php?mod=theme');
				}
			} else {
				if (file_exists('../'.DIR_CON.'/themes/'.$this->postring->valid($_POST['folder'], 'xss'))) {
					$this->poflash->error($GLOBALS['_']['theme_message_4'], 'admin.php?mod=theme');
				} else {
					$archive = new PoPclZip('../'.DIR_CON.'/component/theme/blank-theme.zip');
					if ($archive->extract(PCLZIP_OPT_PATH, '../'.DIR_CON.'/themes/'.$this->postring->valid($_POST['folder'], 'xss')) == 0) {
						$this->poflash->error($GLOBALS['_']['theme_message_4'], 'admin.php?mod=theme');
					}
					$data = array(
						'title' => $this->postring->valid($_POST['title'], 'xss'),
						'author' => $this->postring->valid($_POST['author'], 'xss'),
						'folder' => $this->postring->valid($_POST['folder'], 'xss')
					);
					$query_theme = $this->podb->insertInto('theme')->values($data);
					$query_theme->execute();
					$this->poflash->success($GLOBALS['_']['theme_message_1'], 'admin.php?mod=theme');
				}
			}
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['theme_addnew']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=theme&act=addnew', 'enctype' => true, 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-12">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['theme_title'], 'name' => 'title', 'id' => 'title', 'mandatory' => true, 'options' => 'required'));?>
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['theme_author'], 'name' => 'author', 'id' => 'author', 'mandatory' => true, 'options' => 'required'));?>
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['theme_folder'], 'name' => 'folder', 'id' => 'folder', 'mandatory' => true, 'options' => 'required'));?>
								<div class="form-group">
									<label><?=$GLOBALS['_']['theme_file'];?> <i>(.zip)</i> <span class="text-danger">*</span></label>
									<input name="fupload" id="fupload" type="file" /><br />
									<p><i><span class="text-danger">*</span> <?=$GLOBALS['_']['theme_file_help'];?></i></p>
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit tema.
	 *
	 * This function is used to display and process edit theme page.
	 *
	*/
	public function edit()
	{
		if (!$this->auth($_SESSION['leveluser'], 'theme', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if (file_exists('../'.DIR_CON.'/themes/'.$this->postring->valid($_POST['folder'], 'xss').'/'.$this->postring->valid($_POST['file'], 'xss'))){
				$data = $_POST['code_content'];
				$data = str_replace("textareapopojicms", "textarea", $data);
				$newdata = stripslashes($data);
				if ($newdata != ''){
					$fw = fopen('../'.DIR_CON.'/themes/'.$this->postring->valid($_POST['folder'], 'xss').'/'.$this->postring->valid($_POST['file'], 'xss'), 'w') or die('Could not open file!');
					$fb = fwrite($fw,$newdata) or die('Could not write to file');
					fclose($fw);
				}
			}
			$this->poflash->success($GLOBALS['_']['theme_message_2'], 'admin.php?mod=theme&act=edit&folder='.$this->postring->valid($_POST['folder'], 'xss').'&id='.$this->postring->valid($_POST['file'], 'xss'));
		}
		if (file_exists('../'.DIR_CON.'/themes/'.$this->postring->valid($_GET['folder'], 'xss').'/'.$this->postring->valid($_GET['id'], 'xss'))) {
			$fh = fopen('../'.DIR_CON.'/themes/'.$this->postring->valid($_GET['folder'], 'xss').'/'.$this->postring->valid($_GET['id'], 'xss'), "r") or die("Could not open file!");
			$data = fread($fh, filesize('../'.DIR_CON.'/themes/'.$this->postring->valid($_GET['folder'], 'xss').'/'.$this->postring->valid($_GET['id'], 'xss'))) or die("Could not read file!");
			$data = str_replace("textarea", "textareapopojicms", $data);
			fclose($fh);
		?>
		<style type="text/css">
			.CodeMirror { height: 800px; }
			.CodeMirror-matchingtag { background: #4d4d4d; }
			.breakpoints { width: .8em; }
			.breakpoint { color: #3498db; }
		</style>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['theme_edit'].' - '.$this->postring->valid($_GET['id'], 'xss'));?>
					<div class="pull-right" style="margin-top:-70px;">
						<div class="btn-group">
							<button type="button" class="btn btn-sm btn-primary"><i class="fa fa-file-o"></i>&nbsp;&nbsp;<?=$GLOBALS['_']['theme_btn_another'];?></button>
							<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="caret"></span><span class="sr-only">&nbsp;</span>
							</button>
							<ul class="dropdown-menu">
							<?php
								$theme_dir = new PoDirectory();
								$theme_files = $theme_dir->listDir('../'.DIR_CON.'/themes/'.$this->postring->valid($_GET['folder'], 'xss').'/');
								foreach($theme_files as $theme_file) {
									if ($theme_file != 'index.html' && $theme_file != 'preview.jpg') {
										if (!is_dir('../'.DIR_CON.'/themes/'.$this->postring->valid($_GET['folder'], 'xss').'/'.$theme_file)) {
							?>
								<li><a href="admin.php?mod=theme&act=edit&folder=<?=$this->postring->valid($_GET['folder'], 'xss');?>&id=<?=$theme_file;?>"><i class="fa fa-file-o"></i>&nbsp;&nbsp;<?=$theme_file;?></a></li>
							<?php }}} ?>
							</ul>
						</div>
						<a href="javascript:void(0)" class="btn btn-sm btn-info shortcut-key-btn" data-toggle="tooltip" title="<?=$GLOBALS['_']['theme_btn_shortcut'];?>"><i class="fa fa-keyboard-o"></i></a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 shortcut-key" style="display:none;">
					<div class="panel panel-default">
						<div class="panel-body">
							<ul>
								<li>Put the cursor on or inside a pair of tags to highlight them. Press <strong>Ctrl+J</strong> to jump to the tag that matches the one under the cursor.</li>
								<li>Click the line-number gutter to add or remove <i>breakpoints</i>.</li>
								<li>Press <strong>F11</strong> when cursor is in the editor to toggle full screen editing. <strong>Esc</strong> can also be used to <i>exit</i> full screen editing.</li>
								<li>Press <strong>Ctrl+Space</strong> to activate completion.</li>
								<li>Demonstration of primitive search/replace functionality. The keybindings (which can be overridden by custom keymaps) are :
									<ul>
										<li><strong>Ctrl+F / Cmd-F</strong> for Start searching</li>
										<li><strong>Ctrl+G / Cmd+G</strong> for Find next</li>
										<li><strong>Shift+Ctrl+G / Shift+Cmd+G</strong> for Find previous</li>
										<li><strong>Shift+Ctrl+F / Cmd+Option+F</strong> for Replace</li>
										<li><strong>Shift+Ctrl+R / Shift+Cmd+Option+F</strong> for Replace all</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=theme&act=edit', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'folder', 'value' => $this->postring->valid($_GET['folder'], 'xss')));?>
						<?=$this->pohtml->inputHidden(array('name' => 'file', 'value' => $this->postring->valid($_GET['id'], 'xss')));?>
						<div class="form-group">
							<textarea name="code_content" id="pocodemirror" style="width:100%; height:800px;"><?=$data;?></textarea>
						</div>
					<?=$this->pohtml->formAction();?>
				</div>
			</div>
		</div>
		<?php
		}
	}

	/**
	 * Fungsi ini digunakan untuk mengaktifkan tema.
	 *
	 * This function use for theme activated.
	 *
	*/
	public function active()
	{
		if (!$this->auth($_SESSION['leveluser'], 'theme', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_GET)) {
			$query_alltheme = $this->podb->update('theme')->set(array('active' => 'N'));
			$query_alltheme->execute();
			$query_theme = $this->podb->update('theme')
				->set(array('active' => 'Y'))
				->where('id_theme', $this->postring->valid($_GET['id'], 'sql'));
			$query_theme->execute();
			$this->poflash->success($GLOBALS['_']['theme_message_7'], 'admin.php?mod=theme');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus tema.
	 *
	 * This function is used to display and process delete theme page.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'theme', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$theme = $this->podb->from('theme')->where('id_theme', $this->postring->valid($_POST['id'], 'sql'))->limit(1)->fetch();
			if (file_exists('../'.DIR_CON.'/themes/'.$theme['folder'].'/config/uninstall.php')) {
				header('location:admin.php?mod=theme&act=uninstall&folder='.$theme['folder']);
			} else {
				$delete_dir = new PoDirectory();
				$delete_folder = $delete_dir->deleteDir("../".DIR_CON."/themes/".$theme['folder']);
				if ($delete_folder) {
					$query = $this->podb->deleteFrom('theme')->where('id_theme', $this->postring->valid($_POST['id'], 'sql'));
					$query->execute();
					$this->poflash->success($GLOBALS['_']['theme_message_3'], 'admin.php?mod=theme');
				} else {
					$this->poflash->error($GLOBALS['_']['theme_message_6'], 'admin.php?mod=theme');
				}
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman install tema.
	 *
	 * This function is used to display and process install theme page.
	 *
	*/
	public function install()
	{
		if (!$this->auth($_SESSION['leveluser'], 'theme', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/themes/'.$_GET['folder'].'/config/install.php';
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman uninstall tema.
	 *
	 * This function is used to display and process uninstall theme page.
	 *
	*/
	public function uninstall()
	{
		if (!$this->auth($_SESSION['leveluser'], 'theme', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/themes/'.$_GET['folder'].'/config/uninstall.php';
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman config tema.
	 *
	 * This function is used to display and process config theme page.
	 *
	*/
	public function config()
	{
		if (!$this->auth($_SESSION['leveluser'], 'theme', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		include_once '../'.DIR_CON.'/themes/'.$_GET['folder'].'/config/config.php';
	}

}