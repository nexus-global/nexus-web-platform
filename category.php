<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPVoyager
 */

get_header(); 
$layout = ot_get_option('pp_blog_layout','full-width');
$position = 'alternative';
$mapflag = true;
if(is_category()) { $cat_ID = get_query_var('cat'); $category_settings = get_option("category_$cat_ID"); } 

if(isset($category_settings['map'])) {
	$map_type = $category_settings['map'];
	if ($map_type == 'none') {
		$mapflag = false;
	} elseif ($map_type == 'global') {
		echo do_shortcode('[tp-global-map class="'.$position.'" category="'.$cat_ID.'"]' );
	} else {
		echo do_shortcode('[tp-custom-map id="'.$map_type.'" width="100%" height="300px" type="as_global" class="page-map"] ' ); 
	}
} else {
	echo do_shortcode('[tp-global-map class="'.$position.'" category="'.$cat_ID.'"]' );
}


?>
<!-- Titlebar
================================================== -->
<section class="titlebar">
	<div class="container">
	    <div class="sixteen columns">
	       <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
	       <nav id="breadcrumbs">
	             <?php if(ot_get_option('pp_breadcrumbs','on') == 'on') echo dimox_breadcrumbs(); ?>
	        </nav>
	    </div>
	</div>
</section>
<?php	the_archive_description( '<div class="container"><div class="sixteen columns"><div class="taxonomy-description">', '</div></div></div>' ); ?>


<?php if($mapflag) { if($position != 'alternative') {?>
	<div id="home-post-container">
	<?php } } ?>

<!-- Content
================================================== -->
<div class="container <?php echo esc_attr($layout); ?>">

	<?php 
	if ($mapflag) { ?>
		<!-- Map Navigation -->
		<ul id="mapnav-buttons" class="<?php echo esc_attr($position); ?>">
		    <li><a href="#" id="prevpoint" title="Previous Point On Map"><?php _e('Prev','wpvoyager') ?></a></li>
		    <li><a href="#" id="nextpoint" title="Next Point On Map"><?php _e('Next','wpvoyager') ?></a></li>
		</ul>
	<?php } ?>

	<!-- Blog Posts -->
	<?php if($layout !="full-width") { ?>
		<div class="eleven columns"> 
	<?php } else { ?>
		<div class="sixteen columns">
	<?php } 
			if ( have_posts() ) :
				while ( have_posts() ) : the_post(); 
				get_template_part( 'post-formats/content-box', get_post_format() );
			endwhile; 
		else : 
				get_template_part( 'post-formats/content-box', 'none' ); 
		endif; 
	
	if($layout =="full-width") { ?></div><?php } ?>
	<!-- Blog Posts / End -->

	<div class="clearfix"></div>

	<!-- Pagination -->
	<div class="pagination-container alt">
		<?php 
		if(function_exists('wp_pagenavi')) { 
			wp_pagenavi(array(
				'next_text' => '<i class="fa fa-chevron-right"></i>',
				'prev_text' => '<i class="fa fa-chevron-left"></i>',
				'use_pagenavi_css' => false,
				));
		} else {
			the_posts_navigation(array(
	 			'prev_text'  => ' ',
	            'next_text'  => ' ',
			)); 
		}
		?>
	</div>
	<?php if($layout !="full-width") { ?>
	</div>
	<?php get_sidebar(); 
	} ?>
</div>
<?php if($mapflag) {  if($position != 'alternative') { ?></div><?php } } ?>

<?php get_footer(); ?>
