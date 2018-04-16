<?php

/**
* Clear shortcode
* Usage: [clear]
*/
if (!function_exists('pp_clear')) {
    function pp_clear() {
        return '<div class="clear"></div>';
    }
    add_shortcode( 'clear', 'pp_clear' );
}

/**
* Icon shortcode
* Usage: [icon icon="icon-exclamation"]
*/
function pp_icon($atts) {
    extract(shortcode_atts(array(
        'icon'=>''), $atts));
    return '<i class="fa '.$icon.'"></i>';
}
add_shortcode('icon', 'pp_icon');

/**
* Tooltip shortcode
* Usage: [tooltip title="" url=""] [/tooltip] // color, gray, light
*/
function pp_tooltip($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => '',
        'url' => '#',
        'side' => 'top'
        ), $atts));
    return '<a href="'.$url.'" class="tooltip '.$side.'" title="'.esc_attr($title).'">'.$content.'</a>';
}

add_shortcode('tooltip', 'pp_tooltip');


/**
* List style shortcode
* Usage: [list type="check"] [/list] // check, arrow, checkbg, arrowbg
*/
function pp_liststyle($atts, $content = null) {
    extract(shortcode_atts(array(
        "style" => '1',
        "color" => 'no'
        ), $atts));
    if($color=='yes') { $class="color"; } else { $class = ' '; };
    $output = '<div class="list-'.$style.' '.$class.'">'.$content.'</div>';
    return $output;
}

add_shortcode('list', 'pp_liststyle');


/**
* Quote shortcode
* Usage: [quote author="icon-exclamation" source=""]
*/
function pp_quote($atts, $content = null) {
    extract(shortcode_atts(array(
        'author'=>'',
        'source'=>''
        ),
    $atts));
    $output = '
        <div class="post-quote">
            <span class="icon"></span>
            <blockquote>'.do_shortcode( $content );

    if(!empty($source)) {
        $output .= '<a href="'.$source.'">';
    }

    $output .= '<span>'.$author.'</span>';

    if(!empty($source)) {
        $output .= '</a>';
    }

    $output .= '</blockquote>
        </div>';

    return $output;
}
add_shortcode('quote', 'pp_quote');

/**
* Quote shortcode
* Usage: [quote author="icon-exclamation" source=""]
*/
function pp_fullwidth($atts, $content = null) {
    extract(shortcode_atts(array(
        'author'=>'',
        'source'=>''
        ),
    $atts));
    $output = '
        <div class="full-width-element">'.do_shortcode( $content ).'</div>';
    return $output;
}
add_shortcode('fullwidth', 'pp_fullwidth');




/**
* Spacer shortcode
* Usage: [space]
*/
if (!function_exists('pp_spacer')) {
    function pp_spacer($atts, $content ) {
        extract(shortcode_atts(array(
            'class' => ''
            ), $atts));
        return '<div class="clearfix"></div><div class="'.$class.'"></div>';
    }
    add_shortcode( 'space', 'pp_spacer' );
}

if (!function_exists('pp_divider')) {
    function pp_divider($atts, $content ) {
        extract(shortcode_atts(array(
            'class' => 'margin-top-30'
            ), $atts));
        return '<div class="clearfix"></div><div class="divider '.$class.'"></div>';
    }
    add_shortcode( 'divider', 'pp_divider' );
}


/**
* Columns shortcode
* Usage: [column width="eight" place="" custom_class=""] [/column]
*/

function pp_column($atts, $content = null) {
    extract( shortcode_atts( array(
        'width' => 'eight',
        'place' => '',
        'custom_class' => ''
        ), $atts ) );

    switch ( $width ) {
        case "1/3" : $w = "column one-third"; break;
        case "one-third" : $w = "column one-third"; break;

        case "2/3" :
        $w = "column two-thirds";
        break;

        case "one" : $w = "one columns"; break;
        case "two" : $w = "two columns"; break;
        case "three" : $w = "three columns"; break;
        case "four" : $w = "four columns"; break;
        case "five" : $w = "five columns"; break;
        case "six" : $w = "six columns"; break;
        case "seven" : $w = "seven columns"; break;
        case "eight" : $w = "eight columns"; break;
        case "nine" : $w = "nine columns"; break;
        case "ten" : $w = "ten columns"; break;
        case "eleven" : $w = "eleven columns"; break;
        case "twelve" : $w = "twelve columns"; break;
        case "thirteen" : $w = "thirteen columns"; break;
        case "fourteen" : $w = "fourteen columns"; break;
        case "fifteen" : $w = "fifteen columns"; break;
        case "sixteen" : $w = "sixteen columns"; break;

        default :
        $w = 'columns eight';
    }

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $column ='<div class="'.$w.' '.$custom_class.' '.$p.'">'.do_shortcode( $content ).'</div>';
    if($place=='last') {
        $column .= '<br class="clear" />';
    }
    return $column;
}

add_shortcode('column', 'pp_column');



function pp_accordion( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => 'Tab',
        'icon' => ''
        ), $atts));
    $output = '<h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span>';
    if(!empty($icon)) { $output .= '<i class="fa fa-'.$icon.'"></i>'; }
    $output .= $title.'</h3><div><p>'.do_shortcode( $content ).'</p></div>';
    return $output;
}
add_shortcode( 'accordion', 'pp_accordion' );

function pp_accordion_wrap( $atts, $content ) {
    extract(shortcode_atts(array(), $atts));
    return '<div class="accordion">'.do_shortcode( $content ).'</div>';
}
add_shortcode( 'accordionwrap', 'pp_accordion_wrap' );



function etdc_tab_group( $atts, $content ) {
    $GLOBALS['pptab_count'] = 0;
    do_shortcode( $content );
    $count = 0;
    if( is_array( $GLOBALS['tabs'] ) ) {
        foreach( $GLOBALS['tabs'] as $tab ) {
            $count++;
            $tabs[] = '<li><a href="#tab'.$count.'">'.$tab['title'].'</a></li>';
            $panes[] = '<div class="tab-content" id="tab'.$count.'">'.$tab['content'].'</div>';
        }
        $return = "\n".'<ul class="tabs-nav">'.implode( "\n", $tabs ).'</ul>'."\n".'<div class="tabs-container">'.implode( "\n", $panes ).'</div>'."\n";
    }
    return $return;
}

/**
* Usage: [tab title="" ] [/tab]
*/
function etdc_tab( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => 'Tab %d',
        ), $atts));

    $x = $GLOBALS['pptab_count'];
    $GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['pptab_count'] ), 'content' =>  do_shortcode( $content ) );
    $GLOBALS['pptab_count']++;
}
add_shortcode( 'tabgroup', 'etdc_tab_group' );

add_shortcode( 'tab', 'etdc_tab' );


/**
* Dropcap shortcode type = full
* Usage: [dropcap color="gray"] [/dropcap]// margin-down margin-both
*/
if (!function_exists('pp_dropcap')) {
    function pp_dropcap($atts, $content = null) {
        extract(shortcode_atts(array(
            'type'=>''), $atts));
        return '<span class="dropcap '.$type.'">'.$content.'</span>';
    }
    add_shortcode('dropcap', 'pp_dropcap');
}


/**
* Highlight shortcode
* Usage: [highlight style="gray"] [/highlight] // color, gray, light
*/
function pp_highlight($atts, $content = null) {
    extract(shortcode_atts(array(
        'style' => 'gray'
        ), $atts));
    return '<span class="highlight '.$style.'">'.$content.'</span>';
}
add_shortcode('highlight', 'pp_highlight');


/**
* Box shortcodes
* Usage: [box type=""] [/box]
*/

function pp_box($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => ''
        ), $atts));
    return '<div class="notification closeable '.$type.'"><p>'.do_shortcode( $content ).'</p><a class="close" href="#"></a></div>';
}

add_shortcode('box', 'pp_box');

function pp_button($atts, $content = null) {
    extract(shortcode_atts(array(
        "url" => '#',
        "color" => 'color',  //gray color dark
        "customcolor" => '',
        "iconcolor" => 'white',
        "icon" => '',
        "target" => '',
        "customclass" => '',
        "from_vs" => 'no',
        ), $atts));
    if($from_vs == 'yes') {
        $link = vc_build_link( $url );
        $a_href = $link['url'];
        $a_title = $link['title'];
        $a_target = $link['target'];
        $output = '<a class="button '.$color.' '.$customclass.'" href="'.$a_href.'" title="'.esc_attr( $a_title ).'" target="'.$a_target.'"';
        if(!empty($customcolor)) { $output .= 'style="background-color:'.$customcolor.'"'; }
        $output .= '>';
        if(!empty($icon)) { $output .= '<i class="fa fa-'.$icon.'  '.$iconcolor.'"></i> '; }
        $output .= $a_title.'</a>';
    } else {
        $output = '<a class="button '.$color.' '.$customclass.'" href="'.$url.'" ';
        if(!empty($target)) { $output .= 'target="'.$target.'"'; }
        if(!empty($customcolor)) { $output .= 'style="background-color:'.$customcolor.'"'; }
        $output .= '>';
        if(!empty($icon)) { $output .= '<i class="fa fa-'.$icon.'  '.$iconcolor.'"></i> '; }
        $output .= $content.'</a>';
    }
    return $output;
}
add_shortcode('button', 'pp_button');


function pp_share_btn($atts) {
    extract(shortcode_atts(array(
        "facebook" => '',
        "pinit" => '',
        "twitter" => '',
        "gplus" => '',

        ), $atts));
    global $post;

    $id = $post->ID;
    $title = urlencode($post->post_title);
    $url =  urlencode( get_permalink($id) );
    $summary = urlencode(string_limit_words($post->post_excerpt,20));
    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'medium' );
    $imageurl = urlencode($thumb[0]);

    $output ='<!-- Share Buttons -->
    <div class="share-buttons">
    <ul>
        <li><a href="#">'.__("Share","wpvoyager").'</a></li>';
        if(!empty($facebook)) $output .= '<li class="share-facebook"><a target="_blank" href="https://www.facebook.com/sharer.php?s=100&amp;p[title]=' . esc_attr($title) . '&amp;p[url]=' . $url . '&amp;p[summary]=' . esc_attr($summary) . '&amp;p[images][0]=' . $imageurl . '"">Facebook</a></li>';
        if(!empty($pinit)) $output .= '<li class="share-pinit"><a target="_blank" href="http://pinterest.com/pin/create/button/?url=' . $url . '&amp;description=' . esc_attr($summary) . '&media=' . $imageurl . '" onclick="window.open(this.href); return false;">Pin it</a></li>';
        if(!empty($gplus)) $output .= '<li class="share-gplus"><a target="_blank" href="https://plus.google.com/share?url=' . $url . '&amp;title="' . esc_attr($title) . '" onclick=\'javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;\'>Google Plus</a></li>';
        if(!empty($twitter)) $output .= '<li class="share-twitter"><a target="_blank"  href="https://twitter.com/share?url=' . $url . '&amp;text=' . esc_attr($summary ). '" title="' . __( 'Twitter', 'wpvoyager' ) . '">Twitter</a></li>';
    $output .= '</ul>
    </div>
    <div class="clearfix"></div>';
    return $output;
}

add_shortcode('shareit', 'pp_share_btn');



function pp_basic_slider( $atts, $content ) {
    extract(shortcode_atts(array(
        'ids' => '',
        'caption' => 'yes',
        'fullwidth' => 'yes',
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        ), $atts));

    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $ids ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $orderby ) ) {
            $orderby = 'post__in';
        }
        $include = $ids;
    }

    if ( ! empty( $include ) ) {
        $_attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    }
    
    if ( empty( $attachments ) ) {
        return '';
    }

    if($fullwidth != "no") {
        $output = '<div class="royalSlider rsDefault full-width-element ">';
    } else {
        $output = '<div class="royalSlider rsDefault">';
    }
    $i = 0;
    foreach ( $attachments as $id => $attachment ) {

        $link_output = wp_get_attachment_image_src( $id, 'full' );
        $image_output = wp_get_attachment_image_src( $id, 'full-content' );
         $image_meta  = wp_get_attachment_metadata( $id );
        $output .= '
            <div class="rsContent">
                <a href="'.$link_output[0].'" class="view" ><img class="rsImg" src="'.$image_output[0].'" alt=""></a>';
        if ( trim($attachment->post_excerpt) ) {
            $output .= '
                <div class="infoBlock" data-fade-effect="" data-move-offset="10" data-move-effect="bottom" data-speed="200">
                ' . wptexturize($attachment->post_excerpt) . '
                </div>';
        }
        $output .= "</div>";
        
    }
    $output .= '
        </div><div class="clearfix"></div>';
    return $output;
}
add_shortcode( 'slider', 'pp_basic_slider' );


function pp_photoGrid( $atts, $content ) {
    extract(shortcode_atts(array(
        'ids' => '',
        'columns' => 'two',
        'caption' => 'yes',
        'fullwidth' => 'yes',
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        ), $atts));

    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $ids ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $orderby ) ) {
            $orderby = 'post__in';
        }
        $include = $ids;
    }

    if ( ! empty( $include ) ) {
        $_attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    }
    
    if ( empty( $attachments ) ) {
        return '';
    }

    if($fullwidth != "no") {
        $output = '<div class="photoGrid '.$columns.' clearfix full-width-element"> ';
    } else {
        $output = '<div class="photoGrid  '.$columns.' clearfix"> ';
    }
    $i = 0;
    $randID = rand(1, 99);
    foreach ( $attachments as $id => $attachment ) {

        $link_output = wp_get_attachment_image_src( $id, 'full' );
        $image_output = wp_get_attachment_image_src( $id, 'grid-size' );
        $image_meta  = wp_get_attachment_metadata( $id );
     
        $output .= '
                <a href="'.$link_output[0].'" rel="grid-'.$randID.'" class="item view" title="'.$image_meta['image_meta']['caption'].'">
                    <img src="'.$image_output[0].'" alt="">
                </a>';
    }
    $output .= "
        </div>\n";
    return $output;
}
add_shortcode( 'photogrid', 'pp_photoGrid' );
