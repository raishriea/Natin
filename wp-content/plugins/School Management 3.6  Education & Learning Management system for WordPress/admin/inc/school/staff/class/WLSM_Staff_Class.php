<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

class WLSM_Staff_Class {
	public static function fetch_classes() {
		$current_user = WLSM_M_Role::can( 'manage_classes' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Class::get_sections_page_url();

		$query = WLSM_M_Staff_Class::fetch_classes_query( $school_id, $session_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Class::fetch_classes_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(c.label LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'c.label', 'sections_count', 'students_count' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY c.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Class::fetch_classes_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();
		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				$sections_count = absint( $row->sections_count );
				if ( ! $sections_count ) {
					$sections_count = '<a class="text-primary wlsm-font-bold" href="' . esc_url( $page_url . "&action=sections&id=" . $row->ID ) . '">' . esc_html__( 'Add Sections', 'school-management' ) . '</a>';
				} else {
					$sections_count = '<a class="text-primary wlsm-font-bold" href="' . esc_url( $page_url . "&action=sections&id=" . $row->ID ) . '">' . $sections_count . '</a>';
				}

				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Class::get_label_text( $row->label ) ),
					$sections_count,
					absint( $row->students_count ),
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die;
	}

	public static function fetch_class_sections() {
		$current_user = WLSM_M_Role::can( 'manage_classes' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		$class_school_id = isset( $_POST['class_school'] ) ? absint( $_POST['class_school'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'class-sections-' . $class_school_id ], 'class-sections-' . $class_school_id ) ) {
			die();
		}

		$page_url = WLSM_M_Staff_Class::get_sections_page_url();

		$query = WLSM_M_Staff_Class::fetch_sections_query( $school_id, $session_id, $class_school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Class::fetch_sections_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(se.label LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'se.label', 'students_count' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY se.label ASC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Class::fetch_sections_query_count( $school_id, $class_school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();
		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				$default_section_text = '';

				if ( $row->ID !== $row->default_section_id ) {
					$default_section_text = '';
					$delete_section = '<a class="text-danger wlsm-delete-section" data-nonce="' . esc_attr( wp_create_nonce( 'delete-section-' . $row->ID ) ) . '" data-class="' . esc_attr( $row->class_id ) . '" data-section="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will remove section from the class. All student records in this section of all sessions will be moved to the default section.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>';
				} else {
					$default_section_text = ' <small class="text-secondary"> - ' . WLSM_M_STAFF_CLASS::get_default_section_text() . '</small>';
					$delete_section = '';
				}

				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->label ) ) . $default_section_text,
					absint( $row->students_count ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=sections&id=" . $row->class_id ) . '&section_id=' . $row->ID . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;' . $delete_section
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die;
	}

	public static function save_section() {
		$current_user = WLSM_M_Role::can( 'manage_classes' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		try {
			ob_start();
			global $wpdb;

			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			if ( $section_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-section-' . $section_id ], 'edit-section-' . $section_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-section'], 'add-section' ) ) {
					die();
				}
			}

			$class_id = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;

			// Checks if class exists in the school.
			$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );

			if ( ! $class_school ) {
				throw new Exception( esc_html__( 'Class not found.', 'school-management' ) );
			}

			$class_school_id = $class_school->ID;

			// Checks if section exists.
			if ( $section_id ) {
				$section = WLSM_M_Staff_Class::get_section( $school_id, $section_id, $class_school_id );

				if ( ! $section ) {
					throw new Exception( esc_html__( 'Section not found.', 'school-management' ) );
				}
			}

			$label = isset( $_POST['label'] ) ? sanitize_text_field( $_POST['label'] ) : '';

			$is_default = isset( $_POST['is_default'] ) ? (bool) ( $_POST['is_default'] ) : 0;

			// Start validation.
			$errors = array();

			if ( empty( $label ) ) {
				$errors['label'] = esc_html__( 'Please provide section label.', 'school-management' );
			}

			if ( strlen( $label ) > 191 ) {
				$errors['label'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'school-management' );
			}

			// Checks if section already exists in the class with this label.
			if ( $section_id ) {
				$section_exist = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) as count FROM ' . WLSM_SECTIONS . ' as se WHERE se.label = %s AND se.ID != %d AND se.class_school_id = %d', $label, $section_id, $class_school_id ) );
			} else {
				$section_exist = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) as count FROM ' . WLSM_SECTIONS . ' as se WHERE se.label = %s AND se.class_school_id = %d', $label, $class_school_id ) );
			}

			if ( $section_exist ) {
				$errors['label'] = esc_html__( 'Section already exists.', 'school-management' );
			}
			// End validation.

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// Data to update or insert.
				$data = array(
					'label'           => $label,
					'class_school_id' => $class_school_id,
				);

				// Checks if update or insert.
				if ( $section_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_SECTIONS, $data, array( 'ID' => $section_id ) );
					$message = esc_html__( 'Section updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_SECTIONS, $data );
					$message = esc_html__( 'Section added successfully.', 'school-management' );
					$reset   = true;

					$section_id = $wpdb->insert_id;
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				if ( $is_default ) {
					$success = $wpdb->update(
						WLSM_CLASS_SCHOOL,
						array( 'default_section_id' => $section_id, 'updated_at' => current_time( 'Y-m-d H:i:s' ) ),
						array( 'ID' => $class_school_id )
					);

					$buffer = ob_get_clean();
					if ( ! empty( $buffer ) ) {
						throw new Exception( $buffer );
					}

					if ( false === $success ) {
						throw new Exception( $wpdb->last_error );
					}
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_section() {
		$current_user = WLSM_M_Role::can( 'manage_classes' );

		if ( ! $current_user ) {
			die();
		}

		WLSM_Helper::check_demo();

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-section-' . $section_id ], 'delete-section-' . $section_id ) ) {
				die();
			}

			$class_id = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;

			// Checks if class exists in the school.
			$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );

			if ( ! $class_school ) {
				throw new Exception( esc_html__( 'Class not found.', 'school-management' ) );
			}

			$class_school_id = $class_school->ID;

			$default_section_id = $class_school->default_section_id;

			// Checks if section exists.
			$section = WLSM_M_Staff_Class::get_section( $school_id, $section_id, $class_school_id );

			if ( ! $section ) {
				throw new Exception( esc_html__( 'Section not found.', 'school-management' ) );
			}

			if ( $section->ID === $default_section_id ) {
				throw new Exception( esc_html__( 'Default section can\'t be deleted.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->update(
				WLSM_STUDENT_RECORDS,
				array( 'section_id' => $default_section_id, 'updated_at' => current_time( 'Y-m-d H:i:s' ) ),
				array( 'section_id' => $section_id )
			);

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$success = $wpdb->delete( WLSM_SECTIONS, array( 'ID' => $section_id ) );
			$message = esc_html__( 'Section deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function manage_attendance() {
		$current_user = WLSM_M_Role::can( 'manage_attendance' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		if ( ! wp_verify_nonce( $_POST[ 'nonce' ], 'manage-attendance' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$class_id   = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			$attendance_date = isset( $_POST['attendance_date'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['attendance_date'] ) ) : NULL;

			// Start validation.
			$errors = array();

			if ( empty( $class_id ) ) {
				$errors['class_id'] = esc_html__( 'Please select a class.', 'school-management' );
			} else {
				$class_school = WLSM_M_School::get_class_school( $class_id, $school_id );
				if ( ! $class_school ) {
					$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
				} else {
					$class_school_id = $class_school->ID;
					if ( ! empty( $section_id ) ) {
						$section = WLSM_M_Staff_Class::fetch_section( $school_id, $section_id, $class_school_id );
						if ( ! $section ) {
							$errors['section_id'] = esc_html__( 'Section not found.', 'school-management' );
						} else {
							$section_label = $section->label;
						}
					} else {
						$section_label = esc_html__( 'All Sections', 'school-management' );
					}
				}
			}

			if ( empty( $attendance_date ) ) {
				$errors['attendance_date'] = esc_html__( 'Please specify date.', 'school-management' );
			} else {
				$attendance_date = $attendance_date->format( 'Y-m-d' );
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

		if ( count( $errors ) < 1 ) {
			try {
				ob_start();

				if ( ! $section_id ) {
					// Get class students in current session.
					$students = WLSM_M_Staff_Class::get_class_students( $school_id, $session_id, $class_id );
				} else {
					// Get section students in current session.
					$students = WLSM_M_Staff_Class::get_section_students( $school_id, $session_id, $section_id );
				}

				if ( count( $students ) ) {
					$all_student_ids = array_map( function( $student ) {
						return $student->ID;
					}, $students );

					// Get saved attendance.
					$all_student_ids_count = count( $all_student_ids );

					$place_holders = array_fill( 0, $all_student_ids_count, '%s' );

					$all_student_ids_format = implode( ', ', $place_holders );

					$prepare = array_merge( array( $attendance_date ), $all_student_ids );

					$saved_attendance = $wpdb->get_results( $wpdb->prepare( 'SELECT student_record_id, status FROM ' . WLSM_ATTENDANCE . ' WHERE attendance_date = "%s" AND student_record_id IN (' . $all_student_ids_format . ')', $prepare ), OBJECT_K );
				?>
				<input type="hidden" name="class_id_final" value="<?php echo esc_attr( $class_id ); ?>">
				<input type="hidden" name="section_id_final" value="<?php echo esc_attr( $section_id ); ?>">
				<input type="hidden" name="attendance_date_final" value="<?php echo esc_attr( $_POST['attendance_date'] ); ?>">

				<!-- Students attendance. -->
				<div class="wlsm-form-section">
					<div class="row">
						<div class="col-md-12">
							<div class="wlsm-form-sub-heading-small wlsm-font-bold">
								<span>
								<?php
								/* translators: 1: class label, 2: section label */
								printf(
									wp_kses(
										__( 'Attendance - Class: <span class="text-secondary">%1$s</span> | Section: <span class="text-secondary">%2$s</span>', 'school-management' ),
										array( 'span' => array( 'class' => array() ) )
									),
									esc_html( WLSM_M_Class::get_label_text( $class_school->label ) ),
									esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) )
								);
								?>
								</span>
								<span class="float-right">
								<?php
								printf(
									wp_kses(
										/* translators: %s: date of attendance */
										__( 'Date: <span class="text-dark wlsm-font-bold">%s</span>', 'school-management' ),
										array( 'span' => array( 'class' => array() ) )
									),
									esc_html( WLSM_Config::get_date_text( $attendance_date ) )
								);
								?>
								</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive w-100">
								<table class="table table-bordered wlsm-students-attendance-table">
									<thead>
										<tr class="bg-primary text-white">
											<th><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Section', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Roll Number', 'school-management' ); ?></th>
											<th>
												<?php esc_html_e( 'Status', 'school-management' ); ?>&nbsp;
												<button type="button" class="btn wlsm-btn-xs btn-success mr-1 wlsm-mark-all-present">
													<i class="fas fa-check"></i>
													<?php esc_html_e( 'Mark All Present', 'school-management' ); ?>
												</button>
												<button type="button" class="btn wlsm-btn-xs btn-danger wlsm-mark-all-absent">
													<i class="fas fa-times"></i>
													<?php esc_html_e( 'Mark All Absent', 'school-management' ); ?>
												</button>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ( $students as $row ) {
											if ( isset( $saved_attendance[ $row->ID ] ) ) {
												$attendance = $saved_attendance[ $row->ID ];
												$status     = $attendance->status;
											} else {
												$status = '';
											}
										?>
										<tr>
											<td>
												<?php echo esc_html( $row->enrollment_number ); ?>
											</td>
											<td>
												<input type="hidden" name="student[<?php echo esc_attr( $row->ID ); ?>]" value="<?php echo esc_attr( $row->ID ); ?>">
												<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $row->roll_number ) ); ?>
											</td>
											<td>
												<?php foreach ( WLSM_Helper::attendance_status() as $key => $value ) { ?>
												<div class="form-check form-check-inline">
													<input <?php checked( $key, $status, true ); ?> class="form-check-input wlsm-attendance-status-input" type="radio" name="status[<?php echo esc_attr( $row->ID ); ?>]" id="wlsm_attendance_status_<?php echo esc_attr( $key ); ?>_<?php echo esc_attr( $row->ID ); ?>" value="<?php echo esc_attr( $key ); ?>">
													<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_attendance_status_<?php echo esc_attr( $key ); ?>_<?php echo esc_attr( $row->ID ); ?>">
														<?php echo esc_html( $value ); ?>
													</label>
												</div>
												<?php } ?>
											</td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-2 mb-2">
					<div class="col-md-12 text-center">
						<?php
						printf(
							wp_kses(
								/* translators: %s: date of attendance */
								__( 'Attendance Date: <span class="wlsm-font-bold">%s</span>', 'school-management' ),
								array( 'span' => array( 'class' => array() ) )
							),
							esc_html( WLSM_Config::get_date_text( $attendance_date ) )
						);
						?>
					</div>
				</div>

				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-sm btn-success" id="wlsm-take-attendance-btn" data-message-title="<?php esc_attr_e( 'Confirm!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Are you sure to save attendance status?', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Save', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save Changes', 'school-management' ); ?>
						</button>
					</div>
				</div>
				<?php
				} else {
				?>
				<div class="alert alert-warning wlsm-font-bold">
					<i class="fas fa-exclamation-triangle"></i>
					<?php esc_html_e( 'There is no student in this class or section.', 'school-management' ); ?>
				</div>
				<?php
				}
				$html = ob_get_clean();

				wp_send_json_success( array( 'html' => $html ) );

			} catch ( Exception $exception ) {
				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					$response = $buffer;
				} else {
					$response = $exception->getMessage();
				}
				wp_send_json_error( $response );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function take_attendance() {
		$current_user = WLSM_M_Role::can( 'manage_attendance' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		if ( ! wp_verify_nonce( $_POST[ 'take-attendance' ], 'take-attendance' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$class_id   = isset( $_POST['class_id_final'] ) ? absint( $_POST['class_id_final'] ) : 0;
			$section_id = isset( $_POST['section_id_final'] ) ? absint( $_POST['section_id_final'] ) : 0;

			$attendance_date = isset( $_POST['attendance_date_final'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['attendance_date_final'] ) ) : NULL;

			// Start validation.
			if ( empty( $class_id ) ) {
				throw new Exception( esc_html__( 'Please select a class.', 'school-management' ) );
			} else {
				$class_school = WLSM_M_School::get_class_school( $class_id, $school_id );
				if ( ! $class_school ) {
					throw new Exception( esc_html__( 'Class not found.', 'school-management' ) );
				} else {
					$class_school_id = $class_school->ID;
					if ( ! empty( $section_id ) ) {
						$section = WLSM_M_Staff_Class::get_section( $school_id, $section_id, $class_school_id );
						if ( ! $class_school ) {
							throw new Exception( esc_html__( 'Section not found.', 'school-management' ) );
						}
					}
				}
			}

			if ( empty( $attendance_date ) ) {
				throw new Exception( esc_html__( 'Please specify date.', 'school-management' ) );
			} else {
				$attendance_date = $attendance_date->format( 'Y-m-d' );
			}

			if ( ! $section_id ) {
				// Get class students in current session.
				$students = WLSM_M_Staff_Class::get_class_students( $school_id, $session_id, $class_id );
			} else {
				// Get section students in current session.
				$students = WLSM_M_Staff_Class::get_section_students( $school_id, $session_id, $section_id );
			}

			$all_student_ids = array_map( function( $student ) {
				return $student->ID;
			}, $students );

			$student_ids = ( isset( $_POST['student'] ) && is_array( $_POST['student'] ) ) ? $_POST['student'] : array();
			$status_ids  = ( isset( $_POST['status'] ) && is_array( $_POST['status'] ) ) ? $_POST['status'] : array();

			$allowed_status_ids = array_keys( WLSM_Helper::attendance_status() );

			$unique_status_ids = array_unique( $status_ids );

			if ( array_intersect( $unique_status_ids, $allowed_status_ids ) != $unique_status_ids ) {
				wp_send_json_error( esc_html__( 'Please select valid attendance status.', 'school-management' ) );
			}

			$student_ids_keys = array_keys( $student_ids );
			$status_ids_keys  = array_keys( $status_ids );

			if ( ! count( $student_ids ) ) {
				wp_send_json_error( esc_html__( 'No students found.', 'school-management' ) );
			} else if ( ( array_intersect( $student_ids, $all_student_ids ) != $student_ids ) || ( $student_ids_keys != array_values( $student_ids ) ) ) {
				wp_send_json_error( esc_html__( 'Please select valid students.', 'school-management' ) );
			} else if ( array_intersect( $student_ids_keys, $status_ids_keys ) != $student_ids_keys ) {
				wp_send_json_error( esc_html__( 'Invalid selection of students or attendance status.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$attendance_absent_date = DateTime::createFromFormat( 'Y-m-d', $attendance_date );

			foreach ( $student_ids_keys as $student_id ) {
				if ( isset( $students[ $student_id ] ) ) {
					$student = $students[ $student_id ];
					$status  = $status_ids[ $student_id ];

					if ( ! empty( $status ) ) {
						$sql = 'INSERT INTO ' . WLSM_ATTENDANCE . ' (attendance_date, student_record_id, status) VALUES ("%s", %d, "%s") ON DUPLICATE KEY UPDATE status = "%s", updated_at = "%s"';

						$success = $wpdb->query( $wpdb->prepare( $sql, $attendance_date, $student_id, $status, $status, current_time( 'Y-m-d H:i:s' ) ) );

						$buffer = ob_get_clean();
						if ( ! empty( $buffer ) ) {
							throw new Exception( $buffer );
						}

						if ( false === $success ) {
							throw new Exception( $wpdb->last_error );
						}

						if ( 'a' === $status ) {
							// Notify for absent student.
							$data = array(
								'school_id'       => $school_id,
								'session_id'      => $session_id,
								'student_id'      => $student_id,
								'attendance_date' => $attendance_absent_date->format( WLSM_Config::date_format() )
							);

							wp_schedule_single_event( time() + 30, 'wlsm_notify_for_absent_student', $data );
						}

					} else {
						$wpdb->delete(
							WLSM_ATTENDANCE,
							array( 'attendance_date' => $attendance_date, 'student_record_id' => $student_id )
						);
						$buffer = ob_get_clean();
						if ( ! empty( $buffer ) ) {
							throw new Exception( $buffer );
						}
					}

				} else {
					throw new Exception( esc_html__( 'Please select valid students.', 'school-management' ) );
				}
			}

			$wpdb->query( 'COMMIT;' );

			$message = esc_html__( 'Attendance saved successfully.', 'school-management' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function view_attendance() {
		$current_user = WLSM_M_Role::can( 'manage_attendance' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		if ( ! wp_verify_nonce( $_POST[ 'nonce' ], 'view-attendance' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$class_id   = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			$year_month = isset( $_POST['year_month'] ) ? DateTime::createFromFormat( 'F Y', sanitize_text_field( $_POST['year_month'] ) ) : NULL;

			// Start validation.
			$errors = array();

			if ( empty( $class_id ) ) {
				$errors['class_id'] = esc_html__( 'Please select a class.', 'school-management' );
			} else {
				$class_school = WLSM_M_School::get_class_school( $class_id, $school_id );
				if ( ! $class_school ) {
					$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
				} else {
					$class_school_id = $class_school->ID;
					if ( ! empty( $section_id ) ) {
						$section = WLSM_M_Staff_Class::fetch_section( $school_id, $section_id, $class_school_id );
						if ( ! $section ) {
							$errors['section_id'] = esc_html__( 'Section not found.', 'school-management' );
						} else {
							$section_label = $section->label;
						}
					} else {
						$section_label = esc_html__( 'All Sections', 'school-management' );
					}
				}
			}

			if ( empty( $year_month ) ) {
				$errors['year_month'] = esc_html__( 'Please specify the month.', 'school-management' );
			} else {
				$month_format = $year_month->format( 'F' );
				$year_format  = $year_month->format( 'Y' );

				$month = $year_month->format( 'm' );
				$year  = $year_month->format( 'Y' );

				$number_of_days = $year_month->format( 't' );

				$start_date = new DateTime( "{$year}-{$month}-01" );
				$end_date   = new DateTime( "{$year}-{$month}-{$number_of_days}" );

				$date_range = new DatePeriod( $start_date, DateInterval::createFromDateString('1 day'), $end_date->modify( '+1 day' ) );
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

		if ( count( $errors ) < 1 ) {
			try {
				ob_start();

				if ( ! $section_id ) {
					// Get class students in current session.
					$students = WLSM_M_Staff_Class::get_class_students( $school_id, $session_id, $class_id, $year, $month );
				} else {
					// Get section students in current session.
					$students = WLSM_M_Staff_Class::get_section_students( $school_id, $session_id, $section_id );
				}

				if ( count( $students ) ) {
					$all_student_ids = array_map( function( $student ) {
						return $student->ID;
					}, $students );

					// Get saved attendance.
					$all_student_ids_count = count( $all_student_ids );

					$place_holders = array_fill( 0, $all_student_ids_count, '%s' );

					$all_student_ids_format = implode( ', ', $place_holders );

					$prepare = array_merge( array( $year, $month ), $all_student_ids );

					$saved_attendance = $wpdb->get_results( $wpdb->prepare( 'SELECT student_record_id, attendance_date, status FROM ' . WLSM_ATTENDANCE . ' WHERE YEAR(attendance_date) = %d AND MONTH(attendance_date) = %d AND student_record_id IN (' . $all_student_ids_format . ')', $prepare ) );

					require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/attendance_sheet.php';
				} else {
				?>
				<div class="alert alert-warning wlsm-font-bold">
					<i class="fas fa-exclamation-triangle"></i>
					<?php esc_html_e( 'There is no student in this class or section.', 'school-management' ); ?>
				</div>
				<?php
				}
				$html = ob_get_clean();

				wp_send_json_success( array( 'html' => $html ) );

			} catch ( Exception $exception ) {
				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					$response = $buffer;
				} else {
					$response = $exception->getMessage();
				}
				wp_send_json_error( $response );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function fetch_notices() {
		$current_user = WLSM_M_Role::can( 'manage_notices' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Class::get_notices_page_url();

		$query = WLSM_M_Staff_Class::fetch_notice_query( $school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Class::fetch_notice_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(n.title LIKE "%' . $search_value . '%") OR ' .
				'(n.link_to LIKE "%' . $search_value . '%") OR ' .
				'(u.user_login LIKE "%' . $search_value . '%")';

				$search_value_lowercase = strtolower( $search_value );
				if ( preg_match( '/^none$/', $search_value_lowercase ) ) {
					$link_to = '';
				}

				if ( isset( $link_to ) ) {
					$condition .= ' OR (n.link_to = "' . $link_to . '")';
				}

				if ( preg_match( '/^inac(|t|ti|tiv|tive)$/', $search_value_lowercase ) ) {
					$is_active = 0;
				} else if ( preg_match( '/^acti(|v|ve)$/', $search_value_lowercase ) ) {
					$is_active = 1;
				}
				if ( isset( $is_active ) ) {
					$condition .= ' OR (n.is_active = ' . $is_active . ')';
				}

				$created_at = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $created_at ) {
					$format_created_at = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $created_at ) {
							$created_at        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_created_at = 'Y-m';
						}
					} else if ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $created_at ) {
							$created_at        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_created_at = 'Y-m';
						}
					} else if ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $created_at ) {
							$created_at        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_created_at = 'Y-m';
						}
					} else if ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $created_at ) {
							$created_at        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_created_at = 'Y-m';
						}
					}

					if ( ! $created_at ) {
						$created_at        = DateTime::createFromFormat( 'Y', $search_value );
						$format_created_at = 'Y';
					}
				}

				if ( $created_at && isset( $format_created_at ) ) {
					$created_at = $created_at->format( $format_created_at );
					$created_at = ' OR (n.created_at LIKE "%' . $created_at . '%")';

					$condition .= $created_at;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'n.title', 'n.link_to', 'n.is_active', 'n.created_at', 'u.user_login' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY n.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Class::fetch_notice_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				$link_to = $row->link_to;

				if ( 'url' === $link_to ) {
					$link_to = '<a target="_blank" href="' . esc_url( $row->url ) . '">' . esc_html( WLSM_M_Staff_Class::get_link_to_text( $link_to ) ) . '</a>';
				} else if ( 'attachment' === $link_to ) {
					$link_to = esc_html( WLSM_M_Staff_Class::get_link_to_text( $link_to ) );
					if ( ! empty ( $row->attachment ) ) {
						$attachment = $row->attachment;
						$link_to .= '<br><a target="_blank" href="' . esc_url( wp_get_attachment_url( $attachment ) ) . '"><i class="fas fa-search"></i></a>';
					}
				} else {
					$link_to = esc_html( WLSM_M_Staff_Class::get_none_text() );
				}

				// Table columns.
				$data[] = array(
					esc_html( WLSM_Config::limit_string( WLSM_M_Staff_Class::get_name_text( $row->title ) ) ),
					$link_to,
					esc_html( WLSM_M_Staff_Class::get_status_text( $row->is_active ) ),
					esc_html( WLSM_Config::get_date_text( $row->created_at ) ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->username ) ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-notice" data-nonce="' . esc_attr( wp_create_nonce( 'delete-notice-' . $row->ID ) ) . '" data-notice="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the notice.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function save_notice() {
		$current_user = WLSM_M_Role::can( 'manage_notices' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$notice_id = isset( $_POST['notice_id'] ) ? absint( $_POST['notice_id'] ) : 0;

			if ( $notice_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-notice-' . $notice_id ], 'edit-notice-' . $notice_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-notice'], 'add-notice' ) ) {
					die();
				}
			}

			// Checks if notice exists.
			if ( $notice_id ) {
				$notice = WLSM_M_Staff_Class::get_notice( $school_id, $notice_id );

				if ( ! $notice ) {
					throw new Exception( esc_html__( 'Notice not found.', 'school-management' ) );
				}
			}

			$title      = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
			$link_to    = isset( $_POST['link_to'] ) ? sanitize_text_field( $_POST['link_to'] ) : '';
			$attachment = ( isset( $_FILES['attachment'] ) && is_array( $_FILES['attachment'] ) ) ? $_FILES['attachment'] : NULL;
			$url        = isset( $_POST['url'] ) ? esc_url_raw( $_POST['url'] ) : '';
			$classes    = ( isset( $_POST['classes'] ) && is_array( $_POST['classes'] ) ) ? $_POST['classes'] : array();
			$is_active  = isset( $_POST['is_active'] ) ? (bool) $_POST['is_active'] : 1;

			// Start validation.
			$errors = array();

			if ( empty( $title ) ) {
				$errors['title'] = esc_html__( 'Please provide notice text.', 'school-management' );
			}

			if ( ! in_array( $link_to, array( 'url', 'attachment' ) ) ) {
				$link_to = '';
			}

			if ( 'attachment' === $link_to ) {
				if ( isset( $attachment['tmp_name'] ) && ! empty( $attachment['tmp_name'] ) ) {
					if ( ! WLSM_Helper::is_valid_file( $attachment, 'attachment' ) ) {
						$errors['attachment'] = esc_html__( 'This file type is not allowed.', 'school-management' );
					}
				}
			}

			$class_schools = array();

			if ( count( $classes ) ) {
				foreach ( $classes as $class_id ) {
					$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );
					if ( ! $class_school ) {
						$errors['classes[]'] = esc_html__( 'Class not found.', 'school-management' );
						wp_send_json_error( $errors );
					} else {
						$class_school_id = $class_school->ID;
						array_push( $class_schools, $class_school_id );
					}
				}

				$class_schools = array_unique( $class_schools );
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

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				if ( $notice_id ) {
					$message = esc_html__( 'Notice updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Notice added successfully.', 'school-management' );
					$reset   = true;
				}

				// Notice data.
				$data = array(
					'title'     => $title,
					'link_to'   => $link_to,
					'url'       => $url,
					'is_active' => $is_active,
					'added_by'  => get_current_user_id(),
				);

				if ( ! empty( $attachment ) ) {
					$attachment = media_handle_upload( 'attachment', 0 );
					if ( is_wp_error( $attachment ) ) {
						throw new Exception( $attachment->get_error_message() );
					}
					$data['attachment'] = $attachment;

					if ( $notice_id && $notice->attachment ) {
						$attachment_id_to_delete = $notice->attachment;
					}
				}

				if ( $notice_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_NOTICES, $data, array( 'ID' => $notice_id, 'school_id' => $school_id ) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$data['school_id'] = $school_id;

					$success = $wpdb->insert( WLSM_NOTICES, $data );

					$notice_id = $wpdb->insert_id;
				}

				if ( $notice_id ) {
					if ( count( $class_schools ) > 0 ) {
						$values                      = array();
						$place_holders               = array();
						$place_holders_class_schools = array();
						foreach ( $class_schools as $class_school_id ) {
							array_push( $values, $class_school_id, $notice_id );
							array_push( $place_holders, '(%d, %d)' );
							array_push( $place_holders_class_schools, '%d' );
						}

						// Insert class_school_notice records.
						$sql     = 'INSERT IGNORE INTO ' . WLSM_CLASS_SCHOOL_NOTICE . '(class_school_id, notice_id) VALUES ';
						$sql     .= implode( ', ', $place_holders );
						$success = $wpdb->query( $wpdb->prepare( "$sql ", $values ) );

						// Delete class_school_notice records not in array.
						$sql     = 'DELETE FROM ' . WLSM_CLASS_SCHOOL_NOTICE . ' WHERE notice_id = %d AND class_school_id NOT IN (' . implode( ', ', $place_holders_class_schools ) . ')';
						array_unshift( $class_schools , $notice_id );
						$success = $wpdb->query( $wpdb->prepare( "$sql ", $class_schools ) );
					} else {
						// Delete class_school_notice records for notice.
						$success = $wpdb->delete( WLSM_CLASS_SCHOOL_NOTICE, array( 'notice_id' => $notice_id ) );
					}
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				if ( isset( $attachment_id_to_delete ) ) {
					wp_delete_attachment( $attachment_id_to_delete, true );
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_notice() {
		$current_user = WLSM_M_Role::can( 'manage_notices' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$notice_id = isset( $_POST['notice_id'] ) ? absint( $_POST['notice_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-notice-' . $notice_id ], 'delete-notice-' . $notice_id ) ) {
				die();
			}

			// Checks if notice exists.
			$notice = WLSM_M_Staff_Class::get_notice( $school_id, $notice_id );

			if ( ! $notice ) {
				throw new Exception( esc_html__( 'Notice not found.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_NOTICES, array( 'ID' => $notice_id ) );
			$message = esc_html__( 'Notice deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			if ( $notice->attachment ) {
				$attachment_id_to_delete = $notice->attachment;
				wp_delete_attachment( $attachment_id_to_delete, true );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function fetch_subjects() {
		$current_user = WLSM_M_Role::can( 'manage_subjects' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Class::get_subjects_page_url();

		$query = WLSM_M_Staff_Class::fetch_subject_query( $school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Class::fetch_subject_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(sj.label LIKE "%' . $search_value . '%") OR ' .
				'(sj.code LIKE "%' . $search_value . '%") OR ' .
				'(sj.type LIKE "%' . $search_value . '%") OR ' .
				'(c.label LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'sj.label', 'sj.code', 'sj.type', 'c.label', 'admins_count' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY sj.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Class::fetch_subject_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				$admins_count = absint( $row->admins_count );
				if ( ! $admins_count ) {
					$admins_count = '<a class="text-primary wlsm-font-bold" href="' . esc_url( $page_url . "&action=teachers&id=" . $row->ID ) . '">' . esc_html__( 'Assign Teachers', 'school-management' ) . '</a>';
				} else {
					$admins_count = '<a class="text-primary wlsm-font-bold" href="' . esc_url( $page_url . "&action=teachers&id=" . $row->ID ) . '">' . $admins_count . '</a>';
				}

				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Staff_Class::get_subject_label_text( $row->subject_name ) ),
					esc_html( WLSM_M_Staff_Class::get_subject_code_text( $row->code ) ),
					esc_html( WLSM_M_Staff_Class::get_subject_type_text( $row->type ) ),
					esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
					$admins_count,
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-subject" data-nonce="' . esc_attr( wp_create_nonce( 'delete-subject-' . $row->ID ) ) . '" data-subject="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the subject.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function save_subject() {
		$current_user = WLSM_M_Role::can( 'manage_subjects' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$subject_id = isset( $_POST['subject_id'] ) ? absint( $_POST['subject_id'] ) : 0;

			if ( $subject_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-subject-' . $subject_id ], 'edit-subject-' . $subject_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-subject'], 'add-subject' ) ) {
					die();
				}
			}

			// Checks if subject exists.
			if ( $subject_id ) {
				$subject = WLSM_M_Staff_Class::get_subject( $school_id, $subject_id );

				if ( ! $subject ) {
					throw new Exception( esc_html__( 'Subject not found.', 'school-management' ) );
				}
			}

			$label    = isset( $_POST['label'] ) ? sanitize_text_field( $_POST['label'] ) : '';
			$code     = isset( $_POST['code'] ) ? sanitize_text_field( $_POST['code'] ) : '';
			$type     = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
			$class_id = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;

			// Start validation.
			$errors = array();

			if ( empty( $label ) ) {
				$errors['label'] = esc_html__( 'Please provide subject name.', 'school-management' );
			} else {
				if ( strlen( $label ) > 100 ) {
					$errors['label'] = esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' );
				}
			}

			if ( strlen( $code ) > 40 ) {
				$errors['code'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}

			if ( ! in_array( $type, array_keys( WLSM_Helper::subject_type_list() ) ) ) {
				$errors['type'] = esc_html__( 'Please select subject type.', 'school-management' );
			}

			if ( empty( $class_id ) ) {
				$errors['class_id'] = esc_html__( 'Please select a class.', 'school-management' );
			} else {
				$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );
				if ( ! $class_school ) {
					$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
				} else {
					$class_school_id = $class_school->ID;
				}
			}

			if ( count( $errors ) ) {
				wp_send_json_error( $errors );
			}

			if ( isset( $class_school_id ) ) {
				// Checks if subject name already exists for this class.
				if ( $subject_id ) {
					$subject_exists = $wpdb->get_row( $wpdb->prepare( 'SELECT sj.ID FROM ' . WLSM_SUBJECTS . ' as sj WHERE sj.class_school_id = %d AND sj.ID != %d AND sj.label = "%s"', $class_school_id, $subject_id, $label ) );
				} else {
					$subject_exists = $wpdb->get_row( $wpdb->prepare( 'SELECT sj.ID FROM ' . WLSM_SUBJECTS . ' as sj WHERE sj.class_school_id = %d AND sj.label = "%s"', $class_school_id, $label ) );
				}

				if ( $subject_exists ) {
					$errors['label'] = esc_html__( 'Subject name already exists.', 'school-management' );
				}

				if ( ! empty( $code ) ) {
					// Checks if subject code already exists for this class.
					if ( $subject_id ) {
						$subject_exists = $wpdb->get_row( $wpdb->prepare( 'SELECT sj.ID FROM ' . WLSM_SUBJECTS . ' as sj WHERE sj.class_school_id = %d AND sj.ID != %d AND sj.code = "%s"', $class_school_id, $subject_id, $code ) );
					} else {
						$subject_exists = $wpdb->get_row( $wpdb->prepare( 'SELECT sj.ID FROM ' . WLSM_SUBJECTS . ' as sj WHERE sj.class_school_id = %d AND sj.code = "%s"', $class_school_id, $code ) );
					}

					if ( $subject_exists ) {
						$errors['code'] = esc_html__( 'Subject code already exists.', 'school-management' );
					}
				}
			} else {
				$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
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

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				if ( $subject_id ) {
					$message = esc_html__( 'Subject updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Subject added successfully.', 'school-management' );
					$reset   = true;
				}

				// Subject data.
				$data = array(
					'label'           => $label,
					'code'            => $code,
					'type'            => $type,
					'class_school_id' => $class_school_id,
				);

				if ( $subject_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_SUBJECTS, $data, array( 'ID' => $subject_id ) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_SUBJECTS, $data );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_subject() {
		$current_user = WLSM_M_Role::can( 'manage_subjects' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$subject_id = isset( $_POST['subject_id'] ) ? absint( $_POST['subject_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-subject-' . $subject_id ], 'delete-subject-' . $subject_id ) ) {
				die();
			}

			// Checks if subject exists.
			$subject = WLSM_M_Staff_Class::get_subject( $school_id, $subject_id );

			if ( ! $subject ) {
				throw new Exception( esc_html__( 'Subject not found.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_SUBJECTS, array( 'ID' => $subject_id ) );
			$message = esc_html__( 'Subject deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function fetch_subject_admins() {
		$current_user = WLSM_M_Role::can( 'manage_subjects' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$subject_id = isset( $_POST['subject'] ) ? absint( $_POST['subject'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'subject-admins-' . $subject_id ], 'subject-admins-' . $subject_id ) ) {
			die();
		}

		$page_url = WLSM_M_Staff_Class::get_subjects_page_url();

		$query = WLSM_M_Staff_Class::fetch_subject_admins_query( $school_id, $subject_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' GROUP BY a.ID';

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(a.name LIKE "%' . $search_value . '%") OR ' .
				'(a.phone LIKE "%' . $search_value . '%") OR ' .
				'(u.user_login LIKE "%' . $search_value . '%") OR ' .
				'(a.is_active LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'a.name', 'a.phone', 'u.user_login', 'a.is_active' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY a.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Class::fetch_subject_admins_query_count( $school_id, $subject_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();
		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ),
					esc_html( WLSM_M_Staff_Class::get_phone_text( $row->phone ) ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->username ) ),
					esc_html( WLSM_M_Staff_Class::get_status_text( $row->is_active ) ),
					'<a class="text-danger wlsm-delete-subject-admin" data-nonce="' . esc_attr( wp_create_nonce( 'delete-subject-admin-' . $row->ID ) ) . '" data-subject="' . esc_attr( $subject_id ) . '" data-admin="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will remove this teacher from the subject.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die;
	}

	public static function delete_subject_admin() {
		$current_user = WLSM_M_Role::can( 'manage_subjects' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$subject_id = isset( $_POST['subject_id'] ) ? absint( $_POST['subject_id'] ) : 0;
			$admin_id   = isset( $_POST['admin_id'] ) ? absint( $_POST['admin_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-subject-admin-' . $admin_id ], 'delete-subject-admin-' . $admin_id ) ) {
				die();
			}

			// Checks if subject exists.
			$subject = WLSM_M_Staff_Class::get_subject( $school_id, $subject_id );

			if ( ! $subject ) {
				throw new Exception( esc_html__( 'Subject not found.', 'school-management' ) );
			}

			// Checks if admin exists in the subject
			$admin_subject = WLSM_M_Staff_Class::get_admin_subject( $school_id, $subject_id, $admin_id );

			if ( ! $admin_subject ) {
				throw new Exception( esc_html__( 'Teacher is not assigned to this subject.', 'school-management' ) );
			}

			$admin_subject_id = $admin_subject->ID;

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_ADMIN_SUBJECT, array( 'ID' => $admin_subject_id ) );
			$message = esc_html__( 'Teacher removed from the subject successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function get_keyword_admins() {
		$current_user = WLSM_M_Role::can( 'manage_subjects' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		$keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';

		$admins = WLSM_M_Staff_Class::get_keyword_active_admins( $school_id, $keyword );

		$admins = array_map( function( $admin ) {
			$admin->label = esc_html( stripcslashes( $admin->label ) );

			if ( $admin->phone ) {
				$admin->label .= ' (' . esc_html( $admin->phone ) . ')';
			}
			unset( $admin->phone );

			if ( $admin->username ) {
				$admin->label .= ' (' . esc_html( $admin->username ) . ')';
			}

			return $admin;
		}, $admins );

		wp_send_json_success( $admins );
	}

	public static function assign_subject_admins() {
		$current_user = WLSM_M_Role::can( 'manage_subjects' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$subject_id = isset( $_POST['subject_id'] ) ? absint( $_POST['subject_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'assign-admins-' . $subject_id ], 'assign-admins-' . $subject_id ) ) {
				die();
			}

			$admins = ( isset( $_POST['admins'] ) && is_array( $_POST['admins'] ) ) ? $_POST['admins'] : array();

			// Checks if subject exists.
			$subject = WLSM_M_Staff_Class::get_subject( $school_id, $subject_id );

			if ( ! $subject ) {
				throw new Exception( esc_html__( 'Subject not found.', 'school-management' ) );
			}

			// Start validation.
			$errors = array();

			if ( ! count( $admins ) ) {
				$errors['keyword'] = esc_html__( 'Please select atleast one teacher to assign.', 'school-management' );
			} else {
				$admins = WLSM_M_Staff_Class::get_active_admins_ids_in_school( $school_id, $admins );
			}

			// End validation.

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				$values              = array();
				$place_holders       = array();

				foreach ( $admins as $admin_id ) {
					array_push( $values, $admin_id, $subject_id );
					array_push( $place_holders, '(%d, %d)' );
				}

				// Insert admin_subject records.
				$sql     = 'INSERT IGNORE INTO ' . WLSM_ADMIN_SUBJECT . ' (admin_id, subject_id) VALUES ';
				$sql     .= implode( ', ', $place_holders );
				$success = $wpdb->query( $wpdb->prepare( "$sql ", $values ) );

				$message = esc_html__( 'Teachers assigned successfully.', 'school-management' );

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function fetch_timetable() {
		$current_user = WLSM_M_Role::can( 'manage_timetable' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Class::get_timetable_page_url();

		$query = WLSM_M_Staff_Class::fetch_timetable_query( $school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Class::fetch_timetable_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(c.label LIKE "%' . $search_value . '%") OR ' .
				'(se.label LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'c.label', 'se.label' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY c.label, se.label';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Class::fetch_timetable_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
					esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=timetable&id=" . $row->ID ) . '"><span class="dashicons dashicons-search"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-timetable" data-nonce="' . esc_attr( wp_create_nonce( 'delete-timetable-' . $row->ID ) ) . '" data-timetable="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the timetable.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function delete_timetable() {
		$current_user = WLSM_M_Role::can( 'manage_timetable' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-timetable-' . $section_id ], 'delete-timetable-' . $section_id ) ) {
				die();
			}

			// Checks if class routines exists for section.
			$routine_ids = WLSM_M_Staff_Class::get_section_routine_ids( $school_id, $section_id );

			if ( ! count( $routine_ids ) ) {
				throw new Exception( esc_html__( 'Timetable not found.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$place_holders = array();

			foreach ( $routine_ids as $routine_id ) {
				array_push( $place_holders, '%d' );
			}

			$sql = 'DELETE FROM ' . WLSM_ROUTINES . ' WHERE ID IN(' . implode( ', ', $place_holders ) . ')';

			$success = $wpdb->query( $wpdb->prepare( $sql, $routine_ids ) );
			$message = esc_html__( 'Timetable deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function save_routine() {
		$current_user = WLSM_M_Role::can( 'manage_timetable' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$routine_id = isset( $_POST['routine_id'] ) ? absint( $_POST['routine_id'] ) : 0;

			if ( $routine_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-routine-' . $routine_id ], 'edit-routine-' . $routine_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-routine'], 'add-routine' ) ) {
					die();
				}
			}

			$class_id   = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;
			$subject_id = isset( $_POST['subject_id'] ) ? absint( $_POST['subject_id'] ) : 0;
			$admin_id   = isset( $_POST['admin_id'] ) ? absint( $_POST['admin_id'] ) : 0;

			$start_time = isset( $_POST['start_time'] ) ? DateTime::createFromFormat( WLSM_Config::get_default_time_format(), sanitize_text_field( $_POST['start_time'] ) ) : 0;
			$end_time   = isset( $_POST['end_time'] ) ? DateTime::createFromFormat( WLSM_Config::get_default_time_format(), sanitize_text_field( $_POST['end_time'] ) ) : 0;

			$day = isset( $_POST['day'] ) ? absint( $_POST['day'] ) : 0;

			$room_number = isset( $_POST['room_number'] ) ? sanitize_text_field( $_POST['room_number'] ) : '';

			// Checks if routine exists.
			if ( $routine_id ) {
				$routine = WLSM_M_Staff_Class::get_routine( $school_id, $routine_id );

				if ( ! $routine ) {
					throw new Exception( esc_html__( 'Class routine not found.', 'school-management' ) );
				}
			}

			// Start validation.
			$errors = array();

			if ( empty( $class_id ) ) {
				$errors['class_id'] = esc_html__( 'Please select a class.', 'school-management' );
			} else {
				$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );
				if ( ! $class_school ) {
					$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
					wp_send_json_error( $errors );
				} else {
					$class_school_id = $class_school->ID;
				}

				if ( empty( $section_id ) ) {
					$errors['section_id'] = esc_html__( 'Please select section.', 'school-management' );
					wp_send_json_error( $errors );
				} else {
					// Checks if section exists.
					$section = WLSM_M_Staff_Class::get_section( $school_id, $section_id, $class_school_id );
					if ( ! $section ) {
						$errors['section_id'] = esc_html__( 'Section not found.', 'school-management' );
						wp_send_json_error( $errors );
					}
				}
			}

			if (  empty( $subject_id ) ) {
				$errors['subject_id'] = esc_html__( 'Please select a subject.', 'school-management' );
			} else {
				// Check if subject belongs to a class.
				$subject = WLSM_M_Staff_Class::get_class_subject( $school_id, $class_id, $subject_id );
				if ( ! $subject ) {
					$errors['subject_id'] = esc_html__( 'Subject not found.', 'school-management' );
				}
			}

			$admin_subject = NULL;
			if ( $admin_id ) {
				// Check if teacher belongs to subject.
				$admin_subject = WLSM_M_Staff_Class::get_admin_subject( $school_id, $subject_id, $admin_id );

				if ( ! $admin_subject ) {
					$errors['admin_id'] = esc_html__( 'Teacher not found.', 'school-management' );
				}
			}

			if ( ! $admin_subject ) {
				$admin_id = NULL;
			}

			if ( empty( $start_time ) ) {
				$errors['start_time'] = esc_html__( 'Please specify start time.', 'school-management' );
			} else {
				$start_time = $start_time->format( 'H:i:s' );
			}

			if ( empty( $end_time ) ) {
				$errors['end_time'] = esc_html__( 'Please specify end time.', 'school-management' );
			} else {
				$end_time = $end_time->format( 'H:i:s' );
			}

			if ( ! in_array( $day, array_keys( WLSM_Helper::days_list() ) ) ) {
				$errors['day'] = esc_html__( 'Please select a day.', 'school-management' );
			}

			if ( ! empty( $room_number ) && ( strlen( $room_number ) > 40 ) ) {
				$errors['end_time'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
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

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				if ( $routine_id ) {
					$message = esc_html__( 'Class routine updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Class routine added successfully.', 'school-management' );
					$reset   = true;
				}

				// Routine data.
				$data = array(
					'start_time'  => $start_time,
					'end_time'    => $end_time,
					'day'         => $day,
					'room_number' => $room_number,
					'subject_id'  => $subject_id,
					'admin_id'    => $admin_id,
					'section_id'  => $section_id,
				);

				if ( $routine_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_ROUTINES, $data, array( 'ID' => $routine_id ) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_ROUTINES, $data );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_routine() {
		$current_user = WLSM_M_Role::can( 'manage_timetable' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$routine_id = isset( $_POST['routine_id'] ) ? absint( $_POST['routine_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-routine-' . $routine_id ], 'delete-routine-' . $routine_id ) ) {
				die();
			}

			// Checks if routine exists.
			$routine = WLSM_M_Staff_Class::get_routine( $school_id, $routine_id );

			if ( ! $routine ) {
				throw new Exception( esc_html__( 'Routine not found.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_ROUTINES, array( 'ID' => $routine_id ) );
			$message = esc_html__( 'Routine deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function fetch_study_materials() {
		$current_user = WLSM_M_Role::can( 'manage_study_materials' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Class::get_study_materials_page_url();

		$query = WLSM_M_Staff_Class::fetch_study_material_query( $school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Class::fetch_study_material_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(sm.label LIKE "%' . $search_value . '%") OR ' .
				'(sm.description LIKE "%' . $search_value . '%") OR ' .
				'(u.user_login LIKE "%' . $search_value . '%")';

				$created_at = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $created_at ) {
					$format_created_at = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $created_at ) {
							$created_at        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_created_at = 'Y-m';
						}
					} else if ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $created_at ) {
							$created_at        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_created_at = 'Y-m';
						}
					} else if ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $created_at ) {
							$created_at        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_created_at = 'Y-m';
						}
					} else if ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $created_at ) {
							$created_at        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_created_at = 'Y-m';
						}
					}

					if ( ! $created_at ) {
						$created_at        = DateTime::createFromFormat( 'Y', $search_value );
						$format_created_at = 'Y';
					}
				}

				if ( $created_at && isset( $format_created_at ) ) {
					$created_at = $created_at->format( $format_created_at );
					$created_at = ' OR (sm.created_at LIKE "%' . $created_at . '%")';

					$condition .= $created_at;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'sm.label', 'sm.description', 'sm.created_at', 'u.user_login' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY sm.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Class::fetch_study_material_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$data[] = array(
					esc_html( stripcslashes( $row->title ) ),
					esc_html( WLSM_Config::limit_string( WLSM_M_Staff_Class::get_name_text( $row->description ) ) ),
					esc_html( WLSM_Config::get_date_text( $row->created_at ) ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->username ) ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-study-material" data-nonce="' . esc_attr( wp_create_nonce( 'delete-study-material-' . $row->ID ) ) . '" data-study-material="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the study_material.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function save_study_material() {
		$current_user = WLSM_M_Role::can( 'manage_study_materials' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$study_material_id = isset( $_POST['study_material_id'] ) ? absint( $_POST['study_material_id'] ) : 0;

			if ( $study_material_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-study-material-' . $study_material_id ], 'edit-study-material-' . $study_material_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-study-material'], 'add-study-material' ) ) {
					die();
				}
			}

			// Checks if study_material exists.
			if ( $study_material_id ) {
				$study_material = WLSM_M_Staff_Class::get_study_material( $school_id, $study_material_id );

				if ( ! $study_material ) {
					throw new Exception( esc_html__( 'Study material not found.', 'school-management' ) );
				}

				$saved_attachments = $study_material->attachments;
				if ( is_serialized( $saved_attachments ) ) {
					$saved_attachments = unserialize( $saved_attachments );
				} else {
					if ( ! is_array( $saved_attachments ) ) {
						$saved_attachments = array();
					}
				}
			}

			$label       = isset( $_POST['label'] ) ? sanitize_text_field( $_POST['label'] ) : '';
			$description = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
			$classes     = ( isset( $_POST['classes'] ) && is_array( $_POST['classes'] ) ) ? $_POST['classes'] : array();
			$attachments = ( isset( $_FILES['attachment'] ) && is_array( $_FILES['attachment'] ) ) ? $_FILES['attachment'] : array();

			$attachments_from_input = ( isset( $_POST['saved_attachment'] ) && is_array( $_POST['saved_attachment'] ) ) ? $_POST['saved_attachment'] : array();

			// Start validation.
			$errors = array();

			if ( empty( $label ) ) {
				$errors['label'] = esc_html__( 'Please specify study material title.', 'school-management' );
			} else {
				if ( strlen( $label ) > 100 ) {
					$errors['label'] = esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' );
				}
			}

			$class_schools = array();

			if ( count( $classes ) ) {
				foreach ( $classes as $class_id ) {
					$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );
					if ( ! $class_school ) {
						$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
						wp_send_json_error( $errors );
					} else {
						$class_school_id = $class_school->ID;
						array_push( $class_schools, $class_school_id );
					}
				}

				$class_schools = array_unique( $class_schools );
			}

			$files = array();
			if ( isset( $attachments['tmp_name'] ) && is_array( $attachments ) && count( $attachments ) ) {
				foreach( $attachments['tmp_name'] as $key => $attachment ) {
					array_push(
						$files,
						array(
							'name'     => $attachments['name'][ $key ],
							'tmp_name' => $attachment,
							'type'     => $attachments['type'][ $key ],
							'error'    => $attachments['error'][ $key ],
							'size'     => $attachments['size'][ $key ],
						)
					);
				}
			}

			if ( ! empty( $files ) ) {
				foreach ( $files as $file ) {
					if ( ! WLSM_Helper::is_valid_file( $file, 'attachment' ) ) {
						$errors['attachment[]'] = esc_html__( 'This file type is not allowed.', 'school-management' );
						wp_send_json_error( $errors );
					}
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

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				if ( $study_material_id ) {
					$message = esc_html__( 'Study material updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Study material added successfully.', 'school-management' );
					$reset   = true;
				}

				// Study material data.
				$data = array(
					'label'       => $label,
					'description' => $description,
					'added_by'    => get_current_user_id(),
					'school_id'   => $school_id,
				);

				$attachment_ids = array();
				if ( isset( $attachments['tmp_name'] ) && is_array( $attachments ) && count( $attachments ) ) {
					foreach ( $attachments['tmp_name'] as $key => $attachment ) {
						if ( $attachments['name'][ $key ] ) {
							$attachment = array(
								'name'     => sanitize_file_name( $attachments['name'][ $key ] ),
								'type'     => $attachments['type'][ $key ],
								'tmp_name' => $attachments['tmp_name'][ $key ],
								'error'    => $attachments['error'][ $key ],
								'size'     => $attachments['size'][ $key ]
							);
							$_FILES = array( 'attachment' => $attachment );
							$attachment_id = media_handle_upload( 'attachment', 0 );
							if ( is_wp_error( $attachment_id ) ) {
			  					throw new Exception( $attachment_id->get_error_message() );
							}
							array_push( $attachment_ids, $attachment_id );
						}
					}
				}

				if ( $study_material_id ) {
					$saved_attachments = array_intersect( $attachments_from_input, $saved_attachments );
					$attachment_ids    = array_merge( $attachment_ids, $saved_attachments );
				}

				if ( count( $attachment_ids ) ) {
					$attachments_serialized = serialize( $attachment_ids );
					$data['attachments'] = $attachments_serialized;
				} else {
					$data['attachments'] = NULL;
				}

				if ( $study_material_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_STUDY_MATERIALS, $data, array( 'ID' => $study_material_id ) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_STUDY_MATERIALS, $data );

					$study_material_id = $wpdb->insert_id;
				}

				if ( $study_material_id ) {
					if ( count( $class_schools ) > 0 ) {
						$values                      = array();
						$place_holders               = array();
						$place_holders_class_schools = array();
						foreach ( $class_schools as $class_school_id ) {
							array_push( $values, $class_school_id, $study_material_id );
							array_push( $place_holders, '(%d, %d)' );
							array_push( $place_holders_class_schools, '%d' );
						}

						// Insert class_school_study_material records.
						$sql     = 'INSERT IGNORE INTO ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . '(class_school_id, study_material_id) VALUES ';
						$sql     .= implode( ', ', $place_holders );
						$success = $wpdb->query( $wpdb->prepare( "$sql ", $values ) );

						// Delete class_school_study_material records not in array.
						$sql     = 'DELETE FROM ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . ' WHERE study_material_id = %d AND class_school_id NOT IN (' . implode( ', ', $place_holders_class_schools ) . ')';
						array_unshift( $class_schools , $study_material_id );
						$success = $wpdb->query( $wpdb->prepare( "$sql ", $class_schools ) );
					} else {
						// Delete class_school_study_material records for study_material.
						$success = $wpdb->delete( WLSM_CLASS_SCHOOL_STUDY_MATERIAL, array( 'study_material_id' => $study_material_id ) );
					}
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_study_material() {
		$current_user = WLSM_M_Role::can( 'manage_study_materials' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$study_material_id = isset( $_POST['study_material_id'] ) ? absint( $_POST['study_material_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-study-material-' . $study_material_id ], 'delete-study-material-' . $study_material_id ) ) {
				die();
			}

			// Checks if study material exists.
			$study_material = WLSM_M_Staff_Class::get_study_material( $school_id, $study_material_id );

			if ( ! $study_material ) {
				throw new Exception( esc_html__( 'Study material not found.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_STUDY_MATERIALS, array( 'ID' => $study_material_id ) );
			$message = esc_html__( 'Study material deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$attachments = $study_material->attachments;
			if ( is_serialized( $attachments ) ) {
				$attachments = unserialize( $attachments );
				foreach ( $attachments as $attachment_id_to_delete ) {
					wp_delete_attachment( $attachment_id_to_delete, true );
				}
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function fetch_homeworks() {
		$current_user = WLSM_M_Role::can( 'manage_homework' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Class::get_homeworks_page_url();

		$query = WLSM_M_Staff_Class::fetch_homework_query( $school_id, $session_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Class::fetch_homework_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(hw.title LIKE "%' . $search_value . '%") OR ' .
				'(u.user_login LIKE "%' . $search_value . '%")';

				$homework_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $homework_date ) {
					$format_homework_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $homework_date ) {
							$homework_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_homework_date = 'Y-m';
						}
					} else if ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $homework_date ) {
							$homework_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_homework_date = 'Y-m';
						}
					} else if ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $homework_date ) {
							$homework_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_homework_date = 'Y-m';
						}
					} else if ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $homework_date ) {
							$homework_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_homework_date = 'Y-m';
						}
					}

					if ( ! $homework_date ) {
						$homework_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_homework_date = 'Y';
					}
				}

				if ( $homework_date && isset( $format_homework_date ) ) {
					$homework_date = $homework_date->format( $format_homework_date );
					$homework_date = ' OR (hw.homework_date LIKE "%' . $homework_date . '%")';

					$condition .= $homework_date;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'hw.title', 'hw.description', 'c.label', 'hw.homework_date', 'u.user_login' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY hw.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Class::fetch_homework_query_count( $school_id, $session_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$data[] = array(
					esc_html( stripcslashes( $row->title ) ),
					esc_html( WLSM_Config::limit_string( WLSM_M_Staff_Class::get_name_text( $row->description ) ) ),
					esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
					esc_html( WLSM_Config::get_date_text( $row->homework_date ) ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->username ) ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-homework" data-nonce="' . esc_attr( wp_create_nonce( 'delete-homework-' . $row->ID ) ) . '" data-homework="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the homework.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function save_homework() {
		$current_user = WLSM_M_Role::can( 'manage_homework' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$homework_id = isset( $_POST['homework_id'] ) ? absint( $_POST['homework_id'] ) : 0;

			if ( $homework_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-homework-' . $homework_id ], 'edit-homework-' . $homework_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-homework'], 'add-homework' ) ) {
					die();
				}
			}

			// Checks if homework exists.
			if ( $homework_id ) {
				$homework = WLSM_M_Staff_Class::get_homework( $school_id, $session_id, $homework_id );

				if ( ! $homework ) {
					throw new Exception( esc_html__( 'Homework not found.', 'school-management' ) );
				}
			}

			$title         = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
			$description   = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
			$homework_date = isset( $_POST['homework_date'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['homework_date'] ) ) : NULL;
			$class_id      = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$sections      = ( isset( $_POST['sections'] ) && is_array( $_POST['sections'] ) ) ? $_POST['sections'] : array();

			$sms_to_students = isset( $_POST['sms_to_students'] ) ? (bool) $_POST['sms_to_students'] : 0;
			$sms_to_parents  = isset( $_POST['sms_to_parents'] ) ? (bool) $_POST['sms_to_parents'] : 0;

			// Start validation.
			$errors = array();

			if ( empty( $title ) ) {
				$errors['title'] = esc_html__( 'Please specify homework title.', 'school-management' );
			} else {
				if ( strlen( $title ) > 255 ) {
					$errors['title'] = esc_html__( 'Maximum length cannot exceed 255 characters.', 'school-management' );
				}
			}

			if ( ! $class_id ) {
				$errors['class_id'] = esc_html__( 'Please select a class.', 'school-management' );
			} else {
				$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );
				if ( ! $class_school ) {
					$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
					wp_send_json_error( $errors );
				}

				$class_school_id = $class_school->ID;
			}

			if ( count( $sections ) ) {
				foreach ( $sections as $section_id ) {
					$section_exists = WLSM_M_Staff_Class::get_section( $school_id, $section_id, $class_school_id );
					if ( ! $section_exists ) {
						$errors['sections[]'] = esc_html__( 'Please select valid class sections.', 'school-management' );
						wp_send_json_error( $errors );
					}
				}
			} else {
				$errors['sections[]'] = esc_html__( 'Please select class sections.', 'school-management' );
			}

			if ( empty( $homework_date ) ) {
				$errors['homework_date'] = esc_html__( 'Please specify homework date.', 'school-management' );
			} else {
				$homework_date = $homework_date->format( 'Y-m-d' );
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

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// Get students of sections.
				$student_ids = array();
				if ( count( $sections ) > 0 && ( $sms_to_students || $sms_to_parents ) ) {
					$student_ids = WLSM_M_Staff_General::fetch_sections_student_ids( $session_id, $sections );
				}

				if ( $homework_id ) {
					$message = esc_html__( 'Homework updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Homework assigned successfully.', 'school-management' );
					$reset   = true;
				}

				// Homework data.
				$data = array(
					'title'         => $title,
					'description'   => $description,
					'homework_date' => $homework_date,
					'added_by'      => get_current_user_id(),
					'session_id'    => $session_id,
					'school_id'     => $school_id,
				);

				if ( $homework_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_HOMEWORK, $data, array( 'ID' => $homework_id ) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_HOMEWORK, $data );

					$homework_id = $wpdb->insert_id;
				}

				if ( $homework_id ) {
					if ( count( $sections ) > 0 ) {
						$values                 = array();
						$place_holders          = array();
						$place_holders_sections = array();
						foreach ( $sections as $section_id ) {
							array_push( $values, $section_id, $homework_id );
							array_push( $place_holders, '(%d, %d)' );
							array_push( $place_holders_sections, '%d' );
						}

						// Insert homework_section records.
						$sql     = 'INSERT IGNORE INTO ' . WLSM_HOMEWORK_SECTION . '(section_id, homework_id) VALUES ';
						$sql     .= implode( ', ', $place_holders );
						$success = $wpdb->query( $wpdb->prepare( "$sql ", $values ) );

						// Delete homework_section records not in array.
						$sql     = 'DELETE FROM ' . WLSM_HOMEWORK_SECTION . ' WHERE homework_id = %d AND section_id NOT IN (' . implode( ', ', $place_holders_sections ) . ')';
						array_unshift( $sections , $homework_id );
						$success = $wpdb->query( $wpdb->prepare( "$sql ", $sections ) );
					} else {
						// Delete homework_section records for homework.
						$success = $wpdb->delete( WLSM_HOMEWORK_SECTION, array( 'homework_id' => $homework_id ) );
					}
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				foreach ( $student_ids as $student_id ) {
					// Notify for homework message.
					$data = array(
						'school_id'  => $school_id,
						'student_id' => $student_id,
						'sms'        => array(
							'message'    => $description,
							'to_student' => $sms_to_students,
							'to_parent'  => $sms_to_parents
						)
					);

					wp_schedule_single_event( time() + 30, 'wlsm_notify_for_homework_message', $data );
				}

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_homework() {
		$current_user = WLSM_M_Role::can( 'manage_homework' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$homework_id = isset( $_POST['homework_id'] ) ? absint( $_POST['homework_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-homework-' . $homework_id ], 'delete-homework-' . $homework_id ) ) {
				die();
			}

			// Checks if homework exists.
			$homework = WLSM_M_Staff_Class::get_homework( $school_id, $session_id, $homework_id );

			if ( ! $homework ) {
				throw new Exception( esc_html__( 'Homework not found.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_HOMEWORK, array( 'ID' => $homework_id ) );
			$message = esc_html__( 'Homework deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}
}
