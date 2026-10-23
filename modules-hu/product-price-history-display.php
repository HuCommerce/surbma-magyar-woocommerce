<?php

/**
 * Display the public Product price history
 */

// Locate wp-load.php. In a normal install the plugin lives in wp-content/plugins/, so it is 5 levels up from this file.
// If the plugin folder is a symlink (local development), __FILE__ is the real path, so fall back to the requested script path.
$wp_load = dirname( __FILE__, 5 ) . '/wp-load.php';
if ( ! file_exists( $wp_load ) && ! empty( $_SERVER['SCRIPT_FILENAME'] ) ) {
	$wp_load = dirname( $_SERVER['SCRIPT_FILENAME'], 5 ) . '/wp-load.php'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
}
if ( ! file_exists( $wp_load ) && ! empty( $_SERVER['DOCUMENT_ROOT'] ) ) {
	$wp_load = rtrim( $_SERVER['DOCUMENT_ROOT'], '/' ) . '/wp-load.php'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
}
if ( ! file_exists( $wp_load ) ) {
	exit;
}

require_once $wp_load;

$product_id = isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$product = wc_get_product( $product_id );

// Only show published products to users, who can't edit the product. For variations the parent product must be published too.
if ( $product && ! current_user_can( 'edit_post', $product_id ) ) {
	$cps_hc_gems_visibility_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product_id;
	if ( 'publish' !== get_post_status( $product_id ) || 'publish' !== get_post_status( $cps_hc_gems_visibility_id ) || post_password_required( $cps_hc_gems_visibility_id ) ) {
		$product = false;
	}
}

// Stop if we don't process a product
if ( $product ) {
	$product_regular_price = intval( $product->get_regular_price() );

	// Always get the actual and active price
	$product_price = intval( $product->get_price() );

	$current_time = current_datetime();
	$current_time = strval( gmdate( 'Y-m-d H:i:s', $current_time->getTimestamp() + $current_time->getOffset() ) );

	$cps_hc_gems_delete_nonce_action = 'cps_hc_gems_delete_product_price_history_' . $product_id;

	// Delete the price history only on a POST request with a valid nonce
	$cps_hc_gems_manual_request = isset( $_POST['hc-product_price_history'] ) ? sanitize_key( wp_unslash( $_POST['hc-product_price_history'] ) ) : false;
	if ( $cps_hc_gems_manual_request ) {
		$cps_hc_gems_nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
		if ( 'delete' === $cps_hc_gems_manual_request && current_user_can( 'manage_options' ) && wp_verify_nonce( $cps_hc_gems_nonce, $cps_hc_gems_delete_nonce_action ) ) {
			delete_post_meta( $product_id, '_hc_product_price_history' );
		}

		wp_safe_redirect( add_query_arg( 'product_id', $product_id, CPS_HC_GEMS_URL . '/modules-hu/product-price-history-display.php' ) );
		exit;
	}

	// If there is no data, create the first price item. Only users, who can edit the product, can save data here.
	if ( ! get_post_meta( $product_id, '_hc_product_price_history' ) && current_user_can( 'edit_post', $product_id ) ) {
		$product_price_history = array(
			array( $current_time, $product_regular_price, $product_price )
		);
		update_post_meta( $product_id, '_hc_product_price_history', $product_price_history );
	}

	$product_price_history = get_post_meta( $product_id, '_hc_product_price_history', true );
	$product_price_history = is_array( $product_price_history ) ? $product_price_history : array();
	array_multisort( $product_price_history, SORT_DESC );

	// Create special array for Google Chart
	$chart_array = $product_price_history;
	// Change data order to show proper timeline
	array_multisort( $chart_array, SORT_ASC );
	// Add heading to chart
	$chart_heading = array( 'Dátum', 'Normál ár', 'Aktív ár' );
	array_unshift( $chart_array, $chart_heading );

	// Convert data to CSV
	$csv = '';
	$header = false;
	foreach ( $product_price_history as $line ) {
		if ( !$header ) {
			$header = array_keys( $line );
			$csv .= implode( ',', $header );
			$header = array_flip( $header );
		}

		$line_array = array();

		foreach( $line as $value ) {
			array_push( $line_array, $value );
		}

		$csv .= "\n" . implode( ',', $line_array );
	}
}

?>
<!DOCTYPE HTML>
<html lan="hu">
	<head>
		<meta charset="utf-8" />
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="<?php echo esc_url( CPS_HC_GEMS_URL . '/cps-sdk/vendors/uikit/css/uikit.min.css' ); ?>"><?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- Standalone HTML page, no wp_head enqueue. ?>
		<script src="<?php echo esc_url( CPS_HC_GEMS_URL . '/cps-sdk/vendors/uikit/js/uikit.min.js' ); ?>"></script><?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Standalone HTML page, no wp_head enqueue. ?>
<?php if ( $product && get_post_meta( $product_id, '_hc_product_price_history', true ) ) { ?>
		<script src="<?php echo esc_url( CPS_HC_GEMS_URL . '/assets/vendor/chartjs/chart.umd.min.js' ); ?>"></script><?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Standalone HTML page, no wp_head enqueue. ?>
		<script type="text/javascript">
			(function() {
				var chartArray = <?php echo wp_json_encode( $chart_array ); ?>;
				var labels = [];
				var normalAr = [];
				var activeAr = [];
				for (var i = 1; i < chartArray.length; i++) {
					labels.push(chartArray[i][0]);
					normalAr.push(chartArray[i][1]);
					activeAr.push(chartArray[i][2]);
				}
				function initPriceHistoryChart() {
					var canvas = document.getElementById('product_price_history_chart');
					if (!canvas) { return; }
					var wrapper = canvas.parentElement;
					if (wrapper) {
						canvas.width = wrapper.clientWidth;
						canvas.height = wrapper.clientHeight;
					}
					// Chart.js 4.x: first argument is canvas element or 2d context (docs use canvas).
					new Chart(canvas, {
						type: 'line',
						data: {
							labels: labels,
							datasets: [
								{ label: 'Normál ár', data: normalAr, borderColor: 'rgb(75, 192, 192)', tension: 0.3, fill: false },
								{ label: 'Aktív ár', data: activeAr, borderColor: 'rgb(255, 99, 132)', tension: 0.3, fill: false }
							]
						},
						options: {
							responsive: false,
							maintainAspectRatio: false,
							plugins: {
								title: { display: true, text: 'Termék ár történet: <?php echo esc_js( $product->get_title() ); ?>' },
								legend: { position: 'bottom' }
							},
							scales: {
								y: { beginAtZero: true }
							}
						}
					});
				}
				if (document.readyState === 'loading') {
					document.addEventListener('DOMContentLoaded', initPriceHistoryChart);
				} else {
					initPriceHistoryChart();
				}
			})();
		</script>
<?php } ?>
<?php if ( $product ) { ?>
		<script>
			function copyJsonData() {
				/* Get the text field */
				var copyJSON = document.getElementById("json-data");

				/* Select the text field */
				copyJSON.select();
				copyJSON.setSelectionRange(0, 99999); /* For mobile devices */

				/* Copy the text inside the text field */
				navigator.clipboard.writeText(copyJSON.value);

				/* Alert the copied text */
				setTimeout(function() {
					alert("JSON adatok kimásolva a vágólapra");
				}, 500);
			}
		</script>
		<script>
			function copyCsvData() {
				/* Get the text field */
				var copyCSV = document.getElementById("csv-data");

				/* Select the text field */
				copyCSV.select();
				copyCSV.setSelectionRange(0, 99999); /* For mobile devices */

				/* Copy the text inside the text field */
				navigator.clipboard.writeText(copyCSV.value);

				/* Alert the copied text */
				setTimeout(function() {
					alert("CSV adatok kimásolva a vágólapra");
				}, 500);
			}
		</script>
<?php } ?>
	</head>
	<body>
		<article class="uk-article">
		<?php if ( $product ) { ?>
			<div class="uk-section uk-section-default uk-section-xsmall">
				<div class="uk-container uk-text-center">
					<h1 class="uk-article-title"><?php echo esc_html( $product->get_title() ); ?></h1>
					<p class="uk-article-meta">Termék ár történet</p>
				</div>
			</div>

			<hr class="uk-margin-remove">

			<div class="uk-section uk-section-muted uk-section-xsmall">
				<div class="uk-container">
					<ul class="uk-subnav uk-subnav-divider uk-flex uk-flex-center" uk-margin>
						<li><a href="<?php echo esc_attr( get_permalink( $product_id ) ); ?>" target="_blank">Termék oldal</a></li>
						<?php if ( current_user_can( 'manage_options' ) ) { ?>
						<li><a href="/wp-admin/edit.php?post_type=product" target="_blank">Admin termékek listázása</a></li>
						<li><a href="/wp-admin/post.php?post=<?php echo esc_attr( $product_id ); ?>&action=edit" target="_blank">Termék szerkesztése</a></li>
						<?php } ?>
					</ul>
				</div>
			</div>

			<hr class="uk-margin-remove">

			<div class="uk-section uk-section-default">
				<div class="uk-container">
					<h3 class="uk-heading-line uk-text-center"><span>Termék ár történet táblázat</span></h3>
					<?php if ( get_post_meta( $product_id, '_hc_product_price_history' ) ) { ?>
					<div class="uk-overflow-auto">
						<table class="uk-table uk-table-striped uk-table-hover uk-table-small uk-table-middle uk-text-center" style="margin: 0 auto;">
							<colgroup>
								<col style="width: 25%;">
								<col style="width: 25%;">
								<col style="width: 25%;">
								<col style="width: 25%;">
							</colgroup>
							<thead>
								<tr>
									<th class="uk-text-center">Dátum</th>
									<th class="uk-text-center">Normál ár</th>
									<th class="uk-text-center">Aktív ár</th>
									<th class="uk-text-center">Aktuális kedvezmény</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$curreny_symbol = get_woocommerce_currency_symbol();
								// date_default_timezone_set('Europe/Budapest');
								for( $i = 0; $i < count( $product_price_history ) ; $i++ ) {
									if ( 0 === $product_price_history[$i][2] || 0 === $product_price_history[$i][1] ) {
										$product_price_discount = 0;
									} else {
										$product_price_discount = intval( number_format( round( ( ( 1 - ( $product_price_history[$i][2] / $product_price_history[$i][1] ) ) * 100 ), 2 ), 2 ) );
									}
									if ( strtotime( $product_price_history[$i][0] ) < strtotime( '-30 day' ) ) {
										echo '<tr class="history-table-old" style="border-left: 5px solid #f0506e;border-right: 1px solid #e5e5e5;" hidden>';
									} else {
										echo '<tr style="border-left: 5px solid #32d296;border-right: 1px solid #e5e5e5;">';
									}
									echo '<td style="text-align: left;">' . esc_html( $product_price_history[$i][0] ) . '</td>';
									echo '<td>' . esc_html( $product_price_history[$i][1] ) . ' ' . esc_html( $curreny_symbol ) . '</td>';
									echo '<td>' . esc_html( $product_price_history[$i][2] ) . ' ' . esc_html( $curreny_symbol ) . '</td>';
									echo '<td>' . esc_html( $product_price_discount ) . '%</td>';
									echo '</tr>';
								}
								?>
								<tr style="border-left: 5px solid #e5e5e5;border-right: 1px solid #e5e5e5;"><td colspan="4" style="padding: 0;"></td></tr>
							</tbody>
						</table>
					</div>
					<div class="uk-section uk-section-xsmall uk-text-center history-table-old"><button class="uk-button uk-button-default" type="button" uk-toggle="target: .history-table-old; animation: uk-animation-fade; queued: true">30 napnál régebbi termék történet mutatása</button></div>
					<?php } else { ?>
						<h2 class="uk-h5 uk-text-center">Nincs még termék ár történet mentve a megadott termékhez. <br>Az árak első módosítása után jön létre a szükséges adat a megjelenítéshez.</h2>
					<?php } ?>
				</div>
			</div>

			<hr class="uk-margin-remove">

			<?php if ( get_post_meta( $product_id, '_hc_product_price_history', true ) ) { ?>
			<div class="uk-section uk-section-muted">
				<div class="uk-container">
					<h3 class="uk-heading-line uk-text-center"><span>Termék ár történet diagram</span></h3>
					<div class="product-price-history-chart-wrapper" style="position: relative; height: 500px; width: 100%; background-color: #fff;">
						<canvas id="product_price_history_chart" style="display: block; box-sizing: border-box; width: 100%; height: 100%;"></canvas>
					</div>
				</div>
			</div>

			<hr class="uk-margin-remove">

			<?php if ( current_user_can( 'manage_options' ) ) { ?>
			<div class="uk-section uk-section-default">
				<div class="uk-container">
					<h3 class="uk-heading-line uk-text-center"><span>Termék ár történet adatok másolása</span></h3>
					<form class="uk-form-horizontal uk-margin-large">
						<div class="uk-margin">
							<label class="uk-form-label" for="form-horizontal-text">JSON formátum</label>
							<div class="uk-form-controls">
								<textarea id="json-data" class="uk-textarea uk-background-muted" rows="10" style="font-family: Consolas,monaco,monospace;font-size: 12px;white-space: pre-wrap;word-break: break-all;" readonly><?php print_r( wp_json_encode( $product_price_history ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r ?></textarea>
								<p class="uk-text-right"><button class="uk-button uk-button-secondary" onclick="copyJsonData()">JSON adatok másolása</button></p>
							</div>
						</div>
						<div class="uk-margin">
							<label class="uk-form-label" for="form-horizontal-select">CSV formátum</label>
							<div class="uk-form-controls">
								<textarea id="csv-data" class="uk-textarea uk-background-muted" rows="10" style="font-family: Consolas,monaco,monospace;font-size: 12px;white-space: pre-wrap;word-break: break-all;" readonly><?php echo wp_kses( $csv, 'strip' ); ?></textarea>
								<p class="uk-text-right uk-text-meta">CSV fejléc adatok jelentése: 0 = Dátum | 1 = Normál ár | 2 = Aktív ár</p>
								<p class="uk-text-right"><button class="uk-button uk-button-secondary" onclick="copyCsvData()">CSV adatok másolása</button></p>
							</div>
						</div>
					</form>
				</div>
			</div>

			<hr class="uk-margin-remove">

			<div class="uk-section uk-section-secondary">
				<div class="uk-container uk-text-center">
					<h3 class="uk-heading-line uk-text-center"><span>Termék ár történet adatok törlése</span></h3>
					<form method="post" action="<?php echo esc_url( add_query_arg( 'product_id', $product_id, CPS_HC_GEMS_URL . '/modules-hu/product-price-history-display.php' ) ); ?>" onsubmit="return confirm('Biztosan törlöd az összes ár történet adatot ennél a terméknél?')">
						<?php wp_nonce_field( $cps_hc_gems_delete_nonce_action ); ?>
						<input type="hidden" name="hc-product_price_history" value="delete">
						<button type="submit" class="uk-button uk-button-danger">Adatok törlése</button>
					</form>
				</div>
			</div>
			<?php } ?>
			<?php } ?>
		<?php } else { ?>
			<div class="uk-section uk-section-default">
				<div class="uk-container uk-container-xsmall">
					<div class="uk-alert-danger uk-text-center" uk-alert>
						<p>Hibás termék azonosító. Így nincs mit megjeleníteni.</p>
					</div>
				</div>
			</div>
		<?php } ?>
		</article>
	</body>
</html>
<?php
