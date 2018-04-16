<?php
/**
 * WPVoyager Theme Customizer
 *
 * @package WPVoyager
 */

/**
 * Convert a hexa decimal color code to its RGB equivalent
 *
 * @param string $hexStr (hexadecimal color value)
 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
 */
function purehex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wpvoyager_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    // color section
    $wp_customize->add_section( 'wpvoyager_color_settings', array(
        'title'          => __('Main theme color','wpvoyager'),
        'priority'       => 35,
        ) );

    $wp_customize->add_setting( 'wpvoyager_main_color', array(
        'default'   => '#3685cf',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpvoyager_main_color', array(
        'label'    => __('Color Settings','wpvoyager'),
        'section'  => 'colors',
        'settings' => 'wpvoyager_main_color',
        )));


    $wp_customize->add_setting( 'wpvoyager_tagline_switch', array(
        'default'  => 'hide',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_attr',
        ));
    $wp_customize->add_control( 'wpvoyager_tagline_switcher', array(
        'settings' => 'wpvoyager_tagline_switch',
        'label'    => __( 'Display Tagline','wpvoyager' ),
        'section'  => 'title_tagline',
        'type'     => 'select',
        'choices'    => array(
            'hide' => 'Hide',
            'show' => 'Show',
            )
        ));

    $wp_customize->add_setting( 'wpvoyager_scheme', array(
        'default'  => 'default',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_attr',
        ));
    $wp_customize->add_control( 'wpvoyager_scheme', array(
        'settings' => 'wpvoyager_scheme',
        'label'    => __( 'Change colour scheme','wpvoyager' ),
        'section'  => 'colors',
        'type'     => 'select',
        'choices'    => array(
            'default' => 'Default',
            'dark' => 'Dark',
            )
        ));

    $wp_customize->add_setting( 'wpvoyager_header_scheme', array(
        'default'  => 'default',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_attr',
        ));
    $wp_customize->add_control( 'wpvoyager_header_scheme', array(
        'settings' => 'wpvoyager_header_scheme',
        'label'    => __( 'Change header color','wpvoyager' ),
        'section'  => 'colors',
        'type'     => 'select',
        'choices'    => array(
            'default' => 'Default',
            'black' => 'Dark',
            )
        ));

    if ( $wp_customize->is_preview() && !is_admin() ) {
        add_action( 'wp_footer', 'wpvoyager_customize_preview', 21);
    }

}
add_action( 'customize_register', 'wpvoyager_customize_register' );

function wpvoyager_customize_preview() { ?>
    <script type="text/javascript">
    ( function( $ ){
        wp.customize('wpvoyager_main_color',function( value ) {
            value.bind(function(to) {

            $('.highlight.color, input[type="button"], input[type="submit"], a.button:not(.box-button)').css('background',to);
            $('body.home .pagination-container.alt ul li a, body.home .pagination-next-prev.alt ul li a, .pagination .current,.pagination ul li a,.pagination-next-prev ul li a, .box-item-text a.button').hover(
                function(){
                    var attr = $(this).attr('orgbackground');
                    if (typeof attr == 'undefined' || attr == false) {
                        var orgbg = $(this).css('background');
                    }
                    $(this).attr('orgbackground', orgbg).css('background', to);
                }, function(){
                    var bg = $(this).attr('orgbackground');
                    $(this).css('background', bg);
                });


            $('.map-box-icon,.tabs-nav li.active a,.ui-accordion .ui-accordion-header-active, #wp-calendar tbody, td#today, .current-page, .owl-carousel .item:hover .title, .newsletter-btn, #backtotop_wpv a').css('background-color',to);
            $('.infoBox-close,#jPanelMenu-menu #current,#jPanelMenu-menu li a,.tabs-nav li.active a,.ui-accordion .ui-accordion-header,.trigger a, .ui-accordion .ui-accordion-header-active, .tagcloud a, .footer-widget .tagcloud a,.post-navigation .nav-links a, .owl-theme .owl-controls .owl-buttons div,  #mapnav-buttons a,.viewer .close:hover').hover(
                function(){
                    var attr = $(this).attr('orgbackground');
                    if (typeof attr == 'undefined' || attr == false) {
                        var orgbg = $(this).css('background-color');
                    }
                    $(this).attr('orgbackground', orgbg).css('background-color', to);
                }, function(){
                    var bg = $(this).attr('orgbackground');
                    $(this).css('background-color', bg);
                });

            $('#logo h1 a:visited, #logo h2 a:visited,.list-1.color li:before,.list-2.color li:before,.list-3.color li:before,.list-4.color li:before,.widget_categories li:before,.widget-out-title_categories li:before,.widget_archive li:before,.widget-out-title_archive li:before,.widget_recent_entries li:before,.widget-out-title_recent_entries li:before,.categories li:before,.widget_meta li:before,.widget_nav_menu li:before,.widget_pages li:before, .widget_rss li:before,#wp-calendar tbody td a,.author-box .title,#not-found i,.post-content span.author a,.post-series-links li:before,.post-series-links li a').css('color',to);
            $('.widget_categories li a,.widget-out-title_categories li a,.widget_archive li a,.widget-out-title_archive li a,.widget_recent_entries li a,.widget-out-title_recent_entries li a,.categories li a,.widget_meta li a,.widget_rss li a,.widget_nav_menu li a,.widget_pages li a,#wp-calendar tfoot td#next a,#wp-calendar tfoot td#prev a, .recentcomments a,#breadcrumbs ul li a,.author-box .contact a,.post-content span a,.meta span a,.author-box a').hover(
            function(){
                var attr = $(this).attr('orgbackground');
                if (typeof attr == 'undefined' || attr == false) {
                    var orgbg = $(this).css('color');
                }
                $(this).attr('orgbackground', orgbg).css('color', to);
            }, function(){
                var bg = $(this).attr('orgbackground');
                $(this).css('color', bg);
            });

            $('#header .menu ul > li:hover > a,#header .menu ul ul,  #wp-calendar tbody, td#today, .fun-facts-container').css('border-color',to);
             $('#current,#header .menu ul li a,#wp-calendar tbody td').hover(
            function(){
                var attr = $(this).attr('orgbackground');
                if (typeof attr == 'undefined' || attr == false) {
                    var orgbg = $(this).css('border-color');
                }
                $(this).attr('orgbackground', orgbg).css('border-color', to);
            }, function(){
                var bg = $(this).attr('orgbackground');
                $(this).css('border-color', bg);
            });
 
        });
    });

    wp.customize('wpvoyager_tagline_switch',function( value ) {
      value.bind(function(to) {
          if(to === 'hide') { $('#blogdesc').hide(); } else { $('#blogdesc').show(); }
      });
    });

 

} )( jQuery )
</script>
<?php
}



function pp_generate_typo_css($typo){
    if($typo){
        $wpv_ot_default_fonts = array('arial','georgia','helvetica','palatino','tahoma','times','trebuchet','verdana');        
        $ot_google_fonts = get_theme_mod( 'ot_google_fonts', array() );
        foreach ($typo as  $key => $value) {
            if(isset($value) && !empty($value)) {
                if($key=='font-color') { $key = "color"; }
                if($key=='font-family') { 
                    if ( ! in_array( $value, $wpv_ot_default_fonts ) ) {
                        $value = $ot_google_fonts[$value]['family']; } 
                    }
                echo $key.":".$value.";";
                
            }
        }
    }
}
function pp_generate_bg_css($typo){
    if($typo){
        foreach ($typo as  $key => $value) {
            if(isset($value) && !empty($value)) {
                if($key=='background-image') $value = "url('".$value."')";
                echo $key.":".$value.";";
            }
        }
    }
}

function mobile_menu_css(){
    $breakpoint = ot_get_option('pp_menu_breakpoint','767');
    $bodytypo = ot_get_option( 'wpvoyager_body_font');
    $menutypo = ot_get_option( 'wpvoyager_menu_font');
    $logotypo = ot_get_option( 'wpvoyager_logo_font');
    $headerstypo = ot_get_option( 'wpvoyager_headers_font');

    $ot_google_fonts = get_theme_mod( 'ot_google_fonts', array() );
 
    if(isset($bodytypo['font-family'])) {
        $tempfamily = $bodytypo['font-family'];
        $wpv_ot_default_fonts = array('arial','georgia','helvetica','palatino','tahoma','times','trebuchet','verdana');
        if ( in_array( $tempfamily, $wpv_ot_default_fonts ) ) {
            $family = $tempfamily;
        } else {
            $ot_google_fonts = get_theme_mod( 'ot_google_fonts', array() );
            $family = $ot_google_fonts[$tempfamily]['family'];  
        }
    } else {
        $family = '';
    }
?>
<style type="text/css">

<?php if($family){ ?>
    body,
    input[type="text"],
    input[type="password"],
    input[type="email"],
    textarea,
    select,
    input.newsletter,
    .map-box p,select#archives-dropdown--1, select#cat, select#categories-dropdown--1,
    .widget_search input.search-field, .widget_text select,.map-box p {
        font-family: "<?php echo $family; ?>";
    }
<?php } ?>
    body { <?php pp_generate_typo_css($bodytypo); ?> }
    h1, h2, h3, h4, h5, h6  { <?php pp_generate_typo_css($headerstypo); ?> }
    #logo h1 a, #logo h2 a { <?php pp_generate_typo_css($logotypo); ?> }
   #header .menu ul > li > a, #header .menu ul li a {  <?php pp_generate_typo_css($menutypo); ?>  }
   
    </style>
  <?php
}
add_action('wp_head', 'mobile_menu_css');

add_action('wp_head', 'custom_stylesheet_content');

function custom_stylesheet_content() {
$ltopmar = ot_get_option( 'pp_logo_top_margin' );
$lbotmar = ot_get_option( 'pp_logo_bottom_margin' );
$taglinemar = ot_get_option( 'pp_tagline_margin' );
$custom_main_color = get_theme_mod('wpvoyager_main_color','#3685cf');

$custom_rgb = purehex2RGB($custom_main_color);
if($custom_rgb) {
    $red = $custom_rgb['red'];
    $green = $custom_rgb['green'];
    $blue = $custom_rgb['blue'];
}
?>
<style type="text/css">
    .boxed #logo,#logo {
        <?php if ( isset( $ltopmar[0] ) && $ltopmar[1] ) { echo 'margin-top:'.$ltopmar[0].$ltopmar[1].';'; } ?>
        <?php if ( isset( $lbotmar[0] ) && $lbotmar[1] ) { echo 'margin-bottom:'.$lbotmar[0].$lbotmar[1].';'; } ?>
    }
    #blogdesc {
        <?php if ( isset( $ltopmar[0] ) && $ltopmar[1] ) { echo 'margin-top:'.$taglinemar[0].$taglinemar[1].';'; } ?>
    }

.infoBox-close:hover, .map-box-icon, #jPanelMenu-menu #current,#jPanelMenu-menu li a:hover, .tabs-nav li.active a:hover,.tabs-nav li.active a,.ui-accordion .ui-accordion-header:hover,.trigger a:hover, .ui-accordion .ui-accordion-header-active:hover,.ui-accordion .ui-accordion-header-active,#wp-calendar tbody, td#today, .tagcloud a:hover, .footer-widget .tagcloud a:hover, .current-page, .post-navigation .nav-links a:hover,.owl-carousel .item:hover .title, .owl-theme .owl-controls .owl-buttons div:hover, #mapnav-buttons a:hover, .newsletter-btn, .viewer .close:hover, #backtotop_wpv a { background-color: <?php echo esc_html($custom_main_color); ?>; }

.highlight.color, input[type="button"], input[type="submit"], a.button, body.home .pagination-container.alt ul li a:hover,
body.home .pagination-next-prev.alt ul li a:hover, .pagination .current,.pagination ul li a:hover,.pagination-next-prev ul li a:hover, .box-item-text a.button:hover 
{ background: <?php echo esc_html($custom_main_color); ?>; }

#current,#header .menu ul li a:hover,#header .menu ul > li:hover > a,#header .menu ul ul, #wp-calendar tbody td:hover, #wp-calendar tbody, td#today, .fun-facts-container {
    border-color: <?php echo esc_html($custom_main_color); ?>;    
}
.list-1.color li:before,
.list-2.color li:before,
.list-3.color li:before,
.list-4.color li:before,
.widget_categories li a:hover,
.widget-out-title_categories li a:hover,
.widget_archive li a:hover,
.widget-out-title_archive li a:hover,
.widget_recent_entries li a:hover,
.widget-out-title_recent_entries li a:hover,
.categories li a:hover,
.widget_meta li a:hover,
.widget_nav_menu li a:hover,
.widget_pages li a:hover,
.widget_categories li:before,
.widget-out-title_categories li:before,
.widget_archive li:before,
.widget-out-title_archive li:before,
.widget_recent_entries li:before,
.widget-out-title_recent_entries li:before,
.categories li:before,
.widget_meta li:before,
.widget_nav_menu li:before,
.widget_pages li:before, 
#wp-calendar tfoot td#next a:hover,#wp-calendar tfoot td#prev a:hover, 
.widget_rss li:before,
#wp-calendar tbody td a,
.widget_rss li a:hover,
.author-box .title,
.recentcomments a:hover,
#breadcrumbs ul li a:hover,
.author-box .contact a:hover,
#not-found i,
.post-content span a:hover,
.post-content span.author a,
.meta span a:hover,
.post-series-links li:before,
.post-series-links li a,
.author-box a:hover
{ color: <?php echo esc_html($custom_main_color); ?>; }
<?php echo esc_html(ot_get_option( 'pp_custom_css' )); ?>

</style>
<?php

}   //eof custom_stylesheet_content ?>