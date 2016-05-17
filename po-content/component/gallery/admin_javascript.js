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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di galeri.
 * This is a main javascript file from PopojiCMS which contains all javascript in gallery.
 *
*/

$(document).ready(function() {
	$('#table-gallery').buildtable('route.php?mod=gallery&act=datatable');
});

$(document).ready(function() {
	$('#table-album').buildtable('route.php?mod=gallery&act=datatable2');
});