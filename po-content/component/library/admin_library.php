<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_library.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman pustaka.
 * This is a php file for handling admin process for library page.
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

class Library extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index pustaka.
	 *
	 * This function use for index library page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'library', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['component_name'], '<a href="../'.DIR_INC.'/js/filemanager/dialog.php?type=0&editor=mce_0" class="btn btn-success btn-sm btn-title pull-right" id="browse-file"><i class="fa fa-image"></i> '.$GLOBALS['_']['addnew'].'</a>');?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=library&act=multidelete', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
						<?php
							$columns = array(
								array('title' => $GLOBALS['_']['library_name'], 'options' => ''),
								array('title' => $GLOBALS['_']['library_type'], 'options' => 'class="no-sort" style="width:180px;"'),
								array('title' => $GLOBALS['_']['library_size'], 'options' => 'class="no-sort" style="width:80px;"'),
								array('title' => $GLOBALS['_']['library_date'], 'options' => 'class="no-sort" style="width:120px;"'),
								array('title' => $GLOBALS['_']['library_action'], 'options' => 'class="no-sort" style="width:50px;"')
							);
						?>
						<?=$this->pohtml->createTable(array('id' => 'table-library', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
					<?=$this->pohtml->formEnd();?>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('library');?>
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
		if (!$this->auth($_SESSION['leveluser'], 'library', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$get_files = new PoDirectory();
		$begin_files = $get_files->listDir('../'.DIR_CON.'/uploads/');
		$files = $get_files->listDir('../'.DIR_CON.'/uploads/');
		$cfiles = count($begin_files);
		if (isset($_POST['search']['value'])) {
			if ($_POST['search']['value'] != '') {
				$files = preg_grep('/'.$this->postring->valid($_POST['search']['value'], 'xss').'/i', $files);
				$cfiles = count($files);
			}
		}
		if (isset($_POST['start']) && $_POST['length'] != -1) {
            $start = intval($_POST['start']);
			$length = intval($_POST['length']);
			$files = array_slice($files, $start, $length);
        }
		if (isset($_POST['order'][0]['dir'])) {
			if ($_POST['order'][0]['dir'] != '') {
				if ($_POST['order'][0]['dir'] == 'asc') {
					sort($files);
				} else {
					rsort($files);
				}
			}
		}
		$out = array(
			"draw" => (isset($_POST['draw']) ? intval($_POST['draw']) : '1'),
			"recordsTotal" => count($begin_files),
			"recordsFiltered" => $cfiles,
			"data" => array()
		);
		$row = array();
		$no = 1;
		foreach($files as $file){
			$row = array();
			if ($file != 'index.html' && $file != 'medium') {
				$row[] = '<div class="text-center">
					<input type="checkbox" id="titleCheckdel" />
					<input type="hidden" class="deldata" name="item['.$no.'][deldata]" value="'.$file.'" disabled />
				</div>';
				$row[] = '<a href="../'.DIR_CON.'/uploads/'.$file.'" target="_blank">'.utf8_encode($file).'</a>';
				$row[] = mime_content_type('../'.DIR_CON.'/uploads/'.$file);
				$row[] = $this->bytes_to_string(filesize('../'.DIR_CON.'/uploads/'.$file));
				$row[] = $this->time_ago(filemtime('../'.DIR_CON.'/uploads/'.$file));
				$row[] = '<div class="text-center">
					<div class="btn-group btn-group-xs">
						<a class="btn btn-xs btn-danger alertdel" id="'.$file.'" data-toggle="tooltip" title="'.$GLOBALS['_']['action_2'].'"><i class="fa fa-times"></i></a>
					</div>
				</div>';
				$out['data'][] = $row;
				$no++;
			}
		}
		echo json_encode($out);
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus pustaka.
	 *
	 * This function is used to display and process delete library page.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'library', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($this->postring->isImage('../'.DIR_CON.'/uploads/'.$_POST['id'])) {
				if (file_exists('../'.DIR_CON.'/thumbs/'.$_POST['id'])) {
					unlink('../'.DIR_CON.'/thumbs/'.$_POST['id']);
				}
				if (file_exists('../'.DIR_CON.'/uploads/medium/medium_'.$_POST['id'])) {
					unlink('../'.DIR_CON.'/uploads/medium/medium_'.$_POST['id']);
				}
				if (file_exists('../'.DIR_CON.'/uploads/'.$_POST['id'])) {
					unlink('../'.DIR_CON.'/uploads/'.$_POST['id']);
				}
			} else {
				if (file_exists('../'.DIR_CON.'/uploads/'.$_POST['id'])) {
					unlink('../'.DIR_CON.'/uploads/'.$_POST['id']);
				}
			}
			$this->poflash->success($GLOBALS['_']['library_message_2'], 'admin.php?mod=library');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi pustaka.
	 *
	 * This function is used to display and process multi delete library page.
	 *
	*/
	public function multidelete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'library', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					if ($this->postring->isImage('../'.DIR_CON.'/uploads/'.$item['deldata'])) {
						if (file_exists('../'.DIR_CON.'/thumbs/'.$item['deldata'])) {
							unlink('../'.DIR_CON.'/thumbs/'.$item['deldata']);
						}
						if (file_exists('../'.DIR_CON.'/uploads/medium/medium_'.$item['deldata'])) {
							unlink('../'.DIR_CON.'/uploads/medium/medium_'.$item['deldata']);
						}
						if (file_exists('../'.DIR_CON.'/uploads/'.$item['deldata'])) {
							unlink('../'.DIR_CON.'/uploads/'.$item['deldata']);
						}
					} else {
						if (file_exists('../'.DIR_CON.'/uploads/'.$item['deldata'])) {
							unlink('../'.DIR_CON.'/uploads/'.$item['deldata']);
						}
					}
				}
				$this->poflash->success($GLOBALS['_']['library_message_2'], 'admin.php?mod=library');
			} else {
				$this->poflash->error($GLOBALS['_']['library_message_4'], 'admin.php?mod=library');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan jumlah byte ke string.
	 *
	 * This function is used to display byte total to string.
	 *
	*/
	public function bytes_to_string($size, $precision = 0)
	{
		$sizes = array('YB', 'ZB', 'EB', 'PB', 'TB', 'GB', 'MB', 'KB', 'bytes');
		$total = count($sizes);
		while($total-- && $size > 1024) $size /= 1024;
		$return = round($size, $precision).' '.$sizes[$total];
		return $return;
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

	/**
	 * Fungsi ini digunakan untuk mengurutkan array.
	 *
	 * This function is used to array sorting.
	 *
	*/
	public function php_multisort($data, $keys)
	{
		foreach ($data as $key => $row)
		{
			foreach ($keys as $k)
			{
				$cols[$k['key']][$key] = $row[$k['key']];
			}
		}
		$idkeys = array_keys($data);
		$i=0;
		$sort = null;
		foreach ($keys as $k)
		{
			if($i>0){$sort.=',';}
			$sort.='$cols['.$k['key'].']';
			if(isset($k['sort'])){$sort.=',SORT_'.strtoupper($k['sort']);}
			if(isset($k['type'])){$sort.=',SORT_'.strtoupper($k['type']);}
			$i++;
		}
		$sort .= ',$idkeys';
		$sort = 'array_multisort('.$sort.');';
		eval($sort);
		foreach($idkeys as $idkey)
		{
			$result[$idkey]=$data[$idkey];
		}
		return $result;
	}

}