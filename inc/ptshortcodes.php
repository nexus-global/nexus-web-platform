<?php
// Extend Purethemes.net Shortcodes plugin
function trizzy_shortcodes_list( $pt_shortcodes ) {
    $ptsc_icons = $icon = wpv_icons_list();
    
    $ptsc_orderby = array(
        'none' => 'none' ,
        'ID' => 'ID' ,
        'author' => 'author' ,
        'title' => 'title' ,
        'name' => 'name' ,
        'date' => 'date' ,
        'modified' => 'modified' ,
        'parent' => 'parent' ,
        'rand' => 'rand' ,
        'comment_count' => 'comment_count' ,
        );

    $ptsc_limit = array();
    for ($i=0; $i < 25 ; $i++) {
       $ptsc_limit[$i] = $i;
    }

    $ptsc_order = array(
        'ASC' => 'from lowest to highest values (1, 2, 3; a, b, c)' ,
        'DESC' => 'from highest to lowest values (3, 2, 1; c, b, a)' ,
        );

    $ptsc_places = array(
        'none' => 'None' , 'first' => 'First' , 'last' => 'Last' , 'center' => 'Center'
    );

    $ptsc_width = array(
        'one' => 'One' ,
        'two' => 'Two' ,
        'three' => 'Three' ,
        'four' => 'Four' ,
        'five' => 'Five' ,
        'six' => 'Six' ,
        'seven' => 'Seven' ,
        'eight' => 'Eight' ,
        'nine' => 'Nine' ,
        'ten' => 'Ten' ,
        'eleven' => 'Eleven' ,
        'twelve' => 'Twelve' ,
        'thirteen' => 'Thirteen' ,
        'fourteen' => 'Fourteen' ,
        'fifteen' => 'Fifteen' ,
        'sixteen' => 'Sixteen' ,
    );

    $ptsc_perc = array();
    for ($i=0; $i < 101 ; $i=$i+5) {
        $ptsc_perc[$i] = $i."%";
    }

    /* set arrays for shortcodes form */
    $astrum_pt_shortcodes = array(


    'icon' => array(
        'label' => 'Icon',
        'has_content' => false,
        'params' => array(
            'icon' => array(
                'type' => 'select',
                'label' => 'Icon',
                'desc' => 'Select icon from the list',
                'options' => $ptsc_icons,
                'std' => '',
            ),
        )
    ),   

    'tooltip' => array(
        'label' => 'Tooltip',
        'has_content' => true,
        'params' => array(
            'title' => array(
                    'type' => 'text',
                    'label' => 'Title',
                    'desc' => 'Set title',
                    'std' => '',
                ),
            'url' => array(
                    'type' => 'text',
                    'label' => 'Url',
                    'desc' => 'Set URL',
                    'std' => '',
                ),
            'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => '',
                ),
            'side' => array(
                'type' => 'select',
                'label' => 'Side',
                'desc' => 'Select the side of tooltip',
                'options' => array(
                    'top' => 'Top',
                    'left' => 'Left',
                    'bottom' => 'Bottom',
                    'right' => 'Right'
                ),
                'std' => '',
            ),
        )
    ),
    'list' => array(
        'label' => 'List',
        'has_content' => true,
        'params' => array(
            'type' => array(
                'type' => 'select',
                'label' => 'List style',
                'desc' => 'Set title',
                'options' => array(
                    '1' => 'Check',
                    '2' => 'Arrow',
                    '3' => 'Check with background',
                    '4' => 'Arrow with background',
                ),
                'std' => ''
            ),
            'color' => array(
                'type' => 'select',
                'label' => 'Colored icons?',
                'desc' => '',
                'options' => array(
                    'yes' => 'Yes',
                    'no' => 'No',
                ),
                'std' => ''
            ),
             'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => ''
            ),
        )
    ),

    'quote' => array(
        'label' => 'Quote (text)',
        'has_content' => true,
        'params' => array(
            'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => '',
                ),
            'author' => array(
                    'type' => 'text',
                    'label' => 'Author name',
                    'desc' => '',
                    'std' => '',
                ),           
            'source' => array(
                    'type' => 'text',
                    'label' => 'Source (optional)',
                    'desc' => '',
                    'std' => '',
                ),
        )
    ),
    'space' => array(
            'label' => 'Spacer',
            'has_content' => false,
            'params' => array(
                'class' => array(
                        'type' => 'text',
                        'label' => 'Custom CSS class',
                        'desc' => '',
                        'std' => '',
                    ),
            ),
            'std' => '',
    ),    
    'divider' => array(
            'label' => 'Divider',
            'has_content' => false,
            'params' => array(
                'class' => array(
                        'type' => 'text',
                        'label' => 'Custom CSS class',
                        'desc' => '',
                        'std' => '',
                    ),
            ),
            'std' => '',
    ),
    'highlight' => array(
        'label' => 'Highlight (text)',
        'has_content' => true,
        'params' => array(
            'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => '',
                ),
            'style' => array(
                'type' => 'select',
                'label' => 'Color',
                'desc' => 'Select the color for a highlight',
                'options' => array(
                    'gray' => 'Gray',
                    'light' => 'Light',
                    'color' => 'Curent Main Color'
                ),
                'std' => '',
            ),
        )
    ),
    'column' => array(
        'label' => 'Column',
        'has_content' => true,
        'params' => array(
            'place' => array(
                'type' => 'select',
                'label' => 'Placement',
                'desc' => 'If the columns is already in a container, you need to select place in the row it takes',
                'options' => $ptsc_places,
                'std' => '',
            ),
            'width' => array(
                'type' => 'select',
                'label' => 'Width',
                'desc' => 'Select the width of column',
                'options' => $ptsc_width,
                'std' => 'four'
            ),
            'custom_class' => array(
                'type' => 'text',
                'label' => 'Custom class (optional)',
                'std' => '',
            )
        )
    ),
    'dropcap' => array(
            'label' => 'Dropcap',
            'has_content' => true,
            'params' => array(
                'content' => array(
                    'type' => 'textarea',
                    'label' => 'Content',
                    'std' => '',
                    ),
                'type' => array(
                    'type' => 'select',
                    'label' => 'Type',
                    'desc' => 'Select color for dropcap letter',
                    'options' => array(
                        'full' => 'Full',
                        'normal' => 'Standard'
                    ),
                    'std' => '',
                ),
            )
    ),
    'box' => array(
        'label' => 'Notification box',
        'has_content' => true,
        'params' => array(
            'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => '',
                ),
            'type' => array(
                'type' => 'select',
                'label' => 'Type',
                'desc' => 'Select the type of notice box',
                'options' => array(
                    'success' => 'Success',
                    'error' => 'Error',
                    'warning' => 'Warning',
                    'notice' => 'notice'
                ),
                'std' => '',
            ),
        )
    ),
    'photogrid' => array(
        'label' => 'Photogrid',
        'has_content' => false,
        'std' => '',
        'params' => array(
            'ids' => array(
                'type' => 'gallery',
                'label' => 'IDS',
                'std' => '',
                ),
            'captions' => array(
                'type' => 'select',
                'label' => 'Captions',
                'desc' => 'Show titles on images',
                'options' => array(
                    'yes' => 'yes',
                    'no' => 'no'
                ),
                'std' => '',
            ),
            'columns' => array(
                'type' => 'select',
                'label' => 'Columns',
                'desc' => 'Photogrid columns',
                'options' => array(
                    'two' => 'two',
                    'three' => 'three',
                    'four' => 'four',
                    'five' => 'five',
                    'six' => 'six',
                ),
                'std' => 'two',
            ),
            'fullwidth' => array(
                'type' => 'select',
                'label' => 'Full width',
                'desc' => 'Make it wider then text content',
                'options' => array(
                    'yes' => 'yes',
                    'no' => 'no'
                ),
                'std' => '',
            ),
        )
    ),    
    'slider' => array(
        'label' => 'Slider',
        'has_content' => false,
        'std' => '',
        'params' => array(
            'ids' => array(
                'type' => 'gallery',
                'label' => 'IDS',
                'std' => '',
                ),
            'fullwidth' => array(
                'type' => 'select',
                'label' => 'Color',
                'desc' => 'Full width',
                'options' => array(
                    'yes' => 'yes',
                    'no' => 'no'
                ),
                'std' => '',
            ),            
            'captions' => array(
                'type' => 'select',
                'label' => 'Captions',
                'desc' => 'Show titles on images',
                'options' => array(
                    'yes' => 'yes',
                    'no' => 'no'
                ),
                'std' => '',
            ),
        )
    ),
    
);
$pt_shortcodes = array_merge($pt_shortcodes, $astrum_pt_shortcodes);
return $pt_shortcodes;
}


function add_shortcodes() {
    add_filter( 'ptsc_shortcodes', 'trizzy_shortcodes_list' );
}
add_action( 'init', 'add_shortcodes' );

 ?>