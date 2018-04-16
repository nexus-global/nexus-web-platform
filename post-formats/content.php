<?php
/**
 * Template part for displaying posts on blog.
 *
 * @package WPVoyager
 */

$layout = ot_get_option('pp_blog_layout','full-width');
$image_size = ($layout == 'full-width') ? 'full-blog' : 'sb-blog' ;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php if ( has_post_thumbnail() ) { ?>
		<!-- Thumbnail -->
		<a class="post-img" href="<?php echo esc_url(get_permalink()); ?>">
			<?php the_post_thumbnail($image_size); ?>
		</a>
	<?php } ?>
	<!-- Post Content -->
	<div class="post-content centered">
		
		<?php wpvoyager_full_posted_on(); ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<?php the_excerpt(); ?>
			<a href="<?php the_permalink(); ?>" class="button"><?php _e('View More','wpvoyager') ?></a>
	</div>
</article>
