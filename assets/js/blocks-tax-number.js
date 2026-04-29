( function() {
	'use strict';

	function getFormDirectChild( input ) {
		var el = input;
		while ( el && el.parentElement ) {
			if ( el.parentElement.classList.contains( 'wc-block-components-address-form' ) ) {
				return el;
			}
			el = el.parentElement;
		}
		return null;
	}

	function moveTaxNumberAfterCompany( form ) {
		var companyInput = form.querySelector( 'input[id$="-company"]' );
		var taxInput     = form.querySelector( 'input[id*="cps-hc-gems"]' );

		if ( ! companyInput || ! taxInput ) {
			return;
		}

		var companyWrapper = getFormDirectChild( companyInput );
		var taxWrapper     = getFormDirectChild( taxInput );

		if ( ! companyWrapper || ! taxWrapper ) {
			return;
		}

		if ( companyWrapper.nextElementSibling !== taxWrapper ) {
			companyWrapper.parentNode.insertBefore( taxWrapper, companyWrapper.nextElementSibling );
		}

		// Full-width by default — Checkout module will override to 50% when it gets block support
		taxWrapper.style.flex = '0 0 100%';
	}

	function processAddressForms() {
		document.querySelectorAll( '.wc-block-components-address-form' ).forEach( moveTaxNumberAfterCompany );
	}

	var timer = null;
	var observer = new MutationObserver( function( mutations ) {
		for ( var i = 0; i < mutations.length; i++ ) {
			if ( mutations[ i ].addedNodes.length ) {
				clearTimeout( timer );
				timer = setTimeout( processAddressForms, 50 );
				return;
			}
		}
	} );

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', function() {
			observer.observe( document.body, { childList: true, subtree: true } );
			processAddressForms();
		} );
	} else {
		observer.observe( document.body, { childList: true, subtree: true } );
		processAddressForms();
	}
} )();
