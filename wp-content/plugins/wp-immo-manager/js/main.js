(function ($) {

    // Scrollen zu den Ankern
    $('a[href*=\\#]').bind('click', function (e) {
        var href = $(this).attr('href');
        $('html, body').animate({
            scrollTop: $(href).offset().top - 100
        }, 'slow');
        e.preventDefault();
    });

    // Smart Navigation
    function fixNavigationOnScroll() {
        $(window).scroll(function () {

            $('.single-immobilie-onepage', function () {

                var winpos = $(window).scrollTop();
                var nav = $('.smart-navi');
                var navpos = nav.position().top;
                var differenz = (navpos - winpos)+100;

                if (differenz >= 0) {
                    nav.removeClass('mute').removeClass('fixedNavBar-admin').removeClass('fixedNavBar');
                }
                else if ($('.admin-bar').length && differenz <= 0){
                    nav.addClass('mute').addClass('fixedNavBar-admin');
                }
                else if (!$('.admin-bar').length && differenz <= 0){
                    nav.addClass('mute').addClass('fixedNavBar');
                }

                return false;
            });
        });
    }

    if ($('.single-immobilie-onepage').length) {
        fixNavigationOnScroll();
    }

})(jQuery);