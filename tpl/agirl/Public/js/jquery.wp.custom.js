$(document).ready(function() {
	// Active Theme: Select the theme you want to activate on the entire website
	$("body").addClass("wp-theme-3");
	
	//Carousels
	$('.carousel').carousel({
		interval: 5000,
		pause	: 'hover'
	});
	// Sortable list
	$('#ulSorList').mixitup();
	// Fancybox
	$(".theater").fancybox();
	// Fancybox	
	$(".ext-source").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'autoScale'     	: false,
		'type'				: 'iframe',
		'width'				: '50%',
		'height'			: '60%',
		'scrolling'   		: 'no'
	});
	
	// Masonry
	/*
	var container = $('#masonryWr');
	container.masonry({
	  itemSelector: '.item'
	});*/
	// Scroll to top
	$().UItoTop({ easingType: 'easeOutQuart' });
	// Inview animations
	$.fn.waypoint.defaults = {
		context: window,
		continuous: true,
		enabled: true,
		horizontal: false,
		offset: 300,
		triggerOnce: false
	}
	$('.animate-in-view, .chart').waypoint(function(direction) {
		var barColor;
		// Easy Pie Chart
		$(".chart").easyPieChart({
			size:150,
			easing: 'easeOutBounce',
			onStep: function(from, to, percent) {
				$(this.el).find('.percent').text(Math.round(percent));
			},
			barColor:'#FFF',
			delay: 3000,
			trackColor:'rgba(255,255,255,0.2)',
			scaleColor:false,
			lineWidth:16,
			lineCap:'butt'
		});
	});
	$("#btnSignIn").click(function(){
		$("#dropdownForm").hide();
		$("#dropdownProfile").fadeIn(300);	
		return false;
	});
	// Search function
	$("#cmdAsideMenu, #btnHideAsideMenu, .navbar-toggle-aside-menu").click(function(){
		if($("#asideMenu").is(":visible")){
			$("#asideMenu").hide();
			$("body").removeClass("aside-menu-in");
		}
		else{
			$("body").addClass("aside-menu-in");
			$("#asideMenu").show();
		}
		return false;	
	});
	// Theme Switcher for Preview
	var scheme = $.cookie('scheme');
	if (scheme == 'wp-theme-1') {
		$("body").removeClass();
		$("body").addClass("wp-theme-1");
	}
	else if (scheme == 'wp-theme-2') {
		$("body").removeClass();
		$("body").addClass("wp-theme-2");
	}
	else if (scheme == 'wp-theme-3') {
		$("body").removeClass();
		$("body").addClass("wp-theme-3");
	}
	else if (scheme == 'wp-theme-4') {
		$("body").removeClass();
		$("body").addClass("wp-theme-4");
	}
	else if (scheme == 'wp-theme-5') {
		$("body").removeClass();
		$("body").addClass("wp-theme-5");
	}
	else if (scheme == 'wp-theme-6') {
		$("body").removeClass();
		$("body").addClass("wp-theme-6");
	}
	
	var layout = $.cookie('layout');
	if (layout == 'boxed') {
		$(".wrapper").addClass("boxed");
	}
	else{
		$(".wrapper").removeClass("boxed");	
	}
	
	var topHeader = $.cookie('top-header');
	if (topHeader == 'hide') {
		$(".top-header").addClass("hide");
	}
	else{
		$(".top-header").removeClass("hide");
	}
	
	var layout = $.cookie('layout');
	if (layout == 'boxed') {
		$(".wrapper").addClass("boxed");
	}
	else{
		$(".wrapper").removeClass("boxed");	
	}
	
	var background = $.cookie('background');
	if (background == 'body-bg-1') {
		$("body").addClass("body-bg-1");
	}
	else if (background == 'body-bg-2') {
		$("body").addClass("body-bg-2");
	}
	else if (background == 'body-bg-3') {
		$("body").addClass("body-bg-3");
	}
	else if (background == 'body-bg-4') {
		$("body").addClass("body-bg-4");
	}
	else if (background == 'body-bg-5') {
		$("body").addClass("body-bg-5");
	}
	else if (background == 'body-bg-6') {
		$("body").addClass("body-bg-6");
	}
	
	var date = new Date();
	date.setTime(date.getTime() + (5 * 60 * 1000));
	
	$("#cmdRed").click(function(){
		$("body").removeClass(scheme);
		$("body").addClass("wp-theme-1");
		checkAsideMenuVisibility();
		$.cookie('scheme', 'wp-theme-1', { expires:date});
		scheme = "wp-theme-1";
		return false;
	});
	$("#cmdViolet").click(function(){
		$("body").removeClass(scheme);
		$("body").addClass("wp-theme-2");
		checkAsideMenuVisibility();
		$.cookie('scheme', 'wp-theme-2', { expires:date});
		scheme = "wp-theme-2";
		return false;
	});
	$("#cmdBlue").click(function(){
		$("body").removeClass(scheme);
		$("body").addClass("wp-theme-3");
		checkAsideMenuVisibility();
		$.cookie('scheme', 'wp-theme-3', { expires:date});
		scheme = "wp-theme-3";
		return false;
	});
	$("#cmdGreen").click(function(){
		$("body").removeClass(scheme);
		$("body").addClass("wp-theme-4");
		checkAsideMenuVisibility();
		$.cookie('scheme', 'wp-theme-4', { expires:date});
		scheme = "wp-theme-4";
		return false;
	});
	$("#cmdYellow").click(function(){
		$("body").removeClass(scheme);
		$("body").addClass("wp-theme-5");
		checkAsideMenuVisibility();
		$.cookie('scheme', 'wp-theme-5', { expires:date});
		scheme = "wp-theme-5";
		return false;
	});
	$("#cmdOrange").click(function(){
		$("body").removeClass(scheme);
		$("body").addClass("wp-theme-6");
		checkAsideMenuVisibility();
		$.cookie('scheme', 'wp-theme-6', { expires:date});
		scheme = "wp-theme-6";
		return false;
	});
	
	function checkAsideMenuVisibility(){
		if($("#asideMenu").is(":visible")){
			$("#asideMenu").show();
			$("body").addClass("aside-menu-in");
		}
	}
	
	// Layout
	$("#cmbLayout").change(function(){
		if($("#cmbLayout").val() == 2){
			$(".wrapper").addClass("boxed");	
			$.cookie('layout', 'boxed', { expires:date});
		}
		else{
			$(".wrapper").removeClass("boxed");
			$.cookie('layout', 'fluid', { expires:date});
		}
	});
	
	// Top header
	$("#cmbTopHeader").change(function(){
		if($("#cmbTopHeader").val() == 2){
			$(".top-header").addClass("hide");
			$.cookie('top-header', 'hide', { expires:date});	
		}
		else{
			$(".top-header").removeClass("hide");
			$.cookie('top-header', 'show', { expires:date});	
		}
	});
	
	// Pattern/background
	$("#cmbBackground").change(function(){
		if($("#cmbBackground").val() == 1){
			$("body").removeClass(background);
			$("body").addClass("body-bg-1");
			$.cookie('background', 'body-bg-1', { expires:date});
			background = "body-bg-1";	
		}
		else if($("#cmbBackground").val() == 2){
			$("body").removeClass(background);
			$("body").addClass("body-bg-2");
			$.cookie('background', 'body-bg-2', { expires:date});	
			background = "body-bg-2";	
		}
		else if($("#cmbBackground").val() == 3){
			$("body").removeClass(background);
			$("body").addClass("body-bg-3");
			$.cookie('background', 'body-bg-3', { expires:date});	
			background = "body-bg-3";	
		}
		else if($("#cmbBackground").val() == 4){
			$("body").removeClass(background);
			$("body").addClass("body-bg-4");
			$.cookie('background', 'body-bg-4', { expires:date});	
			background = "body-bg-4";	
		}
		else if($("#cmbBackground").val() == 5){
			$("body").removeClass(background);
			$("body").addClass("body-bg-5");
			$.cookie('background', 'body-bg-5', { expires:date});	
			background = "body-bg-5";	
		}
		else if($("#cmbBackground").val() == 6){
			$("body").removeClass(background);
			$("body").addClass("body-bg-6");
			$.cookie('background', 'body-bg-6', { expires:date});	
			background = "body-bg-6";	
		}
	});
});