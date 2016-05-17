/*
 *
 * - PopojiCMS Javascript
 *
 * - File : admin_javascript.js
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di tag.
 * This is a main javascript file from PopojiCMS which contains all javascript in tag.
 *
*/

$(document).ready(function() {
	$('#table-tag').buildtable('route.php?mod=tag&act=datatable');
});

$(document).ready(function() {
	$('#tag').tagsinput();
});