<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

$page_url = WLSM_M_Staff_Accountant::get_fees_page_url();

$school_id = $current_school['id'];

$fee = NULL;

$nonce_action = 'add-fee';

$label  = '';
$amount = '';
$period = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id  = absint( $_GET['id'] );
	$fee = WLSM_M_Staff_Accountant::fetch_fee( $school_id, $id );

	if ( $fee ) {
		$nonce_action = 'edit-fee-' . $fee->ID;

		$label  = $fee->label;
		$amount = $fee->amount;
		$period = $fee->period;
	}
}

$fee_periods = WLSM_Helper::fee_period_list();
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $fee ) {
						printf(
							wp_kses(
								/* translators: %s: fee type */
								__( 'Edit Fee Type: %s', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( stripcslashes( $label ) )
						);
					} else {
						esc_html_e( 'Add New Fee Type', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-file-invoice"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-fee-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-fee">

			<?php if ( $fee ) { ?>
			<input type="hidden" name="fee_id" value="<?php echo esc_attr( $fee->ID ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="wlsm_label" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Fee Type', 'school-management' ); ?>:
						</label>
						<input type="text" name="label" class="form-control" id="wlsm_label" placeholder="<?php esc_attr_e( 'Enter fee type', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $label ) ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_period" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Period', 'school-management' ); ?>:
						</label>
						<select name="period" class="form-control selectpicker" id="wlsm_period" data-live-search="true">
							<?php foreach ( $fee_periods as $key => $value ) { ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $period, true ); ?>>
								<?php echo esc_html( $value ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_amount" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Amount', 'school-management' ); ?>:
						</label>
						<input type="number" step="any" min="0" name="amount" class="form-control" id="wlsm_amount" placeholder="<?php esc_attr_e( 'Enter amount', 'school-management' ); ?>" value="<?php echo esc_attr( $amount ? WLSM_Config::sanitize_money( $amount ) : '' ); ?>">
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-fee-btn">
						<?php
						if ( $fee ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Fee Type', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Fee Type', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
