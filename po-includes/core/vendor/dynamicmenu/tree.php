<?php
/**
 *
 * - PopojiCMS Core Vendor
 *
 * - File : tree.php
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
 * $tree = new Tree;
 * $tree->add_row(1, 0, '', 'Menu 1');
 * $tree->add_row(2, 0, '', 'Menu 2');
 * $tree->add_row(3, 1, '', 'Menu 1.1');
 * $tree->add_row(4, 1, '', 'Menu 1.2');
 * echo $tree->generate_list();
 *
 * Output:
 * <ul>
 * 	<li>Menu 1
 * 		<ul>
 * 			<li>Menu 1.1</li>
 * 			<li>Menu 1.2</li>
 * 		</ul>
 * 	</li>
 * 	<li>Menu 2</li>
 * </ul>
 *
*/

class Tree {

	/**
	 * variable to store temporary data to be processed later
	 *
	 * @var array
	*/
	var $data;

	/**
	 * Add an item
	 *
	 * @param int $id 			ID of the item
	 * @param int $parent 		parent ID of the item
	 * @param string $li_attr 	attributes for <li>
	 * @param string $label		text inside <li></li>
	*/
	public function add_row($id, $parent, $li_attr, $label)
	{
		$this->data[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label);
	}

	/**
	 * Generates nested lists
	 *
	 * @param string $ul_attr
	 * @return string
	*/
	public function generate_list($attr = '', $attrs = '', $attrss = '')
	{
		return $this->ul(0, $attr, $attrs, $attrss);
	}

	/**
	 * Recursive method for generating nested lists
	 *
	 * @param int $parent
	 * @param string $attrs
	 * @return string
	*/
	public function ul($parent = 0, $attr = '', $attrs = '', $attrss = '')
	{
		static $i = 1;
		$indent = str_repeat("\t\t", $i);
		if (isset($this->data[$parent])) {
			if ($attr) {
				$attr = $attr;
			}
			if ($attrs) {
				$attrs = $attrs;
			}
			if ($attrss) {
				$attrss = $attrss;
			}
			$html = "\n$indent";
			$html .= "<ul ".$attr.">";
			$i++;
			foreach ($this->data[$parent] as $row) {
				$child = $this->ul($row['id'], $attrss);
				$html .= "\n\t$indent";
				if ($child) {
					$html .= '<li '.$attrs.'>';
				} else {
					$html .= '<li>';
				}
				$html .= $row['label'];
				if ($child) {
					$i--;
					$html .= $child;
					$html .= "\n\t$indent";
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
	 * Clear the temporary data
	 *
	*/
	public function clear()
	{
		$this->data = array();
	}

}