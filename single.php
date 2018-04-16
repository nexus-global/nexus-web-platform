<?php
/**
 * The template for displaying single posts. Actuall code is in template-parts
 *
 * @package WPVoyager
 */

get_header(); ?>
<!-- Map
================================================== -->
<?php 
	while ( have_posts() ) : the_post(); 
		$type = get_post_meta($post->ID, "pp_post_type", $single = true); 
		if(empty($type)) { $type = 'standard'; }
		get_template_part( 'template-parts/single',$type ); 
	endwhile; // End of the loop. 
get_footer(); ?>
