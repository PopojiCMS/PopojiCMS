<?php
/**
 *
 * - PopojiCMS Core Vendor
 *
 * - File : dashboard_menu.php
 * - Version : 1.0
 * - Author : Gawibowo edited by Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah library untuk mengenerate nested lists.
 * This is library for generating nested lists.
 *
 * Contoh untuk penggunaan class ini
 * Example for uses this class
 *
 *
 * $instance = new DashboardMenu;
 * $menu = $instance->menu(1, 'class="nav" id="side-menu"', '', 'class="nav nav-second-level"');
 * echo $menu;
 *
*/

class DashboardMenu
{
	protected $podb;
	/**
	 * Constructor. Initialize database connection
	*/
	public function __construct()
	{
		$this->pdo = new PDO("mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME."", DATABASE_USER, DATABASE_PASS);
		$this->podb = new FluentPDO($this->pdo);
	}

	/**
	 * Get menu from database, and generate html nested list
	 *
	 * @param int $group_id
	 * @param string $attr, $attrs, $attrss
	 * @return string
	*/
	public function menu($group_id, $attr = '', $attrs = '', $attrss = '')
	{
		global $_;
		$selectlang = (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'id');
		include_once "../".DIR_CON."/lang/main/".$selectlang.".php";
		include_once "tree.php";
		$tree = new Tree;
		$menu = $this->podb->from('menu')
			->where('group_id', $group_id)
			->where('active', 'Y')
			->orderBy(array('parent_id ASC', 'position ASC'))
			->fetchAll();
		foreach ($menu as $row) {
			if ($row['parent_id'] == 0) {
				$label = '<a href="' . $row['url'] . '">';
			} else {
				$label = '<a href="' . $row['url'] . '">';
			}
			if ($row['class'] != '') {
				$label .= '<i class="fa '.$row['class'].' fa-fw"></i> <span>'.(isset($_[$row['title']]) ? $_[$row['title']] : $row['title']).'</span>';
			} else {
				$label .= '<span>'.(isset($_[$row['title']]) ? $_[$row['title']] : $row['title']).'</span>';
			}
			$label .= '</a>';
			$li_attr = '';
			$tree->add_row($row['id'], $row['parent_id'], $li_attr, $label);
		}
		$menu = $tree->generate_list($attr, $attrs, $attrss);
		return $menu;
	}

}