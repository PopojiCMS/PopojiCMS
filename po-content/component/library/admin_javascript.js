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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di pustaka.
 * This is a main javascript file from PopojiCMS which contains all javascript in library.
 *
*/

$(document).ready(function() {
	$('#table-library').buildtable('route.php?mod=library&act=datatable');
});