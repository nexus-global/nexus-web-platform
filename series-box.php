<?php 
global $post;
$layout  = get_post_meta($post->ID, 'pp_sidebar_layout', true);
if(empty($layout)) { $layout = 'full-width'; } 
$boxclass = ($layout == 'full-width') ? "post-series full-width-element" : "post-series" ;?>
<div class="<?php echo esc_attr($boxclass); ?>">
<?php 

global $post;
if ( apply_filters( 'wp_post_series_enable_archive', true ) ) {
	$series_name = '<a href="' . esc_url(get_term_link( $series->term_id, 'post_series' )) . '">' . esc_html( $series->name ) . '</a>';
} else {
	$series_name = esc_html( $series->name );
}
 ?>
	<div class="post-series-title">
		<?php echo ot_get_option('pp_seriestext','This post is part of a series called'); ?> <span><?php echo $series_name; ?></span>

	</div>
	
	<?php if ( is_single() && sizeof( $posts_in_series ) > 2 ) : ?>
		<a href="#" class="show-more-posts"><?php _e('Show More Posts','wpvoyager') ?></a>
		<div class="clearfix"></div>
		<ul class="post-series-links">
			<?php foreach ( $posts_in_series as $key => $post_id ) : ?>
				<li class="<?php if ( is_single( $post_id ) ) { echo "active"; } ?>">
					<?php if ( ! is_single( $post_id ) ) echo '<a href="' . esc_url(get_permalink( $post_id )) . '">'; ?>
					<?php echo get_the_title( $post_id ); ?>
					<?php if ( ! is_single( $post_id ) ) echo '</a>'; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<?php if ( is_single() && sizeof( $posts_in_series ) == 2 ) : ?>
		<?php
			$key = array_search($post->ID, $posts_in_series);
			if (false !== $key) {
			    unset($posts_in_series[$key]);
			}
		?>
		<?php foreach ( $posts_in_series as $k => $next_id ) : ?>
			<a href="<?php echo esc_url(get_permalink( $next_id )); ?>" class="next-post"><?php _e('Next Post', 'wpvoyager') ?></a>
		<?php endforeach; ?>
		<div class="clearfix"></div>
	<?php endif; ?>
</div>