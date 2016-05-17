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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di komentar.
 * This is a main javascript file from PopojiCMS which contains all javascript in comment.
 *
*/

$(document).ready(function() {
	var table = $('#table-comment').DataTable({
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
			'url': 'route.php?mod=comment&act=datatable'
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
			$('.publishdata').click(function(){
				var id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "route.php?mod=comment&act=publishdata",
					data: 'id='+ id,
					cache: false,
					success: function(data){
						if (data == 'Y') {
							$('#publish-span'+id).html('Y');
							$('#publish'+id).parent().attr("data-original-title", "Unpublish Comment");
						} else {
							$('#publish-span'+id).html('N');
							$('#publish'+id).parent().attr("data-original-title", "Publish Comment");
						}
					}
				});
			});
			$('.viewdata').click(function(){
				var id = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "route.php?mod=comment&act=viewdata",
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
					url: "route.php?mod=comment&act=readdata",
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
				var idparent = $(this).attr("id");
				var idpost = $(this).attr("data-post");
				$('#alertreply').modal('show');
				$('#id_parent').val('');
				$('#id_parent').val(idparent);
				$('#id_post').val('');
				$('#id_post').val(idpost);
			});
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	$('#table-comment').on('click','.alertdel', function () {
		var id = $(this).attr("id");
		$('#alertdel').modal('show');
		$('#delid').val(id);
	});
});