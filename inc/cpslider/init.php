<?php
/*
Plugin Name: cpSlider
Plugin URI: http://www.purethemes.net/
Description: WYSIWYG fullscreen cpgraphy slider!
Version: 1.0
Author: purethemes.net
*/

/**
*
*/
require "inc/class.slide.php";
require "inc/class.sliders.php";

class CP_Slider
{

    function __construct()
    {

      //  add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
        add_action( 'admin_menu', array( $this, 'add_menus' ) );
        
    }

    public function register_plugin_styles() {
        wp_register_style( 'cpslider-css', get_template_directory_uri().'/inc/cpslider/css/cpslider.css' );
        wp_enqueue_style( 'cpslider-css' );
    }

    public function register_plugin_scripts() {

        wp_register_script( 'cpslider-js',  get_template_directory_uri().'/inc/cpslider/js/cpslider.js' );
        $slider = '';
        if ( is_page() || is_home() || is_page_template('template-sidebar.php' ) ) {
            if(ot_get_option('pp_front_page_setup') == 'slider') {
                $slider = ot_get_option('pp_slider_select');
            } else if(is_page_template('template-sidebar.php' ) ||  is_page() ) {
                global $post;
                $slider = get_post_meta($post->ID, 'pp_page_slider', true);
            }   

            if(!empty($slider)){
            $sliderarray = get_option( 'cp_slider_'.$slider );
            
            if($sliderarray['autoPlay'] == "false") { $autoPlay = false; }  else { $autoPlay = $sliderarray['autoPlay']; }
           
            if(isset($sliderarray['paginationSpeed'])) {
               $paginationSpeed= $sliderarray['paginationSpeed'];
            } else {
                $paginationSpeed = 800;
            }
            if(isset($sliderarray['slideSpeed'])) {
               $slideSpeed= $sliderarray['slideSpeed'];
            } else {
                $slideSpeed = 200;
            }
            wp_enqueue_script( 'cpslider-js' );
            wp_localize_script('cpslider-js', 'wpvslidervars', array(
                        'autoPlay' => $autoPlay,
                        'paginationSpeed' => $paginationSpeed,
                        'slideSpeed' => $slideSpeed,
                    )
                );
            }
        }
    }

    public function add_menus() {

        if ( array_key_exists( 'page', $_GET ) && 'cp-slider' == $_GET['page'] )  {

            wp_register_style( 'cpslider-admin-css', get_template_directory_uri() . '/inc/cpslider/css/cpslider.admin.css' );
            wp_enqueue_style( 'cpslider-admin-css' );


            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'wp-ajax-response' );

            wp_enqueue_media();
            wp_enqueue_script( 'postbox' );

            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-droppable' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_style("wp-jquery-ui-dialog");

            wp_register_script(
                'cp-slider-js',                                         /* handle */
                get_template_directory_uri().'/inc/cpslider/js/cpslider.admin.js',   /* src */
                array(
                    'jquery', 'jquery-ui-draggable', 'jquery-ui-droppable',
                    'jquery-ui-sortable'
                    ),                                                          /* deps */
                date("YmdHis", @filemtime(  get_template_directory_uri().'/inc/cpslider/js/cpslider.admin.js'  ) ),            /* ver */
                true                                                        /* in_footer */
                );
            wp_enqueue_script( 'cp-slider-js' );
            wp_localize_script( 'cp-slider-js', 'CPVars', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'getthumb' => wp_create_nonce( 'cp_getthumb_ajax_nonce' ),
                ));
        }

        /* Top-level menu page */
        add_menu_page(

            __( 'WPV Slider', 'purepress' ),                                 /* title of options page */
            __( 'WPV Slider', 'purepress' ),                                 /* title of options menu item */
            'edit_theme_options',                               /* permissions level */
            'cp-slider',                                                 /* menu slug */
            array( $this, 'print_slide_groups_page' ),             /* callback to print the page to output */
            'dashicons-slides',    /* icon file */
            null                                                            /* menu position number */
            );

        /* First child, 'Slide Groups' */


    }

    /***********    // !Print functions for each page  ***********/

    public static function print_slide_groups_page() {

        require( dirname( __FILE__ ) . '/inc/admin.sliders.php' );

    }

    public static function print_slides_page() {

        require( dirname( __FILE__ ) . '/inc/admin.slides.php' );

    }

 
    function get_cpslide_thumb($id) {
        //check_ajax_referer('custom_nonce');
        $image = wp_get_attachment_image_src( $id );
        return $image[0];
    }

    public function getCPslider($name) {
        $slider = get_option( 'cp_slider_'.$name );
       
        if(!empty($slider)) {
            switch ($slider['slidertype']) {
                case 'postssel':
                    $args= array(
                        'post_type'  => array('post','page','post_series'),
                        'post__in' => $slider['posts'],
                        'meta_key'    => '_thumbnail_id',
                        'post__not_in' => get_option( 'sticky_posts' ),
                        'orderby' => 'post__in'
                        );
                break;

                case 'posts':
                    if($slider['posts_type'] == 'random') {
                        $orderby = 'rand';
                    } else {
                        $orderby = $slider['posts_order'];
                    }

                    $args= array(
                        'posts_per_page'   => $slider['posts_number'],
                        'orderby' => $orderby,
                        'meta_key'    => '_thumbnail_id',
                        'post__not_in' => get_option( 'sticky_posts' ),
                        'category__in' => $slider['cats'],
                        'tag__and' => $slider['tags']
                        );

                break;

                default:
                # code...
                break;
            }

            ?>
         
<!-- Owl Carousel
================================================== -->
<div class="owl-carousel">
 <?php
    $cpslider_query = new WP_Query($args);
    while ($cpslider_query->have_posts()) : $cpslider_query->the_post();
        $selected_image_id = get_post_meta($cpslider_query->post->ID, 'pp_post_slider_img', true);
        $selected_image   = wp_get_attachment_image_src( $selected_image_id, 'carousel' );
        $thumb = wp_get_attachment_image_src ( get_post_thumbnail_id (), 'carousel' ); ?>
        
        <a href="<?php the_permalink(); ?>" class="item">
            
            <?php 
            if(empty($selected_image[0])){ ?>
                <img src="<?php echo esc_url($thumb[0]); ?>" alt="" />
            <?php } else { ?>
                <img src="<?php echo esc_url($selected_image[0]); ?>" alt="" />
            <?php } ?>

            <div class="title">
                <h2><?php the_title()?></h2>
                <span><?php echo get_the_date(); ?>, <?php esc_html_e('by','wpvoyagger'); ?> <?php the_author(); ?></span>
            </div>

            <span class="owl-item-frame"></span>

        </a>


    <?php endwhile; wp_reset_query();?>
</div>


        <?php
        }
    }
}
?>