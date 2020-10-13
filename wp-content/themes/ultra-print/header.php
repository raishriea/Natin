<?php
/**
 * The header for our theme
 *
 * @subpackage Ultra Print
 * @since 1.0
 * @version 0.1
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} else {
    do_action( 'wp_body_open' );
}?>

<a class="screen-reader-text skip-link" href="#skip-content"><?php esc_html_e( 'Skip to content', 'ultra-print' ); ?></a>

<div id="header">
	<div class="container-fluid">
		<div class="row m-0">
			<div class="col-lg-3 col-md-5">
				<div class="logo">
					<?php if ( has_custom_logo() ) : ?>
	            		<?php the_custom_logo(); ?>
		            <?php endif; ?>
	             	<?php if (get_theme_mod('ultra_print_show_site_title',true)) {?>
              		<?php $blog_info = get_bloginfo( 'name' ); ?>
		                <?php if ( ! empty( $blog_info ) ) : ?>
		                  	<?php if ( is_front_page() && is_home() ) : ?>
		                    	<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		                  	<?php else : ?>
	                      		<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
	                  		<?php endif; ?>
		                <?php endif; ?>
		            <?php }?>
		            <?php if (get_theme_mod('ultra_print_show_tagline',true)) {?>
		                <?php
	                  		$description = get_bloginfo( 'description', 'display' );
		                  	if ( $description || is_customize_preview() ) :
		                ?>
	                  	<p class="site-description">
	                    	<?php echo esc_attr($description); ?>
	                  	</p>
	              		<?php endif; ?>
              		<?php }?>
			    </div>
			</div>
			<div class="<?php if(get_theme_mod('ultra_print_button_url') != '') { ?>col-lg-7 col-md-4 col-3" <?php } else { ?>col-lg-9 col-md-7" <?php } ?>">
				<div class="menu-section">
					<div class="container">
						<div class="toggle-menu responsive-menu">
							<?php if(has_nav_menu('primary')){ ?>
				            	<button onclick="ultra_print_open()" role="tab" class="mobiletoggle"><i class="fas fa-bars"></i><span class="screen-reader-text"><?php esc_html_e('Open Menu','ultra-print'); ?></span></button>
				            <?php }?>
				        </div>
						<div id="sidelong-menu" class="nav sidenav">
			                <nav id="primary-site-navigation" class="nav-menu" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'ultra-print' ); ?>">
			                  	<?php if(has_nav_menu('primary')){
				                    wp_nav_menu( array( 
										'theme_location' => 'primary',
										'container_class' => 'main-menu-navigation clearfix' ,
										'menu_class' => 'clearfix',
										'items_wrap' => '<ul id="%1$s" class="%2$s mobile_nav">%3$s</ul>',
										'fallback_cb' => 'wp_page_menu',
				                    ) ); 
			                  	} ?>
			                  	<a href="javascript:void(0)" class="closebtn responsive-menu" onclick="ultra_print_close()"><i class="fas fa-times"></i><span class="screen-reader-text"><?php esc_html_e('Close Menu','ultra-print'); ?></span></a>
			                </nav>
			            </div>
			        </div>
		        </div>
			</div>
			<?php if (get_theme_mod('ultra_print_button_url') != '') { ?>
				<div class="col-lg-2 col-md-3 col-9">
					<div class="quote-btn">
						<a href="<?php echo esc_url( get_theme_mod( 'ultra_print_button_url','' ) ); ?>"><?php echo esc_html( get_theme_mod( 'ultra_print_button_text','' ) ); ?></a>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
</div>