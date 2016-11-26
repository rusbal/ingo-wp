jQuery(function($) {

	$('.header-top .wpsight-menu, .site-header .wpsight-menu, .nav-primary .wpsight-menu, .nav-secondary .wpsight-menu').addClass('responsive-menu').before('<div class="responsive-menu-icon"></div>');

	$('.responsive-menu-icon').click(function(){
		$(this).next('.wpsight-menu').slideToggle();
	});

	$(window).resize(function(){
		if(window.innerWidth > 768) {
			$('.wpsight-menu, nav .sub-menu').removeAttr('style');
			$('.responsive-menu > .menu-item').removeClass('menu-open');
		}
	});

	$('.responsive-menu > .menu-item').click(function(event){
		if (event.target !== this)
		return;
			$(this).find('.sub-menu:first').slideToggle(function() {
			$(this).parent().toggleClass('menu-open');
		});
	});

});