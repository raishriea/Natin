<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$section_id = $student->section_id;

$homeworks_per_page = 10;

$homeworks_query = 'SELECT hw.ID, hw.title, hw.description, hw.homework_date FROM ' . WLSM_HOMEWORK . ' as hw 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id 
					LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID 
					LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id 
					LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					WHERE s.ID = %d AND ss.ID = %d AND se.ID = %d GROUP BY hw.ID';

$homeworks_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$homeworks_query}) AS combined_table", $school_id, $session_id, $section_id ) );

$homeworks_page = isset( $_GET['homeworks_page'] ) ? absint( $_GET['homeworks_page'] ) : 1;

$homeworks_page_offset = ( $homeworks_page * $homeworks_per_page ) - $homeworks_per_page;

$homeworks = $wpdb->get_results( $wpdb->prepare( $homeworks_query . ' ORDER BY hw.homework_date DESC LIMIT %d, %d', $school_id, $session_id, $section_id, $homeworks_page_offset, $homeworks_per_page ) );
?>
<div class="wlsm-content-area wlsm-section-homeworks wlsm-student-homeworks">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Home Work', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-homeworks-section">
		<?php
		if ( count( $homeworks ) ) {
		?>
		<ul class="wlst-st-list wlsm-st-homeworks">
			<?php
			foreach ( $homeworks as $key => $homework ) {
			?>
			<li>
				<span>
					<?php echo esc_html( stripslashes( $homework->title ) ); ?> <span class="wlsm-st-homework-date wlsm-font-bold"><?php echo esc_html( WLSM_Config::get_date_text( $homework->homework_date ) ); ?></span>
					<a class="wlsm-st-view-homework wlsm-ml-1" data-homework="<?php echo esc_attr( $homework->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-view-homework-' . $homework->ID ) ); ?>" href="#" data-message-title="<?php echo esc_attr( stripslashes( $homework->title ) ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>">
						<?php esc_html_e( 'View', 'school-management' ); ?>
					</a>
				</span>
			</li>
			<?php
			}
		?>
		</ul>
		<div class="wlsm-text-right wlsm-font-medium wlsm-font-bold wlsm-mt-2">
		<?php
		echo paginate_links(
			array(
				'base'      => add_query_arg( 'homeworks_page', '%#%' ),
				'format'    => '',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil( $homeworks_total / $homeworks_per_page ),
				'current'   => $homeworks_page,
			)
		);
		?>
		</div>
		<?php
		} else {
		?>
		<div>
			<span class="wlsm-font-medium wlsm-font-bold">
				<?php esc_html_e( 'There is no homework.', 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
