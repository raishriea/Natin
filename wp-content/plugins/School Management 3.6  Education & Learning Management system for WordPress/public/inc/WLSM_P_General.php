<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_School.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Session.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';

class WLSM_P_General {
	public static function get_school_classes() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'get-school-classes' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id  = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;
			$session_id = isset( $_POST['session_id'] ) ? absint( $_POST['session_id'] ) : 0;

			// Checks if school exists.
			$school = WLSM_M_School::get_active_school( $school_id );

			if ( ! $school ) {
				throw new Exception( esc_html__( 'School not found.', 'school-management' ) );
			}

			if ( $session_id ) {
				// Check if session exists.
				$session = WLSM_M_Session::get_session( $session_id );

				if ( ! $session ) {
					throw new Exception( esc_html__( 'Session not found.', 'school-management' ) );
				}
			}

			$classes = WLSM_M_Staff_General::fetch_school_classes( $school_id );

			$classes = array_map( function( $class ) {
				$class->label = WLSM_M_Class::get_label_text( $class->label );
				return $class;
			}, $classes );

			wp_send_json( $classes );
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json( array() );
		}
	}

	public static function get_school_exams_time_table() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'get-school-exams' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;

			// Checks if school exists.
			$school = WLSM_M_School::get_active_school( $school_id );

			if ( ! $school ) {
				throw new Exception( esc_html__( 'School not found.', 'school-management' ) );
			}

			$exams = WLSM_M_Staff_Examination::get_school_published_exams_time_table( $school_id );

			$exams = array_map( function( $exam ) {
				$exam->label = WLSM_M_Staff_Examination::get_exam_label_text( $exam->exam_title );
				return $exam;
			}, $exams );

			wp_send_json( $exams );
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json( array() );
		}
	}

	public static function get_school_exams_admit_card() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'get-school-exams' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;

			// Checks if school exists.
			$school = WLSM_M_School::get_active_school( $school_id );

			if ( ! $school ) {
				throw new Exception( esc_html__( 'School not found.', 'school-management' ) );
			}

			$exams = WLSM_M_Staff_Examination::get_school_published_exams_admit_card( $school_id );

			$exams = array_map( function( $exam ) {
				$exam->label = WLSM_M_Staff_Examination::get_exam_label_text( $exam->exam_title );
				return $exam;
			}, $exams );

			wp_send_json( $exams );
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json( array() );
		}
	}

	public static function get_school_exams_result() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'get-school-exams' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;

			// Checks if school exists.
			$school = WLSM_M_School::get_active_school( $school_id );

			if ( ! $school ) {
				throw new Exception( esc_html__( 'School not found.', 'school-management' ) );
			}

			$exams = WLSM_M_Staff_Examination::get_school_published_exams_result( $school_id );

			$exams = array_map( function( $exam ) {
				$exam->label = WLSM_M_Staff_Examination::get_exam_label_text( $exam->exam_title );
				return $exam;
			}, $exams );

			wp_send_json( $exams );
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json( array() );
		}
	}

	public static function get_school_certificates() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'get-school-certificates' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;

			// Checks if school exists.
			$school = WLSM_M_School::get_active_school( $school_id );

			if ( ! $school ) {
				throw new Exception( esc_html__( 'School not found.', 'school-management' ) );
			}

			$certificates = WLSM_M_Staff_General::get_school_certificates( $school_id );

			$certificates = array_map( function( $certificate ) {
				$certificate->label = WLSM_M_Staff_Class::get_certificate_label_text( $certificate->label );
				return $certificate;
			}, $certificates );

			wp_send_json( $certificates );
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json( array() );
		}
	}

	public static function save_account_settings() {
		if ( ! wp_verify_nonce( $_POST['save-account-settings'], 'save-account-settings' ) ) {
			die();
		}

		try {
			ob_start();

			$email            = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
			$password         = isset( $_POST['password'] ) ? $_POST['password'] : '';
			$password_confirm = isset( $_POST['password_confirm'] ) ? $_POST['password_confirm'] : '';

			// Start validation.
			$errors = array();

			if ( empty( $email ) ) {
				$errors['email'] = esc_html__( 'Please provide email address.', 'school-management' );
			}

			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$errors['email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
			}

			if ( empty( $password ) ) {
				$errors['password'] = esc_html__( 'Please provide password.', 'school-management' );
			}

			if ( empty( $password_confirm ) ) {
				$errors['password_confirm'] = esc_html__( 'Please confirm password.', 'school-management' );
			}

			if ( $password !== $password_confirm ) {
				$errors['password'] = esc_html__( 'Passwords do not match.', 'school-management' );
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

		$user = wp_get_current_user();

		if ( count( $errors ) < 1 ) {
			try {
				$data = array(
					'ID'         => $user->ID,
					'user_email' => $email,
					'user_pass'  => $password,
				);

				$user_id = wp_update_user( $data );

				if ( is_wp_error( $user_id ) ) {
					throw new Exception( $user_id->get_error_message() );
				}

				wp_set_auth_cookie( $user->ID );
				wp_set_current_user( $user->ID );
				do_action('wp_login', $user->user_login, $user );

				$message = esc_html__( 'Account settings updated.', 'school-management' );

				wp_send_json_success( array( 'message' => $message, 'reload' => true ) );
			} catch ( Exception $exception ) {
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}
}
