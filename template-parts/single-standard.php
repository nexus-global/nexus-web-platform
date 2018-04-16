<?php 
$layout  = get_post_meta($post->ID, 'pp_sidebar_layout', true);
if(empty($layout)) { $layout = 'right-sidebar'; }
$class = ($layout !="full-width") ? "eleven columns" : "sixteen columns" ;

?>
<section class="titlebar">
	<div class="container">
	    <div class="sixteen columns">
	        <h1><?php the_title(); ?></h1>
	        <nav id="breadcrumbs">
	             <?php if(ot_get_option('pp_breadcrumbs','on') == 'on') echo dimox_breadcrumbs(); ?>
	        </nav>
	    </div>
	</div>
</section>
<div id="single-page-container" class="no-photo-no-map">
	<div class="container <?php esc_attr_e($layout); ?>">
		<!-- Blog Posts -->
		<div class="alignment <?php esc_attr_e($class); ?>">

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
		<!-- Blog Posts / End -->
