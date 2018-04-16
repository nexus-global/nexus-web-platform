<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WPVoyager
 */

?>

<!-- Container -->
<?php
$mapstatus = get_post_meta( $post->ID, 'pp_map_show', TRUE );
$titlebar = get_post_meta( $post->ID, 'pp_title_bar_hide', TRUE );
if($titlebar != 'off') {
	$parallaximage = get_post_meta( $post->ID, 'pp_parallax_bg', TRUE );
	$parallaxcolor = get_post_meta( $post->ID, 'pp_parallax_color', TRUE );
	$opacity = get_post_meta( $post->ID, 'pp_parallax_opacity', TRUE );
        if(empty($opacity)) {
            $parallaxopacity = "0.35";
        } else {
            $parallaxopacity = $opacity;
        }
	if(!empty($parallaximage)) {
		if(empty($parallaxcolor)) { $parallaxcolor = "#000000";}
		$custom_rgb = purehex2RGB($parallaxcolor); 
		
		if($custom_rgb) {
		    $red = $custom_rgb['red'];
		    $green = $custom_rgb['green'];
		    $blue = $custom_rgb['blue'];
		
	}
	?>
	<section class="parallax-titlebar background parallax <?php if( $mapstatus == 'on') { echo "has-map"; } ?>"  style="background: url(<?php echo esc_url($parallaximage); ?>); " data-height="160">
		<div class="parallax-overlay" style="background: rgba(<?php echo $red.','.$green.','.$blue.','.$parallaxopacity; ?>)"></div>
		<div class="parallax-title">
			<h2><?php the_title(); ?>
				<?php $subtitle = get_post_meta($post->ID, 'pp_subtitle', TRUE);  if($subtitle) { ?>
					<span><?php esc_html_e($subtitle); ?></span>
				<?php } ?>
			</h2>
		</div>
	</section>
	
	<?php } else { ?>
	
	<!-- Titlebar
	================================================== -->
	<section class="titlebar <?php if( $mapstatus == 'on') { echo "has-map"; } ?>">
		<div class="container">
		    <div class="sixteen columns">
		        <h1><?php the_title(); ?></h1>
		        <nav id="breadcrumbs">
		             <?php if(ot_get_option('pp_breadcrumbs','on') == 'on') echo dimox_breadcrumbs(); ?>
		        </nav>
		    </div>
		</div>
	</section>

	<?php }
} ?>
<?php 
	if( $mapstatus == 'on') {
		$maptype = get_post_meta( $post->ID, 'pp_page_map_type', TRUE );
		if($maptype == "pagemap") {
			echo do_shortcode('[tp-single-map class="page-map"]' ); 
		} elseif ($maptype == "global") {
			echo do_shortcode('[tp-global-map class="page-map"]' );
		}

		 else {
			$custommap = get_post_meta( $post->ID, 'pp_page_custom_map', TRUE );
			if(!empty($custommap)) {
				echo do_shortcode('[tp-custom-map id="'.$custommap.'" width="100%" height="300px" type="as_global" class="page-map"] ' ); 
			}
		}
	}
?>
<?php 
$layout  = get_post_meta($post->ID, 'pp_sidebar_layout', true);
if(empty($layout)) { $layout = 'full-width'; }
$class = ($layout !="full-width") ? "eleven columns" : "sixteen columns" ;

?>

<div class="container <?php esc_attr_e($layout); ?>">
		<?php if( $mapstatus == 'on') { ?><!-- Map Navigation -->
			<ul id="mapnav-buttons" class="alternative">
			    <li><a href="#" id="prevpoint" title="<?php _e('Previous Point On Map','wpvoyager') ?>"><?php _e('Prev','wpvoyager') ?></a></li>
			    <li><a href="#" id="nextpoint" title="<?php _e('Next Point On Map','wpvoyager') ?>"><?php _e('Next','wpvoyager') ?></a></li>
			</ul>
		<?php } ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpvoyager' ),
					'after'  => '</div>',
				) );
			?>

		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php edit_post_link( esc_html__( 'Edit', 'wpvoyager' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-footer -->

		<div class="clearfix"></div>
		<div class="margin-top-25"></div>

  	<?php
        if(ot_get_option('pp_pagecomments','on') == 'on') {
        	
            // If comments are open or we have at least one comment, load up the comment template
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
        }
    ?>

	</article><!-- #post-## -->
	<?php if($layout !="full-width") { get_sidebar(); }?>
</div>

