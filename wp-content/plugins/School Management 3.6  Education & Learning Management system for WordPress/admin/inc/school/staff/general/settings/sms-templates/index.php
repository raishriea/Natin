<?php
defined( 'ABSPATH' ) || die();
?>
<div class="tab-pane fade" id="wlsm-school-sms-templates" role="tabpanel" aria-labelledby="wlsm-school-sms-templates-tab">

	<div class="row">
		<div class="col-md-12">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-sms-templates-settings-form">
				<?php
				$nonce_action = 'save-school-sms-templates-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-sms-templates-settings">

				<?php
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/student_admission.php';
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/invoice_generated.php';
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/online_fee_submission.php';
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/offline_fee_submission.php';
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/student_admission_to_parent.php';
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/invoice_generated_to_parent.php';
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/online_fee_submission_to_parent.php';
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/offline_fee_submission_to_parent.php';
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/absent_student.php';
				?>

				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-sms-templates-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
