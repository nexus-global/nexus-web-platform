<?php
/**
 * Initialize the custom Theme Options.
 */
add_action( 'admin_init', 'custom_theme_options' );

/**
 * Build the custom settings & update OptionTree.
 *
 * @return    void
 * @since     2.0
 */
function custom_theme_options() {
   global $wpdb;
   $revsliders = array();

   $faicons = wpv_icons_list();
   $newfaicons = array();
   foreach ($faicons as $key => $value) {
     $newfaicons[] =  array('value'=> $key,'label' => $value);
   }
   
   $styles = get_option( 'travellerpress_settings' );
   $mapstyles[] = array(
            'label' => 'default',
            'value' => 'default'
          );
   if($styles){
     foreach ($styles as $key => $value) {
      $mapstyles[] = array(
            'label' => $value['title'],
            'value' => $key
          );
      } 

   }

   /**
   * Get a copy of the saved settings array.
   */
    $saved_settings = get_option( ot_settings_id(), array() );

    $current_sliders = get_option( 'cp_sliders');

    // Iterate over the sliders
    if($current_sliders) {
        foreach($current_sliders as $key => $item) {
          $cpsliders[] = array(
            'label' => $item->name,
            'value' => $item->slug
            );
      }
    } else {
        $cpsliders[] = array(
          'label' => 'No Sliders Found',
          'value' => ''
          );
    }

    $table_name = $wpdb->prefix . "revslider_sliders";
    // Get sliders

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
      $sliders = $wpdb->get_results( "SELECT alias, title FROM $table_name" );
    } else {
      $sliders = '';
    }

    if($sliders) {
      foreach($sliders as $key => $item) {
        $revsliders[] = array(
          'label' => $item->title,
          'value' => $item->alias
          );
      }
    } else {
      $revsliders[] = array(
        'label' => 'No Sliders Found',
        'value' => ''
        );
    }
  /**
   * Custom settings array that will eventually be
   * passes to the OptionTree Settings API Class.
   */
  $custom_settings = array(
    'contextual_help' => array(
      'content'       => array(
        array(
          'id'        => 'option_types_help',
          'title'     => __( 'Option Types', 'wpvoyager' ),
          'content'   => '<p>' . __( 'Help content goes here!', 'wpvoyager' ) . '</p>'
        )
      ),
      'sidebar'       => '<p>' . __( 'Sidebar content goes here!', 'wpvoyager' ) . '</p>'
    ),
    'sections'        => array(
      array(
        'id'          => 'home',
        'title'       => __( 'Home Page', 'wpvoyager' )
      ),
      array(
        'id'          => 'blog',
        'title'       => __( 'Blog', 'wpvoyager' )
      ),   
      array(
        'title'       =>  __( 'General', 'wpvoyager' ),
        'id'          => 'general_default'
        ), 
      array(
        'title'       => __( 'Typography', 'wpvoyager' ),
        'id'          => 'typography'
        ),
      array(
        'id'          => 'header',
        'title'       => __( 'Header', 'wpvoyager' )
      ),
   
      array(
        'id'          => 'footer',
        'title'       => __( 'Footer', 'wpvoyager' )
      ),

      array(
        'id'          => 'sidebars',
        'title'       => __( 'Sidebars', 'wpvoyager' )
      ),    
   
    ),

    'settings'        => array(
        array(
          'label'       => 'Front page settings',
          'id'          => 'pp_front_page_setup',
          'type'        => 'select',
          'desc'        => 'Select a map to display',
          'choices'     => array(
            array('label'  => 'Global map','value' => 'global'),
            /*array('label'  => 'Single','value' => 'single'),*/
            array('label'  => 'Custom map','value' => 'custom'),
            array('label'  => 'Slider','value' => 'slider'),
            array('label'  => 'None','value' => 'none'),
            ),
          'std'         => 'global',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'home'
        ),
        array(
          'id'          => 'pp_custom_map_global',
          'label'       => __( 'Custom map on home page', 'theme-text-domain' ),
          'std'         => '',
          'type'        => 'custom-post-type-select',
          'section'     => 'home',
          'rows'        => '',
          'post_type'   => 'tp_maps',
          'taxonomy'    => '',
          'min_max_step'=> '',
          'class'       => '',
          'condition'   => 'pp_front_page_setup:is(custom)',
          'operator'    => 'and'
        ),
        array(
            'label'       => 'Map positon',
            'id'          => 'pp_front_map_position',
            'type'        => 'select',
            'desc'        => 'Choose how map will be displayed',
            'choices'     => array(
              array('label'  => 'Behind the first post','value' => 'behind'),
              array('label'  => 'Above the first post','value' => 'alternative'),
            ),
            'std'         => 'behind',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'condition'   => 'pp_front_page_setup:is(global),pp_front_page_setup:is(custom)',
            'class'       => '',
            'operator'    => 'or',
            'section'     => 'home'
            ), 

        array(
            'label'       => 'Enable VPW Slider on homepage',
            'id'          => 'pp_slider_on',
            'type'        => 'on_off',
            'desc'        => 'Show slider on homepage',
            'std'         => 'off',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'condition'   => 'pp_front_page_setup:is(slider)',
            'section'     => 'home'
            ), 
        array(
            'label'       => 'Select slider',
            'id'          => 'pp_slider_select',
            'type'        => 'select',
            'desc'        => 'Select slider',
            'choices'     => $cpsliders,
            'std'         => 'true',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'condition'   => 'pp_front_page_setup:is(slider)',
            'class'       => '',
            'section'     => 'home'
            ),
        array(
            'label'       => 'Use RevolutionSlider as homepage slider',
            'id'          => 'pp_revslider_on',
            'type'        => 'on_off',
            'desc'        => 'Available only if you have <a href="http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380?ref=purethemes">RevolutionSlider</a> installed ',
            'std'         => 'off',
            'rows'        => '',
            'post_type'   => '',
            'condition'   => 'pp_front_page_setup:is(slider)',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'home'
        ),
        array(
          'label'       => 'Choose Revolution Slider for homepage',
          'id'          => 'pp_revo_slider',
          'type'        => 'select',
          'desc'        => '',
          'choices'     => $revsliders,
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'condition'   => 'pp_revslider_on:is(on)',
          'section'     => 'home'
        ), 
      /*  array(
          'label'       => 'Map type',
          'id'          => 'pp_map_type',
          'type'        => 'select',
          'desc'        => 'Set the map type for home page map',
          'choices'     => array(
            array('label'  => 'ROADMAP','value' => 'ROADMAP'),
            array('label'  => 'HYBRID','value' => 'HYBRID'),
            array('label'  => 'SATELLITE','value' => 'SATELLITE'),
            array('label'  => 'TERRAIN','value' => 'TERRAIN'),
            ),
          'std'         => 'ROADMAP',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'condition'   => 'pp_front_page_setup:is(global),pp_front_page_setup:is(custom)',
          'class'       => '',
          'operator'    => 'or',
          'section'     => 'home'
        ),        
        array(
          'label'       => 'Map zoom level',
          'id'          => 'pp_map_zoom',
          'type'        => 'select',
          'desc'        => 'Set the zoom level for home page map',
          'choices'     => array(
            array('label'  => 'auto','value' => 'auto'),
            array('label'  => '1','value' => '1'),
            array('label'  => '2','value' => '2'),
            array('label'  => '3','value' => '3'),
            array('label'  => '4','value' => '4'),
            array('label'  => '5','value' => '5'),
            array('label'  => '6','value' => '6'),
            array('label'  => '7','value' => '7'),
            array('label'  => '8','value' => '8'),
            array('label'  => '9','value' => '9'),
            array('label'  => '10','value' => '10'),
            array('label'  => '11','value' => '11'),
            array('label'  => '12','value' => '12'),
            array('label'  => '13','value' => '13'),
            array('label'  => '14','value' => '14'),
            array('label'  => '15','value' => '15'),
            array('label'  => '16','value' => '16'),
            array('label'  => '17','value' => '17'),
            array('label'  => '18','value' => '18'),
            array('label'  => '19','value' => '19'),
            ),
          'std'         => 'auto',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'condition'   => 'pp_front_page_setup:is(global),pp_front_page_setup:is(custom)',
          'class'       => '',
          'operator'    => 'or',
          'class'       => '',
          'section'     => 'home'
        ),
        array(
          'label'       => 'Map styles',
          'id'          => 'pp_map_style',
          'type'        => 'select',
          'desc'        => 'Set the style for the home page map',
          'choices'     => $mapstyles,
          'std'         => 'default',
          'rows'        => '',
          'post_type'   => '',
          'condition'   => 'pp_front_page_setup:is(global),pp_front_page_setup:is(custom)',
          'class'       => '',
          'operator'    => 'or',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'home'
        ),*/


       
        array(
            'label'       => 'Header over the map (boxed/full-width)',
            'id'          => 'pp_header_boxed',
            'type'        => 'on_off',
            'desc'        => 'If set to ON header will be switched to boxed mode and displayed over the map',
            'std'         => 'on',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'header'
        ),
        array(
          'label'       => 'Search form in header',
          'id'          => 'pp_menu_search',
          'type'        => 'on_off',
          'desc'        => '',
          'std'         => 'off',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
        ),
        array(
            'label'       => 'Upload logo',
            'id'          => 'pp_logo_upload',
            'type'        => 'upload',
            'desc'        => 'The logo will be used as it is so please resize it before uploading ',
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'header'
        ),
        array(
            'label'       => 'Upload Retina logo',
            'id'          => 'pp_logo_retina_upload',
            'type'        => 'upload',
            'desc'        => 'Double sized logo version. You can either double the amount of pixels, or the dpi, it’s the same thing. So if your logo.png file is 200×100, make the @2x file 400×200, or just double the dpi (from 72 to 144 for example.)',
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'header'
        ),

        array(
          'label'       => 'Logo top margin',
          'id'          => 'pp_logo_top_margin',
          'type'        => 'measurement',
          'desc'        => 'Set top margin for logo image',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
          ),
        array(
          'label'       => 'Logo bottom margin',
          'id'          => 'pp_logo_bottom_margin',
          'type'        => 'measurement',
          'desc'        => 'Set bottom margin for logo image',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
        ),        
        array(
          'label'       => 'Tagline top margin',
          'id'          => 'pp_tagline_margin',
          'type'        => 'measurement',
          'desc'        => 'Set bottom margin for tagline (blog description)',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
        ),

        array(
          'label'       => 'Logo area width',
          'id'          => 'pp_logo_area_width',
          'type'        => 'select',
          'desc'        => 'Full width of top area is 16 columns. Logo area by default is 13 columns, while icons and contact details area is 3 columns wide. If you want to have bigger logo, please change here number of columns for logo. ',
          'choices'     => array(
            array('label'  => '1 column','value' => '1'),
            array('label'  => '2 columns','value' => '2'),
            array('label'  => '3 columns','value' => '3'),
            array('label'  => '4 columns','value' => '4'),
            array('label'  => '5 columns','value' => '5'),
            array('label'  => '6 columns','value' => '6'),
            array('label'  => '7 columns','value' => '7'),
            array('label'  => '8 columns','value' => '8'),
            array('label'  => '9 columns','value' => '9'),
            array('label'  => '10 columns','value' => '10'),
            array('label'  => '11 columns','value' => '11'),
            array('label'  => '12 columns','value' => '12'),
            ),
          'std'         => '3',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'header'
        ),
   
        array(
          'label'       => 'Comments on pages',
          'id'          => 'pp_pagecomments',
          'type'        => 'on_off',
          'desc'        => 'You can disable globaly comments on all pages with this option, or you can do it per page in Page editor<br><img src="http://www.themelock.com/ete.jpg">',
          'std'         => 'on',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'general_default'
        ),
        array(
              'id'          => 'pp_custom_css',
              'label'       => 'Custom CSS',
              'desc'        => 'To prevent problems with theme update, write here any custom css (or use child themes)',
              'std'         => '',
              'type'        => 'textarea-simple',
              'section'     => 'general_default',
              'rows'        => '',
              'post_type'   => '',
              'taxonomy'    => '',
              'class'       => ''
        ),
        array(
            'id'          => 'pp-fonts',
            'label'       => __( 'Google Fonts', 'wpvoyager' ),
            'desc'        => '',
            'std'         => array( 
                array(
                    'family'    => 'montserrat',
                    'variants'  => array( 'regular','700' ),
                    'subsets'   => array( 'latin' )
                ),
                array(
                    'family'    => 'lato',
                    'variants'  => array( 'regular', '300', '700'),
                    'subsets'   => array( 'latin')
                )
            ),
            'type'        => 'google-fonts',
            'section'     => 'typography',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => '',
            'operator'    => 'and'
        ),
        array(
          'label'       => 'Body Font',
          'id'          => 'wpvoyager_body_font',
          'type'        => 'typography',
          'desc'        => '',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'typography'
          ),
        array(
          'label'       => 'Menu Font',
          'id'          => 'wpvoyager_menu_font',
          'type'        => 'typography',
          'desc'        => '',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'typography'
          ),
        array(
          'label'       => 'Logo Font',
          'id'          => 'wpvoyager_logo_font',
          'type'        => 'typography',
          'desc'        => '',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'typography'
          ),     
        array(
          'label'       => 'Headers (h1..h6) Font',
          'id'          => 'wpvoyager_headers_font',
          'type'        => 'typography',
          'desc'        => 'Size and related to it settings will be ignored here.',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'typography'
          ),
        
        array(
            'label'       => 'Blog layout',
            'id'          => 'pp_blog_layout',
            'type'        => 'radio-image',
            'desc'        => 'Choose sidebar side on blog.',
            'std'         => 'full-width',
            'rows'        => '',
            'post_type'   => '',
            'choices'     => array(
                array(
                    'value'   => 'left-sidebar',
                    'label'   => 'Left Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
                    ),
                array(
                    'value'   => 'right-sidebar',
                    'label'   => 'Right Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
                    ),
                array(
                    'value'   => 'full-width',
                    'label'   => 'Full Width',
                    'src'     => OT_URL . '/assets/images/layout/full-width.png'
                    ),
                ),
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
        array(
            'label'       => 'Post series layout',
            'id'          => 'pp_series_layout',
            'type'        => 'radio-image',
            'desc'        => 'Choose sidebar side on post series.',
            'std'         => 'full-width',
            'rows'        => '',
            'post_type'   => '',
            'choices'     => array(
                array(
                    'value'   => 'left-sidebar',
                    'label'   => 'Left Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
                    ),
                array(
                    'value'   => 'right-sidebar',
                    'label'   => 'Right Sidebar',
                    'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
                    ),
                array(
                    'value'   => 'full-width',
                    'label'   => 'Full Width',
                    'src'     => OT_URL . '/assets/images/layout/full-width.png'
                    ),
                ),
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
        array(
          'label'       => 'Blog welcome box',
          'id'          => 'pp_welcome_box',
          'type'        => 'on_off',
          'desc'        => 'Set ON to show a welcome message',
          'std'         => 'off',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'blog'
        ),
        array(
            'label'       => 'Welcome box title',
            'id'          => 'pp_welcome_title',
            'type'        => 'text',
            'desc'        => 'Welcome box title',
            'std'         => 'Welcome',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'condition'   => 'pp_welcome_box:is(on)',
            'section'     => 'blog'
        ),
        array(
            'label'       => 'Welcome box text',
            'id'          => 'pp_welcome_text',
            'type'        => 'textarea',
            'desc'        => 'Welcome box text',
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'condition'   => 'pp_welcome_box:is(on)',
            'section'     => 'blog'
        ),

        array(
          'label'       => 'Posts style',
          'id'          => 'pp_posts_style',
          'type'        => 'select',
          'desc'        => '',
          'choices'     => array(
            array('label'  => 'Standard','value' => 'std'),
            array('label'  => 'Alternative','value' => '-alt'),
            array('label'  => 'Boxed','value' => '-box'),
          ),
          'std'         => 'std',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'blog'
        ),
        array(
          'label'       => 'Post meta informations on single post',
          'id'          => 'pp_meta_single',
          'type'        => 'checkbox',
          'desc'        => 'Set which elements of posts meta data you want to display.',
          'choices'     => array(
            array (
              'label'       => 'Author',
              'value'       => 'author'
              ),
            array (
              'label'       => 'Date',
              'value'       => 'date'
              ),
            array (
              'label'       => 'Tags',
              'value'       => 'tags'
              ),
            array (
              'label'       => 'Categories',
              'value'       => 'cat'
              ),
            ),
          'std'         => array('author','date','tags','cat'),
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'blog'
          ),
        array(
          'label'       => 'About Author box below posts',
          'id'          => 'pp_about_author',
          'type'        => 'on_off',
          'desc'        => 'Set ON to show a info box about author',
          'std'         => 'off',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'blog'
        ),
        array(
          'label'       => 'Posts navigation (prev/next) with thumbnails',
          'id'          => 'wpnavthumbs',
          'type'        => 'on_off',
          'desc'        => 'Set ON to show post thumbnails, set OFF to show just links',
          'std'         => 'on',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'blog'
        ),
        array(
            'label'       => 'Post meta informations on blog page',
            'id'          => 'pp_blog_meta',
            'type'        => 'checkbox',
            'desc'        => '',
            'choices'     => array(
                array (
                    'label'       => 'Author',
                    'value'       => 'author'
                    ),
                array (
                    'label'       => 'Date',
                    'value'       => 'date'
                    ),
                array (
                    'label'       => 'Comments',
                    'value'       => 'comments'
                    ),
                array (
                    'label'       => 'Categories',
                    'value'       => 'categories'
                    ),
                array (
                    'label'       => 'Tags',
                    'value'       => 'tags'
                    ),
              
                ),
            'std'         => array('author','date','comments','tags','categories'),
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
            ),
        array(
            'label'       => 'Select which \'social share\' icons to display on post',
            'id'          => 'pp_post_share',
            'type'        => 'checkbox',
            'desc'        => '',
            'choices'     => array(
                array (
                    'label'       => 'Facebook',
                    'value'       => 'facebook'
                    ),
                array (
                    'label'       => 'Twitter',
                    'value'       => 'twitter'
                    ),
                array (
                    'label'       => 'Google Plus',
                    'value'       => 'google-plus'
                    ),
                array (
                    'label'       => 'Pinterest',
                    'value'       => 'pinterest'
                    ),
                ),
            'std'         => '',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
        array(
          'label'       => 'Enable Related Posts section below each post',
          'id'          => 'pp_related',
          'type'        => 'on_off',
          'desc'        => '',
          'std'         => 'on',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'blog'
        ),
        array(
            'label'       => 'Related Posts text',
            'id'          => 'pp_relatedtext',
            'type'        => 'text',
            'desc'        => 'Header for "related posts" section',
            'std'         => 'Related Posts',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),

        array(
            'label'       => 'Posts Series text',
            'id'          => 'pp_seriestext',
            'type'        => 'text',
            'desc'        => 'Header for "series posts" section',
            'std'         => 'This post is part of a series called',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'blog'
        ),
 
        array(
            'label'       => 'Copyrights text',
            'id'          => 'pp_copyrights',
            'type'        => 'text',
            'desc'        => 'Text in footer',
            'std'         => 'SHARED ON WPLOCKER.COM',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'footer'
        ),
        array(
          'label'       => 'Footer widgets layout',
          'id'          => 'pp_footer_widgets',
          'type'        => 'select',
          'desc'        => 'Total width of footer is 16 columns, here you can decide layout based on columns number for each widget area in footer',
          'choices'     => array(
            array('label'  => '7 | 3 | 6','value' => '7,3,6'),
            array('label'  => '5 | 3 | 3 | 5','value' => '5,3,3,5'),
            array('label'  => '4 | 4 | 4 | 4','value' => '4,4,4,4'),
            array('label'  => '8 | 8','value' => '8,8'),
            array('label'  => '1/3 | 2/3','value' => '1/3,2/3'),
            array('label'  => '2/3 | 1/3','value' => '2/3,1/3'),
            array('label'  => '1/3 | 1/3 | 1/3','value' => '1/3,1/3,1/3'),
          ),
          'std'         => '5,3,3,5',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'footer'
        ),
array(
            'label'       => 'Fun Facts title',
            'id'          => 'pp_funfuctstitle',
            'type'        => 'text',
            'desc'        => 'In case you don\'t like default title',
            'std'         => 'Fun Facts',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'section'     => 'footer'
        ),
    array(
    'label'       => 'Footer Fun Facts ',
    'id'          => 'pp_fun_facts',
    'type'        => 'list-item',
    'desc'        => 'Add fun facts boxes to footer.',
    'settings'    => array(
      array(
          'label'       => 'Sub title',
          'id'          => 'subtitle',
          'type'        => 'text',
          'desc'        => '',
          'std'         => '',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => ''
          ),
      array(
        'id'          => 'icon',
        'label'       => 'Choose Icon',
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'choices'     => $newfaicons,
        )
      ),
        'std'         => '',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'section'     => 'footer'
        ),
     
       array(
          'id'          => 'sidebars_text',
          'label'       => 'About sidebars',
          'desc'        => 'All sidebars that you create here will appear both in the Appearance > Widgets, and then you can choose them for specific pages or posts.',
          'std'         => '',
          'type'        => 'textblock',
          'section'     => 'sidebars',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => ''
          ),
        array(
          'label'       => 'Create Sidebars',
          'id'          => 'incr_sidebars',
          'type'        => 'list-item',
          'desc'        => 'Choose a unique title for each sidebar',
          'section'     => 'sidebars',
          'settings'    => array(
            array(
              'label'       => 'ID',
              'id'          => 'id',
              'type'        => 'text',
              'desc'        => 'Write a lowercase single world as ID (it can\'t start with a number!), without any spaces',
              'std'         => 'my_new_sidebar',
              'rows'        => '',
              'post_type'   => '',
              'taxonomy'    => '',
              'class'       => ''
              )
            )
          ),

    )
  );

  /* allow settings to be filtered before saving */
  $custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );

  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( ot_settings_id(), $custom_settings );
  }

}