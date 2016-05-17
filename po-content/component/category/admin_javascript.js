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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di kategori.
 * This is a main javascript file from PopojiCMS which contains all javascript in category.
 *
*/

$(document).ready(function() {
	$('#table-category').buildtable('route.php?mod=category&act=datatable');
});