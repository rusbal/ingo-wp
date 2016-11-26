/*global wpcasaCurrency, wp */

( function( $ ) {

	$( function() {

		var defaultCurrency;

		// Feature detect + local reference
		// https://mathiasbynens.be/notes/localstorage-pattern
		var storage = ( function() {
			var uid = new Date().toString();
			var storage;
			var result;
			try {
				( storage = window.localStorage ).setItem( uid, uid );
				result = storage.getItem( uid ) === uid;
				storage.removeItem( uid );
				return result && storage;
			} catch ( exception ) {}
		}() );

		/**
		 *  Looks for all prices in a page and convert them.
		 *  Sends an ajax request for every price `$('.listing-price-value')` it finds.
		 *
		 *  @return  void
		 */
		var convert_currency = function( target_currency ) {

			$( '.listing-price-value' ).each( function() {

				var $currentPrice    = $( this ),
					$currentSymbol   = $currentPrice.siblings( '.listing-price-symbol' ),
					$currentCurrency = $currentPrice.siblings( 'meta[itemprop="priceCurrency"]' );

				var data = {
					base_price:      $currentPrice.attr( 'content' ),
					base_currency:   $currentCurrency.attr( 'content' ),
					target_currency: target_currency
				};

				// do not convert into existing same currency
				if ( data.target_currency === data.base_currency ) {
					return;
				}

				// while doing AJAX, hide the price and display a waiting notice
				$currentPrice.text( wpcasaCurrency.loading_text );
				$currentSymbol.hide();

				wp.ajax.send( "convert_currency", {
					success: function( response ) {

						// replace original price by converted target currency
						$currentPrice.text( response.target_price );

						// display the new currency symbol
						$currentSymbol.html( response.target_symbol ).show();

						$currentPrice.trigger( 'wpsight.convert_currency.success', [ response, data ] );
					},
					error: function( error ) {
						$currentPrice.trigger( 'wpsight.convert_currency.error', [ error, data ] );
					},
					data: data
				} );
			} );

			if ( storage ) {
				// remember this choice
				storage.setItem( 'wpsight.defaultCurrency', target_currency );
			}

		};

		// trigger conversion when select dropdown changes
		$( '.currency-select' ).on( 'change', function(){
			convert_currency( $( this ).val() );
		} );

		if ( storage ) {
			// if the user has made a conversion in the past use that as a default
			defaultCurrency = storage.getItem( 'wpsight.defaultCurrency' );
		}

		if ( defaultCurrency ) {
			// apply conversion with default currency
			convert_currency( defaultCurrency );
			$( '.currency-select' ).val( defaultCurrency );
		}

		// Hook into the event to inspect the returned results
		// $( '.listing-price-value' ).on( 'wpsight.convert_currency.error, wpsight.convert_currency.success', function( e, reponse ) {
		//	console.log( reponse );
		// } );

	} );

}( jQuery.noConflict() ) );
