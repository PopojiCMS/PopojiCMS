<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:../../../../.');
} else {
include_once '../../../../po-includes/core/core.php';
require_once 'Facebook/autoload.php';

	$tableoauthfb = new PoCore();
	$currentOauthfb = $tableoauthfb->podb->from('oauth')->fetchAll();
	$appIdOauthfb = $currentOauthfb[0]['oauth_key'];
	$secretOauthfb = $currentOauthfb[0]['oauth_secret'];

	$fb = new Facebook\Facebook([
		'app_id'  => $appIdOauthfb,
		'app_secret' => $secretOauthfb,
		'default_graph_version' => 'v2.5'
	]);

	$helper = $fb->getRedirectLoginHelper();
	$loginUrl = $helper->getLoginUrl(WEB_URL.DIR_CON.'/component/oauth/facebook/oauth.php', ['public_profile', 'email', 'manage_pages', 'publish_actions']);
	header('location:'.$loginUrl);
}
?>