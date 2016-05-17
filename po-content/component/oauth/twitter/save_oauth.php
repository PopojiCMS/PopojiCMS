<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:../../../../.');
} else {
include_once '../../../../po-includes/core/core.php';
require_once 'Twitter/twitteroauth.php';
if (!empty($_POST)) {
	$tableoauthtw = new PoCore();
	$oauth = array(
		'oauth_id' => $_POST['twuserid'],
		'oauth_user' => $_POST['twusername'],
		'oauth_token1' => $_POST['tokenuser'],
		'oauth_token2' => $_POST['tokenuser_secret']
	);
	$query_setting = $tableoauthtw->podb->update('oauth')
		->set($oauth)
		->where('id_oauth', '2');
	$query_setting->execute();
	$tableoauthtw->poflash->success('OAuth has been successfully updated', WEB_URL.DIR_ADM.'/admin.php?mod=setting#oauth');
}
}
?>