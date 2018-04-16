<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WPVoyager
 */

?>

<!-- Footer
================================================== -->
<div id="footer">
	<!-- Container -->
	<div class="container">
		<?php 
		$footer_layout = ot_get_option('pp_footer_widgets','5,3,3,5');
        $footer_layout_array = explode(',', $footer_layout); 
        $x = 0;
        foreach ($footer_layout_array as $value) {
            $x++;
             ?>
             <div class="<?php echo esc_attr(wpvoyager_number_to_width($value)); ?> columns">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer'.$x)) : endif; ?>
            </div>
        <?php } ?>

		<!-- About -->
		<br>

		<!-- Fun Facts -->
		<?php $funfacts = ot_get_option('pp_fun_facts',array()); 
		if(!empty($funfacts)) { ?>
		<div class="sixteen columns">
			<h4><?php echo esc_html(ot_get_option('pp_funfuctstitle')); ?></h4>

			<div class="fun-facts-container">
				<?php 
				foreach ($funfacts as $fun) { ?>
				<div class="fun-fact">
					<i class="fa <?php echo esc_attr($fun['icon']); ?>"></i>
					<div class="fun-fact-content"><?php echo esc_html($fun['title']); ?>
						<?php if(isset($fun['subtitle'])) { ?><span><?php echo esc_html($fun['subtitle']); ?></span><?php } ?>
					</div>
				</div>
				<?php } ?>
				
			</div>
		</div>
		<?php } ?>
		<!-- Copyright -->
		<div class="sixteen columns">
			<div class="copyright">
				<?php $copyrights = ot_get_option('pp_copyrights' );
		        if (function_exists('icl_register_string')) {
		            icl_register_string('Copyrights in footer','copyfooter', $copyrights);
		            echo icl_t('Copyrights in footer','copyfooter', $copyrights);
		        } else {
		            echo wp_kses($copyrights,array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),));
		        } ?>
	        </div>
		</div>
		<div id="backtotop_wpv"><a href="#"></a></div>
	</div>
	<!-- Container / End -->

</div>
<!-- Footer / End -->


<?php wp_footer(); ?>

</body>
</html>
