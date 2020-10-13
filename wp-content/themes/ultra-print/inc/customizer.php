<?php
/**
 * Ultra Print: Customizer
 *
 * @subpackage Ultra Print
 * @since 1.0
 */

use WPTRT\Customize\Section\Ultra_Print_Button;

add_action( 'customize_register', function( $manager ) {

	$manager->register_section_type( Ultra_Print_Button::class );

	$manager->add_section(
		new Ultra_Print_Button( $manager, 'ultra_print_pro', [
			'title'       => __( 'Ultra Print Pro', 'ultra-print' ),
			'priority'    => 0,
			'button_text' => __( 'Go Pro', 'ultra-print' ),
			'button_url'  => esc_url( 'https://www.luzuk.com/products/printing-wordpress-theme/', 'ultra-print')
		] )
	);

} );

// Load the JS and CSS.
add_action( 'customize_controls_enqueue_scripts', function() {

	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script(
		'ultra-print-customize-section-button',
		get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/js/customize-controls.js' ),
		[ 'customize-controls' ],
		$version,
		true
	);

	wp_enqueue_style(
		'ultra-print-customize-section-button',
		get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/css/customize-controls.css' ),
		[ 'customize-controls' ],
 		$version
	);

} );

function ultra_print_customize_register( $wp_customize ) {

	$wp_customize->add_setting('ultra_print_show_site_title',array(
       'default' => true,
       'sanitize_callback'	=> 'sanitize_text_field'
    ));
    $wp_customize->add_control('ultra_print_show_site_title',array(
       'type' => 'checkbox',
       'label' => __('Show / Hide Site Title','ultra-print'),
       'section' => 'title_tagline'
    ));

    $wp_customize->add_setting('ultra_print_show_tagline',array(
       'default' => true,
       'sanitize_callback'	=> 'sanitize_text_field'
    ));
    $wp_customize->add_control('ultra_print_show_tagline',array(
       'type' => 'checkbox',
       'label' => __('Show / Hide Site Tagline','ultra-print'),
       'section' => 'title_tagline'
    ));

	$wp_customize->add_panel( 'ultra_print_panel_id', array(
	    'priority' => 10,
	    'capability' => 'edit_theme_options',
	    'theme_supports' => '',
	    'title' => __( 'Theme Settings', 'ultra-print' ),
	    'description' => __( 'Description of what this panel does.', 'ultra-print' ),
	) );

	$wp_customize->add_section( 'ultra_print_theme_options_section', array(
    	'title'      => __( 'General Settings', 'ultra-print' ),
		'priority'   => 30,
		'panel' => 'ultra_print_panel_id'
	) );

	$wp_customize->add_setting('ultra_print_theme_options',array(
        'default' => __('Right Sidebar','ultra-print'),
        'sanitize_callback' => 'ultra_print_sanitize_choices'	        
	));
	$wp_customize->add_control('ultra_print_theme_options',array(
        'type' => 'radio',
        'label' => __('Do you want this section','ultra-print'),
        'section' => 'ultra_print_theme_options_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','ultra-print'),
            'Right Sidebar' => __('Right Sidebar','ultra-print'),
            'One Column' => __('One Column','ultra-print'),
            'Three Columns' => __('Three Columns','ultra-print'),
            'Four Columns' => __('Four Columns','ultra-print'),
            'Grid Layout' => __('Grid Layout','ultra-print')
        ),
	));

	//Header section
	$wp_customize->add_section( 'ultra_print_header_section' , array(
    	'title' => __( 'Header Section', 'ultra-print' ),
		'priority' => null,
		'panel' => 'ultra_print_panel_id'
	) );

	$wp_customize->add_setting('ultra_print_button_text',array(
       	'default' => '',
       	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('ultra_print_button_text',array(
	   	'type' => 'text',
	   	'label' => __('Add Button Text','ultra-print'),
	   	'section' => 'ultra_print_header_section',
	));

	$wp_customize->add_setting('ultra_print_button_url',array(
       	'default' => '',
       	'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('ultra_print_button_url',array(
	   	'type' => 'url',
	   	'label' => __('Add Button URL','ultra-print'),
	   	'section' => 'ultra_print_header_section',
	));

	//home page slider
	$wp_customize->add_section( 'ultra_print_slider_section' , array(
    	'title'      => __( 'Slider Settings', 'ultra-print' ),
		'priority'   => null,
		'panel' => 'ultra_print_panel_id'
	) );

	$wp_customize->add_setting('ultra_print_slider_hide_show',array(
       	'default' => false,
       	'sanitize_callback'	=> 'ultra_print_sanitize_checkbox'
	));
	$wp_customize->add_control('ultra_print_slider_hide_show',array(
	   	'type' => 'checkbox',
	   	'label' => __('Show / Hide slider','ultra-print'),
	   	'section' => 'ultra_print_slider_section',
	));

	for ( $count = 1; $count <= 4; $count++ ) {
		$wp_customize->add_setting( 'ultra_print_slider' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'ultra_print_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'ultra_print_slider' . $count, array(
			'label' => __( 'Select Slide Image Page', 'ultra-print' ),
			'section' => 'ultra_print_slider_section',
			'type' => 'dropdown-pages'
		) );
	}

	//Our Services
	$wp_customize->add_section('ultra_print_service',array(
		'title'	=> __('Services Section','ultra-print'),
		'description'=> __('This section will appear below the slider.','ultra-print'),
		'panel' => 'ultra_print_panel_id',
	));

	$categories = get_categories();
	$cats = array();
	$i = 0;
	$cat_pst[]= 'select';
	foreach($categories as $category){
		if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_pst[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('ultra_print_category_setting',array(
		'default'	=> 'select',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('ultra_print_category_setting',array(
		'type'    => 'select',
		'choices' => $cat_pst,
		'label' => __('Select Category to display Post','ultra-print'),
		'description' => __('Image Size (58px x 58px)','ultra-print'),
		'section' => 'ultra_print_service',
	));

	//About Section 
	$wp_customize->add_section('ultra_print_services_section',array(
		'title'	=> __('About Section','ultra-print'),
		'description'=> __('This section will appear below the Slider section.','ultra-print'),
		'panel' => 'ultra_print_panel_id',
	));
	
	$wp_customize->add_setting( 'ultra_print_services_page', array(
		'default'           => '',
		'sanitize_callback' => 'ultra_print_sanitize_dropdown_pages'
	));
	$wp_customize->add_control( 'ultra_print_services_page', array(
		'label'    => __( 'Select about Page', 'ultra-print' ),
		'description' => __('Image size (255px x 300px)', 'ultra-print'),
		'section'  => 'ultra_print_services_section',
		'type'     => 'dropdown-pages'
	));

	//Footer
    $wp_customize->add_section( 'ultra_print_footer', array(
    	'title'      => __( 'Footer Text', 'ultra-print' ),
		'priority'   => null,
		'panel' => 'ultra_print_panel_id'
	) );

    $wp_customize->add_setting('ultra_print_footer_copy',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('ultra_print_footer_copy',array(
		'label'	=> __('Footer Text','ultra-print'),
		'section'	=> 'ultra_print_footer',
		'setting'	=> 'ultra_print_footer_copy',
		'type'		=> 'text'
	));

	$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport   = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport  = 'postMessage';

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.site-title a',
		'render_callback' => 'ultra_print_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.site-description',
		'render_callback' => 'ultra_print_customize_partial_blogdescription',
	) );

	//front page
	$num_sections = apply_filters( 'ultra_print_front_page_sections', 4 );

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
		$wp_customize->add_setting( 'panel_' . $i, array(
			'default'           => false,
			'sanitize_callback' => 'ultra_print_sanitize_dropdown_pages',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'panel_' . $i, array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Content', 'ultra-print' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Select pages to feature in each area from the dropdowns. Add an image to a section by setting a featured image in the page editor. Empty sections will not be displayed.', 'ultra-print' ) ),
			'section'        => 'theme_options',
			'type'           => 'dropdown-pages',
			'allow_addition' => true,
			'active_callback' => 'ultra_print_is_static_front_page',
		) );

		$wp_customize->selective_refresh->add_partial( 'panel_' . $i, array(
			'selector'            => '#panel' . $i,
			'render_callback'     => 'ultra_print_front_page_section',
			'container_inclusive' => true,
		) );
	}
}
add_action( 'customize_register', 'ultra_print_customize_register' );

function ultra_print_customize_partial_blogname() {
	bloginfo( 'name' );
}

function ultra_print_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

function ultra_print_is_static_front_page() {
	return ( is_front_page() && ! is_home() );
}

function ultra_print_is_view_with_layout_option() {
	// This option is available on all pages. It's also available on archives when there isn't a sidebar.
	return ( is_page() || ( is_archive() && ! is_active_sidebar( 'sidebar-1' ) ) );
}