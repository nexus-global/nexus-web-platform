<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package WPVoyager
 */
?>

<div class="five columns sidebar">
	<?php
    if(is_singular()){
        $sidebar = get_post_meta($post->ID, "pp_sidebar_set", $single = true);
        if ($sidebar) {
            if ( ! dynamic_sidebar( $sidebar ) ) :?>

            <aside id="archives" class="widget">
                <h4 class="widget-title"><?php _e( 'Archives', 'wpvoyager' ); ?></h4>
                <ul>
                    <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                </ul>
            </aside>

            <aside id="meta" class="widget">
                <h4 class="widget-title"><?php _e( 'Meta', 'wpvoyager' ); ?></h4>
                <ul>
                    <?php wp_register(); ?>
                    <li><?php wp_loginout(); ?></li>
                    <?php wp_meta(); ?>
                </ul>
            </aside>
        <?php endif;
        } else {
           if (!dynamic_sidebar('sidebar-1')) : ?>
               <aside id="archives" class="widget">
                    <h4 class="widget-title"><?php _e( 'Archives', 'wpvoyager' ); ?></h4>
                    <ul>
                        <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                    </ul>
                </aside>

                <aside id="meta" class="widget">
                    <h4 class="widget-title"><?php _e( 'Meta', 'wpvoyager' ); ?></h4>
                    <ul>
                        <?php wp_register(); ?>
                        <li><?php wp_loginout(); ?></li>
                        <?php wp_meta(); ?>
                    </ul>
                </aside> 
           <?php endif;
        }
    } else {
        if (!dynamic_sidebar('sidebar-1')) : ?>
        <aside id="archives" class="widget">
            <h4 class="widget-title"><?php _e( 'Archives', 'wpvoyager' ); ?></h4>
            <ul>
                <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
            </ul>
        </aside>

        <aside id="meta" class="widget">
            <h4 class="widget-title"><?php _e( 'Meta', 'wpvoyager' ); ?></h4>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <?php wp_meta(); ?>
            </ul>
        </aside>
        <?php endif;
    } // end primary widget area
    ?>
</div><!-- #secondary -->
