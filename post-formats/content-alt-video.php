<?php
/**
 * Template part for displaying posts on blog.
 *
 * @package WPVoyager
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('alt'); ?>>
	
	<?php if ( ! post_password_required() ) { 
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
	<!-- Post Content -->
	<div class="post-content">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php wpvoyager_posted_on(); ?>
		<?php the_excerpt(); ?>
		<a href="<?php the_permalink(); ?>" class="button"><?php _e('View More','wpvoyager') ?></a>
	</div>
</article>
