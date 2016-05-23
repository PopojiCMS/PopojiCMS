/*
 *
 * - PopojiCMS Javascript
 *
 * - File : dashboard-core.js
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file utama javascript PopojiCMS yang memuat semua javascript di dashboard.
 * This is a main javascript file from PopojiCMS which contains all javascript in dashboard.
 *
*/

!function($) {
    "use strict";

    var Sidemenu = function() {
        this.$body = $("body"),
        this.$openLeftBtn = $(".open-left"),
        this.$menuItem = $("#sidebar-menu a")
    };

    Sidemenu.prototype.openLeftBar = function() {
		$("#wrapper").toggleClass("enlarged");
		$("#wrapper").addClass("forced");

		if($("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left")) {
			$("body").removeClass("fixed-left").addClass("fixed-left-void");
		} else if(!$("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left-void")) {
			$("body").removeClass("fixed-left-void").addClass("fixed-left");
		}

		if($("#wrapper").hasClass("enlarged")) {
			$(".left ul").removeAttr("style");
		} else {
			$(".subdrop").siblings("ul:first").show();
		}

		toggle_slimscroll(".slimscrollleft");
		$("body").trigger("resize");
	},

	Sidemenu.prototype.menuItemClick = function(e) {
		if (!$("#wrapper").hasClass("enlarged")) {
			if ($(this).parent().hasClass("has_sub")) {
				e.preventDefault();
			}   
			if (!$(this).hasClass("subdrop")) {
				$("ul",$(this).parents("ul:first")).slideUp(350);
				$("a",$(this).parents("ul:first")).removeClass("subdrop");
				$("#sidebar-menu .pull-right i").removeClass("md-remove").addClass("md-add");
				$(this).next("ul").slideDown(350);
				$(this).addClass("subdrop");
				$(".pull-right i",$(this).parents(".has_sub:last")).removeClass("md-add").addClass("md-remove");
				$(".pull-right i",$(this).siblings("ul")).removeClass("md-remove").addClass("md-add");
			} else if ($(this).hasClass("subdrop")) {
				$(this).removeClass("subdrop");
				$(this).next("ul").slideUp(350);
				$(".pull-right i",$(this).parent()).removeClass("md-remove").addClass("md-add");
			}
		} 
	},

    Sidemenu.prototype.init = function() {
		var $this  = this;
		$(".open-left").click(function(e) {
			e.stopPropagation();
			$this.openLeftBar();
		});

		$this.$menuItem.on('click', $this.menuItemClick);

		$("#sidebar-menu ul li.has_sub a.active").parents("li:last").children("a:first").addClass("active").trigger("click");
	},

	$.Sidemenu = new Sidemenu, $.Sidemenu.Constructor = Sidemenu
}(window.jQuery),

function($) {
    "use strict";

	var App = function() {
		this.VERSION = "1.0", 
		this.AUTHOR = "PopojiCMS", 
		this.SUPPORT = "info@popojicms.org", 
		this.pageScrollElement = "html, body", 
		this.$body = $("body")
	};

	App.prototype.onDocReady = function(e) {
		FastClick.attach(document.body);
		resizefunc.push("initscrolls");
		resizefunc.push("changeptype");

		$('.animate-number').each(function(){
			$(this).animateNumbers($(this).attr("data-value"), true, parseInt($(this).attr("data-duration"))); 
		});

		$(window).resize(debounce(resizeitems,100));
		$("body").trigger("resize");

		$('.right-bar-toggle').on('click', function(e){
			e.preventDefault();
			$('#wrapper').toggleClass('right-bar-enabled');
		});
    },

	App.prototype.init = function() {
		var $this = this;
		$(document).ready($this.onDocReady);
		$.Sidemenu.init();
	},

	$.App = new App, $.App.Constructor = App
}(window.jQuery),

function($) {
	"use strict";
	$.App.init();
}(window.jQuery);

function executeFunctionByName(functionName, context) {
	var args = [].slice.call(arguments).splice(2);
	var namespaces = functionName.split(".");
	var func = namespaces.pop();
	for(var i = 0; i < namespaces.length; i++) {
		context = context[namespaces[i]];
	}
	return context[func].apply(this, args);
}

var w,h,dw,dh;
var changeptype = function(){
	w = $(window).width();
	h = $(window).height();
	dw = $(document).width();
	dh = $(document).height();

	if (jQuery.browser.mobile === true) {
		$("body").addClass("mobile").removeClass("fixed-left");
	}

	if (!$("#wrapper").hasClass("forced")) {
		if (w > 990) {
			$("body").removeClass("smallscreen").addClass("widescreen");
			$("#wrapper").removeClass("enlarged");
		} else {
			$("body").removeClass("widescreen").addClass("smallscreen");
			$("#wrapper").addClass("enlarged");
			$(".left ul").removeAttr("style");
		}
		if ($("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left")) {
			$("body").removeClass("fixed-left").addClass("fixed-left-void");
		} else if (!$("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left-void")) {
			$("body").removeClass("fixed-left-void").addClass("fixed-left");
		}
	}
	toggle_slimscroll(".slimscrollleft");
}

var debounce = function(func, wait, immediate) {
	var timeout, result;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) result = func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) result = func.apply(context, args);
			return result;
	};
}

function resizeitems(){
	if($.isArray(resizefunc)){  
		for (i = 0; i < resizefunc.length; i++) {
			window[resizefunc[i]]();
		}
	}
}

function initscrolls(){
	if (jQuery.browser.mobile !== true) {
		$('.slimscroller').slimscroll({
			height: 'auto',
			size: "8px"
		});

		$('.slimscrollleft').slimScroll({
			height: 'auto',
			position: 'right',
			size: "8px",
			color: '#d0d0d0',
			wheelStep: 5
		});
	}
}

function toggle_slimscroll(item){
	if ($("#wrapper").hasClass("enlarged")) {
		$(item).css("overflow","inherit").parent().css("overflow","inherit");
		$(item). siblings(".slimScrollBar").css("visibility","hidden");
	} else {
		$(item).css("overflow","hidden").parent().css("overflow","hidden");
		$(item). siblings(".slimScrollBar").css("visibility","visible");
	}
}

$(function() {
	function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++) {
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam) {
				return sParameterName[1];
			}
		}
	}
	var activeUrl = window.location.pathname.match(/[^\/]+$/)[0];
	var activeMod = getUrlParameter('mod');
	var activeAct = getUrlParameter('act');
	var activePage = activeUrl + '?mod=' + activeMod

	$('ul#main_menu > li a').not("ul li ul a").each(function(){
		var currentPage = $(this).attr('href');
		if (activePage == currentPage) {
			$(this).addClass('active subdrop');
		}
	});

	$('ul.list-unstyled li a').each(function(){
		var currentPage = $(this).attr('href');
		if (activeAct == '' || activeAct == null || activeAct == undefined) {
			if (activePage == currentPage) {
				$(this).parent().addClass('active');
				$(this).parent().parent().css('display','block');
			}
		} else {
			var activePageAct = activeUrl + '?mod=' + activeMod + '&act=' + activeAct;
			if (activePageAct == currentPage) {
				$(this).parent().addClass('active');
				$(this).parent().parent().css('display','block');
			}
		}
	});

	$(".dark-sidebar-menu li a").each(function () {
 		if ($(this).next().length > 0) {
 			$(this).addClass("parent");
 		};
 	});

 	var menux = $('.dark-sidebar-menu li a.parent');
 	$('<div class="more"><i class="fa fa-angle-down"></i></div>').insertBefore(menux);
 	$('.more').click(function () {
 		$(this).parent('li').toggleClass('open');
 	});

	$('.parent').click(function (e) {
		e.preventDefault();
 		$(this).parent('li').toggleClass('open');
 	});

	$('.menu-btn').click(function () {
		$('nav.dark-sidebar').toggleClass('menu-open');
	});
});

$(function() {
	var offset = 300,
		offset_opacity = 1200,
		scroll_top_duration = 700,
		$back_to_top = $('.cd-top');

	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('cd-fade-out');
		}
	});

	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});
});

$(function() {
	$('[data-toggle="tooltip"]').tooltip();
});

(function($){
	var BuildTable = function(element, url, sort)
	{
		tsort = (sort === undefined) ? 0 : sort;
		var table = $(element).DataTable({
			"autoWidth": false,
			"order": [[1, tsort]],
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
				'url': url
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
				$(".alertdel").click(function(){
					var id = $(this).attr("id");
					$('#alertdel').modal('show');
					$('#delid').val(id);
				});
				$('[data-toggle="tooltip"]').tooltip();
			}
		});
		new $.fn.dataTable.Responsive(table);
		$(element).on('click','.alertdel', function () {
			var id = $(this).attr("id");
			$('#alertdel').modal('show');
			$('#delid').val(id);
		});
	};

	$.fn.buildtable = function(url, sort)
	{
		return this.each(function()
		{
			var buildtable = new BuildTable(this, url, sort);
		});
	};
})(jQuery);

$(function() {
	$("#alertalldel").on("show.bs.modal", function (e) {
		var form = $(e.relatedTarget).closest('form');
		$(this).find('.modal-footer #confirmdel').data('form', form);
	});

	$("#alertalldel").find(".modal-footer #confirmdel").on('click', function(){
		$(this).data('form').submit();
	});
});

$(function() {
	$("#browse-file").fancybox({
		'width'		: 900,
		'height'	: 600,
		'type'		: 'iframe',
		'autoScale'	: false
	});
});

$(function() {
	$(".alert").fadeTo(3000, 500).slideUp(500, function(){
		$(".alert").alert('close');
	});
});