<form class="uk-form-horizontal" method="post" action="options.php">
	<div class="uk-card uk-card-small uk-card-default uk-card-hover uk-margin-bottom">
		<div class="uk-card-header uk-background-muted">
			<nav class="uk-navbar-container uk-margin" uk-navbar>
				<div class="uk-navbar-left">
					<div class="uk-navbar-item uk-logo">
						<div>HuCommerce beállítások</div>
					</div>
				</div>
				<div class="uk-navbar-right">
					<div class="uk-navbar-item"><input type="submit" class="uk-button uk-button-primary uk--button-large" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></div>
				</div>
			</nav>
		</div>
		<div class="uk-card-body">
			<div class="uk-grid-collapse uk-grid-divider uk-grid-match" uk-grid>
				<div class="uk-width-auto@m">
					<ul class="uk-nav uk-nav-default" uk-switcher="connect: #component-nav; animation: uk-animation-fade">
						<li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: settings"></span> Vezérlőpult</a></li>
						<li class="uk-nav-divider"></li>
						<li class="uk-nav-header">HuCommerce modulok</li>
						<li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: world"></span> Magyar formátum javítások</a></li>
						<li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: credit-card"></span> Pénztár oldal</a></li>
						<li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: plus-circle"></span> Plusz/minusz mennyiségi gombok</a></li>
						<li class="uk-nav-divider"></li>
						<li class="uk-nav-header">Egyéb</li>
						<li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: lifesaver"></span> Támogatás</a></li>
						<li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: info"></span> Információk</a></li>
						<li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: mail"></span> Hírlevél feliratkozás</a></li>
						<li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: hashtag"></span> Webhely információk</a></li>
					</ul>
				</div>

				<div class="uk-width-expand@m">
					<ul id="component-nav" class="uk-switcher">
						<li>
							<h4 class="uk-heading-divider">Üdvözöllek a HuCommerce vezérlőpultján</h4>
							<p>Iratkozz fel a HuCommerce hírlevélre, amiben a legújabb funkciókról, akciókról és különleges ajánlatainkról írunk.</p>
						</li>
						<li>
							<h4 class="uk-heading-divider">Magyar formátum javítások</h4>
							<p>A keresztnév és vezetéknév sorrendjének a megfordítása a Pénztár oldalon akkor, ha a webáruház magyar nyelvű. Megye mező elrejtése ha a cím Magyarország.</p>
							<div class="uk-margin uk-padding-small uk-padding-remove-vertical uk-padding-remove-left">
								<div class="uk-form-label"><strong>Modul aktiválása:</strong> Magyar formátum javítások</div>
								<div class="uk-form-controls">
									<div class="switch-wrap">
										<label class="switch">
											<?php $huformatfixValue = isset( $options['huformatfix'] ) ? $options['huformatfix'] : 1; ?>
											<input id="surbma_hc_fields[huformatfix]" name="surbma_hc_fields[huformatfix]" type="checkbox" value="1" <?php checked( '1', $huformatfixValue ); ?> />
											<span class="slider round"></span>
										</label>
									</div>
								</div>
							</div>

							<hr>

							<div class="uk-card uk-card-small uk-card-body uk-background-muted">

								<div class="uk-margin">
									<div class="uk-form-label">Pénztár oldal <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Pénztár oldali mezők és egyéb funkciók módosításai.; pos: right"></span></div>
									<div class="uk-form-controls">
										<div class="switch-wrap uk--margin-remove">
											<label class="switch">
												<?php $moduleCheckoutValue = isset( $options['module-checkout'] ) ? $options['module-checkout'] : 0; ?>
												<input id="module-checkout" name="surbma_hc_fields[module-checkout]" type="checkbox" value="1" <?php checked( '1', $moduleCheckoutValue ); ?> />
												<span class="slider round"></span>
											</label>
										</div>
									</div>
								</div>

								<hr>

								<div class="uk-margin">
									<div class="uk-form-label">Pénztár oldal <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Pénztár oldali mezők és egyéb funkciók módosításai.; pos: right"></span></div>
									<div class="uk-form-controls">
										<div class="switch-wrap uk--margin-remove">
											<label class="switch">
												<?php $moduleCheckoutValue = isset( $options['module-checkout'] ) ? $options['module-checkout'] : 0; ?>
												<input id="module-checkout" name="surbma_hc_fields[module-checkout]" type="checkbox" value="1" <?php checked( '1', $moduleCheckoutValue ); ?> />
												<span class="slider round"></span>
											</label>
										</div>
									</div>
								</div>

								<hr>

								<div class="uk-margin">
									<div class="uk-form-label">Pénztár oldal <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Pénztár oldali mezők és egyéb funkciók módosításai.; pos: right"></span></div>
									<div class="uk-form-controls">
										<div class="switch-wrap uk--margin-remove">
											<label class="switch">
												<?php $moduleCheckoutValue = isset( $options['module-checkout'] ) ? $options['module-checkout'] : 0; ?>
												<input id="module-checkout" name="surbma_hc_fields[module-checkout]" type="checkbox" value="1" <?php checked( '1', $moduleCheckoutValue ); ?> />
												<span class="slider round"></span>
											</label>
										</div>
									</div>
								</div>

							</div>
						</li>
						<li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
						<li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
						<li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur, sed do eiusmod.</li>
						<li>
							<h4 class="uk-heading-divider">Bővítmény linkek</h4>
							<ul class="uk-list">
								<li><a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce" target="_blank">Hivatalos támogató fórum</a></li>
								<li><a href="https://hu.wordpress.org/plugins/surbma-magyar-woocommerce/#reviews" target="_blank">Olvasd el az értékeléseket (5/5 csillag)</a></li>
							</ul>
							<hr>
							<p>
								<strong>Tetszik a bővítmény? Kérlek értékeld 5 csillaggal:</strong>
								 <a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce/reviews/#new-post" target="_blank">Új értékelés létrehozása</a>
							</p>
							<h4 class="uk-heading-divider">Tervezett funkciók</h4>
							<ul class="uk-list">
								<li><span uk-icon="icon: check; ratio: 0.8"></span> Webáruházak kötelező jogi megfelelésének a technikai biztosítása.</li>
								<li><span uk-icon="icon: check; ratio: 0.8"></span> Globális adatok, amiket shortcode-dal lehet bárhol megjeleníteni.</li>
								<li><span uk-icon="icon: check; ratio: 0.8"></span> Köszönő oldal egyedi módosítási lehetősége.</li>
							</ul>
						</li>
						<li>
							<h4 class="uk-heading-divider">Hírlevél feliratkozás</h4>
							<p>Iratkozz fel a HuCommerce hírlevélre, amiben a legújabb funkciókról, akciókról és különleges ajánlatainkról írunk.</p>
							<p><a class="uk-button uk-button-danger uk-button-large uk--width-1-1" href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=<?php echo urlencode( $current_user->user_email ); ?>&FNAME=<?php echo urlencode( $current_user->user_firstname ); ?>&LNAME=<?php echo urlencode( $current_user->user_lastname ); ?>&URL=<?php echo urlencode( $home_url ); ?>" target="_blank"><span uk-icon="mail"></span> Hírlevél feliratkozás</a></p>
						</li>
						<li>
							<?php
							// global WP_Debug_Data
							if ( ! class_exists( 'WP_Debug_Data' ) ) {
								require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
							}
							if ( ! class_exists( 'WP_Site_Health' ) ) {
								require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
							}
							WP_Debug_Data::check_for_updates();
							$info = WP_Debug_Data::debug_data();
							echo esc_attr( WP_Debug_Data::format( $info, 'info' ) );
							?>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="uk-card-footer uk-background-muted">
			<nav class="uk-navbar-container uk-margin" uk-navbar>
				<div class="uk-navbar-left">
					<div class="uk-navbar-item">
						<strong>Tetszik a bővítmény? <a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce/reviews/#new-post" target="_blank">Kérlek értékeld 5 csillaggal!</a></strong>
					</div>
				</div>
				<div class="uk-navbar-right">
					<div class="uk-navbar-item"><input type="submit" class="uk-button uk-button-primary uk--button-large" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></div>
				</div>
			</nav>
		</div>
	</div>
</form>
