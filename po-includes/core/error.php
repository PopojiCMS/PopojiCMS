<?php
/**
 *
 * - PopojiCMS Core
 *
 * - File : error.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah library untuk menangani request error.
 * This is library for handling error request.
 *
 * Contoh untuk penggunaan class ini
 * Example for uses this class
 *
 *
 * $error = new PoError;
 * $error->notfound();
 *
*/

class PoError
{

	function __construct(){}

	/**
	 * Fungsi ini digunakan untuk menangani request error notfound
	 *
	 * This function use for handling request error notfound.
	 *
	*/
	public function notfound()
	{
		header("HTTP/1.1 404 Not Found");
		?>
		<html>
			<head>
				<title>404 Not Found</title>
				<link rel="shortcut icon" href="../po-includes/images/favicon.png" />
				<link type="text/css" rel="stylesheet" href="../po-includes/css/bootstrap.min.css" />
			</head>
		<body>
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-center">
						<h1 class="page-header">Page Not Found <small class="text-danger">Error 404</small></h1>
						<p>
							The page you requested could not be found, either contact your webmaster or try again.<br />
							Use your browsers <b>Back</b> button to navigate to the page<br />
							you have previously come from.
						</p>
					</div>
				</div>
			</div>
		</body>
		</html>
		<?php
	}

	/**
	 * Fungsi ini digunakan untuk menangani request error forbidden
	 *
	 * This function use for handling request error forbidden.
	 *
	*/
	public function forbidden()
	{
		header("HTTP/1.1 403 Forbidden");
		?>
		<html>
			<head>
				<title>403 Forbidden</title>
				<link rel="shortcut icon" href="../po-includes/images/favicon.png" />
				<link type="text/css" rel="stylesheet" href="../po-includes/css/bootstrap.min.css" />
			</head>
		<body>
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-center">
						<h1 class="page-header">Page Forbidden <small class="text-danger">Error 403</small></h1>
						<p>
							You don't have permission to access this page, either contact your webmaster or try again.<br />
							Use your browsers <b>Back</b> button to navigate to the page<br />
							you have previously come from.
						</p>
					</div>
				</div>
			</div>
		</body>
		</html>
		<?php
	}

}