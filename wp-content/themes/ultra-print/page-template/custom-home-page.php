<?php
/**
 * Template Name: Custom Home
 */

get_header(); ?>

<main id="skip-content" role="main">

	<?php do_action( 'ultra_print_above_slider' ); ?>

	<?php if( get_theme_mod('ultra_print_slider_hide_show') != ''){ ?>
		<section id="slider">
			<div class="slider-design">
				<div class="slider-leftsvg">
					<svg viewBox="0 0 650 150" preserveAspectRatio="none"><path d="M154.62,-3.45 C152.36,107.06 35.55,62.66 0.00,149.50 L-2.25,143.58 L0.00,0.00 Z" style="stroke: none; "></path></svg>
				</div>
				<svg class="slider-circle">
				  	<defs>
					   	<clipPath id="cut-off-bottom">
					      <rect x="0" y="0" width="200" height="80"></rect>
					    </clipPath>
				  	</defs>
				  	<circle cx="100" cy="100" r="100" clip-path="url(#cut-off-bottom)"></circle>
				</svg>
				<svg class="slider-circle2"height="100px" width="100px">
					<circle cx="50px" cy="50px" r="40px"></circle>
				</svg>
			</div>
		  	<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel"> 
			    <?php $ultra_print_slider_pages = array();
		      	for ( $count = 1; $count <= 4; $count++ ) {
			        $mod = intval( get_theme_mod( 'ultra_print_slider' . $count ));
			        if ( 'page-none-selected' != $mod ) {
			          $ultra_print_slider_pages[] = $mod;
			        }
		      	}
		      	if( !empty($ultra_print_slider_pages) ) :
			        $args = array(
			          	'post_type' => 'page',
			          	'post__in' => $ultra_print_slider_pages,
			          	'orderby' => 'post__in'
			        );
			        $query = new WP_Query( $args );
			        if ( $query->have_posts() ) :
			          $i = 1;
			    ?>     
				    <div class="carousel-inner" role="listbox">
				      	<?php  while ( $query->have_posts() ) : $query->the_post(); ?>
					        <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
					          	<div class="carousel-caption">
						            <div class="inner_carousel">
						              	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?><span class="screen-reader-text"><?php the_title(); ?></span></a></h2>
						              	<p><?php $ultra_print_excerpt = get_the_excerpt(); echo esc_html( ultra_print_string_limit_words( $ultra_print_excerpt,20 ) ); ?></p>
						            </div>
					          	</div>
					        </div>
				      	<?php $i++; endwhile; 
				      	wp_reset_postdata();?>
				    </div>
			    <?php else : ?>
			    	<div class="no-postfound"></div>
	      		<?php endif;
			    endif;?>
		  	</div>
		  	<div class="clearfix"></div>
		</section>
	<?php }?>

	<?php do_action('ultra_print_below_slider'); ?>

	<section id="our-services">
		<div class="fertured-wave">
			<svg viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M161.39,-22.20 C144.47,40.95 345.93,-78.45 516.36,163.31 L500.00,0.00 L209.36,-38.97 Z"></path></svg>
		</div>
		<svg class="fertured-circle" height="150px" width="80px">
			<circle cx="70px" cy="70px" r="70px"></circle>
		</svg>
		<div class="container">
			<div class="service-box">
	            <div class="row">
		      		<?php $ultra_print_catData1 =  get_theme_mod('ultra_print_category_setting');
      				if($ultra_print_catData1){ 
	      				$page_query = new WP_Query(array( 'category_name' => esc_html($ultra_print_catData1 ,'ultra-print')));?>
		        		<?php while( $page_query->have_posts() ) : $page_query->the_post(); ?>	
		          			<div class="col-lg-3 col-md-6">
		          				<div class="service-section">
		      						<div class="service-img">
							      		<?php the_post_thumbnail(); ?>
							  		</div>
	          						<div class="service-content">
					            		<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
		            				</div>	
		          				</div>
						    </div>
		          		<?php endwhile; 
		          	wp_reset_postdata();
		      		}?>
	      		</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</section>

	<?php /*--- About Section ---*/ ?>
	<section id="about-section">
		<div class="container-fluid">
	    	<?php $ultra_print_about_pages = array();
	      	$mod = intval( get_theme_mod( 'ultra_print_services_page'));
	     	if ( 'page-none-selected' != $mod ) {
	        	$ultra_print_about_pages[] = $mod;
	      	}
	    	if( !empty($ultra_print_about_pages) ) :
	      	$args = array(
	        	'post_type' => 'page',
	        	'post__in' => $ultra_print_about_pages,
	        	'orderby' => 'post__in'
	      	);
	      	$query = new WP_Query( $args );
	     	if ( $query->have_posts() ) :
		        while ( $query->have_posts() ) : $query->the_post(); ?> 
		        	<div class="row m-0">    	
		          		<div class="col-lg-5 col-md-12">
		          			<svg class="dots-svg" xmlns="http://www.w3.org/2000/svg" width="175.457" height="116.943">
								<g fill="#63B3ED">
									<path d="M172.058 6.786a3.369 3.369 0 112.393-.976 3.393 3.393 0 01-2.4.978zM168.689 31.301a3.393 3.393 0 113.727 3.011 3.393 3.393 0 01-3.727-3.011zM168.689 58.827a3.369 3.369 0 111.235 2.283 3.393 3.393 0 01-1.235-2.283zM168.69 113.902a3.393 3.393 0 113.722 3.023 3.417 3.417 0 01-3.722-3.023zM168.689 86.376a3.393 3.393 0 113.727 3.011 3.393 3.393 0 01-3.727-3.011zM148.021 6.786a3.393 3.393 0 113.393-3.369 3.393 3.393 0 01-3.393 3.369zM144.652 31.301a3.393 3.393 0 113.725 3.01 3.393 3.393 0 01-3.727-3.011zM144.652 58.827a3.369 3.369 0 111.235 2.283 3.393 3.393 0 01-1.235-2.283zM144.652 113.902a3.393 3.393 0 113.725 3.01 3.393 3.393 0 01-3.725-3.01zM144.652 86.376a3.393 3.393 0 113.725 3.01 3.393 3.393 0 01-3.727-3.011zM122.431 6.786a3.393 3.393 0 113.369-3.369 3.393 3.393 0 01-3.369 3.369zM119.062 31.301a3.369 3.369 0 113.7 3.011 3.369 3.369 0 01-3.7-3.011zM119.062 58.827a3.369 3.369 0 111.227 2.274 3.369 3.369 0 01-1.227-2.274zM119.062 113.901a3.375 3.375 0 113.7 3.011 3.375 3.375 0 01-3.7-3.011zM119.062 86.379a3.369 3.369 0 113.7 3.011 3.369 3.369 0 01-3.7-3.011zM98.37 6.786a3.369 3.369 0 112.4-.978 3.393 3.393 0 01-2.4.978zM95.006 31.301a3.393 3.393 0 113.727 3.011 3.393 3.393 0 01-3.727-3.011zM95.006 58.827a3.369 3.369 0 111.235 2.283 3.393 3.393 0 01-1.235-2.283zM95.001 113.901a3.393 3.393 0 113.7 2.826 3.393 3.393 0 01-3.7-2.826zM95.006 86.376a3.393 3.393 0 113.727 3.011 3.393 3.393 0 01-3.727-3.011zM77.2 6.786a3.393 3.393 0 113.369-3.369A3.393 3.393 0 0177.2 6.786zM73.831 31.301a3.393 3.393 0 113.656 3.011 3.393 3.393 0 01-3.656-3.011zM73.831 58.828a3.393 3.393 0 113.656 3.011 3.393 3.393 0 01-3.656-3.011zM73.831 113.901a3.393 3.393 0 113.656 3.011 3.393 3.393 0 01-3.656-3.011zM73.831 86.375a3.393 3.393 0 113.656 3.011 3.393 3.393 0 01-3.656-3.011zM53.139 6.786a3.369 3.369 0 112.4-.978 3.393 3.393 0 01-2.4.978zM49.771 31.301a3.393 3.393 0 111.275 2.313 3.393 3.393 0 01-1.275-2.313zM49.771 58.826a3.369 3.369 0 111.269 2.321 3.393 3.393 0 01-1.269-2.321zM49.77 113.901a3.393 3.393 0 113.7 2.826 3.393 3.393 0 01-3.7-2.826zM49.771 86.376a3.393 3.393 0 111.275 2.313 3.393 3.393 0 01-1.275-2.313zM27.549 6.786a3.393 3.393 0 113.393-3.369 3.393 3.393 0 01-3.393 3.369zM24.18 31.301a3.393 3.393 0 113.727 3.011 3.393 3.393 0 01-3.727-3.011zM24.18 58.827a3.369 3.369 0 111.235 2.283 3.393 3.393 0 01-1.235-2.283zM24.18 113.902a3.393 3.393 0 113.727 3.011 3.393 3.393 0 01-3.727-3.011zM24.18 86.376a3.393 3.393 0 113.727 3.011 3.393 3.393 0 01-3.727-3.011zM3.417 6.786a3.393 3.393 0 113.369-3.369 3.393 3.393 0 01-3.369 3.369zM.147 31.301a3.393 3.393 0 113.7 3.011 3.393 3.393 0 01-3.7-3.011zM.147 58.831a3.369 3.369 0 111.227 2.274 3.393 3.393 0 01-1.227-2.274zM.147 113.902a3.369 3.369 0 111.227 2.274 3.393 3.393 0 01-1.227-2.274zM.147 86.376a3.393 3.393 0 113.7 3.011 3.393 3.393 0 01-3.7-3.011z"></path>
								</g>
							</svg>
		          			<svg class="svg">
							  	<clipPath id="my-clip-path" clipPathUnits="objectBoundingBox"><path d="M0.138,0.712 c0.004,0.004,0.008,0.008,0.012,0.012 c0.048,0.049,0.038,0.118,0.08,0.174 c0.045,0.061,0.117,0.093,0.191,0.1 c0.158,0.014,0.247,-0.178,0.27,-0.207 c0.044,-0.055,0.11,-0.09,0.177,-0.108 c0.088,-0.024,0.132,-0.08,0.132,-0.152 c0,-0.093,-0.061,-0.113,-0.072,-0.209 c-0.009,-0.071,0.085,-0.197,-0.042,-0.295 c-0.12,-0.092,-0.211,0.063,-0.374,0.06 c-0.065,-0.001,-0.213,-0.053,-0.278,-0.05 S0.099,0.064,0.061,0.119 c-0.034,0.049,-0.034,0.1,-0.016,0.154 c0.02,0.059,0.002,0.13,-0.022,0.186 C0.013,0.48,0.002,0.501,0,0.525 c-0.001,0.018,0.004,0.036,0.011,0.052 C0.039,0.635,0.094,0.669,0.138,0.712"></path></clipPath>
							</svg>
							<div class="about-img">
								<?php if(has_post_thumbnail()) { ?><?php the_post_thumbnail(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="col-lg-7 col-md-12">
							<div class="about-content">
						      	<h3><?php the_title();?></h3>
						      	<hr>
						      	<p><?php the_excerpt(); ?></p>
						      	<div class="about-btn">
				            		<a href="<?php the_permalink(); ?>"><?php esc_html_e('READ MORE','ultra-print'); ?><i class="fas fa-arrow-right"></i><span class="screen-reader-text"><?php esc_html_e('READ MORE','ultra-print'); ?></span></a>
						       	</div>
					       	</div>
					    </div>
		          	</div>
		        <?php endwhile; 
		        wp_reset_postdata();?>
	      	<?php else : ?>
	          	<div class="no-postfound"></div>
	      	<?php endif;
	    	endif;?>
      		<div class="clearfix"></div>
		</div>
	</section>

	<?php do_action('ultra_print_below_about_section'); ?>

	<div class="container-fluid">
	  	<?php while ( have_posts() ) : the_post(); ?>
	        <?php the_content(); ?>
	    <?php endwhile; // end of the loop. ?>
	</div>
</main>

<?php get_footer(); ?>