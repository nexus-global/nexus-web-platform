<?php
/**
 * Template Name: Page Template with Slider
 *
 * A custom page template with Slider
 *
 *
 * @package WPVoyager
 */





get_header(); 
$slidername = get_post_meta($post->ID, 'pp_page_slider', true);

if($slidername) {
    $slider = new CP_Slider;
    $slider->getCPslider($slidername);
}

 while ( have_posts() ) : the_post(); ?>

    <?php get_template_part( 'template-parts/content', 'page' ); ?>


<?php endwhile; // End of the loop. ?>
<?php get_footer(); ?>
