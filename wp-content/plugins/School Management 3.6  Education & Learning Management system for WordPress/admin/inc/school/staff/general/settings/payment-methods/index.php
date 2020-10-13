<?php
defined( 'ABSPATH' ) || die();

// Razorpay settings.
$settings_razorpay      = WLSM_M_Setting::get_settings_razorpay( $school_id );
$school_razorpay_enable = $settings_razorpay['enable'];
$school_razorpay_key    = $settings_razorpay['razorpay_key'];
$school_razorpay_secret = $settings_razorpay['razorpay_secret'];

// Stripe settings.
$settings_stripe               = WLSM_M_Setting::get_settings_stripe( $school_id );
$school_stripe_enable          = $settings_stripe['enable'];
$school_stripe_publishable_key = $settings_stripe['publishable_key'];
$school_stripe_secret_key      = $settings_stripe['secret_key'];

// PayPal settings.
$settings_paypal              = WLSM_M_Setting::get_settings_paypal( $school_id );
$school_paypal_enable         = $settings_paypal['enable'];
$school_paypal_business_email = $settings_paypal['business_email'];
$school_paypal_mode           = $settings_paypal['mode'];
$school_paypal_notify_url     = $settings_paypal['notify_url'];

// Pesapal settings.
$settings_pesapal               = WLSM_M_Setting::get_settings_pesapal( $school_id );
$school_pesapal_enable          = $settings_pesapal['enable'];
$school_pesapal_consumer_key    = $settings_pesapal['consumer_key'];
$school_pesapal_consumer_secret = $settings_pesapal['consumer_secret'];
$school_pesapal_mode            = $settings_pesapal['mode'];
$school_pesapal_notify_url      = $settings_pesapal['notify_url'];

// Paystack settings.
$settings_paystack          = WLSM_M_Setting::get_settings_paystack( $school_id );
$school_paystack_enable     = $settings_paystack['enable'];
$school_paystack_public_key = $settings_paystack['paystack_public_key'];
$school_paystack_secret_key = $settings_paystack['paystack_secret_key'];
?>
<div class="tab-pane fade" id="wlsm-school-payment-method" role="tabpanel" aria-labelledby="wlsm-school-payment-method-tab">

	<div class="row">
		<div class="col-md-12">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-payment-method-settings-form">
				<?php
				$nonce_action = 'save-school-payment-method-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-payment-method-settings">

				<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_razorpay_fields" aria-expanded="true" aria-controls="wlsm_razorpay_fields">
					<?php esc_html_e( 'Razorpay Payment Gateway', 'school-management' ); ?>
				</button>

				<div class="collapse border border-top-0 border-primary p-3" id="wlsm_razorpay_fields">

					<div class="wlsm_payment_method wlsm_razorpay">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_razorpay_enable" class="wlsm-font-bold">
									<?php esc_html_e( 'Razorpay Payment', 'school-management' ); ?>:
								</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<label for="wlsm_razorpay_enable" class="wlsm-font-bold">
										<input <?php checked( $school_razorpay_enable, true, true ); ?> type="checkbox" name="razorpay_enable" id="wlsm_razorpay_enable" value="1">
										<?php esc_html_e( 'Enable', 'school-management' ); ?>
									</label>
									<?php if ( ! WLSM_Payment::currency_supports_razorpay( $currency ) ) { ?>
									<br>
									<small class="text-secondary">
										<?php
										printf(
											/* translators: %s: currency code */
											__( 'Razorpay does not support currency %s.', 'school-management' ),
											esc_html( $currency )
										);
										?>
									</small>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_razorpay">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_razorpay_key" class="wlsm-font-bold"><?php esc_html_e( 'Razorpay Key', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input name="razorpay_key" type="text" id="wlsm_razorpay_key" value="<?php echo esc_attr( $school_razorpay_key ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Razorpay Key', 'school-management' ); ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_razorpay">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_razorpay_secret" class="wlsm-font-bold"><?php esc_html_e( 'Razorpay Secret', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input name="razorpay_secret" type="text" id="wlsm_razorpay_secret" value="<?php echo esc_attr( $school_razorpay_secret ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Razorpay Secret', 'school-management' ); ?>">
								</div>
							</div>
						</div>
					</div>

				</div>

				<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_stripe_fields" aria-expanded="true" aria-controls="wlsm_stripe_fields">
					<?php esc_html_e( 'Stripe Payment Gateway', 'school-management' ); ?>
				</button>

				<div class="collapse border border-top-0 border-primary p-3" id="wlsm_stripe_fields">

					<div class="wlsm_payment_method wlsm_stripe">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_stripe_enable" class="wlsm-font-bold">
									<?php esc_html_e( 'Stripe Payment', 'school-management' ); ?>:
								</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<label for="wlsm_stripe_enable" class="wlsm-font-bold">
										<input <?php checked( $school_stripe_enable, true, true ); ?> type="checkbox" name="stripe_enable" id="wlsm_stripe_enable" value="1">
										<?php esc_html_e( 'Enable', 'school-management' ); ?>
									</label>
									<?php if ( ! WLSM_Payment::currency_supports_stripe( $currency ) ) { ?>
									<br>
									<small class="text-secondary">
										<?php
										printf(
											/* translators: %s: currency code */
											__( 'Stripe does not support currency %s.', 'school-management' ),
											esc_html( $currency )
										);
										?>
									</small>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_stripe">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_stripe_publishable_key" class="wlsm-font-bold"><?php esc_html_e( 'Stripe Publishable Key', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input name="stripe_publishable_key" type="text" id="wlsm_stripe_publishable_key" value="<?php echo esc_attr( $school_stripe_publishable_key ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Stripe Publishable Key', 'school-management' ); ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_stripe">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_stripe_secret_key" class="wlsm-font-bold"><?php esc_html_e( 'Stripe Secret Key', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input name="stripe_secret_key" type="text" id="wlsm_stripe_secret_key" value="<?php echo esc_attr( $school_stripe_secret_key ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Stripe Secret Key', 'school-management' ); ?>">
								</div>
							</div>
						</div>
					</div>

				</div>

				<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_paypal_fields" aria-expanded="true" aria-controls="wlsm_paypal_fields">
					<?php esc_html_e( 'PayPal Payment Gateway', 'school-management' ); ?>
				</button>

				<div class="collapse border border-top-0 border-primary p-3" id="wlsm_paypal_fields">

					<div class="wlsm_payment_method wlsm_paypal">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_paypal_enable" class="wlsm-font-bold">
									<?php esc_html_e( 'PayPal Payment', 'school-management' ); ?>:
								</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<label for="wlsm_paypal_enable" class="wlsm-font-bold">
										<input <?php checked( $school_paypal_enable, true, true ); ?> type="checkbox" name="paypal_enable" id="wlsm_paypal_enable" value="1">
										<?php esc_html_e( 'Enable', 'school-management' ); ?>
									</label>
									<?php if ( ! WLSM_Payment::currency_supports_paypal( $currency ) ) { ?>
									<br>
									<small class="text-secondary">
										<?php
										printf(
											/* translators: %s: currency code */
											__( 'PayPal does not support currency %s.', 'school-management' ),
											esc_html( $currency )
										);
										?>
									</small>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_paypal">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_paypal_business_email" class="wlsm-font-bold"><?php esc_html_e( 'PayPal Business Email', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input name="paypal_business_email" type="email" class="form-control" id="wlsm_paypal_business_email" value="<?php echo esc_attr( $school_paypal_business_email ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'PayPal Business Email', 'school-management' ); ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_paypal">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_paypal_mode" class="wlsm-font-bold"><?php esc_html_e( 'Payment Mode', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<select name="paypal_mode" class="form-control" id="wlsm_paypal_mode">
										<option <?php selected( $school_paypal_mode, 'sandbox', true ); ?> value="sandbox"><?php esc_html_e( 'Sandbox', 'school-management' ); ?></option>
										<option <?php selected( $school_paypal_mode, 'live', true ); ?> value="live"><?php esc_html_e( 'Live', 'school-management' ); ?></option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_paypal">
						<div class="row">
							<div class="col-md-12">
								<label class="wlsm-font-bold"><?php esc_html_e( 'PayPal Notify URL', 'school-management' ); ?>: </label><br>
								<span class="text-primary"><?php echo esc_url( $school_paypal_notify_url ); ?></span><br>
								<small class="font-weight-bold">
									( <?php esc_html_e( 'To save transactions, you need to enable PayPal IPN (Instant Payment Notification) in your PayPal Business Account and use this notify URL', 'school-management' ); ?>
									)
								</small>
								<small>
									<ol>
										<li><?php esc_html_e( 'Log into your PayPal account.', 'school-management' ); ?></li>
										<li><?php esc_html_e( 'Go to Profile then "My Selling Tools".', 'school-management' ); ?></li>
										<li><?php esc_html_e( 'Look for an option labelled "Instant Payment Notification". Click on the update button for that option.', 'school-management' ); ?></li>
										<li><?php esc_html_e( 'Click "Choose IPN Settings".', 'school-management' ); ?></li>
										<li><?php esc_html_e( 'Enter the URL given above and hit "Save".', 'school-management' ); ?></li>
									</ol>
								</small>
							</div>
						</div>
					</div>

				</div>

				<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_pesapal_fields" aria-expanded="true" aria-controls="wlsm_pesapal_fields">
					<?php esc_html_e( 'Pesapal Payment Gateway', 'school-management' ); ?>
				</button>

				<div class="collapse border border-top-0 border-primary p-3" id="wlsm_pesapal_fields">

					<div class="wlsm_payment_method wlsm_pesapal">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_pesapal_enable" class="wlsm-font-bold">
									<?php esc_html_e( 'Pesapal Payment', 'school-management' ); ?>:
								</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<label for="wlsm_pesapal_enable" class="wlsm-font-bold">
										<input <?php checked( $school_pesapal_enable, true, true ); ?> type="checkbox" name="pesapal_enable" id="wlsm_pesapal_enable" value="1">
										<?php esc_html_e( 'Enable', 'school-management' ); ?>
									</label>
									<?php if ( ! WLSM_Payment::currency_supports_pesapal( $currency ) ) { ?>
									<br>
									<small class="text-secondary">
										<?php
										printf(
											/* translators: %s: currency code */
											__( 'Pesapal does not support currency %s.', 'school-management' ),
											esc_html( $currency )
										);
										?>
									</small>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_pesapal">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_pesapal_consumer_key" class="wlsm-font-bold"><?php esc_html_e( 'Pesapal Consumer Key', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input name="pesapal_consumer_key" type="text" class="form-control" id="wlsm_pesapal_consumer_key" value="<?php echo esc_attr( $school_pesapal_consumer_key ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Pesapal Consumer Key', 'school-management' ); ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_pesapal">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_pesapal_consumer_secret" class="wlsm-font-bold"><?php esc_html_e( 'Pesapal Consumer Secret', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input name="pesapal_consumer_secret" type="text" class="form-control" id="wlsm_pesapal_consumer_secret" value="<?php echo esc_attr( $school_pesapal_consumer_secret ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Pesapal Consumer Secret', 'school-management' ); ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_pesapal">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_pesapal_mode" class="wlsm-font-bold"><?php esc_html_e( 'Payment Mode', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<select name="pesapal_mode" class="form-control" id="wlsm_pesapal_mode">
										<option <?php selected( $school_pesapal_mode, 'sandbox', true ); ?> value="sandbox"><?php esc_html_e( 'Sandbox', 'school-management' ); ?></option>
										<option <?php selected( $school_pesapal_mode, 'live', true ); ?> value="live"><?php esc_html_e( 'Live', 'school-management' ); ?></option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_pesapal">
						<div class="row">
							<div class="col-md-12">
								<label class="wlsm-font-bold"><?php esc_html_e( 'Pesapal Notify URL', 'school-management' ); ?>: </label><br>
								<span class="text-primary"><?php echo esc_url( $school_pesapal_notify_url ); ?></span><br>
								<small class="font-weight-bold">
									( <?php esc_html_e( 'To save transactions, you need to enable Pesapal IPN (Instant Payment Notification) in your Pesapal Account and use this notify URL', 'school-management' ); ?>
									)
								</small>
								<small>
									<ol>
										<li><?php esc_html_e( 'Log into your Pesapal account.', 'school-management' ); ?></li>
										<li><?php esc_html_e( 'Go to "My Account" then "Account Settings".', 'school-management' ); ?></li>
										<li><?php esc_html_e( 'Look for an option labelled "IPN Settings". Click on the update button for that option.', 'school-management' ); ?></li>
										<li><?php esc_html_e( 'Click "Choose IPN Settings".', 'school-management' ); ?></li>
										<li><?php esc_html_e( 'Enter the "Website Domain" and URL given above in "IPN Listener Url" and hit "Save URL".', 'school-management' ); ?></li>
									</ol>
								</small>
							</div>
						</div>
					</div>

				</div>

				<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_paystack_fields" aria-expanded="true" aria-controls="wlsm_paystack_fields">
					<?php esc_html_e( 'Paystack Payment Gateway', 'school-management' ); ?>
				</button>

				<div class="collapse border border-top-0 border-primary p-3" id="wlsm_paystack_fields">

					<div class="wlsm_payment_method wlsm_paystack">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_paystack_enable" class="wlsm-font-bold">
									<?php esc_html_e( 'Paystack Payment', 'school-management' ); ?>:
								</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<label for="wlsm_paystack_enable" class="wlsm-font-bold">
										<input <?php checked( $school_paystack_enable, true, true ); ?> type="checkbox" name="paystack_enable" id="wlsm_paystack_enable" value="1">
										<?php esc_html_e( 'Enable', 'school-management' ); ?>
									</label>
									<?php if ( ! WLSM_Payment::currency_supports_paystack( $currency ) ) { ?>
									<br>
									<small class="text-secondary">
										<?php
										printf(
											/* translators: %s: currency code */
											__( 'Paystack does not support currency %s.', 'school-management' ),
											esc_html( $currency )
										);
										?>
									</small>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_paystack">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_paystack_public_key" class="wlsm-font-bold"><?php esc_html_e( 'Public Key', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input name="paystack_public_key" type="text" id="wlsm_paystack_public_key" value="<?php echo esc_attr( $school_paystack_public_key ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Public Key', 'school-management' ); ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="wlsm_payment_method wlsm_paystack">
						<div class="row">
							<div class="col-md-3">
								<label for="wlsm_paystack_secret_key" class="wlsm-font-bold"><?php esc_html_e( 'Secret Key', 'school-management' ); ?>:</label>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input name="paystack_secret_key" type="text" id="wlsm_paystack_secret_key" value="<?php echo esc_attr( $school_paystack_secret_key ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Secret Secret', 'school-management' ); ?>">
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-payment-method-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
