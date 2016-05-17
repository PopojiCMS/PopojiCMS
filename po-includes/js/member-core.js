/*
 *
 * - PopojiCMS Javascript
 *
 * - File : member-core.js
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di member.
 * This is a main javascript file from PopojiCMS which contains all javascript in member.
 *
*/

$('#login-form').bootstrapValidator({
	message: 'The data entered is not valid',
	feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	},
	fields: {
		username: {
			validators: {
				notEmpty: {
					message: 'Username is required can not be empty'
				}
			}
		},
		password: {
			validators: {
				notEmpty: {
					message: 'Password is required can not be empty'
				}
			}
		}
	}
});

$('#register-form').bootstrapValidator({
	message: 'The data entered is not valid',
	feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	},
	fields: {
		username: {
			validators: {
				notEmpty: {
					message: 'Username is required can not be empty'
				}
			}
		},
		email: {
			validators: {
				notEmpty: {
					message: 'Email is required can not be empty'
				},
				emailAddress: {
					message: 'Email entered is not valid'
				}
			}
		},
		password: {
			validators: {
				notEmpty: {
					message: 'Password is required can not be empty'
				},
				regexp: {
					regexp: /^(?=.*[^a-zA-Z])(?=.*[a-z])(?=.*[A-Z])\S{6,20}$/i,
					message: 'Passwords must be more than 6 characters and is unique combination of numbers and letters'
				}
			}
		},
		repassword: {
            validators: {
				notEmpty: {
					message: 'Retype password is required can not be empty'
				},
                identical: {
                    field: 'password',
                    message: 'The password is entered not equal'
                },
				regexp: {
					regexp: /^(?=.*[^a-zA-Z])(?=.*[a-z])(?=.*[A-Z])\S{6,20}$/i,
					message: 'Passwords must be more than 6 characters and is unique combination of numbers and letters'
				}
            }
        }
	}
});

$('#forgot-form').bootstrapValidator({
	message: 'The data entered is not valid',
	feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	},
	fields: {
		email: {
			validators: {
				notEmpty: {
					message: 'Email is required can not be empty'
				},
				emailAddress: {
					message: 'Email entered is not valid'
				}
			}
		}
	}
});

jQuery(document).ready(function($){
	moveNavigation();
	$(window).on('resize', function(){
		(!window.requestAnimationFrame) ? setTimeout(moveNavigation, 300) : window.requestAnimationFrame(moveNavigation);
	});

	$('.cd-nav-trigger').on('click', function(event){
		event.preventDefault();
		if($('header').hasClass('nav-is-visible')) $('.moves-out').removeClass('moves-out');
		
		$('header').toggleClass('nav-is-visible');
		$('.cd-main-nav').toggleClass('nav-is-visible');
		$('.cd-main-content').toggleClass('nav-is-visible');
	});

	$('.go-back').on('click', function(event){
		event.preventDefault();
		$('.cd-main-nav').removeClass('moves-out');
	});

	$('.cd-subnav-trigger').on('click', function(event){
		event.preventDefault();
		$('.cd-main-nav').toggleClass('moves-out');
	});

	function moveNavigation(){
		var navigation = $('.cd-main-nav-wrapper');
  		var screenSize = checkWindowWidth();
        if ( screenSize ) {
			navigation.detach();
			navigation.insertBefore('.cd-nav-trigger');
		} else {
			navigation.detach();
			navigation.insertAfter('.cd-main-content');
		}
	}

	function checkWindowWidth() {
		var mq = window.getComputedStyle(document.querySelector('header'), '::before').getPropertyValue('content').replace(/"/g, '').replace(/'/g, "");
		return ( mq == 'mobile' ) ? false : true;
	}
});

jQuery(document).ready(function($){
	$(":file").filestyle({
		buttonName: "btn-primary",
		size: "md",
		iconName: "fa fa-upload",
		buttonBefore: false,
		placeholder: "Browse file..."
	});
});

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
			'url': BASE_URL + '/member/post/datatable'
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
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	$('#table-post').on('click','.alertdel', function () {
		var id = $(this).attr("id");
		$('#alertdel').modal('show');
		$('#delid').val(id);
	});
});

$(function() {
	$("#alertalldel").on("show.bs.modal", function (e) {
		var form = $(e.relatedTarget).closest('form');
		$(this).find('.modal-footer #confirmdel').data('form', form);
	});

	$("#alertalldel").find(".modal-footer #confirmdel").on('click', function(){
		$(this).data('form').submit();
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
	var tagname = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: BASE_URL + '/member/post/tag',
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