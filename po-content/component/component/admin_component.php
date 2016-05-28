<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_component.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman komponen.
 * This is a php file for handling admin process for component page.
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

class Component extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index komponen.
	 *
	 * This function use for index component page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'component', 'read')) {
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
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=component&act=multidelete', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => 'Id', 'options' => 'style="width:30px;"'),
								array('title' => $GLOBALS['_']['component_title'], 'options' => ''),
								array('title' => $GLOBALS['_']['component_type'], 'options' => 'style="width:80px;"'),
								array('title' => $GLOBALS['_']['component_date'], 'options' => 'style="width:150px;"'),
								array('title' => $GLOBALS['_']['component_active'], 'options' => 'style="width:100px;"'),
								array('title' => $GLOBALS['_']['component_action'], 'options' => 'class="no-sort" style="width:50px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-component', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = false);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('component');?>
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
		if (!$this->auth($_SESSION['leveluser'], 'component', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'component';
		$primarykey = 'id_component';
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
			array('db' => 'component', 'dt' => '2', 'field' => 'component'),
			array('db' => 'type', 'dt' => '3', 'field' => 'type',
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>".$d."</div>\n";
				}
			),
			array('db' => 'datetime', 'dt' => '4', 'field' => 'datetime',
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>".$this->time_ago(strtotime($d))."</div>\n";
				}
			),
			array('db' => 'active', 'dt' => '5', 'field' => 'active',
				'formatter' => function($d, $row, $i){
					if ($d == 'N') {
						return "<div class='text-center'>".$GLOBALS['_']['component_status_uninstall']."</div>\n";
					} else {
						return "<div class='text-center'>".$GLOBALS['_']['component_status_install']."</div>\n";
					}
				}
			),
			array('db' => $primarykey, 'dt' => '6', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					if ($row['active'] == 'N') {
						if (file_exists('../'.DIR_CON.'/component/'.$row['component'].'/install.php')) {
							$tblinstall = "<a href='admin.php?mod=component&act=install&folder=".$row['component']."&id=".$d."' class='btn btn-xs btn-success' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['component_install']}'><i class='fa fa-plug'></i></a>";
						} else {
							$tblinstall = "<a href='route.php?mod=component&act=install&folder=".$row['component']."&id=".$d."' class='btn btn-xs btn-success' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['component_install']}'><i class='fa fa-plug'></i></a>";
						}
					} else {
						if (file_exists('../'.DIR_CON.'/component/'.$row['component'].'/uninstall.php')) {
							$tblinstall = "<a href='admin.php?mod=component&act=uninstall&folder=".$row['component']."&id=".$d."' class='btn btn-xs btn-warning' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['component_uninstall']}'><i class='fa fa-trash-o'></i></a>";
						} else {
							$tblinstall = "<a href='route.php?mod=component&act=uninstall&folder=".$row['component']."&id=".$d."' class='btn btn-xs btn-warning' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['component_uninstall']}'><i class='fa fa-trash-o'></i></a>";
						}
					}
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							".$tblinstall."
							<a class='btn btn-xs btn-danger alertdel' id='".$d."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>
						</div>\n
					</div>\n";
				}
			)
		);
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns));
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman tambah komponen.
	 *
	 * This function is used to display and process add component page.
	 *
	*/
	public function addnew()
	{
		if (!$this->auth($_SESSION['leveluser'], 'component', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if (!empty($_FILES['fupload']['tmp_name'])) {
				$exp = explode('.', $_FILES['fupload']['name']);
				$componentName = $this->postring->seo_title($exp[0]).'-'.rand(000000,999999).'-popoji.'.$exp[1];
				$componentType = $this->postring->valid($_POST['type'], 'xss');
				if ($componentType == 'component') {
					$folderinstall = 'component';
				} else {
					$folderinstall = 'widget';
				}
				if (in_array($exp[1], array('zip'))) {
					move_uploaded_file($_FILES['fupload']['tmp_name'], '../'.DIR_CON.'/uploads/'.$componentName);
					if (file_exists('../'.DIR_CON.'/'.$folderinstall.'/'.strtolower($this->postring->valid($_POST['component'], 'xss')))) {
						unlink('../'.DIR_CON.'/uploads/'.$componentName);
						$this->poflash->error($GLOBALS['_']['component_message_3'], 'admin.php?mod=component');
					} else {
						$archive = new PoPclZip('../'.DIR_CON.'/uploads/'.$componentName);
						if ($archive->extract(PCLZIP_OPT_PATH, '../'.DIR_CON.'/'.$folderinstall.'/'.strtolower($this->postring->valid($_POST['component'], 'xss'))) == 0) {
							unlink('../'.DIR_CON.'/uploads/'.$componentName);
							$this->poflash->error($GLOBALS['_']['component_message_3'], 'admin.php?mod=component');
						}
						$data = array(
							'component' => strtolower($this->postring->valid($_POST['component'], 'xss')),
							'type' => $this->postring->valid($_POST['type'], 'xss'),
							'datetime' => date('Y-m-d H:i:s')
						);
						$query_component = $this->podb->insertInto('component')->values($data);
						$query_component->execute();
						unlink('../'.DIR_CON.'/uploads/'.$componentName);
						if (file_exists('../'.DIR_CON.'/'.$folderinstall.'/'.strtolower($this->postring->valid($_POST['component'], 'xss')).'/install.php')) {
							$this->poflash->success($GLOBALS['_']['component_message_1'], 'admin.php?mod=component&act=install&folder='.strtolower($this->postring->valid($_POST['component'], 'xss')));
						} else {
							$this->poflash->success($GLOBALS['_']['component_message_1'], 'admin.php?mod=component');
						}
					}
				} else {
					$this->poflash->error($GLOBALS['_']['component_message_3'], 'admin.php?mod=component');
				}
			} else {
				$this->poflash->error($GLOBALS['_']['component_message_3'], 'admin.php?mod=component');
			}
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['component_addnew']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=component&act=addnew', 'enctype' => true, 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['component_title'], 'name' => 'component', 'id' => 'component', 'mandatory' => true, 'options' => 'required'));?>
									</div>
									<div class="col-md-6">
										<?php
											$item = array();
											$item[] = array('value' => 'component', 'title' => 'Component');
											$item[] = array('value' => 'widget', 'title' => 'Widget');
										?>
										<?=$this->pohtml->inputSelect(array('id' => 'type', 'label' => $GLOBALS['_']['component_type'], 'name' => 'type', 'mandatory' => true), $item);?>
									</div>
									<div class="col-md-12">
										<?=$this->pohtml->inputText(array('type' => 'file', 'label' => $GLOBALS['_']['component_browse_file'], 'name' => 'fupload', 'id' => 'fupload', 'mandatory' => true));?>
									</div>
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus komponen.
	 *
	 * This function is used to display and process delete component page.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'component', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$component = $this->podb->from('component')->where('id_component', $this->postring->valid($_POST['id'], 'sql'))->limit(1)->fetch();
			$componentType = $component['type'];
			if ($componentType == 'component') {
				$folderinstall = 'component';
			} else {
				$folderinstall = 'widget';
			}
			if (file_exists('../'.DIR_CON.'/'.$folderinstall.'/'.$component['component'].'/uninstall.php')) {
				header('location:admin.php?mod=component&act=uninstall&folder='.$component['component']);
			} else {
				$delete_dir = new PoDirectory();
				if ($component['active'] == 'Y') {
					$delete_folder = $delete_dir->deleteDir('../'.DIR_CON.'/'.$folderinstall.'/'.$component['component']);
				} else {
					$delete_folder = $delete_dir->deleteDir('../'.DIR_CON.'/'.$folderinstall.'/_'.$component['component']);
				}
				if ($delete_folder) {
					$query = $this->podb->deleteFrom('component')->where('id_component', $this->postring->valid($_POST['id'], 'sql'));
					$query->execute();
					$this->poflash->success($GLOBALS['_']['component_message_2'], 'admin.php?mod=component');
				} else {
					$this->poflash->error($GLOBALS['_']['component_message_4'], 'admin.php?mod=component');
				}
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman install komponen.
	 *
	 * This function is used to display and process install component page.
	 *
	*/
	public function install()
	{
		if (!$this->auth($_SESSION['leveluser'], 'component', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		$component = $this->podb->from('component')->where('id_component', $this->postring->valid($_GET['id'], 'sql'))->limit(1)->fetch();
		$componentType = $component['type'];
		if ($componentType == 'component') {
			$folderinstall = 'component';
		} else {
			$folderinstall = 'widget';
		}
		if (file_exists('../'.DIR_CON.'/'.$folderinstall.'/'.$_GET['folder'].'/install.php')) {
			include_once '../'.DIR_CON.'/'.$folderinstall.'/'.$_GET['folder'].'/install.php';
		} else {
			$dir_ori = realpath(dirname(__FILE__));
			$dir_exp = explode('component', $dir_ori);
			$dir_ren = reset($dir_exp).$folderinstall.DIRECTORY_SEPARATOR.'_'.$_GET['folder'];
			$dir_new = reset($dir_exp).$folderinstall.DIRECTORY_SEPARATOR.$_GET['folder'];
			if (rename($dir_ren, $dir_new)) {
				$query_component = $this->podb->update('component')
					->set(array('active' => 'Y'))
					->where('id_component', $this->postring->valid($_GET['id'], 'sql'));
				$query_component->execute();
				$this->poflash->success($GLOBALS['_']['component_message_5'], 'admin.php?mod=component');
			} else {
				$this->poflash->error($GLOBALS['_']['component_message_6'], 'admin.php?mod=component');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman uninstall komponen.
	 *
	 * This function is used to display and process uninstall component page.
	 *
	*/
	public function uninstall()
	{
		if (!$this->auth($_SESSION['leveluser'], 'component', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		$component = $this->podb->from('component')->where('id_component', $this->postring->valid($_GET['id'], 'sql'))->limit(1)->fetch();
		$componentType = $component['type'];
		if ($componentType == 'component') {
			$folderinstall = 'component';
		} else {
			$folderinstall = 'widget';
		}
		if (file_exists('../'.DIR_CON.'/'.$folderinstall.'/'.$_GET['folder'].'/uninstall.php')) {
			include_once '../'.DIR_CON.'/'.$folderinstall.'/'.$_GET['folder'].'/uninstall.php';
		} else {
			$dir_ori = realpath(dirname(__FILE__));
			$dir_exp = explode('component', $dir_ori);
			$dir_ren = reset($dir_exp).$folderinstall.DIRECTORY_SEPARATOR.$_GET['folder'];
			$dir_new = reset($dir_exp).$folderinstall.DIRECTORY_SEPARATOR.'_'.$_GET['folder'];
			if (rename($dir_ren, $dir_new)) {
				$query_component = $this->podb->update('component')
					->set(array('active' => 'N'))
					->where('id_component', $this->postring->valid($_GET['id'], 'sql'));
				$query_component->execute();
				$this->poflash->success($GLOBALS['_']['component_message_7'], 'admin.php?mod=component');
			} else {
				$this->poflash->error($GLOBALS['_']['component_message_8'], 'admin.php?mod=component');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan string tanggal.
	 *
	 * This function is used to display date string.
	 *
	*/
	public function time_ago($tm,$rcs = 0)
	{
		$cur_tm = time(); $dif = $cur_tm-$tm;
		$pds = array('second','minute','hour','day','week','month','year','decade');
		$lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
		for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

		$no = floor($no); if($no <> 1) $pds[$v] .='s';
		$x=sprintf(sprintf('%%d %s ago', $pds[$v]), $no);
		if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
		return $x;
	}

}