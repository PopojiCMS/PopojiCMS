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

$(document).ready(function() {
	$('#from_stat').datetimepicker({
		format: 'YYYY-MM-DD',
		showTodayButton: true,
		showClear: true
	});
	$("#from_stat").mask("9999-99-99");

	$('#to_stat').datetimepicker({
		format: 'YYYY-MM-DD',
		showTodayButton: true,
		showClear: true
	});
	$("#to_stat").mask("9999-99-99");

	$("#from_stat").on("dp.change",function (e) {
		$('#to_stat').data("DateTimePicker").minDate(e.date);
	});

	$("#to_stat").on("dp.change",function (e) {
		$('#from_stat').data("DateTimePicker").maxDate(e.date);
	});

	$('#clearDate').click(function() {
		$('#from_stat').data("DateTimePicker").minDate(false);
		$('#to_stat').data("DateTimePicker").maxDate(false);
		return false;
	});
});