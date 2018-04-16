<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPVoyager
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php if ( has_post_thumbnail() ) { ?>
		<!-- Thumbnail -->
		<a class="post-img" href="<?php echo esc_url(get_permalink()); ?>">
			<?php the_post_thumbnail('post'); ?>
		</a>
	<?php } ?>
	<!-- Post Content -->
	<div class="post-content">
		
		<?php wpvoyager_full_posted_on(); ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<?php the_excerpt(); ?>
			<a href="<?php the_permalink(); ?>" class="button"><?php _e('View More','wpvoyager') ?></a>
	</div>
</article>


