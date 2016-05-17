<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:../../../../.');
} else {
include_once '../../../../po-includes/core/core.php';
require_once 'Facebook/autoload.php';
if (!empty($_POST)) {
	$tableoauthfb = new PoCore();
	$fb_type = $_POST['fbtype'];
	if ($fb_type == "user") {
		$oauth = array(
			'oauth_id' => $_POST['fbid'],
			'oauth_user' => $_POST['fbusername'],
			'oauth_token1' => $_POST['fbtoken'],
			'oauth_fbtype' => $_POST['fbtype']
		);
	} else {
		$oauth = array(
			'oauth_id' => $_POST['fbpagesid'],
			'oauth_user' => $_POST['fbusername'],
			'oauth_token1' => $_POST['fbtoken'],
			'oauth_fbtype' => $_POST['fbtype']
		);
	}
	$query_setting = $tableoauthfb->podb->update('oauth')
		->set($oauth)
		->where('id_oauth', '1');
	$query_setting->execute();
	$tableoauthfb->poflash->success('OAuth has been successfully updated', WEB_URL.DIR_ADM.'/admin.php?mod=setting#oauth');
}
}
?>