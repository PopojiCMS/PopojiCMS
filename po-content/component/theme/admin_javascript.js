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
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di tema.
 * This is a main javascript file from PopojiCMS which contains all javascript in theme.
 *
*/

$(document).ready(function() {
	$('.alertdel').click(function(){
		var id = $(this).attr("id");
		$('#alertdel').modal('show');
		$('#delid').val(id);
	});
});

$(document).ready(function() {
	$('.shortcut-key-btn').click(function(){
		$(".shortcut-key").toggle();
	});
});

$(document).ready(function() {
	var editor = CodeMirror.fromTextArea(document.getElementById("pocodemirror"), {
		lineNumbers: true,
		mode: "php",
		extraKeys: {
			"Ctrl-J": "toMatchingTag",
			"F11": function(cm) {
				cm.setOption("fullScreen", !cm.getOption("fullScreen"));
				$(".CodeMirror").css({"z-index": "101"});
			},
			"Esc": function(cm) {
				if (cm.getOption("fullScreen")) {
					cm.setOption("fullScreen", false);
					$(".CodeMirror").css({"z-index": "1"});
				}
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
});