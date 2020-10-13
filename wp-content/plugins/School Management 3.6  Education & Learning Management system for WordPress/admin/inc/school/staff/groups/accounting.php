<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';

$page_url_invoices = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_INVOICES );
$page_url_fees     = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_FEES );
$page_url_expenses = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXPENSES );
$page_url_income   = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_INCOME );
?>
<div class="wlsm container-fluid">
	<?php
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php';
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="text-center wlsm-section-heading-block">
				<span class="wlsm-section-heading">
					<i class="fas fa-file-invoice"></i>
					<?php esc_html_e( 'Accounting', 'school-management' ); ?>
				</span>
			</div>
		</div>
	</div>

	<div class="row mt-3 mb-3">
		<?php if ( WLSM_M_Role::check_permission( array( 'manage_invoices' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Fee Invoices', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_invoices ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Fee Invoices', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_invoices . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Fee Invoice', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_invoices . '&action=payment_history' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Payment History', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_expenses' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Expense', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_expenses ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Expense', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_expenses . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Expense', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_expenses . '&action=category' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Expense Category', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_income' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Income', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_income ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Income', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_income . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Income', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_income . '&action=category' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Income Category', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_fees' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Fee Types', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_fees ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Fee Types', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_fees . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Fee Type', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
