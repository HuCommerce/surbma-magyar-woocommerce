<?php
$wp_root = dirname( dirname( __FILE__ ) );

require_once( $wp_root . "../../../../wp-load.php" );

if ( ! current_user_can( 'manage_options' ) ) die();

$product_id = ( isset( $_GET['product_id'] ) ) ? $_GET['product_id'] : 0;
$product = wc_get_product( $product_id );

if ( get_post_meta( $product_id, '_hc_product_price_history' ) ) {
	$product_price_history = get_post_meta( $product_id, '_hc_product_price_history', true );
	array_multisort( $product_price_history, SORT_DESC );

	// Create special array for Google Chart
	$chart_array = $product_price_history;
	// Change data order to show proper timeline
	array_multisort( $chart_array, SORT_ASC );
	// Add heading to chart
	$chart_heading = array( 'Dátum', 'Normál ár', 'Aktív ár' );
	array_unshift( $chart_array, $chart_heading );
	// Convert array to json
	$chart_data = json_encode( $chart_array );
}
?>
<!DOCTYPE HTML>
<html lan="hu">
	<head>
		<meta charset="utf-8" />
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.14.3/css/uikit.min.css" integrity="sha512-iWrYv6nUp7gzf+Ut/gMjxZn+SWdaiJYn+ZZNq63t2JO6kBpDc40wQfBzC1eOAzlwIMvRyuS974D1R8p1BTdaUw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.14.3/js/uikit.min.js" integrity="sha512-wqamZDJQvRHCyy5j5dfHbqq0rUn31pS2fJeNL4vVjl0gnSVIZoHFqhwcoYWoJkVSdh5yORJt+T9lTdd8j9W4Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<?php if ( get_post_meta( $product_id, '_hc_product_price_history' ) ) { ?>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				var data = google.visualization.arrayToDataTable(<?php echo $chart_data; ?>);

				var options = {
					title: 'Termék ár történet: <?php echo $product->get_title(); ?>',
					curveType: 'function',
					legend: { position: 'bottom' }
				};

				var chart = new google.visualization.LineChart(document.getElementById('product_price_history_chart'));

				chart.draw(data, options);
			}
		</script>
		<?php } ?>
	</head>
	<body>
		<div class="uk-section uk-section-default">
			<div class="uk-container">
				<?php if ( $product ) { ?>
				<h1 class="uk-h3 uk-text-center">Termék ár történet: <?php echo $product->get_title(); ?></h1>
				<ul class="uk-subnav uk-subnav-divider uk-flex uk-flex-center" uk-margin>
					<li><a href="/wp-admin/edit.php?post_type=product" target="_blank">Admin termékek listázása</a></li>
					<li><a href="<?php echo get_permalink( $product_id ); ?>" target="_blank">Termék oldal</a></li>
					<li><a href="/wp-admin/post.php?post=<?php echo $product_id; ?>&action=edit" target="_blank">Termék szerkesztése</a></li>
				</ul>
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
								<th class="uk-text-center">Aznapi kedvezmény mértéke</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$curreny_symbol = get_woocommerce_currency_symbol();
							date_default_timezone_set('Europe/Budapest');
							for( $i = 0; $i < count( $product_price_history ) ; $i++ ) {
								$product_price_discount = intval( number_format( round( ( ( 1 - ( $product_price_history[$i][2] / $product_price_history[$i][1] ) ) * 100 ), 2 ), 2 ) );
								if ( strtotime( $product_price_history[$i][0] ) < strtotime( '-30 day' ) ) {
									echo '<tr class="history-table-old" style="border-left: 5px solid #f0506e;border-right: 1px solid #e5e5e5;" hidden>';
								} else {
									echo '<tr style="border-left: 5px solid #32d296;border-right: 1px solid #e5e5e5;">';
								}
								echo '<td style="text-align: left;">' . $product_price_history[$i][0] . '</td>';
								echo '<td>' . $product_price_history[$i][1] . ' ' . $curreny_symbol . '</td>';
								echo '<td>' . $product_price_history[$i][2] . ' ' . $curreny_symbol . '</td>';
								echo '<td>' . $product_price_discount . '%</td>';
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
				<?php } else { ?>
					<h2 class="uk-h5 uk-text-center">Hibás termék azonosító. Így nincs mit megjeleníteni.</h2>
				<?php } ?>
			</div>
		</div>
		<?php if ( get_post_meta( $product_id, '_hc_product_price_history' ) ) { ?>
		<div class="uk-section uk-section-muted">
			<div class="uk-container">
				<!-- https://developers.google.com/chart/interactive/docs/gallery/linechart?hl=hu -->
				<div id="product_price_history_chart" style="width: 100%; height: 500px"></div>
			</div>
		</div>

		<div class="uk-section uk-section-muted">
			<div class="uk-container">
				<h2 class="uk-h5 uk-text-center">JSON formátum</h2>
				<pre style="white-space: pre-wrap;word-break: break-all;"><code><?php print_r( json_encode( $product_price_history ) ); ?></code></pre>
			</div>
		</div>
		<?php } ?>
	</body>
</html>
<?php
