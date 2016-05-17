<?php
/**
 *
 * - PopojiCMS Core
 *
 * - File : html.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah library untuk membuat html otomatis.
 * This is library for create automatic html.
 *
 * Contoh untuk penggunaan class ini
 * Example for uses this class
 *
 *
 * $form = new PoHtml;
 * echo $form->formStart(array('method' => 'post', 'action' => 'index.php', 'enctype' => false, 'autocomplete' => 'off', 'options' => 'role="form"'));
 * echo $form->inputHidden(array('name' => 'mod', 'value' => 'user', 'options' => ''));
 * echo $form->inputText(array('type' => 'text', 'label' => 'Username', 'name' => 'username', 'id' => 'username', 'class' => '', 'value' => '', 'placeholder' => 'Username', 'mandatory' => true, 'options' => ''));
 * echo $form->inputText(array('type' => 'password', 'label' => 'Password', 'name' => 'password', 'id' => 'password', 'class' => '', 'value' => '', 'placeholder' => 'Password', 'mandatory' => false, 'options' => ''));
 * echo $form->formSubmit(array('name' => 'submit', 'class' => 'btn-primary', 'id' => 'btnsubmit', 'value' => 'Submit', 'icon' => 'fa fa-check', 'options' => ''));
 * echo $form->formEnd();
 *
*/

class PoHtml
{

	function __construct(){}

	/**
	 * Fungsi ini digunakan untuk membuat awal dari form
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create begin of form element
	 * Each parameter must be write when calling function
	 *
	 * $param['method'] = string post or get
	 * $param['action'] = string
	 * $param['enctype'] = boolean true or false
	 * $param['autocomplete'] = string on or off
	 * $param['options'] = string
	 *
	*/
	public function formStart($param = array())
	{
		$default = array(
			'method' => null, 'action' => null, 'enctype' => false, 'autocomplete' => 'on', 'options' => null
		);
		$param = array_merge($default, $param);
		if ($param['enctype'] === true) {
			$start = "<form method=\"{$param['method']}\" action=\"{$param['action']}\" enctype=\"multipart/form-data\" autocomplete=\"{$param['autocomplete']}\" {$param['options']}>\n";
		} else {
			$start = "<form method=\"{$param['method']}\" action=\"{$param['action']}\" autocomplete=\"{$param['autocomplete']}\" {$param['options']}>\n";
		}
		return($start);
	}

	/**
	 * Fungsi ini digunakan untuk membuat akhir dari form
	 *
	 * This function use for create end of form element
	 *
	*/
	public function formEnd()
	{
		$end = "</form>\n";
		return($end);
	}

	/**
	 * Fungsi ini digunakan untuk membuat input hidden
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create hidden input
	 * Each parameter must be write when calling function
	 *
	 * $param['name'] = string
	 * $param['value'] = string
	 * $param['options'] = string
	 *
	*/
	public function inputHidden($param = array())
	{
		$default = array(
			'name' => null, 'value' => null, 'options' => null
		);
		$param = array_merge($default, $param);
		$inputhidden = "<input type=\"hidden\" name=\"{$param['name']}\" value=\"{$param['value']}\" {$param['options']}>\n";
		return($inputhidden);
	}

	/**
	 * Fungsi ini digunakan untuk membuat input text
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create text input
	 * Each parameter must be write when calling function
	 *
	 * $param['type'] = string
	 * $param['id'] = string
	 * $param['class'] = string
	 * $param['label'] = string
	 * $param['name'] = string
	 * $param['value'] = string
	 * $param['placeholder'] = string
	 * $param['mandatory'] = boolean true or false
	 * $param['options'] = string
	 * $param['help'] = string
	 * $inputgroup = boolean true or false
	 * $inputgroupopt = tring
	 *
	*/
	public function inputText($param = array(), $inputgroup = false, $inputgroupopt = null)
	{
		$default = array(
			'type' => null, 'id' => null, 'class' => null, 'label' => null, 'name' => null, 'value' => null, 'placeholder' => null, 'mandatory' => false, 'options' => null, 'help' => null
		);
		$param = array_merge($default, $param);
		$inputtext = "<div class=\"form-group\">\n";
		if ($param['mandatory'] === true) {
			$inputtext .= "<label for=\"{$param['id']}\">{$param['label']} <span class=\"text-danger\">*</span></label>\n";
		} else {
			$inputtext .= "<label for=\"{$param['id']}\">{$param['label']}</label>\n";
		}
		if ($param['type'] == 'file') {
			$formcontrol = "";
		} else {
			$formcontrol = "form-control";
		}
		if ($inputgroup == true) {
			$inputtext .= "<div class=\"input-group\">\n";
		}
		$inputtext .= "<input type=\"{$param['type']}\" class=\"{$formcontrol} {$param['class']}\" id=\"{$param['id']}\" name=\"{$param['name']}\" value=\"{$param['value']}\" placeholder=\"{$param['placeholder']}\" {$param['options']}>\n";
		if ($inputgroup == true) {
			$inputtext .= "<span class=\"input-group-btn\">\n";
			$inputtext .= "<a href=\"{$inputgroupopt['href']}\" id=\"{$inputgroupopt['id']}\" class=\"btn {$inputgroupopt['class']}\" {$inputgroupopt['options']}>{$inputgroupopt['title']}</a>\n";
			$inputtext .= "</span>\n";
			$inputtext .= "</div>\n";
		}
		if ($param['help'] != null) {
			$inputtext .= "<span class=\"help-block\">{$param['help']}</span>\n";
		}
		$inputtext .= "</div>\n";
		return($inputtext);
	}

	/**
	 * Fungsi ini digunakan untuk membuat input textarea
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create textarea input
	 * Each parameter must be write when calling function
	 *
	 * $param['id'] = string
	 * $param['class'] = string
	 * $param['label'] = string
	 * $param['name'] = string
	 * $param['value'] = string
	 * $param['placeholder'] = string
	 * $param['mandatory'] = boolean true or false
	 * $param['options'] = string
	 * $param['help'] = string
	 *
	*/
	public function inputTextarea($param = array())
	{
		$default = array(
			'id' => null, 'class' => null, 'label' => null, 'name' => null, 'value' => null, 'placeholder' => null, 'mandatory' => false, 'options' => null, 'help' => null
		);
		$param = array_merge($default, $param);
		$inputtext = "<div class=\"form-group\">\n";
		if ($param['mandatory'] === true) {
			$inputtext .= "<label for=\"{$param['id']}\">{$param['label']} <span class=\"text-danger\">*</span></label>\n";
		} else {
			$inputtext .= "<label for=\"{$param['id']}\">{$param['label']}</label>\n";
		}
		$inputtext .= "<textarea class=\"form-control {$param['class']}\" id=\"{$param['id']}\" name=\"{$param['name']}\" placeholder=\"{$param['placeholder']}\" {$param['options']}>{$param['value']}</textarea>\n";
		if ($param['help'] != null) {
			$inputtext .= "<span class=\"help-block\">{$param['help']}</span>\n";
		}
		$inputtext .= "</div>\n";
		return($inputtext);
	}

	/**
	 * Fungsi ini digunakan untuk membuat input select
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create select input
	 * Each parameter must be write when calling function
	 *
	 * $param['id'] = string
	 * $param['class'] = string
	 * $param['label'] = string
	 * $param['name'] = string
	 * $param['mandatory'] = boolean true or false
	 * $param['options'] = string
	 * $item = array()
	 *
	*/
	public function inputSelect($param = array(), $item = array())
	{
		$default = array(
			'id' => null, 'class' => null, 'label' => null, 'name' => null, 'mandatory' => false, 'options' => null
		);
		$param = array_merge($default, $param);
		$inputselect = "<div class=\"form-group\">\n";
		if ($param['mandatory'] === true) {
			$inputselect .= "<label for=\"{$param['id']}\">{$param['label']} <span class=\"text-danger\">*</span></label>\n";
		} else {
			$inputselect .= "<label for=\"{$param['id']}\">{$param['label']}</label>\n";
		}
		$inputselect .= "<select class=\"form-control {$param['class']}\" id=\"{$param['id']}\" name=\"{$param['name']}\" {$param['options']}>\n";
		if (!empty($item)) {
			foreach($item as $itm){
				$inputselect .= "<option value=\"{$itm['value']}\">{$itm['title']}</option>";
			}
		}
		$inputselect .= "</select>\n";
		$inputselect .= "</div>\n";
		return($inputselect);
	}

	/**
	 * Fungsi ini digunakan untuk membuat input select no option
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create select input no option
	 * Each parameter must be write when calling function
	 *
	 * $param['id'] = string
	 * $param['class'] = string
	 * $param['label'] = string
	 * $param['name'] = string
	 * $param['mandatory'] = boolean true or false
	 * $param['options'] = string
	 *
	*/
	public function inputSelectNoOpt($param = array())
	{
		$default = array(
			'id' => null, 'class' => null, 'label' => null, 'name' => null, 'mandatory' => false, 'options' => null
		);
		$param = array_merge($default, $param);
		$inputselect = "<div class=\"form-group\">\n";
		if ($param['mandatory'] === true) {
			$inputselect .= "<label for=\"{$param['id']}\">{$param['label']} <span class=\"text-danger\">*</span></label>\n";
		} else {
			$inputselect .= "<label for=\"{$param['id']}\">{$param['label']}</label>\n";
		}
		$inputselect .= "<select class=\"form-control {$param['class']}\" id=\"{$param['id']}\" name=\"{$param['name']}\" {$param['options']}>\n";
		return($inputselect);
	}

	/**
	 * Fungsi ini digunakan untuk membuat akhir dari input select no option
	 *
	 * This function use for create end of input select no option
	 *
	*/
	public function inputSelectNoOptEnd()
	{
		$end = "</select>\n";
		$end .= "</div>\n";
		return($end);
	}

	/**
	 * Fungsi ini digunakan untuk membuat input radio
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create radio input
	 * Each parameter must be write when calling function
	 *
	 * $param['label'] = string
	 * $param['mandatory'] = boolean true or false
	 * $item = array();
	 *
	*/
	public function inputRadio($param = array(), $item = array(), $inline = false)
	{
		$default = array(
			'label' => null, 'mandatory' => null
		);
		$param = array_merge($default, $param);
		if ($inline == true) {
			$radioinline = "radio-inline";
		} else {
			$radioinline = "";
		}
		$inputradio = "<div class=\"form-group\">\n";
		$inputradio .= "<div class=\"row\">\n";
		if ($param['mandatory'] === true) {
			$inputradio .= "<label class=\"col-md-3\">{$param['label']} <span class=\"text-danger\">*</span></label>\n";
		} else {
			$inputradio .= "<label class=\"col-md-3\">{$param['label']}</label>\n";
		}
		$inputradio .= "<div class=\"col-md-9\">\n";
		$noitem = 1;
		foreach($item as $itm){
			$inputradio .= "<div class=\"radio {$radioinline}\">\n";
			$inputradio .= "<input type=\"radio\" name=\"{$itm['name']}\" id=\"{$itm['id']}-{$noitem}\" value=\"{$itm['value']}\" {$itm['options']} />\n";
			$inputradio .= "<label for=\"{$itm['id']}-{$noitem}\">{$itm['title']}</label>\n";
			$inputradio .= "</div>\n";
			$noitem++;
		}
		$inputradio .= "</div>\n";
		$inputradio .= "</div>\n";
		$inputradio .= "</div>\n";
		return($inputradio);
	}

	/**
	 * Fungsi ini digunakan untuk membuat input checkbox
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create checkbox input
	 * Each parameter must be write when calling function
	 *
	 * $param['label'] = string
	 * $param['mandatory'] = boolean true or false
	 * $item = array();
	 *
	*/
	public function inputCheckbox($param = array(), $item = array(), $inline = false)
	{
		$default = array(
			'label' => null, 'mandatory' => null
		);
		$param = array_merge($default, $param);
		if ($inline == true) {
			$checkinline = "checkbox-inline";
		} else {
			$checkinline = "";
		}
		$inputcheck = "<div class=\"form-group\">\n";
		$inputcheck .= "<div class=\"row\">\n";
		if ($param['mandatory'] === true) {
			$inputcheck .= "<label class=\"col-md-3\">{$param['label']} <span class=\"text-danger\">*</span></label>\n";
		} else {
			$inputcheck .= "<label class=\"col-md-3\">{$param['label']}</label>\n";
		}
		$inputcheck .= "<div class=\"col-md-9\">\n";
		$noitem = 1;
		foreach($item as $itm){
			$inputcheck .= "<div class=\"checkbox {$checkinline}\">\n";
			$inputcheck .= "<input type=\"checkbox\" name=\"{$itm['name']}\" id=\"{$itm['id']}-{$noitem}\" value=\"{$itm['value']}\" {$itm['options']} />\n";
			$inputcheck .= "<label for=\"{$itm['id']}-{$noitem}\">{$itm['title']}</label>\n";
			$inputcheck .= "</div>\n";
			$noitem++;
		}
		$inputcheck .= "</div>\n";
		$inputcheck .= "</div>\n";
		$inputcheck .= "</div>\n";
		return($inputcheck);
	}

	/**
	 * Fungsi ini digunakan untuk membuat tombol submit
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create submit button
	 * Each parameter must be write when calling function
	 *
	 * $param['id'] = string
	 * $param['class'] = string
	 * $param['name'] = string
	 * $param['value'] = string
	 * $param['icon'] = boolean false or string
	 * $param['options'] = string
	 *
	*/
	public function formSubmit($param = array())
	{
		$default = array(
			'id' => null, 'class' => null, 'name' => null, 'value' => null, 'icon' => null, 'options' => null
		);
		$param = array_merge($default, $param);
		if ($param['icon'] === false) {
			$submit = "<button type=\"submit\" class=\"btn {$param['class']}\" id=\"{$param['id']}\" name=\"{$param['name']}\" {$param['options']}>{$param['value']}</button>\n";
		} else {
			$submit = "<button type=\"submit\" class=\"btn {$param['class']}\" id=\"{$param['id']}\" name=\"{$param['name']}\" {$param['options']}><i class=\"{$param['icon']}\"></i> {$param['value']}</button>\n";
		}
		return($submit);
	}

	/**
	 * Fungsi ini digunakan untuk membuat tombol aksi form
	 *
	 * This function use for create form action button
	 *
	 * $color_btn_left = string
	 * $color_btn_right = string
	 *
	*/
	public function formAction($color_btn_left = 'btn-primary', $color_btn_right = 'btn-danger' )
	{
		$action = "<div class=\"form-group\">\n";
		$action .= "<button type=\"submit\" class=\"btn {$color_btn_left}\"><i class=\"fa fa-check\"></i> {$GLOBALS['_']['action_5']}</button>\n";
		$action .= "<button type=\"reset\" class=\"btn {$color_btn_right} pull-right\" onclick=\"self.history.back()\"><i class=\"fa fa-times\"></i> {$GLOBALS['_']['action_6']}</button>\n";
		$action .= "</div>\n";
		return($action);
	}

	/**
	 * Fungsi ini digunakan untuk membuat awal dari tabel
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create begin of table element
	 * Each parameter must be write when calling function
	 *
	 * $identity = array()
	 * $columns = array()
	 * $tfoot = boolean
	 *
	*/
	public function createTable($identity, $columns, $tfoot = true)
	{
		$table = $this->tableStart($identity);
		$table .= $this->tableThead($columns);
		if ($tfoot == true) {
			$table .= $this->tableTfoot(count($columns));
		}
		$table .= $this->tableEnd();
		return($table);
	}

	/**
	 * Fungsi ini digunakan untuk membuat awal dari tabel
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create begin of table element
	 * Each parameter must be write when calling function
	 *
	 * $param['id'] = string
	 * $param['class'] = string
	 * $param['options'] = string
	 *
	*/
	public function tableStart($param = array())
	{
		$default = array(
			'id' => null, 'class' => null, 'options' => null
		);
		$param = array_merge($default, $param);
		$start = "<table id=\"{$param['id']}\" class=\"{$param['class']}\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" {$param['options']}>\n";
		return($start);
	}

	/**
	 * Fungsi ini digunakan untuk membuat akhir dari tabel
	 *
	 * This function use for create end of table element
	 *
	*/
	public function tableEnd()
	{
		$end = "</table>\n";
		return($end);
	}

	/**
	 * Fungsi ini digunakan untuk membuat thead pada tabel
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create thead at table
	 * Each parameter must be write when calling function
	 *
	 * $columns = array()
	 *
	*/
	public function tableThead($columns = array())
	{
		$thead = "<thead>\n";
		$thead .= "<tr>\n";
		$thead .= "<th class=\"no-sort\" style=\"width:10px;\"></th>\n";
		foreach($columns as $col) {
			$thead .= "<th {$col['options']}>{$col['title']}</th>\n";
		}
		$thead .= "</tr>\n";
		$thead .= "</thead>\n";
		return($thead);
	}

	/**
	 * Fungsi ini digunakan untuk membuat tfoot pada tabel
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create tfoot at table
	 * Each parameter must be write when calling function
	 *
	 * $colspan = number
	 *
	*/
	public function tableTfoot($colspan)
	{
		$tfoot = "<tfoot>\n";
		$tfoot .= "<tr>\n";
		$tfoot .= "<td style=\"width:10px;\" class=\"text-center\"><input type=\"checkbox\" id=\"titleCheck\" data-toggle=\"tooltip\" title=\"{$GLOBALS['_']['action_3']}\" /></td>\n";
		$tfoot .= "<td colspan=\"{$colspan}\">\n";
		$tfoot .= "<button class=\"btn btn-sm btn-danger\" type=\"button\" data-toggle=\"modal\" data-target=\"#alertalldel\"><i class=\"fa fa-trash-o\"></i> {$GLOBALS['_']['action_4']}</button>\n";
		$tfoot .= "</td>\n";
		$tfoot .= "</tr>\n";
		$tfoot .= "</tfoot>\n";
		return($tfoot);
	}

	/**
	 * Fungsi ini digunakan untuk membuat kepala judul pada konten admin
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create header title at admin content
	 * Each parameter must be write when calling function
	 *
	 * $title = string
	 *
	*/
	public function headTitle($title, $options = null)
	{
		$head = "<div class=\"block-header\">\n";
		$head .= "<h3>{$title}</h3>\n";
		$head .= "<ol class=\"list-inline list-unstyled\">\n";
		$head .= "<li><a href=\"admin.php?mod=home\">{$GLOBALS['_']['dashboard']}</a></li>\n";
		$head .= "<li>/</li>\n";
		$head .= "<li class=\"active\">{$title}</li>\n";
		$head .= "</ol>\n";
		if ($options != null) {
			$head .= $options;
		}
		$head .= "</div>\n";
		return($head);
	}

	/**
	 * Fungsi ini digunakan untuk membuat dialog hapus pada konten admin
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create delete dialog at admin content
	 * Each parameter must be write when calling function
	 *
	 * $component = string
	 *
	*/
	public function dialogDelete($component, $act = null)
	{
		$dialogdel = "<div id=\"alertdel\" class=\"modal fade\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">\n";
		$dialogdel .= "<div class=\"modal-dialog\">\n";
		$dialogdel .= "<div class=\"modal-content\">\n";
		if ($act == null) {
			$dialogdel .= "<form method=\"post\" action=\"route.php?mod={$component}&act=delete\" autocomplete=\"off\">\n";
		} else {
			$dialogdel .= "<form method=\"post\" action=\"route.php?mod={$component}&act={$act}\" autocomplete=\"off\">\n";
		}
		$dialogdel .= "<div class=\"modal-header\">\n";
		$dialogdel .= "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
		$dialogdel .= "<h4 id=\"modal-title\"><i class=\"fa fa-exclamation-triangle text-danger\"></i> {$GLOBALS['_']['dialogdel_1']}</h4>\n";
		$dialogdel .= "</div>\n";
		$dialogdel .= "<div class=\"modal-body\">\n";
		$dialogdel .= "<input type=\"hidden\" id=\"delid\" name=\"id\" />\n";
		$dialogdel .= "{$GLOBALS['_']['dialogdel_2']}\n";
		$dialogdel .= "</div>\n";
		$dialogdel .= "<div class=\"modal-footer\">\n";
		$dialogdel .= "<button type=\"submit\" class=\"btn btn-sm btn-danger\"><i class=\"fa fa-trash-o\"></i> {$GLOBALS['_']['dialogdel_3']}</button>\n";
		$dialogdel .= "<button type=\"button\" class=\"btn btn-sm btn-default\" data-dismiss=\"modal\" aria-hidden=\"true\"><i class=\"fa fa-sign-out\"></i> {$GLOBALS['_']['dialogdel_4']}</button>\n";
		$dialogdel .= "</div>\n";
		$dialogdel .= "</form>\n";
		$dialogdel .= "</div>\n";
		$dialogdel .= "</div>\n";
		$dialogdel .= "</div>\n";
		return($dialogdel);
	}

	/**
	 * Fungsi ini digunakan untuk membuat dialog hapus semua pada konten admin
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create delete all dialog at admin content
	 * Each parameter must be write when calling function
	 *
	*/
	public function dialogDeleteAll()
	{
		$dialogdelall = "<div id=\"alertalldel\" class=\"modal fade\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">\n";
		$dialogdelall .= "<div class=\"modal-dialog\">\n";
		$dialogdelall .= "<div class=\"modal-content\">\n";
		$dialogdelall .= "<div class=\"modal-header\">\n";
		$dialogdelall .= "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
		$dialogdelall .= "<h4 id=\"modal-title\"><i class=\"fa fa-exclamation-triangle text-danger\"></i> {$GLOBALS['_']['dialogdel_1']}</h4>\n";
		$dialogdelall .= "</div>\n";
		$dialogdelall .= "<div class=\"modal-body\">{$GLOBALS['_']['dialogdel_2']}</div>\n";
		$dialogdelall .= "<div class=\"modal-footer\">\n";
		$dialogdelall .= "<button type=\"button\" class=\"btn btn-sm btn-danger\" id=\"confirmdel\"><i class=\"fa fa-trash-o\"></i> {$GLOBALS['_']['dialogdel_3']}</button>\n";
		$dialogdelall .= "<button type=\"button\" class=\"btn btn-sm btn-default\" data-dismiss=\"modal\" aria-hidden=\"true\"><i class=\"fa fa-sign-out\"></i> {$GLOBALS['_']['dialogdel_4']}</button>\n";
		$dialogdelall .= "</div>\n";
		$dialogdelall .= "</div>\n";
		$dialogdelall .= "</div>\n";
		$dialogdelall .= "</div>\n";
		return($dialogdelall);
	}

	/**
	 * Fungsi ini digunakan untuk membuat pesan alert pada konten admin
	 * Setiap parameter harus di tulis saat pemanggilan function
	 *
	 * This function use for create alert message at admin content
	 * Each parameter must be write when calling function
	 *
	 * $type = string(success, info, warning, danger)
	 * $text = string
	 *
	*/
	public function alert($type, $text)
	{
		$alert = "<div class=\"alert alert-{$type}\" role=\"alert\">\n";
		$alert .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\n";
		$alert .= "{$text}</div>\n";
		return($alert);
	}

	/**
	 * Fungsi ini digunakan untuk membuat pesan error pada konten admin
	 *
	 * This function use for create error message at admin content
	 *
	*/
	public function error()
	{
	?>
		<div class="row">
			<div class="col-lg-12 text-center">
				<h1 class="page-header">Page Not Found <small class="text-danger">Error 404</small></h1>
				<p>
					The page you requested could not be found, either contact your webmaster or try again.<br />
					Use your browsers <b>Back</b> button to navigate to the page you have previously<br />
					come from <b>or you could just press this neat little button :</b>
				</p>
				<a href="?mod=home&act=index" class="btn btn-sm btn-primary"><i class="fa fa-home"></i> Take Me Home</a>
			</div>
		</div>
	<?php
	}

}