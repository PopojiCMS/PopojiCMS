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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di pengguna.
 * This is a main javascript file from PopojiCMS which contains all javascript in user.
 *
*/

$(document).ready(function() {
	$('#table-user').buildtable('route.php?mod=user&act=datatable');
});

$(document).ready(function() {
	$('#table-user-level').buildtable('route.php?mod=user&act=datatable2', 'asc');
});

$(document).ready(function() {
	$('#username').bind('input', function(){
		$(this).val(function(_, v){
			return v.replace(/\s+/g, '');
		});
	});
});

$(document).ready(function() {
	$("#change-lock-type").click(function(){
		$("#locktype").val('1');
		$("#newpassword").val('');
		$(".box-password").hide();
		$(".box-password-lock").show();
	});

	$("#change-lock-type-2").click(function(){
		$("#locktype").val('0');
		$("#newpassword").val('');
		$(".box-password").show();
		$(".box-password-lock").hide();
	});

	$("#change-pattern").click(function(){
		var lock = new PatternLock('#patternHolder',{
			margin:18,
			onDraw:function(pattern){
				var patternval = lock.getPattern();
				$("#newpassword").val(patternval);
			}
		});
	});
});

$(document).ready(function() {
	$("#checkallrole").click(function(){
		$('#table-role input:checkbox').not(this).prop('checked', this.checked);
	});
});