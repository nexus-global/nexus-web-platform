<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<!-- Post Content -->
	<div class="post-content">
		<h2 class="entry-title"><?php esc_html_e( 'Nothing Found', 'wpvoyager' ); ?></h2>
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'wpvoyager' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'wpvoyager' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'wpvoyager' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div>
</article>