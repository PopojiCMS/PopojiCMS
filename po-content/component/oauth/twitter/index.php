<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser']) AND $_SESSION['login'] == 0) {
	header('location:../../../../.');
} else {
include_once '../../../../po-includes/core/core.php';
require_once 'Twitter/twitteroauth.php';

$tableoauthtw = new PoCore();
$currentOauthtw = $tableoauthtw->podb->from('oauth')->fetchAll();
$conkeyOauthtw = $currentOauthtw[1]['oauth_key'];
$consecretOauthtw = $currentOauthtw[1]['oauth_secret'];

define('CONSUMER_KEY', ''.$conkeyOauthtw.'');
define('CONSUMER_SECRET', ''.$consecretOauthtw.'');
define('OAUTH_CALLBACK', ''.WEB_URL.DIR_CON.'/component/oauth/twitter/index.php');

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, @$_SESSION['oauth_token'], @$_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
if (isset($_REQUEST['oauth_verifier'])) {
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
}

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if ($connection->http_code == 200) {
	/* Save the access tokens. Normally these would be saved in a database for future use. */
	$tokenuser = $access_token['oauth_token'];
	$tokenuser_secret = $access_token['oauth_token_secret'];
	$twuserid = $access_token['user_id'];
	$twusername = $access_token['screen_name'];

	/* Remove no longer needed request tokens */
	unset($_SESSION['oauth_token']);
	unset($_SESSION['oauth_token_secret']);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Connect twitter to your website for automatic publish content to twitter from your post">
		<meta name="author" content="PopojiCMS">
		<link rel="shortcut icon" href="../../../../<?=DIR_INC;?>/images/favicon.png">
		<title>PopojiCMSOAuth 2.0 - Twitter</title>
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
			<p>Connect twitter to your website for automatic publish<br />content to twitter from your post.</p>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<form role="form" method="post" action="save_oauth.php" autocomplete="off">
						<div class="panel panel-default">
							<div class="panel-heading"><span class="glyphicon glyphicon-link"></span> Connect Twitter From User</div>
							<ul class="list-group">
								<li class="list-group-item">Twitter Id
									<input type="text" class="form-control" name="twuserid" value="<?php echo $twuserid; ?>" readonly />
								</li>
								<li class="list-group-item">Twitter Username
									<input type="text" class="form-control" name="twusername" value="<?php echo $twusername; ?>" readonly />
								</li>
								<li class="list-group-item">Twitter Token
									<input type="text" class="form-control" name="tokenuser" value="<?php echo $tokenuser; ?>" readonly />
								</li>
								<li class="list-group-item">Twitter Token Secret
									<input type="text" class="form-control" name="tokenuser_secret" value="<?php echo $tokenuser_secret; ?>" readonly />
								</li>
							</ul>
							<div class="panel-footer"></div>
						</div>
						<button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-transfer"></span> Connect User</button>
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
	</body>
</html>
<?php } else { ?>
	<script language="javascript" type="text/javascript">
		window.location.href="oauth.php";
	</script>
<?php
	}
}
?>