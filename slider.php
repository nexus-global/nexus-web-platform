<?php
 
if(ot_get_option('pp_revslider_on','off') == 'on') {
	echo '<div class="container"><div class="sixteen columns">'; 
	if ( function_exists( 'putRevSlider' ) ) :
		putRevSlider(ot_get_option( 'pp_revo_slider' )); 
	endif;
	echo "</div></div>";
}
if(ot_get_option('pp_slider_on') == 'on') {
    $slider = new CP_Slider;
    $slider->getCPslider(ot_get_option('pp_slider_select'));
} 
?>