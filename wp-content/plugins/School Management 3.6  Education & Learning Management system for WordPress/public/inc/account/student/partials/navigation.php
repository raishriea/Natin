<?php
defined( 'ABSPATH' ) || die();
?>
<input class="wlsm-menu-btn" type="checkbox" id="wlsm-menu-btn">
<label class="wlsm-menu-label" for="wlsm-menu-btn"><span class="wlsm-menu-icon"></span></label>
<ul class="wlsm-navigation-links">
	<li>
		<a class="wlsm-navigation-link<?php if ( '' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array(), $current_page_url ) ); ?>"><?php esc_html_e( 'Dashboard', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'fee-invoices' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'fee-invoices' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Fee Invoices', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'payment-history' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'payment-history' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Payment History', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'study-materials' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'study-materials' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Study Materials', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'homework' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'homework' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Home Work', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'noticeboard' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'noticeboard' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Noticeboard', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'class-time-table' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'class-time-table' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Class Time Table', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'exams-time-table' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'exams-time-table' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Exam Time Table', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'exam-admit-card' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'exam-admit-card' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Admit Card', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'exam-results' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'exam-results' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Exam Results', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'certificates' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'certificates' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Certificates', 'school-management' ); ?></a>
	</li>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'attendance' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'attendance' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Attendance', 'school-management' ); ?></a>
	</li>
</ul>
