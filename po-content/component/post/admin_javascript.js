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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di post.
 * This is a main javascript file from PopojiCMS which contains all javascript in post.
 *
*/

$(document).ready(function() {
	var table = $('#table-post').DataTable({
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
			'url': 'route.php?mod=post&act=datatable'
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
			$('.tbl-subscribe').click(function () {
				var id = $(this).attr("id");
				$(this).html("<i class='fa fa-rss'></i> Waiting...");
				$.ajax({
					type: "POST",
					url: "route.php?mod=post&act=subscribe",
					data: 'id='+ id,
					cache: false,
					success: function(){
						$('.tbl-subscribe').html("<i class='fa fa-rss'></i> Subscribe");
					}
				});
				return false;
			});
			$('.setheadline').click(function(){
				var id = $(this).attr("id");
				var headline = $("#seth"+id).attr("data-headline");
				if (headline == "Y") {
					var dataheadline = "N";
				} else {
					var dataheadline = "Y";
				}
				$("#seth"+id).html("Waiting...");
				$.ajax({
					type: "POST",
					url: "route.php?mod=post&act=setheadline",
					data: 'id='+ id + '&headline='+ dataheadline,
					cache: false,
					success: function(){
						if (headline == "Y") {
							$("#seth"+id).attr("data-headline","N");
							$("#seth"+id).html("<i class='fa fa-star'></i> Not Set Headline");
						} else {
							$("#seth"+id).attr("data-headline","Y");
							$("#seth"+id).html("<i class='fa fa-star text-warning'></i> Set Headline");
						}
					}
				});
			});
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	$('#table-post').on('click','.alertdel', function () {
		var id = $(this).attr("id");
		$('#alertdel').modal('show');
		$('#delid').val(id);
	});
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
			url: "route.php?mod=post&act=delimage",
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

$(document).ready(function() {
	$('#publishdate').datetimepicker({
		format: 'YYYY-MM-DD',
		showTodayButton: true,
		showClear: true
	});

	$('#publishtime').datetimepicker({
		format: 'hh:mm:ss'
	});

	$("#publishdate").mask("9999/99/99");
	$("#publishtime").mask("99:99:99");
});

$(document).on("change", ".box-category input[type='checkbox']", function(e){
	$(this).siblings('ul')
		.find("input[type='checkbox']")
		.prop('checked', this.checked);
});

$(document).ready(function() {
	$('#category-refresh').on('click', function(){
		var id = $(this).attr('data-id');
		$('.box-category').html('<div class="category-load text-success"><i class="fa fa-refresh"></i> Loading...</div>');
		$.ajax({
			type: "POST",
			url: "route.php?mod=post&act=get_category",
			data: "id=" + id,
			cache: false,
			success: function(data){
				$('.box-category').html(data);
			}
		});
		return false;
	});
});

$(document).ready(function() {
	var tagname = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: 'route.php?mod=post&act=get_tag',
			prepare: function (query, settings) {
				$(".tt-hint").show();
				settings.type = "POST";
				settings.data = 'search=' + query;
				return settings;
			},
			filter: function (parsedResponse) {
				$(".tt-hint").hide();
				return parsedResponse;
            }
		}
	});

	tagname.initialize();

	$('#tag').tagsinput({
		typeaheadjs: {
			name: 'tagname',
			displayKey: 'title',
			valueKey: 'tag_seo',
			source: tagname.ttAdapter()
		}
	});

	$(".twitter-typeahead").css('display', 'inline');
});

$(document).ready(function() {
	Dropzone.options.postgallery = {
		acceptedFiles: ".jpg,.jpeg,.png",
		autoProcessQueue: true,
		uploadMultiple: false,
		parallelUploads: 24,
		maxFiles: 24,
		addRemoveLinks: false,
		dictRemoveFile: "Remove",
		dictCancelUpload: "Cancel",
		dictDefaultMessage: "Drop images files here",
		url: 'route.php?mod=post&act=addgallery',
		sending: function(file, xhr, formData) {
			formData.append('id_post', ''+$("#id_post").val()+'');
		}
	};
});

$(document).ready(function() {
	$('.btn-remove-gal').click(function () {
		var id = $(this).attr("id");
		$.ajax({
			type: "POST",
			url: "route.php?mod=post&act=deletegallery",
			data: 'id='+ id,
			cache: false,
			success: function(){
				$('#col-gal-'+id).remove();
			}
		});
		return false;
	});
});

$(document).ready(function() {
	$('#hide-right').click(function () {
		if ($('#right-post').is(":visible")) {
			$('#right-post').hide();
			$('#left-post').removeClass('col-md-8');
			$('#left-post').addClass('col-md-12');
			$(this).html('');
			$(this).html('&nbsp;<i class="fa fa-angle-left"></i>&nbsp;');
		} else {
			$('#left-post').removeClass('col-md-12');
			$('#left-post').addClass('col-md-8');
            $('#right-post').show();
			$(this).html('');
			$(this).html('&nbsp;<i class="fa fa-angle-right"></i>&nbsp;');
		}
	});
});