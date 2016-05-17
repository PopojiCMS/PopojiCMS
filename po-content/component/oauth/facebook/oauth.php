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
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="PopojiCMSOAuth 1.0 - Connect facebook to your website for automatic publish content to facebook from your post">
		<meta name="author" content="PopojiCMS">
		<link rel="shortcut icon" href="../../../../<?=DIR_INC;?>/images/favicon.png">
		<title>PopojiCMSOAuth 2.0 - Facebook</title>
		<!-- Bootstrap core CSS -->
		<link href="../../../../<?=DIR_INC;?>/css/bootstrap.min.css" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->

		<style type="text/css">
			body {
				background: #ffffff;
				font-family: 'Open Sans', sans-serif;
				font-size: 12px;
				color: #666666;
				padding: 0px;
			}
			.jumbotron {
				background: #428bca;
				color: #ffffff;
				padding: 50px;
			}
			.jumbotron h1 {
				font-size: 36px;
			}
			.jumbotron p {
				font-size: 20px;
			}
			#footer .container {
				padding: 40px 0 10px 0;
			}
			.text-muted {
				padding: 20px;
			}
		</style>
	</head>
	<body>
		<div class="jumbotron text-center">
			<h1>PopojiCMSOAuth 2.0</h1>
			<p>Connect facebook to your website for automatic publish<br />content to facebook from your post.</p>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<form role="form" method="post" action="save_oauth.php" autocomplete="off">
						<input type="hidden" name="fbtype" value="user" />
						<input type="hidden" name="fbtoken" value="<?php echo $accessToken; ?>" />
						<div class="panel panel-default">
							<div class="panel-heading"><span class="glyphicon glyphicon-link"></span> Connect Facebook From User</div>
							<ul class="list-group">
								<li class="list-group-item">Facebook Id
									<input type="text" class="form-control" name="fbid" value="<?php echo $user['id']; ?>" readonly />
								</li>
								<li class="list-group-item">Facebook Username
									<input type="text" class="form-control" name="fbusername" value="<?php echo $user['email']; ?>" readonly />
								</li>
								<li class="list-group-item">Facebook Fullname
									<input type="text" class="form-control" name="fbfullname" value="<?php echo $user['name']; ?>" readonly />
								</li>
							</ul>
							<div class="panel-footer"></div>
						</div>
						<button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-transfer"></span> Connect User</button>
					</form>
					<p>&nbsp;</p><p>&nbsp;</p>
				</div>
				<div class="col-md-6">
					<form role="form" method="post" action="save_oauth.php" autocomplete="off">
						<input type="hidden" name="fbtype" value="pages" />
						<input type="hidden" name="fbtoken" value="<?php echo $accessToken; ?>" />
						<input type="hidden" name="fbid" value="<?php echo $user['id']; ?>" />
						<input type="hidden" name="fbusername" value="<?php echo $user['email']; ?>" />
						<input type="hidden" name="fbfullname" value="<?php echo $user['name']; ?>" />
						<div class="panel panel-default">
							<div class="panel-heading"><span class="glyphicon glyphicon-link"></span> Connect Facebook From Pages</div>
							<ul class="list-group">
								<li class="list-group-item">Facebook Pages
									<select id="fbpages" class="form-control">
										<?php
											foreach ($pages as $page) {
												echo '<option value="'.$page["id"].'">'.$page["name"].'</option>';
											}
										?>
									</select>
								</li>
								<li class="list-group-item">Facebook Pages Id
									<input type="text" class="form-control" name="fbpagesid" id="fbpagesid" value="" readonly />
								</li>
								<li class="list-group-item">Facebook Pages Name
									<input type="text" class="form-control" name="fbpagesname" id="fbpagesname" value="" readonly />
								</li>
							</ul>
							<div class="panel-footer"></div>
						</div>
						<button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-transfer"></span> Connect Pages</button>
					</form>
				</div>
			</div>
		</div>
		<div id="footer">
			<div class="container">
				<p class="text-muted">PopojiCMSOAuth 2.0 &copy; 2014-2016. PopojiCMS Team. All Rights Reserved.</p>
			</div>
		</div>
		<script type="text/javascript" src="../../../../<?=DIR_INC;?>/js/jquery/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../../../../<?=DIR_INC;?>/js/bootstrap/bootstrap.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#fbpages').on('click', function (e) {
					var optionSelected = $("option:selected", this);
					var valueSelected = optionSelected.val();
					var valueSelectedT = optionSelected.text();
					$("#fbpagesid").val(valueSelected);
					$("#fbpagesname").val(valueSelectedT);
				});
			});
		</script>
	</body>
</html>
<?php
}
?>