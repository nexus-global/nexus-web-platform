<?php 
$layout  = get_post_meta($post->ID, 'pp_sidebar_layout', true);
if(empty($layout)) { $layout = 'full-width'; }
if($layout !="full-width") {
	$class="eleven columns";
} else {
	$class="sixteen columns";
}
?>

<?php 
$image_id = get_post_meta($post->ID, "pp_post_slider_img", $single = true); 
if(empty($image_id)) {
	$image_id = get_post_thumbnail_id();
}
$image = wp_get_attachment_image_src( $image_id, 'full' ); // r
if($image) { ?>
	<div id="fullscreen-image" class="fullscreen background parallax" style="background-image: url(<?php echo esc_url($image[0]); ?>)" data-img-width="<?php esc_attr_e($image[1]); ?>" data-img-height="<?php esc_attr_e($image[2]); ?>" data-diff="100">
		<div class="fullscreen-image-title">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php wpvoyager_full_posted_on(); ?>
		</div>
		<div class="scroll-to-content bounce"></div>
	</div>
<?php } ?>

<div id="single-page-container" class="just-photo-no-map">
	<div class="container <?php echo esc_attr($layout); ?>">

			<!-- Blog Posts -->
		<div class="alignment <?php echo esc_attr($class); ?>">

			<div class="page-content">
			<?php 
			if ( ! post_password_required() ) { 
				$video = get_post_meta($post->ID, '_format_video_embed', true);
				if($video) {
				?>
					<div class="embed">
					    <?php
					      $video = get_post_meta($post->ID, '_format_video_embed', true);
					      if(wp_oembed_get($video)) { echo wp_oembed_get($video); } else { echo $video;}
					    ?>
				  	</div>
			  	<?php }
		  	} ?>
				<?php the_content(); ?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpvoyager' ),
						'after'  => '</div>',
					) );

					$metas = ot_get_option('pp_meta_single',array());
					if (in_array("categories", $metas)) { 
						$utility_text = __( 'This entry was posted in %1$s','wpvoyager');
						$categories_list = get_the_category_list( __( ', ', 'wpvoyager' )); 
						printf($utility_text,$categories_list);
					}
					if (in_array("tags", $metas)) {
						the_tags( '<ul class="tagcloud inpost"><li>', '</li><li>', '</li></ul>' ); 
					}
				?>
	
				<div class="other-content">
				<?php 
					wpv_share_post();
					wpv_about_author();
					wpv_related_posts($post); ?>
					<!-- Related Posts -->

					<?php wpv_post_nav(); ?>
					<div class="clearfix"></div>
					<div class="margin-top-50"></div>

					<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>
				</div>
			</div>	
		</div>
		<?php if($layout !="full-width") { get_sidebar(); }?>
	</div>
</div>
