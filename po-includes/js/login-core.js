/*
 *
 * - PopojiCMS Javascript
 *
 * - File : login-core.js
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di halaman login.
 * This is a main javascript file from PopojiCMS which contains all javascript in login page.
 *
*/


function showPassword() {
    var key_attr = $('#login-password').attr('type');
    if(key_attr != 'text') {
		$('.checkbox').addClass('show');
		$('#login-password').attr('type', 'text');
    } else {
		$('.checkbox').removeClass('show');
		$('#login-password').attr('type', 'password');
    }
}

$(function() {
	$("#login-username").on('keyup', function() {
		var username = $('#login-username').val();
		var dataString = 'username='+ username;
		$.ajax({
			type: "POST",
			url: "route.php?mod=login&act=searchlocktype",
			data: dataString,
			cache: false,
			success: function(data){
				if (data == "0") {
					$('.box-password').show();
					$('.box-checkbox').show();
					$('.box-action').show();
					$('.box-password-lock').hide();
					$('.box-con-password').html('<input type="password" id="login-password" name="password" class="form-control" placeholder="Password" />');
					$('#login-password').focus();
					$('.box-con-password-lock').html('');
				} else if (data == "1") {
					$('.box-password').hide();
					$('.box-checkbox').hide();
					$('.box-action').hide();
					$('.box-password-lock').show();
					$('.box-con-password').html('');
					$('.box-con-password-lock').html('<input type="hidden" id="login-password-lock" name="password" />');
				} else {
					$('.box-password').hide();
					$('.box-checkbox').hide();
					$('.box-action').hide();
					$('.box-password-lock').hide();
					$('.box-con-password').html('');
					$('.box-con-password-lock').html('');
				}
			}
		});
		return false;
	});

	var lock = new PatternLock('#patternHolder',{
		margin:10,
		onDraw:function(pattern){
			var patternval = lock.getPattern();
			$("#login-password-lock").val(patternval);
			$("#form-login").submit();
		}
	});
});