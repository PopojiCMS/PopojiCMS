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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di halaman.
 * This is a main javascript file from PopojiCMS which contains all javascript in pages.
 *
*/

$(document).ready(function() {
	$('#table-pages').buildtable('route.php?mod=pages&act=datatable');
});

$(document).ready(function() {
	$('#title-1').on('input', function() {
		var permalink;
		permalink = $.trim($(this).val());
		permalink = permalink.replace(/\s+/g,' ');
		$('#seotitle').val(permalink.toLowerCase());
		$('#seotitle').val($('#seotitle').val().replace(/\W/g, ' '));
		$('#seotitle').val($.trim($('#seotitle').val()));
		$('#seotitle').val($('#seotitle').val().replace(/\s+/g, '-'));
		var gappermalink = $('#seotitle').val();
		$('#permalink').html(gappermalink);
	});

	$('#seotitle').on('input', function() {
		var permalink;
		permalink = $(this).val();
		permalink = permalink.replace(/\s+/g,' ');
		$('#seotitle').val(permalink.toLowerCase());
		$('#seotitle').val($('#seotitle').val().replace(/\W/g, ' '));
		$('#seotitle').val($('#seotitle').val().replace(/\s+/g, '-'));
		var gappermalink = $('#seotitle').val();
		$('#permalink').html(gappermalink);
	});

	$('.del-image').click(function () {
		var id = $(this).attr("id");
		$('#alertdelimg').modal('show');
		$('.btn-del-image').attr("id",id);
	});

	$('.btn-del-image').click(function () {
		var id = $(this).attr("id");
		var dataString = 'id='+ id;
		$.ajax({
			type: "POST",
			url: "route.php?mod=pages&act=delimage",
			data: dataString,
			cache: false,
			success: function(data){
				$('#alertdelimg').modal('hide');
				$('#image-box').hide();
				$('#picture').val('');
			}
		});
		return false;
	});

	initMCEall();

	$('.tiny-text').on('click', function (e) {
		e.stopPropagation();
		var id = $(this).attr("data-lang");
		tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'po-wysiwyg-'+id);
	});

	$('.tiny-visual').on('click', function (e) {
		e.stopPropagation();
		var id = $(this).attr("data-lang");
		tinymce.EditorManager.execCommand('mceAddEditor',true, 'po-wysiwyg-'+id);
	});
});