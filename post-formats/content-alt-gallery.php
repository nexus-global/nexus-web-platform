<?php
/**
 * Template part for displaying posts on blog.
 *
 * @package WPVoyager
 */

$layout = ot_get_option('pp_blog_layout','full-width');
$image_size = ($layout == 'full-width') ? 'full-blog' : 'sb-blog' ;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('alt'); ?>>

<?php
  if ( ! post_password_required() ) {
    $gallery = get_post_meta($post->ID, '_format_gallery', TRUE);
    if($gallery) {
      preg_match( '/ids=\'(.*?)\'/', $gallery, $matches );
        if ( isset( $matches[1] ) ) {
          // Found the IDs in the shortcode
           $ids = explode( ',', $matches[1] );
        } else {  
          // The string is only IDs
          $ids = ! empty( $gallery ) && $gallery != '' ? explode( ',', $gallery ) : array();
        }
        echo '<div class="front-slider rsDefault">';
        foreach ($ids as $imageid) { ?>
            <?php   $image_link = wp_get_attachment_url( $imageid );
                    if ( ! $image_link )
                       continue;
                    $image          = wp_get_attachment_image_src( $imageid, $image_size);
                    $image_title    = esc_attr( get_the_title( $imageid ) ); ?>
                    <a href="<?php the_permalink(); ?>" class="post-img"  title="<?php esc_attr_e($image_title);?>"><img class="rsImg" src="<?php echo esc_url($image[0]); ?>" /></a>
            <?php ?>
      <?php } //eof for each?>
     </div>
<?php }
  } //eof password protected ?>
	<!-- Post Content -->
	<div class="post-content">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php wpvoyager_posted_on(); ?>
		<?php the_excerpt(); ?>
		<a href="<?php the_permalink(); ?>" class="button"><?php _e('View More','wpvoyager') ?></a>
	</div>
</article>
