<?php
session_start();
include_once '../../../../po-includes/core/core.php';
require_once '../../../../po-content/component/oauth/facebook/Facebook/autoload.php';

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

	try {
		$accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	if (!isset($accessToken)) {
		if ($helper->getError()) {
			header('HTTP/1.0 401 Unauthorized');
			echo "Error: " . $helper->getError() . "\n";
			echo "Error Code: " . $helper->getErrorCode() . "\n";
			echo "Error Reason: " . $helper->getErrorReason() . "\n";
			echo "Error Description: " . $helper->getErrorDescription() . "\n";
		} else {
			header('HTTP/1.0 400 Bad Request');
			echo 'Bad request';
		}
		exit;
	}

	$oAuth2Client = $fb->getOAuth2Client();

	if (!$accessToken->isLongLived()) {
		try {
			$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		}
		catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
			exit;
		}
	}

	$_SESSION['fb_access_token'] = (string) $accessToken;
	$get_users = $fb->get('/me?fields=id,name,email', $accessToken);
	$user = $get_users->getGraphUser();
	$get_pages = $fb->get('/me/accounts', $accessToken);
	$pages = $get_pages->getGraphEdge();

	$user_count = $tableoauthfb->podb->from('users')
		->where('username', $user['email'])
		->where('level', '4')
		->where('block', 'Y')
		->count();
	if ($user_count > 0) {
		$user_data = $tableoauthfb->podb->from('users')
			->where('username', $user['email'])
			->where('level', '4')
			->where('block', 'Y')
			->limit(1)
			->fetch();
		$timeout = new PoTimeout;
		$timeout->rec_session_member($user_data);
		$timeout->timer_member();
		$sid_lama = session_id();
		session_regenerate_id();
		$sid_baru = session_id();
		$sesi = array(
			'id_session' => $sid_baru
		);
		$query = $tableoauthfb->podb->update('users')
			->set($sesi)
			->where('username', $user['email']);
		$query->execute();
		header('location:'.WEB_URL.'member');
	} else {
		$last_user = $tableoauthfb->podb->from('users')->limit(1)->orderBy('id_user DESC')->fetch();
		$data = array(
			'id_user' => $last_user['id_user']+1,
			'username' => $user['email'],
			'password' => '',
			'nama_lengkap' => $user['name'],
			'email' => $user['email'],
			'level' => '4',
			'tgl_daftar' => date('Ymd'),
			'block' => 'Y',
			'id_session' => md5($user['id'])
		);
		$query = $tableoauthfb->podb->insertInto('users')->values($data);
		$query->execute();
		$user_data = $tableoauthfb->podb->from('users')
			->where('username', $user['email'])
			->where('level', '4')
			->where('block', 'Y')
			->limit(1)
			->fetch();
		$timeout = new PoTimeout;
		$timeout->rec_session_member($user_data);
		$timeout->timer_member();
		header('location:'.WEB_URL.'member');
	}
?>