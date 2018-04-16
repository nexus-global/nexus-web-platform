<?php echo do_shortcode('[tp-single-map]' ); ?>

<?php 
$layout  = get_post_meta($post->ID, 'pp_sidebar_layout', true);
if(empty($layout)) { $layout = 'full-width'; }
$class = ($layout !="full-width") ? "eleven columns" : "sixteen columns" ;

?>

<div id="single-page-container" class="no-photo-just-map">
	<div class="container <?php esc_attr_e($layout); ?>">

		<!-- Map Navigation -->
		<ul id="mapnav-buttons">
		    <li><a href="#" id="prevpoint" title="<?php _e('Previous Point On Map','wpvoyager') ?>"><?php _e('Prev','wpvoyager') ?></a></li>
		    <li><a href="#" id="nextpoint" title="<?php _e('Next Point On Map','wpvoyager') ?>"><?php _e('Next','wpvoyager') ?></a></li>
		</ul>

		<div class="sixteen columns">
			<div class="post-title">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php wpvoyager_full_posted_on(); ?>
			</div>
		</div>

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
					if (in_array("cat", $metas)) { 
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
					wpv_related_posts($post); 
					wpv_post_nav(); 
				?>
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