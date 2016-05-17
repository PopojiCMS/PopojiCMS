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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di pengaturan.
 * This is a main javascript file from PopojiCMS which contains all javascript in setting.
 *
*/

$(document).ready(function() {
	$('#table-language').buildtable('route.php?mod=setting&act=datatable', 'asc');
});

$(document).ready(function() {
	var editor = CodeMirror.fromTextArea(document.getElementById("pocodemirror"), {
		lineNumbers: true,
		mode: "php",
		extraKeys: {
			"Ctrl-J": "toMatchingTag",
			"F11": function(cm) {
				cm.setOption("fullScreen", !cm.getOption("fullScreen"));
			},
			"Esc": function(cm) {
				if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
			},
			"Ctrl-Space": "autocomplete"
		},
		gutters: ["CodeMirror-linenumbers", "breakpoints"],
		styleActiveLine: true,
		autoCloseBrackets: true,
		autoCloseTags: true,
		theme: "github"
	});

	editor.on("gutterClick", function(cm, n) {
		var info = cm.lineInfo(n);
		cm.setGutterMarker(n, "breakpoints", info.gutterMarkers ? null : makeMarker());
	});

	function makeMarker() {
		var marker = document.createElement("div");
		marker.style.color = "#ff0000";
		marker.innerHTML = "●";
		return marker;
	}

	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');

	$('.nav-tabs a').click(function (e) {
		$(this).tab('show');
		var scrollmem = $('body').scrollTop();
		window.location.hash = this.hash;
		$('html,body').scrollTop(scrollmem);
		editor.refresh();
	});
});

$(document).ready(function() {
	$(":file").filestyle({
		buttonText: "",
		buttonName: "btn-default",
		size: "sm",
		iconName: "fa fa-upload",
		buttonBefore: true,
		placeholder: "Browse file..."
	});

    $.fn.editable.defaults.mode = 'inline';

	$('#web_name').editable({
		validate: function(value) {
			if($.trim(value) == '') {
				return 'This field is required';
			}
		}
	});
	$('#web_url').editable({
		validate: function(value) {
			if($.trim(value) == '') {
				return 'This field is required';
			}
		}
	});
	$('#web_meta').editable({
		validate: function(value) {
			if($.trim(value) == '') {
				return 'This field is required';
			}
		}
	});
	$('#web_keyword').editable();
	$('#web_owner').editable();
	$('#email').editable();
	$('#telephone').editable();
	$('#fax').editable();
	$('#address').editable();
	$('#geocode').editable();

	$('#img_medium').editable({
		validate: function(value) {
			if($.trim(value) == '') {
				return 'This field is required';
			}
			var regvalid = /^\d+x\d+$/;
			if(!regvalid.test(value)) {
				return 'This value must be following format 000x000';
			}
		}
	});

	function getJsonCountry(){
		var arr = [];
		$.getJSON("../po-includes/core/json/country.json", function(data) {
			$.each(data, function(key, val) {
				arr.push({
					value: val.name,
					text: val.name
				});
			});
		});
		return arr;
	}
	$('#country').editable({
        source: getJsonCountry(),
		success: function(response, newValue) {
			$('#region_state').editable('option', 'source', getJsonRegion(newValue));  
			$('#region_state').editable('setValue', null);
		}
	});

	$('#region_state').editable({
		sourceError: 'Please, select value in country list' 
	});
	function getJsonRegion(country){
		var arr = [];
		var country = country.toLowerCase();
		$.getJSON("../po-includes/core/json/region/"+country+".json", function(data) {
			$.each(data.provinces, function(key, val) {
				arr.push({
					value: val,
					text: val
				});
			});
		});
		console.log(arr);
		return arr;
	}

	$('#timezone').editable({
        source: getJsonTimezone()
	});
	function getJsonTimezone(){
		var arr = [];
		$.getJSON("../po-includes/core/json/timezone.json", function(data) {
			$.each(data, function(key, val) {
				arr.push({
					value: val.value,
					text: val.text
				});
			});
		});
		return arr;
	}

	$('#maintenance').editable({
        source: [
			{value: 'Y', text: 'Y'},
			{value: 'N', text: 'N'}
		]
	});
	$('#member_registration').editable({
        source: [
			{value: 'Y', text: 'Y'},
			{value: 'N', text: 'N'}
		]
	});
	$('#comment').editable({
        source: [
			{value: 'Y', text: 'Y'},
			{value: 'N', text: 'N'}
		]
	});
	$('#item_per_page').editable({
		validate: function(value) {
			if($.trim(value) == '') {
				return 'This field is required';
			}
		}
	});
	$('#google_analytics').editable();
	$('#recaptcha_sitekey').editable({
		validate: function(value) {
			if($.trim(value) == '') {
				return 'This field is required';
			}
		}
	});
	$('#recaptcha_secretkey').editable({
		validate: function(value) {
			if($.trim(value) == '') {
				return 'This field is required';
			}
		}
	});

	$('#mail_protocol').editable({
        source: [
			{value: 'SMTP', text: 'SMTP'},
			{value: 'Mail', text: 'Mail'}
		]
	});
	$('#mail_hostname').editable();
	$('#mail_username').editable();
	$('#mail_password').editable();
	$('#mail_port').editable();

	$('#oauth_fb_app_id').editable();
	$('#oauth_fb_app_secret').editable();
	$('#oauth_tw_consumer_key').editable();
	$('#oauth_tw_consumer_secret').editable();
});