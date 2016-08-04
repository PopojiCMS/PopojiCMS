<?php
/**
 *
 * - PopojiCMS Core Vendor
 *
 * - File : front_menu.php
 * - Version : 1.1
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
 * $instance = new FrontMenu;
 * $menu = $instance->menu(1, 'class="nav" id="side-menu"', '', 'class="nav nav-second-level"');
 * echo $menu;
 *
*/

class FrontMenu
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
	 * @param string $attr, $attrs, $attrss, $wrapper, $endwrapper
	 * @return string
	*/
	public function menu($group_id, $attr = '', $attrs = '', $attrss = '', $wrapper = '<div>', $endwrapper = '</div>')
	{
		global $_;
		$selectlang = (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'id');
		include_once DIR_CON."/lang/main/".$selectlang.".php";
		include_once "tree.php";
		$tree = new Tree;
		$base_root = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && !in_array(strtolower($_SERVER['HTTPS']),array('off','no'))) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
		$base_url = preg_replace("/\/(index\.php$)/", "", $base_root);
		$menu = $this->podb->from('menu')
			->where('group_id', $group_id)
			->where('active', 'Y')
			->orderBy(array('parent_id ASC', 'position ASC'))
			->fetchAll();
		foreach ($menu as $row) {
			if (!preg_match("~^(?:f|ht)tps?://~i", $row['url'])) {
				$menu_url = $base_url.'/'.$row['url'];
			} else {
				$menu_url = $row['url'];
			}
			if ($row['parent_id'] == 0) {
				if ($row['class'] != '') {
					$label = '<a ' . $row['class'] . ' href="' . $menu_url . '" '.($row['target'] != 'none' ? 'target="' . $row['target'] . '"' : '').'>';
				} else {
					$label = '<a href="' . $menu_url . '" '.($row['target'] != 'none' ? 'target="' . $row['target'] . '"' : '').'>';
				}
			} else {
				if ($row['class'] != '') {
					$label = '<a ' . $row['class'] . ' href="' . $menu_url . '" '.($row['target'] != 'none' ? 'target="' . $row['target'] . '"' : '').'>';
				} else {
					$label = '<a href="' . $menu_url . '" '.($row['target'] != 'none' ? 'target="' . $row['target'] . '"' : '').'>';
				}
			}
			$label .= $wrapper.$row['title'].$endwrapper;
			$label .= '</a>';
			$li_attr = '';
			$tree->add_row($row['id'], $row['parent_id'], $li_attr, $label);
		}
		$menu = $tree->generate_list($attr, $attrs, $attrss);
		return $menu;
	}

}