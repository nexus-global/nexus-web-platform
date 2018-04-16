<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPVoyager
 */

get_header(); 
$layout = ot_get_option('pp_series_layout','full-width');
$position = 'alternative';
$mapflag = true;
$series = get_query_var('post_series');
if($mapflag){
	echo do_shortcode('[tp-global-map postseries="'.$series.'"]' );
}
?>
<!-- Titlebar
================================================== -->
<section class="titlebar taxonomy">
	<div class="container">
	    <div class="sixteen columns">
	       <h1 class="page-title"><?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo esc_html($term->name); ?></h1>
	    </div>
	</div>
</section>


<!-- Content
================================================== -->

<?php if($mapflag) { if($position != 'alternative') {?>
	<div id="home-post-container">
	<?php } } ?>

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
			<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
		<?php } else { ?>
			<div class="sixteen columns">
			<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
				
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
