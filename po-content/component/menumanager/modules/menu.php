<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : menu.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses utama pada menu manager khusus di menu.
 * This is a php file that is used to handle the main process on the menu manager specialty of menu.
 *
*/

class Menu extends Menumanager
{

	/**
	 * Show menu manager
	*/
	public function menumanager()
	{
		$group_id = 1;
		if (isset($_GET['group_id'])) {
			$group_id = (int)$_GET['group_id'];
		}
		$cari_id = $this->num_row($group_id);
		if ($cari_id > 0){
			$menu = $this->get_menu($group_id);
			$data['menu_ul'] = '<ul id="easymm"></ul>';
			if ($menu) {
				include_once '../po-content/component/menumanager/includes/tree.php';
				$tree = new MenuTree;
				foreach ($menu as $row) {
					$tree->add_row(
						$row[MENU_ID],
						$row[MENU_PARENT],
						' id="menu-'.$row[MENU_ID].'" class="sortable"',
						$this->get_label($row)
					);
				}
				$data['menu_ul'] = $tree->generate_list('id="easymm"');
			}
			$data['group_id'] = $group_id;
			$data['group_title'] = $this->get_menu_group_title($group_id);
			$data['menu_groups'] = $this->get_menu_groups();
			$this->view('menu', $data);
		}else{
			$menu = $this->get_menu('1');
			$data['menu_ul'] = '<ul id="easymm"></ul>';
			if ($menu) {
				include '../po-content/component/menumanager/includes/tree.php';
				$tree = new MenuTree;
				foreach ($menu as $row) {
					$tree->add_row(
						$row[MENU_ID],
						$row[MENU_PARENT],
						' id="menu-'.$row[MENU_ID].'" class="sortable"',
						$this->get_label($row)
					);
				}
				$data['menu_ul'] = $tree->generate_list('id="easymm"');
			}
			$data['group_id'] = '1';
			$data['group_title'] = $this->get_menu_group_title('1');
			$data['menu_groups'] = $this->get_menu_groups();
			$this->view('menu', $data);
		}
	}

	/**
	 * Add menu action
	 * For use with ajax
	 * Return json data
	*/
	public function add()
	{
		if (isset($_POST['title'])) {
			$data = array(
				MENU_TITLE => trim($_POST['title']),
				MENU_URL => $_POST['url'],
				MENU_CLASS => $_POST['class'],
				MENU_ACTIVE => 'Y',
				MENU_GROUP => $_POST['group_id'],
				MENU_POSITION => $this->get_last_position($_POST['group_id']) + 1
			);
			if (!empty($data[MENU_TITLE])) {
				$query = $this->podb->insertInto(MENU_TABLE)->values($data);
				if ($query->execute()) {
					$sql = $this->podb->from(MENU_TABLE)
						->limit(1)
						->orderBy(MENU_ID.' DESC')
						->fetch();
					$data[MENU_ID] = $sql[MENU_ID];
					$response['status'] = 1;
					$li_id = 'menu-'.$data[MENU_ID];
					$response['li'] = '<li id="'.$li_id.'" class="sortable">'.$this->get_label($data).'</li>';
					$response['li_id'] = $li_id;
				} else {
					$response['status'] = 2;
					$response['msg'] = 'Add menu error.';
				}
			} else {
				$response['status'] = 3;
			}
			header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * Show edit menu form
	*/
	public function edit()
	{
		if (isset($_GET['id'])) {
			$id = (int)$_GET['id'];
			$data['row'] = $this->get_row($id);
			$this->view('menu_edit', $data);
		}
	}

	/**
	 * Save menu
	 * Action for edit menu
	 * return json data
	*/
	public function save()
	{
		if (isset($_POST['title'])) {
			$data = array(
				MENU_TITLE => trim($_POST['title']),
				MENU_ID => $_POST['menu_id'],
				MENU_URL => $_POST['url'],
				MENU_CLASS => $_POST['class'],
				MENU_ACTIVE => $_POST['active'],
				MENU_TARGET => $_POST['target']
			);
			if (!empty($data[MENU_TITLE])) {
				$query = $this->podb->update(MENU_TABLE)
					->set($data)
					->where(MENU_ID, $data[MENU_ID]);
				if ($query->execute()) {
					$response['status'] = 1;
					$d['title'] = $data[MENU_TITLE];
					$d['url'] = $data[MENU_URL];
					$d['klass'] = $data[MENU_CLASS]; //klass instead of class because of an error in js
					$d['active'] = $data[MENU_ACTIVE];
					$d['target'] = $data[MENU_TARGET];
					$response['menu'] = $d;
				} else {
					$response['status'] = 2;
					$response['msg'] = 'Edit menu error.';
				}
			} else {
				$response['status'] = 3;
			}
			header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * Delete menu action
	 * Also delete all submenus under current menu
	 * return json data
	*/
	public function delete()
	{
		if (isset($_POST['id'])) {
			$id = (int)$_POST['id'];
			$this->get_descendants($id);
			if (!empty($this->ids)) {
				$this->ids[] = $id;
				$query = $this->podb->deleteFrom(MENU_TABLE)->where(MENU_ID, $this->ids);
			} else {
				$query = $this->podb->deleteFrom(MENU_TABLE)->where(MENU_ID, array($id));
			}
			if ($query->execute()) {
				$response['success'] = true;
			} else {
				$response['success'] = false;
			}
			header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * Save menu position
	*/
	public function save_position()
	{
		if (isset($_POST['easymm'])) {
			$easymm = $_POST['easymm'];
			$this->update_position(0, $easymm);
		}
	}

	/**
	 * Recursive function for save menu position
	*/
	private function update_position($parent, $children) {
		$i = 1;
		foreach ($children as $k => $v) {
			$id = (int)$children[$k]['id'];
			$data = array(
				MENU_PARENT => $parent,
				MENU_POSITION => $i
			);
			$query = $this->podb->update(MENU_TABLE)
				->set($data)
				->where(MENU_ID, $id);
			$query->execute();
			if (isset($children[$k]['children'][0])) {
				$this->update_position($id, $children[$k]['children']);
			}
			$i++;
		}
	}

	/**
	 * Get items from menu table
	 *
	 * @param int $group_id
	 * @return array
	*/
	private function get_menu($group_id)
	{
		$sqls = $this->podb->from(MENU_TABLE)
			->where(MENU_GROUP, $group_id)
			->orderBy(array(MENU_PARENT, MENU_POSITION))
			->fetchAll();
		return $sqls;
	}

	/**
	 * Get one item from menu table
	 *
	 * @param unknown_type $id
	 * @return unknown
	*/
	private function get_row($id)
	{
		$sqls = $this->podb->from(MENU_TABLE)
			->where(MENU_ID, $id)
			->fetch();
		return $sqls;
	}

	private function num_row($id)
	{
		$sqls = $this->podb->from(MENUGROUP_TABLE)
			->where(MENUGROUP_ID, $id)
			->count();
		return $sqls;
	}

	/**
	 * Recursive method
	 * Get all descendant ids from current id
	 * save to $this->ids
	 *
	 * @param int $id
	*/
	private function get_descendants($id)
	{
		$sqls = $this->podb->from(MENU_TABLE)
			->where(MENU_PARENT, $id)
			->fetchAll();
		$csqls = $this->podb->from(MENU_TABLE)
			->where(MENU_PARENT, $id)
			->count();
		if ($csqls > 0) {
			foreach ($sqls as $sql) {
				$this->ids[] = $sql[MENU_ID];
				$this->get_descendants($sql[MENU_ID]);
			}
		}
	}

	/**
	 * Get the highest position number
	 *
	 * @param int $group_id
	 * @return string
	*/
	private function get_last_position($group_id)
	{
		$sqls = $this->podb->from(MENU_TABLE)
			->select(array('MAX('.MENU_POSITION.')'))
			->where(MENU_GROUP, $group_id)
			->limit(1)
			->fetch();
		$data = $sqls[MENU_ID];
		return $data;
	}

	/**
	 * Get all items in menu group table
	 *
	 * @return array
	*/
	private function get_menu_groups()
	{
		$sqls = $this->podb->from(MENUGROUP_TABLE)
			->select(array(MENUGROUP_ID, MENUGROUP_TITLE))
			->fetchAll();
		$data = array();
		foreach($sqls as $sql) {
			$data[] = $sql;
		}
		return $data;
	}

	/**
	 * Get menu group title
	 *
	 * @param int $group_id
	 * @return string
	*/
	private function get_menu_group_title($group_id)
	{
		$sqls = $this->podb->from(MENUGROUP_TABLE)
			->select(array(MENUGROUP_TITLE))
			->where(MENUGROUP_ID, $group_id)
			->limit(1)
			->fetch();
		$data = $sqls[MENUGROUP_TITLE];
		return $data;
	}

	/**
	 * Get label for list item in menu manager
	 * this is the content inside each <li>
	 *
	 * @param array $row
	 * @return string
	*/
	private function get_label($row)
	{
		$label =
			'<div class="ns-row">' .
				'<div class="ns-title">'.$row[MENU_TITLE].'</div>' .
				'<div class="ns-url">'.$row[MENU_URL].'</div>' .
				'<div class="ns-class">'.$row[MENU_CLASS].'</div>' .
				'<div class="ns-active">'.$row[MENU_ACTIVE].'</div>' .
				'<div class="ns-actions">' .
					'<a href="#" class="edit-menu" title="Edit Menu">' .
						'<img src="../'.DIR_INC.'/images/menu/edit.png" alt="Edit">' .
					'</a>' .
					'<a href="#" class="delete-menu">' .
						'<img src="../'.DIR_INC.'/images/menu/cross.png" alt="Delete">' .
					'</a>' .
					'<input type="hidden" name="menu_id" value="'.$row[MENU_ID].'">' .
				'</div>' .
			'</div>';
		return $label;
	}

}