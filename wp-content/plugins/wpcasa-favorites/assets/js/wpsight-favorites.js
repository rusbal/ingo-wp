jQuery(document).ready(function($){	   
		    	
	var favorites_cookie = WPSight_Favorites.favorites_cookie;
	var favorites_add = $('.favorites-add');
	var favorites_see = $('.favorites-see');
	var favorites_remove = $('.favorites-remove');
	var favorites_page = WPSight_Favorites.favorites_page;
	
	var listing = $('.listing, .single-listing');
	
	var removeValue = function(list, value) {
	  var values = list.split(",");
	  for(var i = 0 ; i < values.length ; i++) {
	    if(values[i] == value) {
	      values.splice(i, 1);
	      return values.join(",");
	    }
	  }
	  return list;
	};
	
	if($.cookie(favorites_cookie)!=null) {
		
		listing.each(function() {		
			var favorite_id = $(this).find(favorites_add).data('favorite');			
			if($.cookie(favorites_cookie).search(favorite_id)!=-1) {
				$(this).find(favorites_see).show();
				$(this).find(favorites_add).hide();
				if($(this).find('.favorites-see small').length == 0) {
					$(this).find(favorites_see).append( WPSight_Favorites.favorites_badge_before + $.cookie(favorites_cookie).split(',').length + WPSight_Favorites.favorites_badge_after );
				}
			}
		
		});
	}

	favorites_add.click(function(e) {
    	e.preventDefault();
    	
    	var favorite_id = $(this).data('favorite');
    	
		if($.cookie(favorites_cookie)==null || $.cookie(favorites_cookie)=='') {
			$.cookie(favorites_cookie, favorite_id,{ expires: parseInt( WPSight_Favorites.favorites_expire ), path: WPSight_Favorites.favorites_cookie_path });
		} else {
			var saved = $.cookie(favorites_cookie);			
			$.cookie(favorites_cookie, saved + ',' + favorite_id,{ expires: parseInt( WPSight_Favorites.favorites_expire ), path: WPSight_Favorites.favorites_cookie_path });
		}

		$(this).fadeOut(75, function() {
		  favorites_see.fadeIn(75);
		  favorites_see.append( WPSight_Favorites.favorites_badge_before + $.cookie(favorites_cookie).split(',').length + WPSight_Favorites.favorites_badge_after );
		});
		
		return false;

	});
	
	favorites_remove.click(function(e) {
		e.preventDefault();
		
		var favorite_id = $(this).data('favorite');
		var saved = removeValue($.cookie(favorites_cookie), favorite_id);
		
		$.cookie(favorites_cookie, saved,{ expires: parseInt( WPSight_Favorites.favorites_expire ), path: WPSight_Favorites.favorites_cookie_path });
		
		$('#listing-'+favorite_id).fadeOut(50, function() {
			$('.listings-panel-found').text($.cookie(favorites_cookie).split(',').length);
			if($.cookie(favorites_cookie)=='') {
				$('.listings-panel-wrap').fadeOut(50);
				$('.wpsight-favorites-sc').append( WPSight_Favorites.favorites_no );
			}
		});
		
		return false;				
		
	});
	
	/** LISTINGS COMPARE */
	
	var btn = $('.listings-compare');
	var fade = $('.wpsight-listing-section-summary, .wpsight-listing-section-description, .wpsight-listing-section-compare-fade');
	var comp = $('.wpsight-listing-section-compare');
	
	if ($.cookie(WPSight_Favorites.compare_cookie) && $.cookie(WPSight_Favorites.compare_cookie) == 'open' && $('body').hasClass( 'page-id-' + favorites_page ) ) {
		fade.hide();
	    comp.show();
	    btn.addClass('open');
	}
	
	btn.live('click', function(e) {
		e.preventDefault();
		if ( comp.is(':visible') ) {
	    	comp.fadeOut(100, function() {
		    	btn.removeClass('open');
		    	fade.fadeIn(100, function() {
			    	if ( $.isFunction($.fn.matchHeight) ) {
			    		$.fn.matchHeight._update();
			    	}
		    	});
	    	});
	    	$.cookie(WPSight_Favorites.compare_cookie, 'closed',{ expires: 60, path: WPSight_Favorites.favorites_cookie_path });
	    } else {
	    	fade.fadeOut(100, function() {
		    	btn.addClass('open');
		    	comp.fadeIn(100);
			    if ( $.isFunction($.fn.matchHeight) ) {
			    	$.fn.matchHeight._update();
			    }
	    	});
	    	$.cookie(WPSight_Favorites.compare_cookie, 'open',{ expires: 60, path: WPSight_Favorites.favorites_cookie_path });
	    }
	});

});