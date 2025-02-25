<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/constants.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_Language.php';
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_Shortcode.php';
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_Widget.php';

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Schedule.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_P_General.php';
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_P_Invoice.php';
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_P_Student.php';
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_P_Inquiry.php';
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_P_Exam.php';
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_P_Certificate.php';
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/WLSM_P_Print.php';

// Load translation.
add_action( 'plugins_loaded', array( 'WLSM_Language', 'load_translation' ) );

// Register widgets.
add_action( 'widgets_init', array( 'WLSM_Widget', 'register_widgets' ) );

// Add shortcodes.
add_shortcode( 'school_management_account', array( 'WLSM_Shortcode', 'account' ) );
add_shortcode( 'school_management_inquiry', array( 'WLSM_Shortcode', 'inquiry' ) );
add_shortcode( 'school_management_fees', array( 'WLSM_Shortcode', 'fees' ) );
add_shortcode( 'school_management_noticeboard', array( 'WLSM_Shortcode', 'noticeboard' ) );
add_shortcode( 'school_management_exam_time_table', array( 'WLSM_Shortcode', 'exam_time_table' ) );
add_shortcode( 'school_management_exam_admit_card', array( 'WLSM_Shortcode', 'exam_admit_card' ) );
add_shortcode( 'school_management_exam_result', array( 'WLSM_Shortcode', 'exam_result' ) );
add_shortcode( 'school_management_certificate', array( 'WLSM_Shortcode', 'certificate' ) );

// Enqueue shortcode assets.
add_action('wp_enqueue_scripts', array( 'WLSM_Shortcode', 'enqueue_assets' ) );

// Show admin bar for woocommerce.
add_filter( 'woocommerce_prevent_admin_access', '__return_false' );
add_filter( 'woocommerce_disable_admin_bar', '__return_false' );

// Schedules.
add_action( 'wlsm_notify_for_student_admission', array( 'WLSM_Schedule', 'notify_for_student_admission' ), 10, 4 );
add_action( 'wlsm_notify_for_invoice_generated', array( 'WLSM_Schedule', 'notify_for_invoice_generated' ), 10, 3 );
add_action( 'wlsm_notify_for_online_fee_submission', array( 'WLSM_Schedule', 'notify_for_online_fee_submission' ), 10, 3 );
add_action( 'wlsm_notify_for_offline_fee_submission', array( 'WLSM_Schedule', 'notify_for_offline_fee_submission' ), 10, 3 );
add_action( 'wlsm_notify_for_student_admission_to_parent', array( 'WLSM_Schedule', 'notify_for_student_admission_to_parent' ), 10, 4 );
add_action( 'wlsm_notify_for_invoice_generated_to_parent', array( 'WLSM_Schedule', 'notify_for_invoice_generated_to_parent' ), 10, 3 );
add_action( 'wlsm_notify_for_online_fee_submission_to_parent', array( 'WLSM_Schedule', 'notify_for_online_fee_submission_to_parent' ), 10, 3 );
add_action( 'wlsm_notify_for_offline_fee_submission_to_parent', array( 'WLSM_Schedule', 'notify_for_offline_fee_submission_to_parent' ), 10, 3 );
add_action( 'wlsm_notify_for_absent_student', array( 'WLSM_Schedule', 'notify_for_absent_student' ), 10, 4 );
add_action( 'wlsm_notify_for_custom_message', array( 'WLSM_Schedule', 'notify_for_custom_message' ), 10, 5 );
add_action( 'wlsm_notify_for_homework_message', array( 'WLSM_Schedule', 'notify_for_homework_message' ), 10, 3 );

// Get students with pending invoices.
add_action( 'wp_ajax_wlsm-p-get-students-with-pending-invoices', array( 'WLSM_P_Invoice', 'get_students_with_pending_invoices' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-students-with-pending-invoices', array( 'WLSM_P_Invoice', 'get_students_with_pending_invoices' ) );

// Get student pending invoices.
add_action( 'wp_ajax_wlsm-p-get-student-pending-invoices', array( 'WLSM_P_Invoice', 'get_student_pending_invoices' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-student-pending-invoices', array( 'WLSM_P_Invoice', 'get_student_pending_invoices' ) );

// Get student pending invoice.
add_action( 'wp_ajax_wlsm-p-get-student-pending-invoice', array( 'WLSM_P_Invoice', 'get_student_pending_invoice' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-student-pending-invoice', array( 'WLSM_P_Invoice', 'get_student_pending_invoice' ) );

// Pay invoice amount.
add_action( 'wp_ajax_wlsm-p-pay-invoice-amount', array( 'WLSM_P_Invoice', 'pay_invoice_amount' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-pay-invoice-amount', array( 'WLSM_P_Invoice', 'pay_invoice_amount' ) );

// Submit inquiry.
add_action( 'wp_ajax_wlsm-p-submit-inquiry', array( 'WLSM_P_Inquiry', 'submit_inquiry' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-submit-inquiry', array( 'WLSM_P_Inquiry', 'submit_inquiry' ) );

// Process Razorpay.
add_action( 'wp_ajax_wlsm-p-pay-with-razorpay', array( 'WLSM_P_Invoice', 'process_razorpay' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-pay-with-razorpay', array( 'WLSM_P_Invoice', 'process_razorpay' ) );

// Process Stripe.
add_action( 'wp_ajax_wlsm-p-pay-with-stripe', array( 'WLSM_P_Invoice', 'process_stripe' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-pay-with-stripe', array( 'WLSM_P_Invoice', 'process_stripe' ) );

// Process PayPal.
add_action( 'wp_ajax_nopriv_wlsm-p-pay-with-paypal', array( 'WLSM_P_Invoice', 'process_paypal' ) );

// Process Pesapal.
add_action( 'wp_ajax_wlsm-p-pay-with-pesapal', array( 'WLSM_P_Invoice', 'process_pesapal' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-pay-with-pesapal', array( 'WLSM_P_Invoice', 'process_pesapal' ) );

// Process Paystack.
add_action( 'wp_ajax_wlsm-p-pay-with-paystack', array( 'WLSM_P_Invoice', 'process_paystack' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-pay-with-paystack', array( 'WLSM_P_Invoice', 'process_paystack' ) );

// Get exam time table.
add_action( 'wp_ajax_wlsm-p-get-exam-time-table', array( 'WLSM_P_Exam', 'get_exam_time_table' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-exam-time-table', array( 'WLSM_P_Exam', 'get_exam_time_table' ) );

// Get exam admit card.
add_action( 'wp_ajax_wlsm-p-get-exam-admit-card', array( 'WLSM_P_Exam', 'get_exam_admit_card' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-exam-admit-card', array( 'WLSM_P_Exam', 'get_exam_admit_card' ) );

// Get exam result.
add_action( 'wp_ajax_wlsm-p-get-exam-result', array( 'WLSM_P_Exam', 'get_exam_result' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-exam-result', array( 'WLSM_P_Exam', 'get_exam_result' ) );

// Get certificate.
add_action( 'wp_ajax_wlsm-p-get-certificate', array( 'WLSM_P_Certificate', 'get_certificate' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-certificate', array( 'WLSM_P_Certificate', 'get_certificate' ) );

// General Actions.
add_action( 'wp_ajax_wlsm-p-get-school-classes', array( 'WLSM_P_General', 'get_school_classes' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-school-classes', array( 'WLSM_P_General', 'get_school_classes' ) );
add_action( 'wp_ajax_wlsm-p-get-school-exams-time-table', array( 'WLSM_P_General', 'get_school_exams_time_table' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-school-exams-time-table', array( 'WLSM_P_General', 'get_school_exams_time_table' ) );
add_action( 'wp_ajax_wlsm-p-get-school-exams-admit-card', array( 'WLSM_P_General', 'get_school_exams_admit_card' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-school-exams-admit-card', array( 'WLSM_P_General', 'get_school_exams_admit_card' ) );
add_action( 'wp_ajax_wlsm-p-get-school-exams-result', array( 'WLSM_P_General', 'get_school_exams_result' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-school-exams-result', array( 'WLSM_P_General', 'get_school_exams_result' ) );
add_action( 'wp_ajax_wlsm-p-get-school-certificates', array( 'WLSM_P_General', 'get_school_certificates' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-get-school-certificates', array( 'WLSM_P_General', 'get_school_certificates' ) );

// Student: Print ID card.
add_action( 'wp_ajax_wlsm-p-st-print-id-card', array( 'WLSM_P_Print', 'student_print_id_card' ) );

// Parent: Print ID card.
add_action( 'wp_ajax_wlsm-p-pr-print-id-card', array( 'WLSM_P_Print', 'parent_print_id_card' ) );

// Student: Print invoice payment.
add_action( 'wp_ajax_wlsm-p-st-print-invoice-payment', array( 'WLSM_P_Print', 'student_print_payment' ) );

// Parent: Print invoice payment.
add_action( 'wp_ajax_wlsm-p-pr-print-invoice-payment', array( 'WLSM_P_Print', 'parent_print_payment' ) );

// Student: View study material.
add_action( 'wp_ajax_wlsm-p-st-view-study-material', array( 'WLSM_P_Student', 'view_study_material' ) );

// Student: View homework.
add_action( 'wp_ajax_wlsm-p-st-view-homework', array( 'WLSM_P_Student', 'view_homework' ) );

// Account settings.
add_action( 'wp_ajax_wlsm-p-save-account-settings', array( 'WLSM_P_General', 'save_account_settings' ) );

// Student: Print class time table.
add_action( 'wp_ajax_wlsm-p-st-print-class-time-table', array( 'WLSM_P_Print', 'student_print_class_time_table' ) );

// Parent: Print class time table.
add_action( 'wp_ajax_wlsm-p-pr-print-class-time-table', array( 'WLSM_P_Print', 'parent_print_class_time_table' ) );

// Parent: Print class time table.
add_action( 'wp_ajax_wlsm-p-pr-print-class-time-table', array( 'WLSM_P_Print', 'parent_print_class_time_table' ) );

// Student: Print exam time table.
add_action( 'wp_ajax_wlsm-p-st-print-exam-time-table', array( 'WLSM_P_Print', 'student_print_exam_time_table' ) );

// Student: Print exam admit card.
add_action( 'wp_ajax_wlsm-p-st-print-exam-admit-card', array( 'WLSM_P_Print', 'student_print_exam_admit_card' ) );

// Student: Print exam results.
add_action( 'wp_ajax_wlsm-p-st-print-exam-results', array( 'WLSM_P_Print', 'student_print_exam_results' ) );

// Student: Print results assessment.
add_action( 'wp_ajax_wlsm-p-st-print-results-assessment', array( 'WLSM_P_Print', 'student_print_results_assessment' ) );

// Parent: Print exam results.
add_action( 'wp_ajax_wlsm-p-pr-print-exam-results', array( 'WLSM_P_Print', 'parent_print_exam_results' ) );

// Shortcode: Print exam time table.
add_action( 'wp_ajax_wlsm-p-print-exam-time-table', array( 'WLSM_P_Print', 'print_exam_time_table' ) );
add_action( 'wp_ajax_nopriv_wlsm-p-print-exam-time-table', array( 'WLSM_P_Print', 'print_exam_time_table' ) );
