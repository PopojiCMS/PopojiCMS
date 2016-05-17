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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di kontak.
 * This is a main javascript file from PopojiCMS which contains all javascript in contact.
 *
*/

$(document).ready(function() {
	var table = $('#table-contact').DataTable({
		"autoWidth": false,
		"responsive": true,
		"order": [[1, 'desc']],
		"columnDefs": [{
		  "targets" : 'no-sort',
		  "orderable" : false
		}],
		"stateSave": true,
		"serverSide": true,
		"processing": true,
		"pageLength": 10,
		"lengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			'type': 'post',
			'url': 'route.php?mod=contact&act=datatable'
		},
		"drawCallback": function(settings) {
			$("#titleCheck").click(function() {
				var checkedStatus = this.checked;
				$("table tbody tr td div:first-child input[type=checkbox]").each(function() {
					this.checked = checkedStatus;
					if (checkedStatus == this.checked) {
						$(this).closest('table tbody tr').removeClass('table-select');
						$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
						$('#totaldata').val($('form input[type=checkbox]:checked').size());
					}
					if (this.checked) {
						$(this).closest('table tbody tr').addClass('table-select');
						$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
						$('#totaldata').val($('form input[type=checkbox]:checked').size());
					}
				});
			});	
			$('table tbody tr td div:first-child input[type=checkbox]').on('click', function () {
				var checkedStatus = this.checked;
				this.checked = checkedStatus;
				if (checkedStatus == this.checked) {
					$(this).closest('table tbody tr').removeClass('table-select');
					$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
					$('#totaldata').val($('form input[type=checkbox]:checked').size());
				}
				if (this.checked) {
					$(this).closest('table tbody tr').addClass('table-select');
					$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
					$('#totaldata').val($('form input[type=checkbox]:checked').size());
				}
			});
			$('table tbody tr td div:first-child input[type=checkbox]').change(function() {
				$(this).closest('tr').toggleClass("table-select", this.checked);
			});
			$('.alertdel').click(function(){
				var id = $(this).attr("id");
				$('#alertdel').modal('show');
				$('#delid').val(id);
			});
			$('.viewdata').click(function(){
				var id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "route.php?mod=contact&act=viewdata",
					data: 'id='+ id,
					cache: false,
					success: function(html){
						$('#viewdata').modal('show');
						$('#viewdata .modal-body').html(html);
					}
				});
			});
			$('.readdata').click(function(){
				var id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "route.php?mod=contact&act=readdata",
					data: 'id='+ id,
					cache: false,
					success: function(data){
						if (data == 'Y') {
							$('#read'+id).removeClass('fa-circle');
							$('#read'+id).addClass('fa-circle-o');
							$('#read'+id).parent().attr("data-original-title", "Mark as Unread");
						} else {
							$('#read'+id).removeClass('fa-circle-o');
							$('#read'+id).addClass('fa-circle');
							$('#read'+id).parent().attr("data-original-title", "Mark as Read");
						}
					}
				});
			});
			$('.alertreply').click(function(){
				var name = $(this).attr("data-name");
				var email = $(this).attr("data-email");
				var subject = $(this).attr("data-subject");
				$('#alertreply').modal('show');
				$('#name').val('');
				$('#name').val(name);
				$('#email').val('');
				$('#email').val(email);
				$('#subject').val('');
				$('#subject').val('RE: ' + subject +'');
			});
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	$('#table-contact').on('click','.alertdel', function () {
		var id = $(this).attr("id");
		$('#alertdel').modal('show');
		$('#delid').val(id);
	});
});

$(document).ready(function() {
	tinymce.init({
		selector: ".textarea-editor",
		skin: "lightgray",
		plugins: [
			"advlist autolink link image lists charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			"table contextmenu directionality emoticons paste textcolor",
			"code fullscreen youtube autoresize"
		],
		menubar : false,
		toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent table",
		toolbar2: "| fontsizeselect | styleselect | link unlink anchor | forecolor backcolor ",
		image_advtab: true,
		fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
		relative_urls: false,
		remove_script_host: false
	});
});