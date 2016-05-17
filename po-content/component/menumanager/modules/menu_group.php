<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : menu_group.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses utama pada menu manager khusus di menu group.
 * This is a php file that is used to handle the main process on the menu manager specialty of menu group.
 *
*/

class Menu_group extends Menumanager
{

	/**
	 * Add menu group action
	 * or
	 * Show add menu group form
	*/
	public function add() {
		if (isset($_POST['title'])) {
			$data = array(
				MENUGROUP_TITLE => trim($_POST['title'])
			);
			if (!empty($data[MENUGROUP_TITLE])) {
				$query = $this->podb->insertInto(MENUGROUP_TABLE)->values($data);
				if ($query->execute()) {
					$sql = $this->podb->from(MENUGROUP_TABLE)
						->limit(1)
						->orderBy(MENUGROUP_ID.' DESC')
						->fetch();
					$response['status'] = 1;
					$response['id'] = $sql[MENUGROUP_ID];
				} else {
					$response['status'] = 2;
					$response['msg'] = 'Add menu group error.';
				}
			} else {
				$response['status'] = 3;
			}
			header('Content-type: application/json');
			echo json_encode($response);
		} else {
			$this->view('menu_group_add');
		}
	}

	/**
	 * Edit menu group action
	*/
	public function edit() {
		if (isset($_POST['title'])) {
			$id = (int)$_POST['id'];
			$response['success'] = false;
			$data = array(
				MENUGROUP_TITLE => trim($_POST['title'])
			);
			$query = $this->podb->update(MENUGROUP_TABLE)
				->set($data)
				->where(MENUGROUP_ID, $id);
			if ($query->execute()) {
				$response['success'] = true;
			}
			header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * Delete menu group action
	 * This will also delete all menus under this group
	*/
	public function delete() {
		if (isset($_POST['id'])) {
			$id = (int)$_POST['id'];
			if ($id == 1) {
				$response['success'] = false;
				$response['msg'] = 'Cannot delete Group ID = 1';
			} else {
				$query = $this->podb->deleteFrom(MENUGROUP_TABLE)->where(MENUGROUP_ID, $id);
				if ($query->execute()) {
					$query = $this->podb->deleteFrom(MENU_TABLE)->where(MENU_GROUP, array($id));
					$query->execute();
					$response['success'] = true;
				} else {
					$response['success'] = false;
				}
			}
			header('Content-type: application/json');
			echo json_encode($response);
		}
	}

}