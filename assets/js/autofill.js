jQuery(document).ready(function($) {
	if (typeof surbma_hc_data != 'undefined') {
		function handlePostcodeCity(type) {
			const $postcodeField = $(`.woocommerce-page #${type}_postcode`);
			const $cityField = $(`.woocommerce-page #${type}_city`);
			
			if (!$postcodeField.length) return;
			
			let cityFieldTouched = false;

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
			$cityField.keyup(function() {
				cityFieldTouched = true;
			});

			// Close tooltip when clicking outside
			$(document).on('click', function(e) {
				if (!$(e.target).closest('.city-tooltip').length && !$(e.target).is($cityField)) {
					$tooltip.hide();
				}
			});

			$postcodeField.on('blur input change focusout keyup', function() {
				const postcode = $postcodeField.val();
				const cities = surbma_hc_data[postcode];
				
				if (postcode.length === 4 && cities && ($cityField.val() === '' || !cityFieldTouched)) {
					if (cities.length === 1) {
						// If there's only one city, set it directly
						$cityField.val(cities[0]);
						$tooltip.hide();
					} else {
						// If there are multiple cities, show the tooltip
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

						// Add city options
						cities.forEach(city => {
							$('<div>', {
								class: 'city-option',
								text: city,
								css: optionStyle,
								click: function() {
									$cityField.val(city);
									cityFieldTouched = true;
									$tooltip.hide();
									
									const $cityFieldWrapper = $(`.woocommerce-checkout #${type}_city_field`);
									$cityFieldWrapper.removeClass('woocommerce-invalid woocommerce-invalid-required-field');
									$cityFieldWrapper.addClass('woocommerce-validated');
									
									$('body').trigger('update_checkout');
								}
							}).appendTo($tooltip);
						});

						// Position and show tooltip
						const fieldOffset = $cityField.offset();
						$tooltip.css({
							top: fieldOffset.top + $cityField.outerHeight() + 5,
							left: fieldOffset.left
						}).show();

						// Clear the text field if it doesn't match any city in the list
						if (!cities.includes($cityField.val())) {
							$cityField.val('');
						}
					}
					
					// Handle validation classes
					if ($cityField.val() !== '') {
						const $cityFieldWrapper = $(`.woocommerce-checkout #${type}_city_field`);
						$cityFieldWrapper.removeClass('woocommerce-invalid woocommerce-invalid-required-field');
						$cityFieldWrapper.addClass('woocommerce-validated');
					}
					
					$('body').trigger('update_checkout');
				} else {
					$tooltip.hide();
				}
			});

			// Show tooltip when clicking on city field if there are multiple options
			$cityField.on('click', function() {
				const postcode = $postcodeField.val();
				const cities = surbma_hc_data[postcode];
				
				if (cities && cities.length > 1) {
					const fieldOffset = $cityField.offset();
					$tooltip.css({
						top: fieldOffset.top + $cityField.outerHeight() + 5,
						left: fieldOffset.left
					}).show();
				}
			});
		}

		// Initialize handlers for both billing and shipping
		['billing', 'shipping'].forEach(type => handlePostcodeCity(type));
	}
});