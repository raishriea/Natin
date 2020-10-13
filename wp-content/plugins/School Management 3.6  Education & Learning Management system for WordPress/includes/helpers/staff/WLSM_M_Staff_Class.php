<?php
defined( 'ABSPATH' ) || die();

class WLSM_M_Staff_Class {
	public static function get_sections_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_CLASSES );
	}

	public static function fetch_classes_query( $school_id, $session_id ) {
		$query = 'SELECT c.ID, c.label, COUNT(DISTINCT se.ID) as sections_count, COUNT(DISTINCT sr.ID) as students_count FROM ' . WLSM_CLASS_SCHOOL . ' as cs 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.class_school_id = cs.ID 
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.section_id = se.ID AND sr.session_id = ' . absint( $session_id ) . ' 
		WHERE cs.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_classes_query_group_by() {
		$group_by = 'GROUP BY c.ID';
		return $group_by;
	}

	public static function fetch_classes_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT c.ID) FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.school_id =' . absint( $school_id );
		return $query;
	}

	public static function get_class( $school_id, $class_id ) {
		global $wpdb;
		$class = $wpdb->get_row( $wpdb->prepare( 'SELECT cs.ID, cs.default_section_id FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.class_id = %d AND cs.school_id = %d', $class_id, $school_id ) );
		return $class;
	}

	public static function get_class_with_label( $school_id, $label ) {
		global $wpdb;
		$class = $wpdb->get_row( $wpdb->prepare( 'SELECT cs.ID, cs.class_id, cs.default_section_id FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE c.label = %s AND cs.school_id = %d', $label, $school_id ) );
		return $class;
	}

	public static function fetch_class( $school_id, $class_id ) {
		global $wpdb;
		$class = $wpdb->get_row( $wpdb->prepare( 'SELECT cs.ID, c.ID as class_id, c.label, cs.default_section_id FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.class_id = %d AND cs.school_id = %d', $class_id, $school_id ) );
		return $class;
	}

	public static function fetch_sections_query( $school_id, $session_id, $class_school_id ) {
		$query = 'SELECT se.ID, se.label, cs.class_id as class_id, cs.default_section_id, COUNT(sr.ID) as students_count FROM ' . WLSM_SECTIONS . ' as se 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.section_id = se.ID AND sr.session_id = ' . absint( $session_id ) . ' 
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND se.class_school_id = ' . absint( $class_school_id );
		return $query;
	}

	public static function fetch_sections_query_group_by() {
		$group_by = 'GROUP BY se.ID';
		return $group_by;
	}

	public static function fetch_sections_query_count( $school_id, $class_school_id ) {
		$query = 'SELECT COUNT(DISTINCT se.ID) FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id WHERE cs.school_id = ' . absint( $school_id ) . ' AND se.class_school_id = ' . absint( $class_school_id );
		return $query;
	}

	public static function get_section( $school_id, $id, $class_school_id ) {
		global $wpdb;
		$section = $wpdb->get_row( $wpdb->prepare( 'SELECT se.ID FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.school_id = %d AND se.ID = %d AND se.class_school_id = %d', $school_id, $id, $class_school_id ) );
		return $section;
	}

	public static function get_section_with_label( $school_id, $label, $class_school_id ) {
		global $wpdb;
		$section = $wpdb->get_row( $wpdb->prepare( 'SELECT se.ID FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.school_id = %d AND se.label = %s AND se.class_school_id = %d', $school_id, $label, $class_school_id ) );
		return $section;
	}

	public static function fetch_section( $school_id, $id, $class_school_id ) {
		global $wpdb;
		$section = $wpdb->get_row( $wpdb->prepare( 'SELECT se.ID, se.label FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.school_id = %d AND se.ID = %d AND se.class_school_id = %d', $school_id, $id, $class_school_id ) );
		return $section;
	}

	public static function get_school_section( $school_id, $section_id ) {
		global $wpdb;
		$section = $wpdb->get_row(
			$wpdb->prepare( 'SELECT se.ID, se.label, c.label as class_label FROM ' . WLSM_SECTIONS . ' as se 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
			WHERE cs.school_id = %d AND se.ID = %d', $school_id, $section_id ) );
		return $section;
	}

	public static function fetch_classes( $school_id ) {
		global $wpdb;
		$classes = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT(c.ID), c.label FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.school_id = %d ORDER BY c.ID ASC', $school_id ) );
		return $classes;
	}

	public static function fetch_sections( $class_school_id ) {
		global $wpdb;
		$sections = $wpdb->get_results( $wpdb->prepare( 'SELECT se.ID, se.label FROM ' . WLSM_SECTIONS . ' as se WHERE se.class_school_id = %d', $class_school_id ) );
		return $sections;
	}

	public static function get_attendance_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ATTENDANCE );
	}

	public static function get_class_students( $school_id, $session_id, $class_id, $only_active = true ) {
		global $wpdb;

		if ( $only_active ) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		$students = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.ID, sr.name, sr.enrollment_number, sr.roll_number, sr.phone, sr.father_name, sr.father_phone, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE cs.school_id = %d AND ss.ID = %d AND cs.class_id = %d' . $where . ' GROUP BY sr.ID ORDER BY sr.roll_number ASC, sr.name ASC', $school_id, $session_id, $class_id ), OBJECT_K );
		return $students;
	}

	public static function get_section_students( $school_id, $session_id, $section_id, $only_active = true ) {
		global $wpdb;

		if ( $only_active ) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		$students = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.ID, sr.name, sr.enrollment_number, sr.roll_number, sr.phone, sr.father_name, sr.father_phone, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE cs.school_id = %d AND ss.ID = %d AND se.ID = %d' . $where . ' GROUP BY sr.ID ORDER BY sr.roll_number ASC, sr.name ASC', $school_id, $session_id, $section_id ), OBJECT_K );
		return $students;
	}

	public static function get_notices_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_NOTICES );
	}

	public static function fetch_notice_query( $school_id ) {
		$query = 'SELECT n.ID, n.title, n.attachment, n.url, n.link_to, n.is_active, n.created_at, u.user_login as username FROM ' . WLSM_NOTICES . ' as n LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = n.added_by WHERE n.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_notice_query_group_by() {
		$group_by = 'GROUP BY n.ID';
		return $group_by;
	}

	public static function fetch_notice_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT n.ID) FROM ' . WLSM_NOTICES . ' as n LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = n.added_by WHERE n.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_notice( $school_id, $id ) {
		global $wpdb;
		$notice = $wpdb->get_row( $wpdb->prepare( 'SELECT n.ID, n.attachment FROM ' . WLSM_NOTICES . ' as n WHERE n.school_id = %d AND n.ID = %d', $school_id, $id ) );
		return $notice;
	}

	public static function fetch_notice( $school_id, $id ) {
		global $wpdb;		
		$notice = $wpdb->get_row( $wpdb->prepare( 'SELECT n.ID, n.title, n.attachment, n.url, n.link_to, n.is_active FROM ' . WLSM_NOTICES . ' as n WHERE n.school_id = %d AND n.ID = %d', $school_id, $id ) );
		return $notice;
	}

	public static function fetch_notice_classes( $school_id, $notice_id ) {
		global $wpdb;
		$classes = $wpdb->get_col( $wpdb->prepare( 'SELECT DISTINCT c.ID FROM ' . WLSM_CLASS_SCHOOL_NOTICE . ' as csn 
			JOIN ' . WLSM_NOTICES . ' as n ON n.ID = csn.notice_id 
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = n.school_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = csn.class_school_id 
			JOIN ' . WLSM_CLASSES . ' as c ON cs.class_id = c.ID 
			WHERE s.ID = %d AND csn.notice_id = %d ORDER BY csn.ID ASC', $school_id, $notice_id ) );
		return $classes;
	}

	public static function get_school_notices( $school_id, $limit = '', $class_school_id = '' ) {
		global $wpdb;
		$sql = 'SELECT n.ID, n.title, n.attachment, n.url, n.link_to, n.is_active, n.created_at, COUNT(DISTINCT csn.ID) as classes_count, COUNT(DISTINCT csn2.ID) as other_classes_count FROM ' . WLSM_NOTICES . ' as n LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL_NOTICE . ' as csn ON csn.notice_id = n.ID AND (csn.class_school_id = %d) LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL_NOTICE . ' as csn2 ON csn2.notice_id = n.ID AND (csn2.class_school_id != %d) WHERE n.school_id = %d AND n.is_active = 1 GROUP BY n.ID HAVING (classes_count = 0 AND other_classes_count = 0) OR classes_count = 1 ORDER BY n.ID DESC';
		if ( $limit ) {
			$sql .= ( ' LIMIT ' . absint( $limit ) );
		}
		$notices = $wpdb->get_results( $wpdb->prepare( $sql, $class_school_id, $class_school_id, $school_id ) );
		return $notices;
	}

	public static function get_subjects_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_SUBJECTS );
	}

	public static function fetch_subject_query( $school_id ) {
		$query = 'SELECT sj.ID, sj.label as subject_name, sj.code, sj.type, c.label as class_label, COUNT(DISTINCT asj.ID) as admins_count FROM ' . WLSM_SUBJECTS . ' as sj 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		LEFT OUTER JOIN ' . WLSM_ADMIN_SUBJECT . ' as asj ON asj.subject_id = sj.ID 
		WHERE cs.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_subject_query_group_by() {
		$group_by = 'GROUP BY sj.ID';
		return $group_by;
	}

	public static function fetch_subject_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT sj.ID) FROM ' . WLSM_SUBJECTS . ' as sj 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE cs.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_subject( $school_id, $id ) {
		global $wpdb;
		$subject = $wpdb->get_row( $wpdb->prepare( 'SELECT sj.ID FROM ' . WLSM_SUBJECTS . ' as sj 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
			WHERE cs.school_id = %d AND sj.ID = %d', $school_id, $id ) );
		return $subject;
	}

	public static function fetch_subject( $school_id, $id ) {
		global $wpdb;
		$subject = $wpdb->get_row( $wpdb->prepare( 'SELECT sj.ID, sj.label as subject_name, sj.code, sj.type, cs.class_id, c.label as class_label FROM ' . WLSM_SUBJECTS . ' as sj 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
			WHERE cs.school_id = %d AND sj.ID = %d', $school_id, $id ) );
		return $subject;
	}

	public static function fetch_subject_admins_query( $school_id, $subject_id ) {
		$query = 'SELECT a.ID, a.name, a.phone, a.is_active, u.user_login as username FROM ' . WLSM_ADMIN_SUBJECT . ' as asj 
		JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = asj.admin_id 
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id 
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id 
		WHERE sf.school_id = ' . absint( $school_id ) . ' AND sj.ID = ' . absint( $subject_id );
		return $query;
	}

	public static function fetch_subject_admins_query_count( $school_id, $subject_id ) {
		$query = 'SELECT COUNT(DISTINCT a.ID) FROM ' . WLSM_ADMIN_SUBJECT . ' as asj 
		JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = asj.admin_id 
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id 
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id 
		WHERE sf.school_id = ' . absint( $school_id ) . ' AND sj.ID = ' . absint( $subject_id );
		return $query;
	}

	public static function fetch_subject_admins( $school_id, $subject_id ) {
		global $wpdb;
		$admins = $wpdb->get_results( $wpdb->prepare( 'SELECT a.ID, a.name, a.phone, a.is_active, u.user_login as username FROM ' . WLSM_ADMIN_SUBJECT . ' as asj 
		JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = asj.admin_id 
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id 
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id 
		WHERE sf.school_id = %d AND sj.ID = %d', $school_id, $subject_id ) );
		return $admins;
	}

	public static function get_subject_admins( $school_id, $subject_id ) {
		global $wpdb;
		$admins = $wpdb->get_results( $wpdb->prepare( 'SELECT a.ID, a.name as label, a.phone FROM ' . WLSM_ADMINS . ' as a 
			JOIN ' . WLSM_ADMIN_SUBJECT . ' asj ON asj.admin_id = a.ID 
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id 
			WHERE sf.school_id = %d AND a.is_active = 1 AND sj.ID = %d GROUP BY a.ID', $school_id, $subject_id ) );
		return $admins;
	}

	public static function get_admin_subject( $school_id, $subject_id, $admin_id ) {
		global $wpdb;
		$admin = $wpdb->get_row( $wpdb->prepare( 'SELECT asj.ID FROM ' . WLSM_ADMIN_SUBJECT . ' as asj 
		JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = asj.admin_id 
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id 
		WHERE sf.school_id = %d AND sj.ID = %d AND a.ID = %d', $school_id, $subject_id, $admin_id ) );
		return $admin;
	}

	public static function get_class_subjects( $school_id, $class_id ) {
		global $wpdb;
		$subjects = $wpdb->get_results( $wpdb->prepare( 'SELECT sj.ID, sj.label, sj.code FROM ' . WLSM_SUBJECTS . ' as sj 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
		WHERE cs.school_id = %d AND cs.class_id = %d', $school_id, $class_id ) );
		return $subjects;
	}

	public static function get_class_subject( $school_id, $class_id, $subject_id ) {
		global $wpdb;
		$subject = $wpdb->get_results( $wpdb->prepare( 'SELECT sj.ID, sj.label FROM ' . WLSM_SUBJECTS . ' as sj 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
		WHERE cs.school_id = %d AND cs.class_id = %d AND sj.ID = %d', $school_id, $class_id, $subject_id ) );
		return $subject;
	}

	public static function get_keyword_active_admins( $school_id, $keyword ) {
		global $wpdb;
		$admins = $wpdb->get_results( $wpdb->prepare( 'SELECT a.ID, a.name as label, a.phone, u.user_login as username FROM ' . WLSM_ADMINS . ' as a 
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id 
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id 
			WHERE sf.school_id = %d AND a.is_active = 1 AND a.name LIKE "%%%s%%" GROUP BY a.ID', $school_id, $wpdb->esc_like( $keyword ) ) );
		return $admins;
	}

	public static function get_active_admins_ids_in_school( $school_id, $admin_ids ) {
		global $wpdb;

		$values        = array( $school_id );
		$place_holders = array();

		foreach ( $admin_ids as $admin_id ) {
			array_push( $values, $admin_id );
			array_push( $place_holders, '%d' );
		}

		$admin_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT a.ID FROM ' . WLSM_ADMINS . ' as a 
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id 
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id 
			WHERE sf.school_id = %d AND a.is_active = 1 AND a.ID IN(' . implode( ', ', $place_holders ) . ')', $values ) );

		return $admin_ids;
	}

	public static function get_study_materials_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_STUDY_MATERIALS );
	}

	public static function fetch_study_material_query( $school_id ) {
		$query = 'SELECT sm.ID, sm.label as title, sm.description, sm.attachments, sm.created_at, u.user_login as username FROM ' . WLSM_STUDY_MATERIALS . ' as sm 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id 
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sm.added_by 
		WHERE s.ID = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_study_material_query_group_by() {
		$group_by = 'GROUP BY sm.ID';
		return $group_by;
	}

	public static function fetch_study_material_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT sm.ID) FROM ' . WLSM_STUDY_MATERIALS . ' as sm 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id 
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sm.added_by 
		WHERE s.ID = ' . absint( $school_id );
		return $query;
	}

	public static function get_study_material( $school_id, $id ) {
		global $wpdb;
		$study_material = $wpdb->get_row( $wpdb->prepare( 'SELECT sm.ID, sm.attachments FROM ' . WLSM_STUDY_MATERIALS . ' as sm 
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id 
			WHERE s.ID = %d AND sm.ID = %d', $school_id, $id ) );
		return $study_material;
	}

	public static function fetch_study_material( $school_id, $id ) {
		global $wpdb;		
		$study_material = $wpdb->get_row( $wpdb->prepare( 'SELECT sm.ID, sm.label as title, sm.description, sm.attachments FROM ' . WLSM_STUDY_MATERIALS . ' as sm 
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id 
			WHERE s.ID = %d AND sm.ID = %d', $school_id, $id ) );
		return $study_material;
	}

	public static function fetch_study_material_classes( $school_id, $study_material_id ) {
		global $wpdb;
		$classes = $wpdb->get_col( $wpdb->prepare( 'SELECT DISTINCT c.ID FROM ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . ' as cssm 
			JOIN ' . WLSM_STUDY_MATERIALS . ' as sm ON sm.ID = cssm.study_material_id 
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = cssm.class_school_id 
			JOIN ' . WLSM_CLASSES . ' as c ON cs.class_id = c.ID 
			WHERE s.ID = %d AND cssm.study_material_id = %d ORDER BY cssm.ID ASC', $school_id, $study_material_id ) );
		return $classes;
	}

	public static function get_homeworks_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_HOMEWORK );
	}

	public static function fetch_homework_query( $school_id, $session_id ) {
		$query = 'SELECT hw.ID, hw.title, hw.description, hw.homework_date, c.label as class_label, u.user_login as username FROM ' . WLSM_HOMEWORK . ' as hw 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id 
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id 
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = hw.added_by 
		LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID 
		LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id 
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE s.ID = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function fetch_homework_query_group_by() {
		$group_by = 'GROUP BY hw.ID';
		return $group_by;
	}

	public static function fetch_homework_query_count( $school_id, $session_id ) {
		$query = 'SELECT COUNT(DISTINCT hw.ID) FROM ' . WLSM_HOMEWORK . ' as hw 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id 
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id 
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = hw.added_by 
		WHERE s.ID = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function get_homework( $school_id, $session_id, $id ) {
		global $wpdb;		
		$homework = $wpdb->get_row( $wpdb->prepare( 'SELECT hw.ID FROM ' . WLSM_HOMEWORK . ' as hw 
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id 
			WHERE s.ID = %d AND ss.ID = %d AND hw.ID = %d', $school_id, $session_id, $id ) );
		return $homework;
	}

	public static function fetch_homework( $school_id, $session_id, $id ) {
		global $wpdb;		
		$homework = $wpdb->get_row( $wpdb->prepare( 'SELECT hw.ID, hw.title, hw.description, hw.homework_date, c.ID as class_id, cs.ID as class_school_id FROM ' . WLSM_HOMEWORK . ' as hw 
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id 
			LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID 
			LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id 
			LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
			WHERE s.ID = %d AND ss.ID = %d AND hw.ID = %d', $school_id, $session_id, $id ) );
		return $homework;
	}

	public static function fetch_homework_sections( $school_id, $session_id, $homework_id ) {
		global $wpdb;
		$sections = $wpdb->get_col( $wpdb->prepare( 'SELECT DISTINCT se.ID FROM ' . WLSM_HOMEWORK_SECTION . ' as hwse 
			JOIN ' . WLSM_HOMEWORK . ' as hw ON hw.ID = hwse.homework_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id 
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id 
			WHERE s.ID = %d AND ss.ID = %d AND hwse.homework_id = %d ORDER BY hwse.ID ASC', $school_id, $session_id, $homework_id ) );
		return $sections;
	}

	public static function fetch_active_students_of_classes( $school_id, $session_id, $class_ids ) {
		global $wpdb;

		$values        = array( $school_id, $session_id );
		$place_holders = array();

		foreach ( $class_ids as $class_id ) {
			array_push( $values, $class_id );
			array_push( $place_holders, '%d' );
		}

		$students = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.ID, sr.enrollment_number, sr.name, sr.phone, sr.email, c.label as class_label, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
			LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID 
			WHERE cs.school_id = %d AND sr.session_id = %d AND sr.is_active = 1 AND c.ID IN(' . implode( ', ', $place_holders ) . ') AND tf.ID IS NULL GROUP BY sr.ID ORDER BY c.label, se.label', $values ) );
		return $students;
	}

	public static function get_timetable_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_TIMETABLE );
	}

	public static function fetch_timetable_query( $school_id ) {
		$query = 'SELECT se.ID, se.label as section_label, c.label as class_label FROM ' . WLSM_ROUTINES . ' as rt 
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE cs.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_timetable_query_group_by() {
		$group_by = 'GROUP BY se.ID';
		return $group_by;
	}

	public static function fetch_timetable_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT se.ID) FROM ' . WLSM_ROUTINES . ' as rt 
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE cs.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_section_routine_ids( $school_id, $section_id ) {
		global $wpdb;
		$routine_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT rt.ID FROM ' . WLSM_ROUTINES . ' as rt 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id 
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = rt.subject_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
			WHERE cs.school_id = %d AND se.ID = %d', $school_id, $section_id ) );
		return $routine_ids;
	}

	public static function get_section_routines_by_day( $school_id, $section_id, $day ) {
		global $wpdb;
		$routines = $wpdb->get_results( $wpdb->prepare( 'SELECT rt.ID, rt.start_time, rt.end_time, rt.day, rt.room_number, sj.label as subject_label, sj.code as subject_code, a.name as teacher_name FROM ' . WLSM_ROUTINES . ' as rt 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id 
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = rt.subject_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = rt.admin_id 
			WHERE cs.school_id = %d AND se.ID = %d AND rt.day = %d ORDER BY rt.start_time', $school_id, $section_id, $day ) );
		return $routines;
	}

	public static function get_routine( $school_id, $id ) {
		global $wpdb;
		$routine = $wpdb->get_row( $wpdb->prepare( 'SELECT rt.ID FROM ' . WLSM_ROUTINES . ' as rt 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id 
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = rt.subject_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
			WHERE cs.school_id = %d AND rt.ID = %d', $school_id, $id ) );
		return $routine;
	}

	public static function fetch_routine( $school_id, $id ) {
		global $wpdb;
		$routine = $wpdb->get_row( $wpdb->prepare( 'SELECT rt.ID, rt.start_time, rt.end_time, rt.day, rt.room_number, rt.subject_id, rt.section_id, c.ID as class_id, c.label as class_label, se.label as section_label, rt.admin_id FROM ' . WLSM_ROUTINES . ' as rt 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id 
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = rt.subject_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
			LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = rt.admin_id 
			WHERE cs.school_id = %d AND rt.ID = %d', $school_id, $id ) );
		return $routine;
	}

	public static function get_section_label_text( $label ) {
		if ( $label ) {
			return stripcslashes( $label );
		}
		return '';
	}

	public static function get_subject_label_text( $label ) {
		if ( $label ) {
			return stripcslashes( $label );
		}
		return '';
	}

	public static function get_subject_code_text( $code ) {
		if ( $code ) {
			return $code;
		}
		return '-';
	}

	public static function get_status_text( $is_active ) {
		if ( $is_active ) {
			return self::get_active_text();
		}
		return self::get_inactive_text();
	}

	public static function get_active_text() {
		return __( 'Active', 'school-management' );
	}

	public static function get_inactive_text() {
		return __( 'Inactive', 'school-management' );
	}

	public static function get_link_to_text( $link_to ) {
		if ( 'attachment' === $link_to ) {
			return self::get_attachment_text();
		} else if ( 'url' === $link_to ) {
			return self::get_url_text();
		}
		return self::get_none_text();
	}

	public static function get_none_text() {
		return __( 'None', 'school-management' );
	}

	public static function get_attachment_text() {
		return __( 'Attachment', 'school-management' );
	}

	public static function get_url_text() {
		return __( 'URL', 'school-management' );
	}

	public static function get_subject_type_text( $subject_type ) {
		if ( isset( WLSM_Helper::subject_type_list()[ $subject_type ] ) ) {
			return WLSM_Helper::subject_type_list()[ $subject_type ];
		}
		return '-';
	}

	public static function get_name_text( $name ) {
		if ( $name ) {
			return stripcslashes( $name );
		}
		return '-';
	}

	public static function get_phone_text( $phone ) {
		if ( $phone ) {
			return $phone;
		}
		return '-';
	}

	public static function get_email_text( $email ) {
		if ( $email ) {
			return $email;
		}
		return '-';
	}

	public static function get_username_text( $username ) {
		if ( $username ) {
			return $username;
		}
		return '-';
	}

	public static function get_admission_no_text( $admission_number ) {
		if ( $admission_number ) {
			return $admission_number;
		}
		return '-';
	}

	public static function get_roll_no_text( $roll_number ) {
		if ( $roll_number ) {
			return $roll_number;
		}
		return '-';
	}

	public static function get_designation_text( $designation ) {
		if ( $designation ) {
			return $designation;
		}
		return '-';
	}

	public static function get_certificate_label_text( $label ) {
		if ( $label ) {
			return $label;
		}
		return '';
	}

	public static function get_default_section_text() {
		return esc_html__( 'Default', 'school-management' );
	}
}
