<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$student_name = WLSM_M_Staff_Class::get_name_text( $student->student_name );

$notices = WLSM_M_Staff_Class::get_school_notices( $school_id, 7, $class_school_id );

$section = WLSM_M_Staff_Class::get_school_section( $school_id, $student->section_id );

$class_label   = $section->class_label;
$section_label = $section->label;
?>
<div class="wlsm-content-area wlsm-section-dashboard wlsm-student-dashboard">
	<div class="wlsm-st-main-title">
		<span>
		<?php
		/* translators: %s: student name */
		printf(
			wp_kses(
				'Welcome <span class="wlsm-font-bold">%s</span>!',
				array( 'span' => array( 'class' => array() ) )
			),
			esc_html( $student_name )
		);
		?>
		</span>
	</div>

	<div class="wlsm-flex-between">
		<div class="wlsm-flex-item wlsm-l-w-50 wlsm-mt-2">
			<div class="wlsm-st-details-heading">
				<span><?php esc_html_e( 'Noticeboard', 'school-management' ); ?></span>
			</div>
			<div class="wlsm-st-recent-notices-section">
				<?php
				if ( count( $notices ) ) {
					$today = new DateTime();
					$today->setTime( 0, 0, 0 );
				?>
				<ul class="wlsm-st-recent-notices">
					<?php
					foreach ( $notices as $key => $notice ) {
						$link_to = $notice->link_to;
						$link    = '#';

						if ( 'url' === $link_to ) {
							if ( ! empty ( $notice->url ) ) {
								$link = $notice->url;
							}
						} else if ( 'attachment' === $link_to ) {
							if ( ! empty ( $notice->attachment ) ) {
								$attachment = $notice->attachment;
								$link       = wp_get_attachment_url( $attachment );
							}
						} else {
							$link = '#';
						}

						$notice_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $notice->created_at );
						$notice_date->setTime( 0, 0, 0 );

						$interval = $today->diff( $notice_date );
					?>
					<li>
						<span>
							<a target="_blank" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( stripslashes( $notice->title ) ); ?> <span class="wlsm-st-notice-date wlsm-font-bold"><?php echo esc_html( WLSM_Config::get_date_text( $notice->created_at ) ); ?></span></a>
							<?php if ( $interval->days < 7 ) { ?>
							<img class="wlsm-st-notice-new" src="<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/images/newicon.gif' ); ?>">
							<?php } ?>
						</span>
					</li>
					<?php
					}
				?>
				</ul>
				<?php
				} else {
				?>
				<div>
					<span class="wlsm-font-medium wlsm-font-bold">
						<?php esc_html_e( 'There is no notice.', 'school-management' ); ?>
					</span>
				</div>
				<?php
				}
				?>
			</div>
		</div>
		<div class="wlsm-flex-item wlsm-l-w-48 wlsm-mt-2">
			<div class="wlsm-st-details">
				<div class="wlsm-st-details-heading">
					<span><?php esc_html_e( 'Your Details', 'school-management' ); ?></span>
				</div>
				<ul class="wlsm-st-details-list">
					<li>
						<span class="wlsm-st-details-list-key"><?php esc_html_e( 'Name' ); ?>:</span>
						<span class="wlsm-st-details-list-value"><?php echo esc_html( $student_name ); ?></span>
					</li>
					<li>
						<span class="wlsm-st-details-list-key"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
						<span class="wlsm-st-details-list-value"><?php echo esc_html( $student->enrollment_number ); ?></span>
					</li>
					<li>
						<span class="wlsm-st-details-list-key"><?php esc_html_e( 'Session', 'school-management' ); ?>:</span>
						<span class="wlsm-st-details-list-value"><?php echo esc_html( WLSM_M_Session::get_label_text( $student->session_label ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-st-details-list-key"><?php esc_html_e( 'Class', 'school-management' ); ?>:</span>
						<span class="wlsm-st-details-list-value"><?php echo esc_html( WLSM_M_Class::get_label_text( $student->class_label ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-st-details-list-key"><?php esc_html_e( 'Section', 'school-management' ); ?>:</span>
						<span class="wlsm-st-details-list-value"><?php echo esc_html( WLSM_M_Class::get_label_text( $student->section_label ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-st-details-list-key"><?php esc_html_e( 'Roll Number', 'school-management' ); ?>:</span>
						<span class="wlsm-st-details-list-value"><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $student->roll_number ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-st-details-list-key"><?php esc_html_e( 'Father Name', 'school-management' ); ?>:</span>
						<span class="wlsm-st-details-list-value"><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $student->father_name ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-st-details-list-key"><?php esc_html_e( 'ID Card', 'school-management' ); ?>:</span>
						<span class="wlsm-st-details-list-value">
							<a class="wlsm-st-print-id-card" data-id-card="<?php echo esc_attr( $user_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-print-id-card-' . $user_id ) ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Print ID Card', 'school-management' ); ?>">
								<?php esc_html_e( 'Print', 'school-management' ); ?>
							</a>
						</span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
