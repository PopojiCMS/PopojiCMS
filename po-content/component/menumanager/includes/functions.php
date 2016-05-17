<?php
/**
 * Generate full url
 * example:
 * echo site_url('news.list');
 *
 * output:
 * http://yoursite.com/index.php?act=news.list
 *
 * @param string $url
 * @return string
*/
 
function site_url($url = '')
{
	if (!empty($url)) {
		return 'admin.php?mod=menumanager&act=' . $url;
	}
	return 'admin.php?mod=menumanager';
}

/**
 * Easy redirect
 * example:
 * redirect('news.list');
 *
 * @param string $url
*/
function redirect($url)
{
	$url = site_url($url);
	header("location: $url");
	die;
}
?>