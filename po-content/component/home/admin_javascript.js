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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di home.
 * This is a main javascript file from PopojiCMS which contains all javascript in home.
 *
*/

$(document).ready(function() {
	window.onload = function(){
		var ctx = document.getElementById("canvas-stats").getContext("2d");
		window.myLine = new Chart(ctx).Line(datastats, {
			responsive: true
		});
	}
});