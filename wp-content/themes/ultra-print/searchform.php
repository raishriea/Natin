<?php
/**
 * Template for displaying search forms in Ultra Print
 *
 * @subpackage Ultra Print
 * @since 1.0
 * @version 0.1
 */
?>

<?php $ultra_print_unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e('Search for:','ultra-print'); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder','ultra-print' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
	</label>
	<button type="submit" class="search-submit"><?php echo esc_html_x( 'Search', 'submit button', 'ultra-print' ); ?></button>
</form>