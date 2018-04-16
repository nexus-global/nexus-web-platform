<?php
/**
 * Initialize the meta boxes.
 */
add_action( 'admin_init', '_custom_meta_boxes' );

/**
 * Meta Boxes demo code.
 *
 * You can find all the available option types
 * in demo-theme-options.php.
 *
 * @return    void
 *
 * @access    private
 * @since     2.0
 */
function _custom_meta_boxes() {

  /**
   * Create a custom meta boxes array that we pass to
   * the OptionTree Meta Box API Class.
   */
  $meta_box_layout = array(
    'id'        => 'pp_metabox_sidebar',
    'title'     => __('Layout','wpvoyager'),
    'desc'      => __('You can choose a sidebar from the list below. Sidebars can be created in the Theme Options and configured in the Appearance -> Widgets.','wpvoyager'),
    'pages'     => array( 'post' ),
    'context'   => 'normal',
    'priority'  => 'high',
    'fields'    =>   array(
      array(
        'id'          => 'pp_sidebar_layout',
        'label'       => __('Layout','wpvoyager'),
        'desc'        => '',
        'std'         => 'left-sidebar',
        'type'        => 'radio_image',
        'class'       => '',
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
        ),
      array(
        'id'          => 'pp_sidebar_set',
        'label'       => __('Sidebar','wpvoyager'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'sidebar-select',
        'class'       => '',
        ),
      array(
        'id'          => 'pp_post_type',
        'label'       => __('Post lead section type','wpvoyager'),
        'desc'        => '',
        'std'         => 'map',
        'type'        => 'select',
        'class'       => '',
        'choices'     => array(
          array(
            'value'   => 'map',
            'label'   => __('Map','wpvoyager')
            ),
          array(
            'value'   => 'photo',
            'label'   => __('Full width photo','wpvoyager'),
            ),          
          array(
            'value'   => 'standard',
            'label'   => __('Simple header','wpvoyager'),
            ),
          ),
        ),
      array(
        'id'          => 'pp_post_slider_img',
        'label'       => 'Post lead full screen image',
        'desc'        => 'For best visual effect it should  be 1920px x 590px.',
        'std'         => '',
        'type'        => 'upload',
        'class'       => 'ot-upload-attachment-id',
        "condition"   => 'pp_post_type:is(photo)'
      ),
     array(
      'id'          => 'pp_parallax_color',
      'label'       => 'Overlay color',
      'desc'        => '',
      'std'         => '#000000',
      'type'        => 'colorpicker',
      'class'       => '',
      "condition"   => 'pp_post_type:is(photo)'
      ),
     array(
      'id'          => 'pp_parallax_opacity',
      'label'       => 'Overlay opacity',
      'desc'        => '',
      'std'         => '0.35',
      'class'       => '',
      'type'        => 'numeric-slider',
      'min_max_step'=> '0,1,0.01',
      "condition"   => 'pp_post_type:is(photo)'
      ),
      )
    );

$meta_box_layout_page = array(
  'id'        => 'pp_metabox_sidebar',
  'title'     => __('Layout','wpvoyager'),
  'desc'      => __('You can choose a sidebar from the list below. Sidebars can be created in the Theme Options and configured in the Appearance -> Widgets.','wpvoyager'),
  'pages'     => array( 'page' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_sidebar_layout',
      'label'       => __('Layout','wpvoyager'),
      'desc'        => '',
      'std'         => 'full-width',
      'type'        => 'radio_image',
      'class'       => '',
      'choices'     => array(
        array(
          'value'   => 'left-sidebar',
          'label'   => __('Left Sidebar','wpvoyager'),
          'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
          ),
        array(
          'value'   => 'right-sidebar',
          'label'   => __('Right Sidebar','wpvoyager'),
          'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
          ),
        array(
          'value'   => 'full-width',
          'label'   => __('Full Width (no sidebar)','wpvoyager'),
          'src'     => OT_URL . '/assets/images/layout/full-width.png'
          )
        ),
      ),
    array(
      'id'          => 'pp_sidebar_set',
      'label'       => 'Sidebar',
      'desc'        => '',
      'std'         => '',
      'type'        => 'sidebar-select',
      'class'       => '',
      )
    )
);


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


$slider = array(
  'id'        => 'pp_metabox_cpslider',
  'title'     => 'Slider settings',
  'desc'      => 'If you want to use  Slider on this page, select page template "Slider Page" and choose here slider you want to display.',
  'pages'     => array( 'page' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_page_slider',
      'label'       => 'Slider',
      'desc'        => '',
      'std'         => '',
      'type'        => 'select',
      'choices'     => $cpsliders,
      'class'       => '',
      )
    )
  );


$parallax = array(
  'id'        => 'pp_metabox_parallax_bg',
  'title'     => 'Page Visual settings',
  'desc'      => '',
  'pages'     => array( 'page' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
     array(
      'id'          => 'pp_map_show',
      'label'       => 'Show map on page',
      'desc'        => 'Set to "On" to show map',
      'std'         => 'off',
      'type'        => 'on_off',
      'class'       => '',
    ),
    array(
        'id'          => 'pp_page_map_type',
        'label'       => __('Which map you want to display','wpvoyager'),
        'desc'        => '',
        'std'         => 'main',
        'type'        => 'select',
        'class'       => '',
        'condition'   => 'pp_map_show:is(on)',
        'choices'     => array(
          array(
            'value'   => 'pagemap',
            'label'   => __('This Page\'s map','wpvoyager')
            ),
          array(
            'value'   => 'global',
            'label'   => __('Global map','wpvoyager')
            ),
          array(
            'value'   => 'custom',
            'label'   => __('Custom Map','wpvoyager'),
            ),          
          ),
        ),
    array(
      'id'          => 'pp_page_custom_map',
      'label'       => __( 'Custom map on this page', 'theme-text-domain' ),
      'std'         => '',
      'type'        => 'custom-post-type-select',
      'post_type'   => 'tp_maps',
      'condition'   => 'pp_page_map_type:is(custom)',
    ),
    array(
      'id'          => 'pp_title_bar_hide',
      'label'       => 'Title bar status on this page',
      'desc'        => 'Set to "Off" to hide title bar',
      'std'         => 'on',
      'type'        => 'on_off',
      'class'       => '',
    ),
    array(
      'id'          => 'pp_parallax_bg',
      'label'       => 'Parallax header background ',
      'desc'        => 'Set image for header, it must be 1920px wide.',
      'std'         => '',
      'type'        => 'upload',
      'class'       => '',
      ),
     array(
      'id'          => 'pp_parallax_color',
      'label'       => 'Overlay color',
      'desc'        => '',
      'std'         => '',
      'type'        => 'colorpicker',
      'class'       => '',
      ),
     array(
      'id'          => 'pp_parallax_opacity',
      'label'       => 'Overlay opacity',
      'desc'        => '',
      'std'         => '',
      'class'       => '',
      'type'        => 'numeric-slider',
      'min_max_step'=> '0,1,0.01',
      ),
      array(
        'id'          => 'pp_subtitle',
        'label'       => 'Subtitle',
        'desc'        => 'Set optional subtitle.',
        'std'         => '',
        'type'        => 'text',
        'class'       => '',
        ),

    )
  );

  /**
   * Register our meta boxes using the
   * ot_register_meta_box() function.
   */
 
  ot_register_meta_box( $meta_box_layout );
  ot_register_meta_box( $meta_box_layout_page );
  ot_register_meta_box( $slider );
  ot_register_meta_box( $parallax );



} ?>