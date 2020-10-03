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

$(document).ready(function() {
	$('#form_album').submit(function() {
		$.ajax({
			type: "POST",
			url: "route.php?mod=gallery&act=addnewalbum",
			data: "modal="+$('#modal').val()+"&title="+$('#title_album').val(),
			dataType: 'html',
			cache: false,
			success: function(data){
				$.ajax({
					type: "GET",
					url: "route.php?mod=gallery&act=fetchalbum",
					dataType: 'json',
					cache: false,
					success: function(data){
						$('#id_album').html('');
						$.each(data, function(index, element) {
							$("#id_album").append('<option value=' + element.id_album + '>' + element.title + '</option>');
				        });
				        $('#modal_album').modal('hide');
					}
				});
			}
		});
	});

	$('#modal_album').on('hidden.bs.modal', function (e) {
		$(this)
			.find("input,textarea,select").val('').end()
			.find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
	});
});