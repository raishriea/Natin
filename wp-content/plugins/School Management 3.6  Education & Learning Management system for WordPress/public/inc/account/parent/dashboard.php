<?php
defined( 'ABSPATH' ) || die();

$students = WLSM_M_Parent::fetch_students( $unique_student_ids );
?>
<hr>
<div class="wlsm-parent-students">
<?php
foreach ( $students as $student ) {
	?>
	<div class="wlsm-parent-student-section">
		<?php
		require WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/partials/student_detail.php';
		?>
		<ul class="wlsm-parent-student-links">
			<li>
				<a class="wlsm-pr-print-id-card" data-id-card="<?php echo esc_attr( $student->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'pr-print-id-card-' . $student->ID ) ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Print ID Card', 'school-management' ); ?>"><?php esc_html_e( 'ID Card', 'school-management' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'fee-invoices', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Fee Invoices', 'school-management' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'payment-history', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Payment History', 'school-management' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'class-time-table', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Class Time Table', 'school-management' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'exam-results', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Exam Results', 'school-management' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'attendance', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Attendance Report', 'school-management' ); ?></a>
			</li>
		</ul>
	</div>
	<hr>
	<?php
}
?>
</div>
