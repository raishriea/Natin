<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Config.php';

class WLSM_P_Student {
	public static function view_study_material() {
		$study_material_id = isset( $_POST['study_material_id'] ) ? absint( $_POST['study_material_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'st-view-study-material-' . $study_material_id ], 'st-view-study-material-' . $study_material_id ) ) {
			die();
		}

		$user_id = get_current_user_id();

		try {
			ob_start();
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$class_school_id = $student->class_school_id;

			$study_material = $wpdb->get_row( $wpdb->prepare( 'SELECT sm.ID, sm.label as title, sm.description, sm.attachments, sm.created_at FROM ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . ' as cssm JOIN ' . WLSM_STUDY_MATERIALS . ' as sm ON sm.ID = cssm.study_material_id WHERE cssm.class_school_id = %d AND sm.ID = %d', $class_school_id, $study_material_id ) );

			if ( ! $study_material ) {
				throw new Exception( esc_html__( 'Study material not found.', 'school-management' ) );
			}

			$attachments = $study_material->attachments;
			if ( is_serialized( $attachments ) ) {
				$attachments = unserialize( $attachments );
			} else {
				if ( ! is_array( $attachments ) ) {
					$attachments = array();
				}
			}

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		?>
		<ul class="wlsm-study-material-data">
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Title', 'school-management' ); ?>:</span>
				<span><?php echo esc_html( stripslashes( $study_material->title ) ); ?></span>
			</li>
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Description', 'school-management' ); ?>:</span>
				<span><?php echo esc_html( stripslashes( $study_material->description ) ); ?></span>
			</li>
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Date', 'school-management' ); ?>:</span>
				<span><?php echo esc_html( WLSM_Config::get_date_text( $study_material->created_at ) ); ?></span>
			</li>
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Attachments', 'school-management' ); ?>:</span>
				<span>
					<?php
					if ( count( $attachments ) ) {
					?>
					<ul class="wlsm-study-material-attachments">
					<?php
					foreach ( $attachments as $attachment ) {
						if ( ! empty ( $attachment ) ) {
							$file_name = basename ( get_attached_file( $attachment ) );
						?>
						<li>
							<a target="_blank" href="<?php echo esc_url( wp_get_attachment_url( $attachment ) ); ?>">
								<?php echo esc_html( $file_name ); ?>
							</a>
						</li>
						<?php
						}
					}
					?>
					</ul>
					<?php
					}
					?>
				</span>
			</li>
		</ul>
		<?php
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function view_homework() {
		$homework_id = isset( $_POST['homework_id'] ) ? absint( $_POST['homework_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'st-view-homework-' . $homework_id ], 'st-view-homework-' . $homework_id ) ) {
			die();
		}

		$user_id = get_current_user_id();

		try {
			ob_start();
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$section_id = $student->section_id;

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$homework = $wpdb->get_row( $wpdb->prepare( 'SELECT hw.ID, hw.title, hw.description, hw.homework_date, c.ID as class_id, cs.ID as class_school_id FROM ' . WLSM_HOMEWORK . ' as hw 
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id 
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id 
				LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID 
				LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id 
				LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
				LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
				WHERE s.ID = %d AND ss.ID = %d AND se.ID = %d AND hw.ID = %d', $school_id, $session_id, $section_id, $homework_id ) );

			if ( ! $homework ) {
				throw new Exception( esc_html__( 'Home work not found.', 'school-management' ) );
			}

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		?>
		<ul class="wlsm-study-material-data">
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Title', 'school-management' ); ?>:</span>
				<span><?php echo esc_html( stripslashes( $homework->title ) ); ?></span>
			</li>
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Description', 'school-management' ); ?>:</span>
				<span><?php echo esc_html( stripslashes( $homework->description ) ); ?></span>
			</li>
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Date', 'school-management' ); ?>:</span>
				<span><?php echo esc_html( WLSM_Config::get_date_text( $homework->homework_date ) ); ?></span>
			</li>
		</ul>
		<?php
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}
}
