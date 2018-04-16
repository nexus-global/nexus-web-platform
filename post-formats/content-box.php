<?php
/**
 * Template part for displaying posts on blog.
 *
 * @package WPVoyager
 */

if ( !has_post_thumbnail() ) { $class = "no-thumb box-item"; } else {$class = "box-item"; }
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
		<!-- Thumbnail -->
		<?php the_post_thumbnail('full-blog'); ?>
	<?php } ?>
	<!-- Post Content -->
	<div class="box-item-text">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<div class="box-meta"><?php wpvoyager_full_posted_on(); ?></div>
			<a href="<?php the_permalink(); ?>" class="button box-button"><?php _e('View More','wpvoyager') ?></a>
	</div>
</article>
