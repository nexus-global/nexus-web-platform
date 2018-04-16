<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package WPVoyager
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wpvoyager_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
    if ( is_page_template('template-slider.php') ) {
        $classes[] = 'alternative';
    }

	return $classes;
}
add_filter( 'body_class', 'wpvoyager_body_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function wpvoyager_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name.
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary.
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'wpvoyager' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'wpvoyager_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function wpvoyager_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'wpvoyager_render_title' );
endif;

function wpv_single_parallax() {
    global $post;
    if(is_single()){
        $parallaximage = get_post_meta( $post->ID, 'pp_parallax_bg', TRUE );
        $parallaxcolor = get_post_meta( $post->ID, 'pp_parallax_color', TRUE );
        $opacity = get_post_meta( $post->ID, 'pp_parallax_opacity', TRUE );
        if(empty($opacity)) {
            $parallaxopacity = "0.35";
        } else {
            $parallaxopacity = $opacity;
        }
    
        if(empty($parallaxcolor)) { $parallaxcolor = "#000000";}
            $custom_rgb = purehex2RGB($parallaxcolor); 
            if($custom_rgb) {
                $red = $custom_rgb['red'];
                $green = $custom_rgb['green'];
                $blue = $custom_rgb['blue'];
            }
        $custom_css = '
        body.single-post  .fullscreen:before {
           background-color: rgba('.$red.','.$green.','.$blue.','.$parallaxopacity.');
        }';
        wp_add_inline_style( 'wpvoyager-style', $custom_css );
    }
}
add_action( 'wp_enqueue_scripts', 'wpv_single_parallax' );

//customize the PageNavi HTML before it is output
add_filter( 'wp_pagenavi', 'wpvoyager_pagination', 10, 2 );
function wpvoyager_pagination($html) {
    $out = '';
    //wrap a's and span's in li's
    $out = str_replace("<a","<li><a",$html);
    $out = str_replace("</a>","</a></li>",$out);
    $out = str_replace("<span","<li><span",$out);
    $out = str_replace("</span>","</span></li>",$out);
    $out = str_replace("<div class='wp-pagenavi'>","",$out);
    $out = str_replace("</div>","",$out);
    return '<div class="pagination"><ul>'.$out.'</ul></div>';
}



if (!function_exists('wpvoyager_number_to_width')) :
function wpvoyager_number_to_width($width) {
    switch ($width) {
        case '1':
        return "one";
        break;
        case '2':
        return "two";
        break;
        case '3':
        return "three";
        break;
        case '4':
        return "four";
        break;
        case '5':
        return "five";
        break;
        case '6':
        return "six";
        break;
        case '7':
        return "seven";
        break;
        case '8':
        return "eight";
        break;
        case '9':
        return "nine";
        break;
        case '10':
        return "ten";
        break;
        case '11':
        return "eleven";
        break;
        case '12':
        return "twelve";
        break;
        case '13':
        return "thirteen";
        break;
        case '14':
        return "fourteen";
        break;
        case '15':
        return "fifteen";
        break;
        case '16':
        return "sixteen";
        break;
        case '1/3':
        return "one-third";
        break;        
        case '2/3':
        return "two-thirds";
        break;
        default:
        return "thirteen";
        break;
    }
}
endif;


add_filter('wp_nav_menu_items','add_search_element_to_menu', 10, 2);
function add_search_element_to_menu( $items, $args ) {
    if(ot_get_option('pp_menu_search','off') == 'on') {
        if( $args->theme_location == 'primary' ) {
        return $items.'<li class="search"><a href="#"><i class="fa fa-search"></i></a></li>';
        }
    }
    return $items;
}

add_filter( 'widget_tag_cloud_args', 'wpv_widget_tag_cloud_args' );
function wpv_widget_tag_cloud_args( $args ) {
    $args['number'] = 12;
    $args['largest'] = 12;
    $args['smallest'] = 12;
    $args['unit'] = 'px';
    return $args;
}


function wpv_share_post(){
    global $post;
    $share_options = ot_get_option('pp_post_share');
    if(!empty($share_options)) {
            $id = $post->ID;
            $title = urlencode($post->post_title);
            $url =  urlencode( get_permalink($id) );
            $summary = urlencode(string_limit_words($post->post_excerpt,20));
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'medium' );
            $imageurl = urlencode($thumb[0]);
    ?>
        <!-- Social -->
        <div class="share-icons-container">
            <h5 class="share-headline"><?php _e('Share','wpvoyager'); ?></h5><br>
            <ul class="share-icons">
                <?php if (in_array("facebook", $share_options)) { ?><li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($url); ?>" title="<?php _e('Share','wpvoyager'); ?>"><i class="fa fa-facebook"></i></a></li> <?php } ?>
                <?php if (in_array("twitter", $share_options)) { ?><li><a href="<?php echo 'https://twitter.com/share?url=' . esc_url($url) . '&amp;text=' . esc_attr($summary ); ?>" title="<?php _e('Tweet','wpvoyager'); ?>"><i class="fa fa-twitter"></i></a></li><?php } ?>
                <?php if (in_array("google-plus", $share_options)) { ?><li><a href="<?php echo 'https://plus.google.com/share?url=' . esc_url($url) . '&amp;title="' . esc_attr($title);?>"  onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" title="<?php _e('Share','wpvoyager'); ?>"><i class="fa fa-google-plus"></i></a></li><?php } ?>
                <?php if (in_array("pinterest", $share_options)) { ?><li><a href="<?php echo 'http://pinterest.com/pin/create/button/?url=' . esc_url($url) . '&amp;description=' . esc_attr($summary) . '&media=' . esc_attr($imageurl); ?>" title="<?php _e('Pin It','wpvoyager'); ?>" onclick="window.open(this.href); return false;"><i class="fa fa-pinterest-p"></i></a></li><?php } ?>
            </ul>
        </div>
        <div class="clearfix"></div>
    <?php } 
}

function wpv_about_author(){ 
    global $post;
    if(ot_get_option('pp_about_author','off') == 'on') {
    ?>
        <!-- About Author -->
        <div class="about-author">
            <?php echo get_avatar( $post->post_author, 94 ); ?>
            <div class="about-description">
                <h5><span class="author vcard"><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ; ?>"><?php echo esc_html( get_the_author() ) ?></a></span></h5>
                <p><?php the_author_meta('description',get_the_author_meta( 'ID' )); ?></p>
            </div>
        </div> <?php    
    }
}

    function wpv_icons_list(){
        $icon = array(
            'no-icon'=> 'No-Icon',
            'fa-adjust' => 'Adjust',
            'fa-adn' => 'Adn',
            'fa-align-center' => 'Align Center',
            'fa-align-justify' => 'Align Justify',
            'fa-align-left' => 'Align Left',
            'fa-align-right' => 'Align Right',
            'fa-ambulance' => 'Ambulance',
            'fa-anchor' => 'Anchor',
            'fa-android' => 'Android',
            'fa-angle-double-down' => 'Angle Double Down',
            'fa-angle-double-left' => 'Angle Double Left',
            'fa-angle-double-right' => 'Angle Double Right',
            'fa-angle-double-up' => 'Angle Double Up',
            'fa-angle-down' => 'Angle Down',
            'fa-angle-left' => 'Angle Left',
            'fa-angle-right' => 'Angle Right',
            'fa-angle-up' => 'Angle Up',
            'fa-apple' => 'Apple',
            'fa-archive' => 'Archive',
            'fa-arrow-circle-down' => 'Arrow Circle Down',
            'fa-arrow-circle-left' => 'Arrow Circle Left',
            'fa-arrow-circle-o-down' => 'Arrow Circle O Down',
            'fa-arrow-circle-o-left' => 'Arrow Circle O Left',
            'fa-arrow-circle-o-right' => 'Arrow Circle O Right',
            'fa-arrow-circle-o-up' => 'Arrow Circle O Up',
            'fa-arrow-circle-right' => 'Arrow Circle Right',
            'fa-arrow-circle-up' => 'Arrow Circle Up',
            'fa-arrow-down' => 'Arrow Down',
            'fa-arrow-left' => 'Arrow Left',
            'fa-arrow-right' => 'Arrow Right',
            'fa-arrows' => 'Arrows',
            'fa-arrows-alt' => 'Arrows Alt',
            'fa-arrows-h' => 'Arrows H',
            'fa-arrows-v' => 'Arrows V',
            'fa-arrow-up' => 'Arrow Up',
            'fa-asterisk' => 'Asterisk',
            'fa-automobile' => 'Automobile',
            'fa-backward' => 'Backward',
            'fa-ban' => 'Ban',
            'fa-bank' => 'Bank',
            'fa-bar-chart-o' => 'Bar Chart O',
            'fa-barcode' => 'Barcode',
            'fa-bars' => 'Bars',
            'fa-bed' => 'Bed',
            'fa-bed' => 'Hotel',
            'fa-beer' => 'Beer',
            'fa-behance' => 'Behance',
            'fa-behance-square' => 'Behance Square',
            'fa-bell' => 'Bell',
            'fa-bell-o' => 'Bell O',
            'fa-bitbucket' => 'Bitbucket',
            'fa-bitbucket-square' => 'Bitbucket Square',
            'fa-bitcoin' => 'Bitcoin',
            'fa-bold' => 'Bold',
            'fa-bolt' => 'Bolt',
            'fa-bomb' => 'Bomb',
            'fa-book' => 'Book',
            'fa-bookmark' => 'Bookmark',
            'fa-bookmark-o' => 'Bookmark O',
            'fa-briefcase' => 'Briefcase',
            'fa-btc' => 'Btc',
            'fa-bug' => 'Bug',
            'fa-building' => 'Building',
            'fa-building-o' => 'Building O',
            'fa-bullhorn' => 'Bullhorn',
            'fa-bullseye' => 'Bullseye',
            'fa-buysellads' => 'Buysellads',
            'fa-cab' => 'Cab',
            'fa-calendar' => 'Calendar',
            'fa-calendar-o' => 'Calendar O',
            'fa-camera' => 'Camera',
            'fa-camera-retro' => 'Camera Retro',
            'fa-car' => 'Car',
            'fa-caret-down' => 'Caret Down',
            'fa-caret-left' => 'Caret Left',
            'fa-caret-right' => 'Caret Right',
            'fa-caret-square-o-down' => 'Caret Square O Down',
            'fa-caret-square-o-left' => 'Caret Square O Left',
            'fa-caret-square-o-right' => 'Caret Square O Right',
            'fa-caret-square-o-up' => 'Caret Square O Up',
            'fa-caret-up' => 'Caret Up',
            'fa-cart-arrow-down' => 'Cart Arrow Down',
            'fa-cart-plus' => 'Cart Plus',
            'fa-certificate' => 'Certificate',
            'fa-chain' => 'Chain',
            'fa-chain-broken' => 'Chain Broken',
            'fa-check' => 'Check',
            'fa-check-circle' => 'Check Circle',
            'fa-check-circle-o' => 'Check Circle O',
            'fa-check-square' => 'Check Square',
            'fa-check-square-o' => 'Check Square O',
            'fa-chevron-circle-down' => 'Chevron Circle Down',
            'fa-chevron-circle-left' => 'Chevron Circle Left',
            'fa-chevron-circle-right' => 'Chevron Circle Right',
            'fa-chevron-circle-up' => 'Chevron Circle Up',
            'fa-chevron-down' => 'Chevron Down',
            'fa-chevron-left' => 'Chevron Left',
            'fa-chevron-right' => 'Chevron Right',
            'fa-chevron-up' => 'Chevron Up',
            'fa-child' => 'Child',
            'fa-circle' => 'Circle',
            'fa-circle-o' => 'Circle O',
            'fa-circle-o-notch' => 'Circle O Notch',
            'fa-circle-thin' => 'Circle Thin',
            'fa-clipboard' => 'Clipboard',
            'fa-clock-o' => 'Clock O',
            'fa-cloud' => 'Cloud',
            'fa-cloud-download' => 'Cloud Download',
            'fa-cloud-upload' => 'Cloud Upload',
            'fa-cny' => 'Cny',
            'fa-code' => 'Code',
            'fa-code-fork' => 'Code Fork',
            'fa-codepen' => 'Codepen',
            'fa-coffee' => 'Coffee',
            'fa-cog' => 'Cog',
            'fa-cogs' => 'Cogs',
            'fa-columns' => 'Columns',
            'fa-comment' => 'Comment',
            'fa-comment-o' => 'Comment O',
            'fa-comments' => 'Comments',
            'fa-comments-o' => 'Comments O',
            'fa-compass' => 'Compass',
            'fa-compress' => 'Compress',
            'fa-connectdevelop' => 'Connectdevelop',
            'fa-copy' => 'Copy',
            'fa-credit-card' => 'Credit Card',
            'fa-crop' => 'Crop',
            'fa-crosshairs' => 'Crosshairs',
            'fa-css3' => 'Css3',
            'fa-cube' => 'Cube',
            'fa-cubes' => 'Cubes',
            'fa-cut' => 'Cut',
            'fa-cutlery' => 'Cutlery',
            'fa-dashboard' => 'Dashboard',
            'fa-dashcube' => 'Dashcube',
            'fa-database' => 'Database',
            'fa-dedent' => 'Dedent',
            'fa-delicious' => 'Delicious',
            'fa-desktop' => 'Desktop',
            'fa-deviantart' => 'Deviantart',
            'fa-diamond' => 'Diamond',
            'fa-digg' => 'Digg',
            'fa-dollar' => 'Dollar',
            'fa-dot-circle-o' => 'Dot Circle O',
            'fa-download' => 'Download',
            'fa-dribbble' => 'Dribbble',
            'fa-dropbox' => 'Dropbox',
            'fa-drupal' => 'Drupal',
            'fa-edit' => 'Edit',
            'fa-eject' => 'Eject',
            'fa-ellipsis-h' => 'Ellipsis H',
            'fa-ellipsis-v' => 'Ellipsis V',
            'fa-empire' => 'Empire',
            'fa-envelope' => 'Envelope',
            'fa-envelope-o' => 'Envelope O',
            'fa-envelope-square' => 'Envelope Square',
            'fa-eraser' => 'Eraser',
            'fa-eur' => 'Eur',
            'fa-euro' => 'Euro',
            'fa-exchange' => 'Exchange',
            'fa-exclamation' => 'Exclamation',
            'fa-exclamation-circle' => 'Exclamation Circle',
            'fa-exclamation-triangle' => 'Exclamation Triangle',
            'fa-expand' => 'Expand',
            'fa-external-link' => 'External Link',
            'fa-external-link-square' => 'External Link Square',
            'fa-eye' => 'Eye',
            'fa-eye-slash' => 'Eye Slash',
            'fa-facebook' => 'Facebook',
            'fa-facebook-official' => 'Facebook Official',
            'fa-facebook-square' => 'Facebook Square',
            'fa-fast-backward' => 'Fast Backward',
            'fa-fast-forward' => 'Fast Forward',
            'fa-fax' => 'Fax',
            'fa-female' => 'Female',
            'fa-fighter-jet' => 'Fighter Jet',
            'fa-file' => 'File',
            'fa-file-archive-o' => 'File Archive O',
            'fa-file-audio-o' => 'File Audio O',
            'fa-file-code-o' => 'File Code O',
            'fa-file-excel-o' => 'File Excel O',
            'fa-file-image-o' => 'File Image O',
            'fa-file-movie-o' => 'File Movie O',
            'fa-file-o' => 'File O',
            'fa-file-pdf-o' => 'File Pdf O',
            'fa-file-photo-o' => 'File Photo O',
            'fa-file-picture-o' => 'File Picture O',
            'fa-file-powerpoint-o' => 'File Powerpoint O',
            'fa-files-o' => 'Files O',
            'fa-file-sound-o' => 'File Sound O',
            'fa-file-text' => 'File Text',
            'fa-file-text-o' => 'File Text O',
            'fa-file-video-o' => 'File Video O',
            'fa-file-word-o' => 'File Word O',
            'fa-file-zip-o' => 'File Zip O',
            'fa-film' => 'Film',
            'fa-filter' => 'Filter',
            'fa-fire' => 'Fire',
            'fa-fire-extinguisher' => 'Fire Extinguisher',
            'fa-flag' => 'Flag',
            'fa-flag-checkered' => 'Flag Checkered',
            'fa-flag-o' => 'Flag O',
            'fa-flash' => 'Flash',
            'fa-flask' => 'Flask',
            'fa-flickr' => 'Flickr',
            'fa-floppy-o' => 'Floppy O',
            'fa-folder' => 'Folder',
            'fa-folder-o' => 'Folder O',
            'fa-folder-open' => 'Folder Open',
            'fa-folder-open-o' => 'Folder Open O',
            'fa-font' => 'Font',
            'fa-forumbee' => 'Forumbee',
            'fa-forward' => 'Forward',
            'fa-foursquare' => 'Foursquare',
            'fa-frown-o' => 'Frown O',
            'fa-gamepad' => 'Gamepad',
            'fa-gavel' => 'Gavel',
            'fa-gbp' => 'Gbp',
            'fa-ge' => 'Ge',
            'fa-gear' => 'Gear',
            'fa-gears' => 'Gears',
            'fa-gift' => 'Gift',
            'fa-git' => 'Git',
            'fa-github' => 'Github',
            'fa-github-alt' => 'Github Alt',
            'fa-github-square' => 'Github Square',
            'fa-git-square' => 'Git Square',
            'fa-gittip' => 'Gittip',
            'fa-glass' => 'Glass',
            'fa-globe' => 'Globe',
            'fa-google' => 'Google',
            'fa-google-plus' => 'Google Plus',
            'fa-google-plus-square' => 'Google Plus Square',
            'fa-graduation-cap' => 'Graduation Cap',
            'fa-group' => 'Group',
            'fa-hacker-news' => 'Hacker News',
            'fa-hand-o-down' => 'Hand O Down',
            'fa-hand-o-left' => 'Hand O Left',
            'fa-hand-o-right' => 'Hand O Right',
            'fa-hand-o-up' => 'Hand O Up',
            'fa-hdd-o' => 'Hdd O',
            'fa-header' => 'Header',
            'fa-headphones' => 'Headphones',
            'fa-heart' => 'Heart',
            'fa-heartbeat' => 'Heartbeat',
            'fa-heart-o' => 'Heart O',
            'fa-history' => 'History',
            'fa-home' => 'Home',
            'fa-hospital-o' => 'Hospital O',
            'fa-h-square' => 'H Square',
            'fa-html5' => 'Html5',
            'fa-image' => 'Image',
            'fa-inbox' => 'Inbox',
            'fa-indent' => 'Indent',
            'fa-info' => 'Info',
            'fa-info-circle' => 'Info Circle',
            'fa-inr' => 'Inr',
            'fa-instagram' => 'Instagram',
            'fa-institution' => 'Institution',
            'fa-italic' => 'Italic',
            'fa-joomla' => 'Joomla',
            'fa-jpy' => 'Jpy',
            'fa-jsfiddle' => 'Jsfiddle',
            'fa-key' => 'Key',
            'fa-keyboard-o' => 'Keyboard O',
            'fa-krw' => 'Krw',
            'fa-language' => 'Language',
            'fa-laptop' => 'Laptop',
            'fa-leaf' => 'Leaf',
            'fa-leanpub' => 'Leanpub',
            'fa-legal' => 'Legal',
            'fa-lemon-o' => 'Lemon O',
            'fa-level-down' => 'Level Down',
            'fa-level-up' => 'Level Up',
            'fa-life-bouy' => 'Life Bouy',
            'fa-life-ring' => 'Life Ring',
            'fa-life-saver' => 'Life Saver',
            'fa-lightbulb-o' => 'Lightbulb O',
            'fa-link' => 'Link',
            'fa-linkedin' => 'Linkedin',
            'fa-linkedin-square' => 'Linkedin Square',
            'fa-linux' => 'Linux',
            'fa-list' => 'List',
            'fa-list-alt' => 'List Alt',
            'fa-list-ol' => 'List Ol',
            'fa-list-ul' => 'List Ul',
            'fa-location-arrow' => 'Location Arrow',
            'fa-lock' => 'Lock',
            'fa-long-arrow-down' => 'Long Arrow Down',
            'fa-long-arrow-left' => 'Long Arrow Left',
            'fa-long-arrow-right' => 'Long Arrow Right',
            'fa-long-arrow-up' => 'Long Arrow Up',
            'fa-magic' => 'Magic',
            'fa-magnet' => 'Magnet',
            'fa-mail-forward' => 'Mail Forward',
            'fa-mail-reply' => 'Mail Reply',
            'fa-mail-reply-all' => 'Mail Reply All',
            'fa-male' => 'Male',
            'fa-map-marker' => 'Map Marker',
            'fa-mars' => 'Mars',
            'fa-mars-double' => 'Mars Double',
            'fa-mars-stroke' => 'Mars Stroke',
            'fa-mars-stroke-h' => 'Mars Stroke H',
            'fa-mars-stroke-v' => 'Mars Stroke V',
            'fa-maxcdn' => 'Maxcdn',
            'fa-medium' => 'Medium',
            'fa-medkit' => 'Medkit',
            'fa-meh-o' => 'Meh O',
            'fa-mercury' => 'Mercury',
            'fa-microphone' => 'Microphone',
            'fa-microphone-slash' => 'Microphone Slash',
            'fa-minus' => 'Minus',
            'fa-minus-circle' => 'Minus Circle',
            'fa-minus-square' => 'Minus Square',
            'fa-minus-square-o' => 'Minus Square O',
            'fa-mobile' => 'Mobile',
            'fa-mobile-phone' => 'Mobile Phone',
            'fa-money' => 'Money',
            'fa-moon-o' => 'Moon O',
            'fa-mortar-board' => 'Mortar Board',
            'fa-motorcycle' => 'Motorcycle',
            'fa-music' => 'Music',
            'fa-navicon' => 'Navicon',
            'fa-neuter' => 'Fa Neuter',
            'fa-openid' => 'Openid',
            'fa-outdent' => 'Outdent',
            'fa-pagelines' => 'Pagelines',
            'fa-paperclip' => 'Paperclip',
            'fa-paper-plane' => 'Paper Plane',
            'fa-paper-plane-o' => 'Paper Plane O',
            'fa-paragraph' => 'Paragraph',
            'fa-paste' => 'Paste',
            'fa-pause' => 'Pause',
            'fa-paw' => 'Paw',
            'fa-pencil' => 'Pencil',
            'fa-pencil-square' => 'Pencil Square',
            'fa-pencil-square-o' => 'Pencil Square O',
            'fa-phone' => 'Phone',
            'fa-phone-square' => 'Phone Square',
            'fa-photo' => 'Photo',
            'fa-picture-o' => 'Picture O',
            'fa-pied-piper' => 'Pied Piper',
            'fa-pied-piper-alt' => 'Pied Piper Alt',
            'fa-pied-piper-square' => 'Pied Piper Square',
            'fa-pinterest' => 'Pinterest',
            'fa-pinterest-p' => 'Pinterest P',
            'fa-pinterest-square' => 'Pinterest Square',
            'fa-plane' => 'Plane',
            'fa-play' => 'Play',
            'fa-play-circle' => 'Play Circle',
            'fa-play-circle-o' => 'Play Circle O',
            'fa-plus' => 'Plus',
            'fa-plus-circle' => 'Plus Circle',
            'fa-plus-square' => 'Plus Square',
            'fa-plus-square-o' => 'Plus Square O',
            'fa-power-off' => 'Power Off',
            'fa-print' => 'Print',
            'fa-puzzle-piece' => 'Puzzle Piece',
            'fa-qq' => 'Qq',
            'fa-qrcode' => 'Qrcode',
            'fa-question' => 'Question',
            'fa-question-circle' => 'Question Circle',
            'fa-quote-left' => 'Quote Left',
            'fa-quote-right' => 'Quote Right',
            'fa-ra' => 'Ra',
            'fa-random' => 'Random',
            'fa-rebel' => 'Rebel',
            'fa-recycle' => 'Recycle',
            'fa-reddit' => 'Reddit',
            'fa-reddit-square' => 'Reddit Square',
            'fa-refresh' => 'Refresh',
            'fa-renren' => 'Renren',
            'fa-reorder' => 'Reorder',
            'fa-repeat' => 'Repeat',
            'fa-reply' => 'Reply',
            'fa-reply-all' => 'Reply All',
            'fa-retweet' => 'Retweet',
            'fa-rmb' => 'Rmb',
            'fa-road' => 'Road',
            'fa-rocket' => 'Rocket',
            'fa-rotate-left' => 'Rotate Left',
            'fa-rotate-right' => 'Rotate Right',
            'fa-rouble' => 'Rouble',
            'fa-rss' => 'Rss',
            'fa-rss-square' => 'Rss Square',
            'fa-rub' => 'Rub',
            'fa-ruble' => 'Ruble',
            'fa-rupee' => 'Rupee',
            'fa-save' => 'Save',
            'fa-scissors' => 'Scissors',
            'fa-search' => 'Search',
            'fa-search-minus' => 'Search Minus',
            'fa-search-plus' => 'Search Plus',
            'fa-sellsy' => 'Sellsy',
            'fa-send' => 'Send',
            'fa-send-o' => 'Send O',
            'fa-server' => 'Fa Server',
            'fa-share' => 'Share',
            'fa-share-alt' => 'Share Alt',
            'fa-share-alt-square' => 'Share Alt Square',
            'fa-share-square' => 'Share Square',
            'fa-share-square-o' => 'Share Square O',
            'fa-shield' => 'Shield',
            'fa-ship' => 'Ship',
            'fa-shirtsinbulk' => 'Shirtsinbulk',
            'fa-shopping-cart' => 'Shopping Cart',
            'fa-signal' => 'Signal',
            'fa-sign-in' => 'Sign In',
            'fa-sign-out' => 'Sign Out',
            'fa-simplybuilt' => 'Simplybuilt',
            'fa-sitemap' => 'Sitemap',
            'fa-skyatlas' => 'Skyatlas',
            'fa-skype' => 'Skype',
            'fa-slack' => 'Slack',
            'fa-sliders' => 'Sliders',
            'fa-smile-o' => 'Smile O',
            'fa-sort' => 'Sort',
            'fa-sort-alpha-asc' => 'Sort Alpha Asc',
            'fa-sort-alpha-desc' => 'Sort Alpha Desc',
            'fa-sort-amount-asc' => 'Sort Amount Asc',
            'fa-sort-amount-desc' => 'Sort Amount Desc',
            'fa-sort-asc' => 'Sort Asc',
            'fa-sort-desc' => 'Sort Desc',
            'fa-sort-down' => 'Sort Down',
            'fa-sort-numeric-asc' => 'Sort Numeric Asc',
            'fa-sort-numeric-desc' => 'Sort Numeric Desc',
            'fa-sort-up' => 'Sort Up',
            'fa-soundcloud' => 'Soundcloud',
            'fa-space-shuttle' => 'Space Shuttle',
            'fa-spinner' => 'Spinner',
            'fa-spoon' => 'Spoon',
            'fa-spotify' => 'Spotify',
            'fa-square' => 'Square',
            'fa-square-o' => 'Square O',
            'fa-stack-exchange' => 'Stack Exchange',
            'fa-stack-overflow' => 'Stack Overflow',
            'fa-star' => 'Star',
            'fa-star-half' => 'Star Half',
            'fa-star-half-empty' => 'Star Half Empty',
            'fa-star-half-full' => 'Star Half Full',
            'fa-star-half-o' => 'Star Half O',
            'fa-star-o' => 'Star O',
            'fa-steam' => 'Steam',
            'fa-steam-square' => 'Steam Square',
            'fa-step-backward' => 'Step Backward',
            'fa-step-forward' => 'Step Forward',
            'fa-stethoscope' => 'Stethoscope',
            'fa-stop' => 'Stop',
            'fa-street-view' => 'Street View',
            'fa-strikethrough' => 'Strikethrough',
            'fa-stumbleupon' => 'Stumbleupon',
            'fa-stumbleupon-circle' => 'Stumbleupon Circle',
            'fa-subscript' => 'Subscript',
            'fa-subway' => 'Fa Subway',
            'fa-suitcase' => 'Suitcase',
            'fa-sun-o' => 'Sun O',
            'fa-superscript' => 'Superscript',
            'fa-support' => 'Support',
            'fa-table' => 'Table',
            'fa-tablet' => 'Tablet',
            'fa-tachometer' => 'Tachometer',
            'fa-tag' => 'Tag',
            'fa-tags' => 'Tags',
            'fa-tasks' => 'Tasks',
            'fa-taxi' => 'Taxi',
            'fa-tencent-weibo' => 'Tencent Weibo',
            'fa-terminal' => 'Terminal',
            'fa-text-height' => 'Text Height',
            'fa-text-width' => 'Text Width',
            'fa-th' => 'Th',
            'fa-th-large' => 'Th Large',
            'fa-th-list' => 'Th List',
            'fa-thumbs-down' => 'Thumbs Down',
            'fa-thumbs-o-down' => 'Thumbs O Down',
            'fa-thumbs-o-up' => 'Thumbs O Up',
            'fa-thumbs-up' => 'Thumbs Up',
            'fa-thumb-tack' => 'Thumb Tack',
            'fa-ticket' => 'Ticket',
            'fa-times' => 'Times',
            'fa-times-circle' => 'Times Circle',
            'fa-times-circle-o' => 'Times Circle O',
            'fa-tint' => 'Tint',
            'fa-toggle-down' => 'Toggle Down',
            'fa-toggle-left' => 'Toggle Left',
            'fa-toggle-right' => 'Toggle Right',
            'fa-toggle-up' => 'Toggle Up',
            'fa-train' => 'Train',
            'fa-transgender' => 'Transgender',
            'fa-transgender-alt' => 'Transgender Alt',
            'fa-trash-o' => 'Trash O',
            'fa-tree' => 'Tree',
            'fa-trello' => 'Trello',
            'fa-trophy' => 'Trophy',
            'fa-truck' => 'Truck',
            'fa-try' => 'Try',
            'fa-tumblr' => 'Tumblr',
            'fa-tumblr-square' => 'Tumblr Square',
            'fa-turkish-lira' => 'Turkish Lira',
            'fa-twitter' => 'Twitter',
            'fa-twitter-square' => 'Twitter Square',
            'fa-umbrella' => 'Umbrella',
            'fa-underline' => 'Underline',
            'fa-undo' => 'Undo',
            'fa-university' => 'University',
            'fa-unlink' => 'Unlink',
            'fa-unlock' => 'Unlock',
            'fa-unlock-alt' => 'Unlock Alt',
            'fa-unsorted' => 'Unsorted',
            'fa-upload' => 'Upload',
            'fa-usd' => 'Usd',
            'fa-user' => 'User',
            'fa-user-md' => 'User Md',
            'fa-user-plus' => 'User Plus',
            'fa-users' => 'Users',
            'fa-user-secret' => 'User Secret',
            'fa-user-times' => 'User Times',
            'fa-venus' => 'Venus',
            'fa-venus-double' => 'Venus Double',
            'fa-venus-mars' => 'Venus Mars',
            'fa-viacoin' => 'Viacoin',
            'fa-video-camera' => 'Video Camera',
            'fa-vimeo-square' => 'Vimeo Square',
            'fa-vine' => 'Vine',
            'fa-vk' => 'Vk',
            'fa-volume-down' => 'Volume Down',
            'fa-volume-off' => 'Volume Off',
            'fa-volume-up' => 'Volume Up',
            'fa-warning' => 'Warning',
            'fa-wechat' => 'Wechat',
            'fa-weibo' => 'Weibo',
            'fa-weixin' => 'Weixin',
            'fa-whatsapp' => 'Whatsapp',
            'fa-wheelchair' => 'Wheelchair',
            'fa-windows' => 'Windows',
            'fa-won' => 'Won',
            'fa-wordpress' => 'Wordpress',
            'fa-wrench' => 'Wrench',
            'fa-xing' => 'Xing',
            'fa-xing-square' => 'Xing Square',
            'fa-yahoo' => 'Yahoo',
            'fa-yen' => 'Yen',
            'fa-youtube' => 'Youtube',
            'fa-youtube-play' => 'Youtube Play',
            'fa-youtube-square' => 'Youtube Square',
        );
        return $icon;
    }


//add extra fields to category edit form hook
add_action ( 'edit_category_form_fields', 'wpv_extra_category_fields');
//add extra fields to category edit form callback function
function wpv_extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
?>

<tr class="form-field">
<th scope="row" valign="top"><label for="map"><?php _e('Category Map','wpvoyager'); ?></label></th>
    <td>
    
    <select name="wpv_cat_meta[map]" id="wpv_cat_meta[map]" class="postform">
        <option <?php selected( $cat_meta['map'], 'global' ) ?> value="global">Global map (with posts only from category)</option>
        <option <?php selected( $cat_meta['map'], 'none' ) ?> value="none">None</option>
        <?php 
$my_posts = get_posts(array( 'post_type' => 'tp_maps', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'any' ) );
/* has posts */
if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
  foreach( $my_posts as $my_post ) {
    $post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
    echo '<option value="' . esc_attr( $my_post->ID ) . '"' . selected( $cat_meta['map'], $my_post->ID, false ) . '>' . $post_title . '</option>';
  }
} else {
    echo '<option value="">' . __( 'No Custom Maps Found', 'option-tree' ) . '</option>';
}
         ?>
    </select>
    <span class="description"><?php _e('Choose what to show on category page - global map, custom map or just posts','wpvoyager'); ?></span>
    </td>
</tr>
<?php
}

add_action ( 'edited_category', 'wpv_save_extra_category_fileds');
   // save extra category extra fields callback function
function wpv_save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['wpv_cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['wpv_cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['wpv_cat_meta'][$key])){
                $cat_meta[$key] = $_POST['wpv_cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}