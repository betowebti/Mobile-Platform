$(function() {

	/*
	 * Change theme color
	 */

	$('#demo-color a[data-class]').on('click', function() {
		var body_class = $(this).attr('data-class');
		$('body').removeClass('scheme1 scheme2 scheme3 scheme4 scheme5 scheme6');
		$('body').addClass(body_class);

		// Checkmark
		$('#demo-color .ion-ios-checkmark-empty').toggleClass('ion-ios-checkmark-empty icon-empty');
		$(this).prev('i').toggleClass('icon-empty ion-ios-checkmark-empty');
		return false;
	});

	/*
	 * Change header image
	 */

	$('#demo-header a[data-src]').on('click', function() {
		// Scroll to header
		$.scrollTo($('#home').offset().top - 60, 400);

		var src = $(this).attr('data-src');
		var current = $('#home .parallax-window').attr('data-image-src');
		$("[data-image-src$='" + current + "']").attr('data-image-src', src);
		$("img[src$='" + current + "']").attr('src', src);

		// Checkmark
		$('#demo-header .ion-ios-checkmark-empty').toggleClass('ion-ios-checkmark-empty icon-empty');
		$(this).prev('i').toggleClass('icon-empty ion-ios-checkmark-empty');
		return false;
	});

	/*
	 * Change section background image
	 */

	$('#demo-bg a[data-src]').on('click', function() {
		// Scroll to section
		$.scrollTo($('#features').offset().top - 60, 400);

		var src = $(this).attr('data-src');
		var current = $('.parallax-window:last').attr('data-image-src');
		$("[data-image-src$='" + current + "']").attr('data-image-src', src);
		$("img[src$='" + current + "']").attr('src', src);

		// Checkmark
		$('#demo-bg .ion-ios-checkmark-empty').toggleClass('ion-ios-checkmark-empty icon-empty');
		$(this).prev('i').toggleClass('icon-empty ion-ios-checkmark-empty');
		return false;
	});

	/*
	 * Change phone
	 */

	$('#demo-phone a[data-class]').on('click', function() {
		// Scroll to phone
		$.scrollTo($('#overview').offset().top - 60, 400);

		var body_class = $(this).attr('data-class');
		$('body').removeClass('iphone6-gold iphone6-silver iphone6-space galaxy-s6');
		$('body').addClass(body_class);

		var mySwiper = $('.swiper-container')[0].swiper;

		mySwiper.destroy();

		var swiper = new Swiper('.swiper-container', {
			pagination: '.swiper-pagination',
			paginationClickable: true,
			autoplay: 3000,
			speed: 500,
			loop: true,
			effect: 'fade',
			grabCursor: true
		});

		// Reset parallax
		$(window).trigger('resize');

		// Checkmark
		$('#demo-phone .ion-ios-checkmark-empty').toggleClass('ion-ios-checkmark-empty icon-empty');
		$(this).prev('i').toggleClass('icon-empty ion-ios-checkmark-empty');

		return false;
	});

});