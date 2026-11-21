jQuery(document).ready(function($) {
	if (typeof surbma_hc_data != 'undefined') {
		let checkoutUpdateTimer;
		const $checkoutForm = $('form.checkout');

		// Share one pending update between billing and shipping autofill changes.
		$('body').off('update_checkout.cps_hc_gems_autofill').on('update_checkout.cps_hc_gems_autofill', function() {
			clearTimeout(checkoutUpdateTimer);
			checkoutUpdateTimer = null;
		});

		// Keep a pending autofill update behind any further address typing.
		$checkoutForm.on('input.cps_hc_gems_autofill keydown.cps_hc_gems_autofill',
			'.address-field input.input-text, .update_totals_on_change input.input-text', function() {
				if (checkoutUpdateTimer) triggerUpdateCheckout();
			}
		);

		function triggerUpdateCheckout() {
			clearTimeout(checkoutUpdateTimer);
			checkoutUpdateTimer = setTimeout(function() {
				checkoutUpdateTimer = null;
				$checkoutForm.trigger('update');
			}, 1000);
		}

		function handlePostcodeCity(type) {
			const $postcodeField = $(`.woocommerce-page #${type}_postcode`);
			const $cityField = $(`.woocommerce-page #${type}_city`);
			
			if (!$postcodeField.length) return;
			
			let cityFieldTouched = false;
			let processingClick = false; // Flag to prevent multiple processing

			// Keep WooCommerce's native address handlers and typing delay intact.
			$postcodeField.off('.cps_hc_gems_autofill').on(
				'blur.cps_hc_gems_autofill input.cps_hc_gems_autofill change.cps_hc_gems_autofill focusout.cps_hc_gems_autofill keyup.cps_hc_gems_autofill',
				postcodeHandler
			);

			// Add position relative to the city field's parent element
			$cityField.parent().css('position', 'relative');
			$cityField.parent().css('display', 'block');

			// Create tooltip container
			const $tooltip = $('<div>', {
				class: 'city-tooltip',
				css: {
					display: 'none',
					position: 'absolute',
					backgroundColor: 'white',
					border: '1px solid #ddd',
					borderRadius: '4px',
					padding: '8px',
					boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
					zIndex: 1000,
					maxHeight: '200px',
					overflowY: 'auto',
					width: $cityField.outerWidth() + 'px'
				}
			}).insertAfter($cityField);

			// Style for city options
			const optionStyle = {
				padding: '4px 8px',
				cursor: 'pointer',
				borderBottom: '1px solid #eee'
			};

			// Add hover effect for options
			$('<style>')
				.text(`
					.city-tooltip .city-option:hover {
						background-color: #f5f5f5;
					}
					.city-tooltip .city-option:last-child {
						border-bottom: none;
					}
				`)
				.appendTo('head');

			// If city is manually added, don't change it
			$cityField.on('keyup.cps_hc_gems_autofill change.cps_hc_gems_autofill', function() {
				cityFieldTouched = true;
			});

			// Close tooltip when clicking outside or focusing on another field
			$(document).on('click.cps_hc_gems_autofill focusin.cps_hc_gems_autofill', function(e) {
				if (!$(e.target).closest('.city-tooltip').length && !$(e.target).is($cityField)) {
					$tooltip.hide();
				}
			});

			// Handler function for postcode field
			function postcodeHandler() {
				if (processingClick) return; // Skip if we're already processing a click
				
				const postcode = $postcodeField.val();
				const cities = surbma_hc_data[postcode];
				const previousCity = $cityField.val();
				
				if (postcode.length === 4 && cities && ($cityField.val() === '' || !cityFieldTouched)) {
					if (cities.length === 1) {
						// If there's only one city, set it directly
						$cityField.val(cities[0]);
						$tooltip.hide();
						updateCityValidation();
					} else {
						// If there are multiple cities, show the tooltip
						updateTooltip(cities);

						// Clear the text field if it doesn't match any city in the list
						if (!cities.includes($cityField.val())) {
							$cityField.val('');
						}
					}
					
					// Handle validation classes
					updateCityValidation();
					
					// Repeated postcode events must not refresh an unchanged city.
					if ($cityField.val() !== previousCity) {
						triggerUpdateCheckout();
					}
				} else {
					$tooltip.hide();
				}
			}
			
			// Function to update tooltip with city options
			function updateTooltip(cities) {
				$tooltip.empty();
				
				// Add a header
				$('<div>', {
					text: 'Település választása:',
					css: {
						fontWeight: 'bold',
						marginBottom: '8px',
						padding: '0 8px'
					}
				}).appendTo($tooltip);

				// Add city options with enhanced click handler
				cities.forEach(city => {
					$('<div>', {
						class: 'city-option',
						text: city,
						css: optionStyle,
						mousedown: function(e) { // Using mousedown instead of click
							e.preventDefault();
							e.stopPropagation();
							
							processingClick = true;
							
							const previousCity = $cityField.val();
							// Set the city value immediately
							$cityField.val(city);
							cityFieldTouched = true;
							
							// Hide tooltip immediately
							$tooltip.hide();
							
							// Focus the city field
							$cityField.focus();
							
							// Update validation
							updateCityValidation();
							
							if (city !== previousCity) {
								triggerUpdateCheckout();
								$cityField.trigger('change');
							}
							processingClick = false;
						}
					}).appendTo($tooltip);
				});

				// Position and show tooltip
				const tooltipTop = $cityField.outerHeight() + 5;
				$tooltip.css({
					top: tooltipTop,
					left: 0
				}).show();
			}
			
			// Function to update city field validation classes
			function updateCityValidation() {
				if ($cityField.val() !== '') {
					const $cityFieldWrapper = $(`.woocommerce-checkout #${type}_city_field`);
					$cityFieldWrapper.removeClass('woocommerce-invalid woocommerce-invalid-required-field');
					$cityFieldWrapper.addClass('woocommerce-validated');
				}
			}
			
			// Show tooltip when city field is active and there are multiple options
			$cityField.on('click.cps_hc_gems_autofill focus.cps_hc_gems_autofill', function() {
				const postcode = $postcodeField.val();
				const cities = surbma_hc_data[postcode];
				
				if (cities && cities.length > 1) {
					updateTooltip(cities);
				}
			});
		}

		// Initialize handlers for both billing and shipping
		['billing', 'shipping'].forEach(type => handlePostcodeCity(type));
	}
});
