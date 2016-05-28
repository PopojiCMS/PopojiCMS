<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : admin_setting.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php yang di gunakan untuk menangani proses admin pada halaman pengaturan.
 * This is a php file for handling admin process for setting page.
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

class Setting extends PoCore
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
	 * Fungsi ini digunakan untuk menampilkan halaman index pangaturan.
	 *
	 * This function use for index setting page.
	 *
	*/
	public function index()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$settings = $this->podb->from('setting')->fetchAll();
		$oauths = $this->podb->from('oauth')->fetchAll();
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['component_name']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs nav-justified" role="tablist">
						<li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab" style="color:#3498DB !important;"><i class="fa fa-desktop"></i>&nbsp;&nbsp;<?=$GLOBALS['_']['setting_general'];?></a></li>
						<li role="presentation"><a href="#image" aria-controls="image" role="tab" data-toggle="tab" style="color:#E74C3C !important;"><i class="fa fa-image"></i>&nbsp;&nbsp;<?=$GLOBALS['_']['setting_image'];?></a></li>
						<li role="presentation"><a href="#local" aria-controls="local" role="tab" data-toggle="tab" style="color:#18BC9C !important;"><i class="fa fa-globe"></i>&nbsp;&nbsp;<?=$GLOBALS['_']['setting_local'];?></a></li>
						<li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab" style="color:#F39C12 !important;"><i class="fa fa-gears"></i>&nbsp;&nbsp;<?=$GLOBALS['_']['setting_config'];?></a></li>
						<li role="presentation"><a href="#mail" aria-controls="mail" role="tab" data-toggle="tab" style="color:#9b59b6 !important;"><i class="fa fa-envelope-o"></i>&nbsp;&nbsp;<?=$GLOBALS['_']['setting_mail'];?></a></li>
						<li role="presentation"><a href="#oauth" aria-controls="oauth" role="tab" data-toggle="tab" style="color:#16a085 !important;"><i class="fa fa-share"></i>&nbsp;&nbsp;<?=$GLOBALS['_']['setting_oauth'];?></a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="general">
							<div class="table-responsive">
								<table class="table table-striped table-hover table-setting">
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_web_name'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="web_name" data-type="text" data-pk="1" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[0]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_web_url'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="web_url" data-type="text" data-pk="2" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[1]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_web_meta'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="web_meta" data-type="textarea" data-rows="3" data-pk="3" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[2]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_web_keyword'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="web_keyword" data-type="textarea" data-rows="3" data-pk="4" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[3]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_web_owner'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="web_owner" data-type="text" data-pk="5" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[4]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_email'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="email" data-type="email" data-pk="6" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[5]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_telephone'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="telephone" data-type="text" data-pk="7" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[6]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_fax'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="fax" data-type="text" data-pk="8" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[7]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_address'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="address" data-type="textarea" data-rows="3" data-pk="9" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[8]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_geocode'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="geocode" data-type="text" data-pk="10" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[9]['value'];?></a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="image">
							<div class="table-responsive">
								<table class="table table-striped table-hover table-setting">
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_favicon'];?></td>
										<td class="link-setting">
											<?php if (file_exists('../'.DIR_INC.'/images/favicon.png')) { ?>
											<img src="../<?=DIR_INC;?>/images/favicon.png" class="thumbnail img-responsive" width="32" />
											<?php } ?>
											<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=setting&act=editfavicon', 'enctype' => true, 'autocomplete' => 'off'));?>
												<div class="input-group col-md-5">
													<input class="form-control input-sm" type="file" name="picture" />
													<span class="input-group-btn">
														<button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-check"></i> <?=$GLOBALS['_']['action_5'];?></button>
													</span>
												</div>
											<?=$this->pohtml->formEnd();?>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_logo'];?></td>
										<td class="link-setting">
											<?php if (file_exists('../'.DIR_INC.'/images/logo.png')) { ?>
											<img src="../<?=DIR_INC;?>/images/logo.png" class="thumbnail img-responsive" width="100" />
											<?php } ?>
											<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=setting&act=editlogo', 'enctype' => true, 'autocomplete' => 'off'));?>
												<div class="input-group col-md-5">
													<input class="form-control input-sm" type="file" name="picture" />
													<span class="input-group-btn">
														<button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-check"></i> <?=$GLOBALS['_']['action_5'];?></button>
													</span>
												</div>
											<?=$this->pohtml->formEnd();?>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_img_medium'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="img_medium" data-type="text" data-pk="13" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[12]['value'];?></a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="local">
							<div class="table-responsive">
								<table class="table table-striped table-hover table-setting">
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_country'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="country" data-type="select" data-pk="14" data-url="route.php?mod=setting&act=edit" data-value="<?=$settings[13]['value'];?>" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[13]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_region_state'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="region_state" data-type="select" data-pk="15" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[14]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_timezone'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="timezone" data-type="select" data-pk="16" data-url="route.php?mod=setting&act=edit" data-value="<?=$settings[15]['value'];?>" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[15]['value'];?></a>
											<br />Date : <?=date('M d, Y');?> ~ Time : <?=date('H:i:s');?>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_admin_language'];?></td>
										<td class="link-setting">
											<div class="row-table">
												<div class="col-table col-md-3">
													<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'admin.php?mod=setting', 'enctype' => false, 'autocomplete' => 'off'));?>
														<select class="form-control input-sm" name="language" onChange="submit()">
															<?php
																$langs = $this->podb->from('language')->where('active', 'Y')->orderBy('id_language ASC')->fetchAll();
																foreach($langs as $lang) {
																$langsel = (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'id');
																if ($langsel == $lang['code']) {
																	$langselopt = 'selected';
																} else {
																	$langselopt = '';
																}
															?>
															<option value="<?=$lang['code'];?>" <?=$langselopt;?>><?=$lang['title'];?></option>
															<?php } ?>
														</select>
													<?=$this->pohtml->formEnd();?>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</div><hr style="margin:0;" />
							<div class="row" style="padding:10px;">
								<div class="col-md-12" style="margin-bottom:10px;">
									<div class="pull-left"><?=$GLOBALS['_']['language_component'];?></div>
									<div class="pull-right"><a href="admin.php?mod=setting&act=addnewlang" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> <?=$GLOBALS['_']['addnew'];?></a></div>
								</div>
								<div class="col-md-12">
									<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=setting&act=multidelete', 'autocomplete' => 'off'));?>
										<?=$this->pohtml->inputHidden(array('name' => 'totaldata', 'value' => '0', 'options' => 'id="totaldata"'));?>
										<?php
											$columns = array(
												array('title' => 'Id', 'options' => 'style="width:30px;"'),
												array('title' => $GLOBALS['_']['language_title'], 'options' => ''),
												array('title' => $GLOBALS['_']['language_code'], 'options' => 'style="width:50px;"'),
												array('title' => $GLOBALS['_']['language_active'], 'options' => 'class="no-sort" style="width:30px;"'),
												array('title' => $GLOBALS['_']['language_action'], 'options' => 'class="no-sort" style="width:50px;"')
											);
										?>
										<?=$this->pohtml->createTable(array('id' => 'table-language', 'class' => 'table table-striped table-bordered'), $columns, $tfoot = true);?>
									<?=$this->pohtml->formEnd();?>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="config">
							<div class="table-responsive">
								<table class="table table-striped table-hover table-setting">
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_maintenance'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="maintenance" data-type="select" data-pk="17" data-url="route.php?mod=setting&act=edit" data-value="<?=$settings[16]['value'];?>" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[16]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_member_registration'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="member_registration" data-type="select" data-pk="18" data-url="route.php?mod=setting&act=edit" data-value="<?=$settings[17]['value'];?>" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[17]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_comment'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="comment" data-type="select" data-pk="19" data-url="route.php?mod=setting&act=edit" data-value="<?=$settings[18]['value'];?>" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[18]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_item_per_page'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="item_per_page" data-type="number" data-pk="20" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[19]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_google_analytics'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="google_analytics" data-type="text" data-pk="21" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[20]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_recaptcha_sitekey'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="recaptcha_sitekey" data-type="text" data-pk="22" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[21]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_recaptcha_secretkey'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="recaptcha_secretkey" data-type="text" data-pk="23" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[22]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_sitemap'];?></td>
										<td>
											<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=setting&act=sitemap', 'autocomplete' => 'off', 'options' => 'class="form-inline"'));?>
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon" style="padding:5px 15px;"><?=$GLOBALS['_']['setting_sitemap_change'];?></div>
														<select class="form-control input-sm" name="changefreq">
															<option value="" selected>None</option>
															<option value="always">Always</option>
															<option value="hourly">Hourly</option>
															<option value="daily">Daily</option>
															<option value="weekly">Weekly</option>
															<option value="monthly">Monthly</option>
															<option value="yearly">Yearly</option>
															<option value="never">Never</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon" style="padding:5px 15px;"><?=$GLOBALS['_']['setting_sitemap_priority'];?></div>
														<select class="form-control input-sm" name="priority">
															<option value="0.1" selected>0.1</option>
															<option value="0.2">0.2</option>
															<option value="0.3">0.3</option>
															<option value="0.4">0.4</option>
															<option value="0.5">0.5</option>
															<option value="0.6">0.6</option>
															<option value="0.7">0.7</option>
															<option value="0.8">0.8</option>
															<option value="0.9">0.9</option>
															<option value="1.0">1.0</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<button class="btn btn-sm btn-primary" type="submit"><?=$GLOBALS['_']['setting_sitemap_btn'];?></button>
												</div>
											<?=$this->pohtml->formEnd();?>
										</td>
									</tr>
								</table>
							</div><hr style="margin:0;" />
							<div class="row" style="padding:10px;">
								<div class="col-md-12" style="margin-bottom:10px;">
									<div class="pull-left"><?=$GLOBALS['_']['setting_meta_social'];?></div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
								<?php
									$filename_meta = "../".DIR_CON."/component/setting/meta_social.txt";
									if (file_exists("$filename_meta")) {
									$fh_meta = fopen($filename_meta, "r") or die("Could not open file!");
									$data_meta = fread($fh_meta, filesize($filename_meta)) or die("Could not read file!");
									fclose($fh_meta);
								?>
									<style type="text/css">
										.CodeMirror { height: 300px; }
										.CodeMirror-matchingtag { background: #4d4d4d; }
										.breakpoints { width: .8em; }
										.breakpoint { color: #3498db; }
									</style>
									<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=setting&act=metasocial', 'autocomplete' => 'off'));?>
										<textarea class="form-control" id="pocodemirror" name="meta_content" style="width:100%; height:300px;"><?=$data_meta;?></textarea>
										<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> <?=$GLOBALS['_']['action_5'];?></button>
									<?=$this->pohtml->formEnd();?>
								<?php } ?>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="mail">
							<div class="table-responsive">
								<table class="table table-striped table-hover table-setting">
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_mail_protocol'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="mail_protocol" data-type="select" data-pk="24" data-url="route.php?mod=setting&act=edit" data-value="<?=$settings[23]['value'];?>" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[23]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_mail_hostname'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="mail_hostname" data-type="text" data-pk="25" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[24]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_mail_username'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="mail_username" data-type="text" data-pk="26" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[25]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_mail_password'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="mail_password" data-type="text" data-pk="27" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[26]['value'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_mail_port'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="mail_port" data-type="number" data-pk="28" data-url="route.php?mod=setting&act=edit" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$settings[27]['value'];?></a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="oauth">
							<div class="table-responsive">
								<table class="table table-striped table-hover table-setting">
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_oauth_fb_app_id'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="oauth_fb_app_id" data-type="text" data-pk="1" data-url="route.php?mod=setting&act=editoauth&key=oauth_key" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$oauths[0]['oauth_key'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_oauth_fb_app_secret'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="oauth_fb_app_secret" data-type="text" data-pk="1" data-url="route.php?mod=setting&act=editoauth&key=oauth_secret" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$oauths[0]['oauth_secret'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_oauth_fb_id'];?></td>
										<td class="link-setting"><?=$oauths[0]['oauth_id'];?></td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_oauth_fb_user'];?></td>
										<td class="link-setting"><?=$oauths[0]['oauth_user'];?></td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_oauth_fb_type'];?></td>
										<td class="link-setting"><?=$oauths[0]['oauth_fbtype'];?></td>
									</tr>
								</table>
							</div>
							<a href="../<?=DIR_CON;?>/component/oauth/facebook/index.php" class="btn btn-sm btn-primary"><?=$GLOBALS['_']['setting_oauth_create'];?></a>
							<a href="route.php?mod=setting&act=deleteoauth&id=1" class="btn btn-sm btn-danger"><?=$GLOBALS['_']['setting_oauth_delete'];?></a>
							<div class="help-block"><span class="text-danger">*)</span> <?=$GLOBALS['_']['setting_oauth_help_1'];?></div>
							<hr style="margin:20px 0;" />
							<div class="table-responsive">
								<table class="table table-striped table-hover table-setting">
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_oauth_tw_consumer_key'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="oauth_tw_consumer_key" data-type="text" data-pk="2" data-url="route.php?mod=setting&act=editoauth&key=oauth_key" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$oauths[1]['oauth_key'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_oauth_tw_consumer_secret'];?></td>
										<td class="link-setting">
											<a href="javascript:void(0)" id="oauth_tw_consumer_secret" data-type="text" data-pk="2" data-url="route.php?mod=setting&act=editoauth&key=oauth_secret" title="<?=$GLOBALS['_']['setting_edit_tooltip'];?>"><?=$oauths[1]['oauth_secret'];?></a>
										</td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_oauth_tw_id'];?></td>
										<td class="link-setting"><?=$oauths[1]['oauth_id'];?></td>
									</tr>
									<tr>
										<td style="width:265px;"><?=$GLOBALS['_']['setting_oauth_tw_user'];?></td>
										<td class="link-setting"><?=$oauths[1]['oauth_user'];?></td>
									</tr>
								</table>
							</div>
							<a href="../<?=DIR_CON;?>/component/oauth/twitter/index.php" class="btn btn-sm btn-primary"><?=$GLOBALS['_']['setting_oauth_create'];?></a>
							<a href="route.php?mod=setting&act=deleteoauth&id=2" class="btn btn-sm btn-danger"><?=$GLOBALS['_']['setting_oauth_delete'];?></a>
							<div class="help-block"><span class="text-danger">*)</span> <?=$GLOBALS['_']['setting_oauth_help_2'];?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?=$this->pohtml->dialogDelete('setting');?>
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
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'read')) {
			echo $this->pohtml->error();
			exit;
		}
		$table = 'language';
		$primarykey = 'id_language';
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
			array('db' => 'title', 'dt' => '2', 'field' => 'title',
				'formatter' => function($d, $row, $i){
					return "<img src=\"../".DIR_INC."/images/flag/".$row['code'].".png\" /> - ".$d."\n";
				}
			),
			array('db' => 'code', 'dt' => '3', 'field' => 'code',
				'formatter' => function($d, $row, $i){
					return "<div class='text-center'>".$d."</div>\n";
				}
			),
			array('db' => 'active', 'dt' => '4', 'field' => 'active'),
			array('db' => $primarykey, 'dt' => '5', 'field' => $primarykey,
				'formatter' => function($d, $row, $i){
					$id = array('1');
					if (in_array($row['id_language'], $id)) {
						$tbldel = "<a class='btn btn-xs btn-danger' data-toggle='tooltip' title='{$GLOBALS['_']['action_9']}'><i class='fa fa-times'></i></a>";
					} else {
						$tbldel = "<a class='btn btn-xs btn-danger alertdel' id='".$row['id_language']."' data-toggle='tooltip' title='{$GLOBALS['_']['action_2']}'><i class='fa fa-times'></i></a>";
					}
					return "<div class='text-center'>\n
						<div class='btn-group btn-group-xs'>\n
							<a href='admin.php?mod=setting&act=editlang&id=".$row['id_language']."' class='btn btn-xs btn-default' id='".$row['id_language']."' data-toggle='tooltip' title='{$GLOBALS['_']['action_1']}'><i class='fa fa-pencil'></i></a>
							$tbldel
						</div>\n
					</div>\n";
				}
			)
		);
		echo json_encode(SSP::simple($_POST, $this->poconnect, $table, $primarykey, $columns));
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit pengaturan.
	 *
	 * This function is used to display and process edit setting page.
	 *
	*/
	public function edit()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$setting = array(
				'value' => $this->postring->valid($_POST['value'], 'xss')
			);
			$query_setting = $this->podb->update('setting')
				->set($setting)
				->where('id_setting', $this->postring->valid($_POST['pk'], 'sql'));
			$query_setting->execute();
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit pengaturan oauth.
	 *
	 * This function is used to display and process edit oauth setting page.
	 *
	*/
	public function editoauth()
	{
		if (!$this->auth($_SESSION['leveluser'], 'oauth', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$oauth = array(
				$this->postring->valid($_GET['key'], 'xss') => $this->postring->valid($_POST['value'], 'xss')
			);
			$query_setting = $this->podb->update('oauth')
				->set($oauth)
				->where('id_oauth', $this->postring->valid($_POST['pk'], 'sql'));
			$query_setting->execute();
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses hapus pengaturan oauth.
	 *
	 * This function is used to display and process delete oauth setting.
	 *
	*/
	public function deleteoauth()
	{
		if (!$this->auth($_SESSION['leveluser'], 'oauth', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_GET)) {
			$oauth = array(
				'oauth_key' => '',
				'oauth_secret' => '',
				'oauth_id' => '',
				'oauth_user' => '',
				'oauth_token1' => '',
				'oauth_token2' => '',
				'oauth_fbtype' => ''
			);
			$query_setting = $this->podb->update('oauth')
				->set($oauth)
				->where('id_oauth', $this->postring->valid($_GET['id'], 'sql'));
			$query_setting->execute();
			$this->poflash->success($GLOBALS['_']['setting_oauth_message_2'], 'admin.php?mod=setting#oauth');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit pengaturan favicon.
	 *
	 * This function is used to display and process edit setting favicon page.
	 *
	*/
	public function editfavicon()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_FILES)) {
			if(!empty($_FILES['picture']['tmp_name'])){
				if (file_exists('../'.DIR_INC.'/images/favicon.png')){
					unlink('../'.DIR_INC.'/images/favicon.png');
				}
				$upload = new PoUpload($_FILES['picture']);
				if ($upload->uploaded) {
					$upload->file_new_name_body = 'favicon';
					$upload->image_convert = 'png';
					$upload->image_resize = true;
					$upload->image_x = 32;
					$upload->image_y = 32;
					$upload->image_ratio = true;
					$upload->process('../'.DIR_INC.'/images/');
					if ($upload->processed) {
						$setting = array(
							'value' => $upload->file_dst_name
						);
						$query_setting = $this->podb->update('setting')
							->set($setting)
							->where('id_setting', '11');
						$query_setting->execute();
						$upload->clean();
					}
				}
				$this->poflash->success($GLOBALS['_']['setting_message_1'], 'admin.php?mod=setting#image');
			} else {
				$this->poflash->error($GLOBALS['_']['setting_message_2'], 'admin.php?mod=setting#image');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit pengaturan logo.
	 *
	 * This function is used to display and process edit setting logo page.
	 *
	*/
	public function editlogo()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_FILES)) {
			if(!empty($_FILES['picture']['tmp_name'])){
				if (file_exists('../'.DIR_INC.'/images/logo.png')){
					unlink('../'.DIR_INC.'/images/logo.png');
				}
				$upload = new PoUpload($_FILES['picture']);
				if ($upload->uploaded) {
					$upload->file_new_name_body = 'logo';
					$upload->image_convert = 'png';
					$upload->process('../'.DIR_INC.'/images/');
					if ($upload->processed) {
						$setting = array(
							'value' => $upload->file_dst_name
						);
						$query_setting = $this->podb->update('setting')
							->set($setting)
							->where('id_setting', '12');
						$query_setting->execute();
						$upload->clean();
					}
				}
				$this->poflash->success($GLOBALS['_']['setting_message_3'], 'admin.php?mod=setting#image');
			} else {
				$this->poflash->error($GLOBALS['_']['setting_message_4'], 'admin.php?mod=setting#image');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman add bahasa.
	 *
	 * This function is used to display and process add category page.
	 *
	*/
	public function addnewlang()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($_SESSION['leveluser'] == '1' OR $_SESSION['leveluser'] == '2') {
				$existslang = $this->podb->from('language')->where('code', $_POST['code'])->count();
				if ($existslang > 0) {
					$this->poflash->error($GLOBALS['_']['language_message_4'], 'admin.php?mod=setting&act=addnewlang');
				} else {
					$language = array(
						'title' => $this->postring->valid($_POST['title'], 'xss'),
						'code' => $_POST['code']
					);
					$query_language = $this->podb->insertInto('language')->values($language);
					$query_language->execute();
					$this->poflash->success($GLOBALS['_']['language_message_1'], 'admin.php?mod=setting#local');
				}
			}
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['language_addnew']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=setting&act=addnewlang', 'autocomplete' => 'off'));?>
						<div class="row">
							<div class="col-md-6">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['language_title'], 'name' => 'title', 'id' => 'title', 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-6">
							<?php
								echo $this->pohtml->inputSelectNoOpt(array('id' => 'code', 'label' => $GLOBALS['_']['language_code'], 'name' => 'code', 'mandatory' => true));
								$get_codes = new PoDirectory();
								$codes = $get_codes->listDir('../'.DIR_INC.'/images/flag/');
								foreach($codes as $code) {
									if ($code != 'index.html') {
										$expcode = explode('.', $code);
										?>
										<option value="<?=$expcode[0];?>"><?=$expcode[0];?></option>
										<?php
									}
								}
								echo $this->pohtml->inputSelectNoOptEnd();
							?>
							</div>
							<div class="col-md-12">
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit bahasa.
	 *
	 * This function is used to display and process edit language page.
	 *
	*/
	public function editlang()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			if ($_SESSION['leveluser'] == '1' OR $_SESSION['leveluser'] == '2') {
				$query_clanguage = $this->podb->update('language')
					->set(array('code' => ''))
					->where('id_language', $this->postring->valid($_POST['id'], 'sql'));
				$query_clanguage->execute();
				$existslang = $this->podb->from('language')->where('code', $_POST['code'])->count();
				if ($existslang > 0) {
					$query_clanguage = $this->podb->update('language')
						->set(array('code' => $_POST['old_code']))
						->where('id_language', $this->postring->valid($_POST['id'], 'sql'));
					$query_clanguage->execute();
					$this->poflash->error($GLOBALS['_']['language_message_5'], 'admin.php?mod=setting&act=editlang&id='.$this->postring->valid($_POST['id'], 'sql'));
				} else {
					$language = array(
						'title' => $this->postring->valid($_POST['title'], 'xss'),
						'code' => $_POST['code'],
						'active' => $this->postring->valid($_POST['active'], 'xss')
					);
					$query_language = $this->podb->update('language')
						->set($language)
						->where('id_language', $this->postring->valid($_POST['id'], 'sql'));
					$query_language->execute();
					$this->poflash->success($GLOBALS['_']['language_message_2'], 'admin.php?mod=setting#local');
				}
			}
		}
		$id = $this->postring->valid($_GET['id'], 'sql');
		$current_language = $this->podb->from('language')
			->where('id_language', $id)
			->limit(1)
			->fetch();
		if (empty($current_language)) {
			echo $this->pohtml->error();
			exit;
		}
		?>
		<div class="block-content">
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->headTitle($GLOBALS['_']['language_edit']);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?=$this->pohtml->formStart(array('method' => 'post', 'action' => 'route.php?mod=setting&act=editlang', 'autocomplete' => 'off'));?>
						<?=$this->pohtml->inputHidden(array('name' => 'id', 'value' => $current_language['id_language']));?>
						<?=$this->pohtml->inputHidden(array('name' => 'old_code', 'value' => $current_language['code']));?>
						<div class="row">
							<div class="col-md-4">
								<?=$this->pohtml->inputText(array('type' => 'text', 'label' => $GLOBALS['_']['language_title'], 'name' => 'title', 'id' => 'title', 'value' => $current_language['title'], 'mandatory' => true, 'options' => 'required'));?>
							</div>
							<div class="col-md-4">
							<?php
								echo $this->pohtml->inputSelectNoOpt(array('id' => 'code', 'label' => $GLOBALS['_']['language_code'], 'name' => 'code', 'mandatory' => true));
								$get_codes = new PoDirectory();
								$codes = $get_codes->listDir('../'.DIR_INC.'/images/flag/');
								foreach($codes as $code) {
									if ($code != 'index.html') {
										$expcode = explode('.', $code);
										if ($current_language['code'] == $expcode[0]) {
											$selectedlang = "selected";
										} else {
											$selectedlang = "";
										}
										?>
										<option value="<?=$expcode[0];?>" <?=$selectedlang;?>><?=$expcode[0];?></option>
										<?php
									}
								}
								echo $this->pohtml->inputSelectNoOptEnd();
							?>
							</div>
							<div class="col-md-4">
								<?php
									if ($current_language['active'] == 'N') {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => '', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => 'checked', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['language_active'], 'mandatory' => true), $radioitem, $inline = false);
									} else {
										$radioitem = array(
											array('name' => 'active', 'id' => 'active', 'value' => 'Y', 'options' => 'checked', 'title' => 'Y'),
											array('name' => 'active', 'id' => 'active', 'value' => 'N', 'options' => '', 'title' => 'N')
										);
										echo $this->pohtml->inputRadio(array('label' => $GLOBALS['_']['language_active'], 'mandatory' => true), $radioitem, $inline = false);
									}
								?>
							</div>
							<div class="col-md-12">
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
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus pengaturan.
	 *
	 * This function is used to display and process delete setting page.
	 *
	*/
	public function delete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$id = array('1');
			if (in_array($this->postring->valid($_POST['id'], 'sql'), $id)) {
				$this->poflash->error($GLOBALS['_']['language_message_6'], 'admin.php?mod=setting#local');
			} else {
				$query = $this->podb->deleteFrom('language')->where('id_language', $this->postring->valid($_POST['id'], 'sql'));
				$query->execute();
				$this->poflash->success($GLOBALS['_']['language_message_3'], 'admin.php?mod=setting#local');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman hapus multi pengaturan.
	 *
	 * This function is used to display and process multi delete setting page.
	 *
	*/
	public function multidelete()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'delete')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$totaldata = $this->postring->valid($_POST['totaldata'], 'xss');
			if ($totaldata != "0") {
				$items = $_POST['item'];
				foreach($items as $item){
					$id = array('1');
					if (!in_array($this->postring->valid($item['deldata'], 'sql'), $id)) {
						$query = $this->podb->deleteFrom('language')->where('id_language', $this->postring->valid($item['deldata'], 'sql'));
						$query->execute();
					}
				}
				$this->poflash->success($GLOBALS['_']['language_message_3'], 'admin.php?mod=setting#local');
			} else {
				$this->poflash->error($GLOBALS['_']['language_message_6'], 'admin.php?mod=setting#local');
			}
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses peta situs.
	 *
	 * This function is used to display and process sitemap.
	 *
	*/
	public function sitemap()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'create')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$changefreq = $this->postring->valid($_POST['changefreq'],'xss');
			$priority = $this->postring->valid($_POST['priority'],'xss');
			$sitemap = new Sitemap($this->posetting[1]['value']);
            $sitemap->setPath('../');
			$sitemap->addItem('/', $priority, $changefreq, $this->podatetime->date_now);
			$datapagess = $this->podb->from('pages')->where('active', 'Y')->fetchAll();
			foreach($datapagess as $datapages){
				$sitemap->addItem('/pages/'.$datapages['seotitle'], $priority, $changefreq, $this->podatetime->date_now);
			}
			$datacats = $this->podb->from('category')->where('active', 'Y')->fetchAll();
			foreach($datacats as $datacat){
				$sitemap->addItem('/category/'.$datacat['seotitle'], $priority, $changefreq, $this->podatetime->date_now);
			}
			$dataposts = $this->podb->from('post')->where('active', 'Y')->fetchAll();
			foreach($dataposts as $dataposts){
				$sitemap->addItem('/detailpost/'.$dataposts['seotitle'], $priority, $changefreq, $dataposts['date']);
			}
			$sitemap->createSitemapIndex($this->posetting[1]['value'], 'Today');
			$this->poflash->success($GLOBALS['_']['setting_sitemap_message'], 'admin.php?mod=setting#config');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses meta sosial.
	 *
	 * This function is used to display and process meta social.
	 *
	*/
	public function metasocial()
	{
		if (!$this->auth($_SESSION['leveluser'], 'setting', 'update')) {
			echo $this->pohtml->error();
			exit;
		}
		if (!empty($_POST)) {
			$filename = "../".DIR_CON."/component/setting/meta_social.txt";
			if (file_exists("$filename")) {
				$newdata = stripslashes($_POST['meta_content']);
				if ($newdata != ''){
					$fw = fopen($filename, 'w') or die('Could not open file!');
					$fb = fwrite($fw,$newdata) or die('Could not write to file');
					fclose($fw);
				}
			}
			$this->poflash->success($GLOBALS['_']['setting_meta_social_message'], 'admin.php?mod=setting#config');
		}
	}

	/**
	 * Fungsi ini digunakan untuk menampilkan dan memproses halaman edit pengaturan logo.
	 *
	 * This function is used to display and process edit setting logo page.
	 *
	*/
	public function get_timezone()
	{
		$timezoneIdentifiers = DateTimeZone::listIdentifiers();
		$utcTime = new DateTime('now', new DateTimeZone('UTC'));
		$tempTimezones = array();
		foreach($timezoneIdentifiers as $timezoneIdentifier){
			$currentTimezone = new DateTimeZone($timezoneIdentifier);
			$tempTimezones[] = array(
				'offset' => (int)$currentTimezone->getOffset($utcTime),
				'identifier' => $timezoneIdentifier
			);
		}
		function sort_list($a, $b){
			return ($a['offset'] == $b['offset']) 
				? strcmp($a['identifier'], $b['identifier'])
				: $a['offset'] - $b['offset'];
		}
		usort($tempTimezones, "sort_list");
		$timezoneList = array();
		foreach($tempTimezones as $key => $tz){
			$sign = ($tz['offset'] > 0) ? '+' : '-';
			$offset = gmdate('H:i', abs($tz['offset']));
			$timezoneList[$key]['value'] = $tz['identifier'];
			$timezoneList[$key]['text'] = '(UTC ' . $sign . $offset . ') ' . $tz['identifier'];
		}
		echo json_encode($timezoneList);
	}

}