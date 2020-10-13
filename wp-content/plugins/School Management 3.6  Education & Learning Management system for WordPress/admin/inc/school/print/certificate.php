<?php
defined( 'ABSPATH' ) || die();

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}

$css = <<<EOT
#wlsm-print-certificate {
	font-size: 16px;
	color: #000;
}
.wlsm-certificate-fields {
	height: 31cm;
	overflow-y: auto;
}
.wlsm-print-certificate-container {
	box-sizing: border-box;
	position: relative;
	width: 21cm;
	height: 29.7cm;
}
.wlsm-certificate-image {
	width: 100%;
	height: 100%;
}
EOT;

foreach ( $fields as $field_key => $field_value ) {
	$css .= '.ctf-data-' . esc_attr( $field_key ) . ' { position: absolute; ';

	foreach ( $field_value['props'] as $key => $prop ) {
		$css .= $key . ': ' . $prop['value'] . $prop['unit'] . ';';
	}
	$css .= ' }';

	if ( $field_value['enable'] ) {
		$css .= '.ctf-data-' . esc_attr( $field_key ) . '{ visibility: visible; }';
	} else {
		$css .= '.ctf-data-' . esc_attr( $field_key ) . '{ visibility: hidden; }';
	}
}

if ( isset( $from_ajax ) ) {
?>
<style>
	<?php echo esc_attr( $css ); ?>
</style>
<?php
} else {
	wp_register_style( 'wlsm-certificate', false );
	wp_enqueue_style( 'wlsm-certificate' );
	wp_add_inline_style( 'wlsm-certificate', $css );
}
?>

<!-- Print certificate. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" data-css="<?php echo esc_attr( $css ); ?>" class="<?php echo esc_attr( $print_button_classes ); ?>" id="wlsm-print-certificate-btn" data-title="<?php esc_attr_e( 'Print Certificate', 'school-management' ); ?>" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-certificate.css' ); ?>"]'><?php esc_html_e( 'Print Certificate', 'school-management' ); ?></button>
	</div>
</div>

<div class="wlsm-container row">
	<div class="col-md-12 wlsm-flex wlsm-justify-center">
		<!-- Print certificate section. -->
		<div class="wlsm" id="wlsm-print-certificate">
			<div class="wlsm-print-certificate-container mx-auto">
				<?php
				if ( ! $image_url ) {
					$image_url = WLSM_PLUGIN_URL . 'assets/images/certificate.png';
				}
				?>
				<img class="ctf-data-field wlsm-certificate-image" src="<?php echo esc_url( $image_url ); ?>">
				<?php
				foreach ( $fields as $field_key => $field_value ) {
					if ( isset( $student ) ) {
						if ( 'name' === $field_key ) {
							$field_output = WLSM_M_Staff_Class::get_name_text( $student->student_name );

						} elseif ( 'certificate-number' === $field_key ) {
							$field_output = $certificate_number;

						} elseif ( 'photo' === $field_key ) {
							if ( ! empty ( $student->photo_id ) ) {
								$field_output = wp_get_attachment_url( $student->photo_id );
							} else {
								$field_output = '';
							}

						} elseif ( 'enrollment-number' === $field_key ) {
							$field_output = $student->enrollment_number;

						} elseif ( 'admission-number' === $field_key ) {
							$field_output = WLSM_M_Staff_Class::get_admission_no_text( $student->admission_number );

						} elseif ( 'roll-number' === $field_key ) {
							$field_output = WLSM_M_Staff_Class::get_roll_no_text( $student->roll_number );

						} elseif ( 'session-label' === $field_key ) {
							$field_output = WLSM_M_Session::get_label_text( $session_label );

						} elseif ( 'session-start-date' === $field_key ) {
							$field_output = WLSM_Config::get_date_text( $session_start_date );

						} elseif ( 'session-end-date' === $field_key ) {
							$field_output = WLSM_Config::get_date_text( $session_end_date );

						} elseif ( 'session-start-year' === $field_key ) {
							$field_output = DateTime::createFromFormat( 'Y-m-d', $session_start_date );
							$field_output = $field_output->format( 'Y' );

						} elseif ( 'session-end-year' === $field_key ) {
							$field_output = DateTime::createFromFormat( 'Y-m-d', $session_end_date );
							$field_output = $field_output->format( 'Y' );

						} elseif ( 'class' === $field_key ) {
							$field_output = WLSM_M_Class::get_label_text( $student->class_label );

						} elseif ( 'section' === $field_key ) {
							$field_output = WLSM_M_Class::get_label_text( $student->section_label );

						} else {
							$field_output = '';
						}
					} else {
						$field_output = WLSM_Helper::get_certificate_place_holder( $field_key );
					}

					if ( 'text' === WLSM_Helper::get_certificate_place_holder_type( $field_key ) ) {
					?>
					<span class="ctf-data-field ctf-data-<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $field_output ); ?></span>
					<?php
					} elseif ( 'image' === WLSM_Helper::get_certificate_place_holder_type( $field_key ) && $field_output ) {
					?>
					<img class="ctf-data-field ctf-data-<?php echo esc_attr( $field_key ); ?>" src="<?php echo esc_url( $field_output ); ?>">
					<?php
					}
				?>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
