<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WPVoyager
 */


function add_menuid ($page_markup) {
	preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
	$divclass = 'menu';
	$toreplace = array('<div class="menu">', '</div>');
	$new_markup = str_replace($toreplace, '', $page_markup);
	$new_markup = preg_replace('/^<ul>/i', '<ul id="responsive">', $new_markup);
	return $new_markup; 
}

add_filter('wp_page_menu', 'add_menuid');


if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'wpvoyager' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( esc_html__( 'Older posts', 'wpvoyager' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( esc_html__( 'Newer posts', 'wpvoyager' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;


if (!function_exists('wpv_number_to_width')) :
function wpv_number_to_width($width) {
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

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'wpvoyager' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;


if ( ! function_exists( 'wpv_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function wpv_post_nav() {
	$isthumbnails = ot_get_option('wpnavthumbs','on');


	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	if ($isthumbnails=='on') { ?>
		<div class="clearfix"></div>
		<div class="margin-top-50"></div>
		<nav class="navigation post-navigation" role="navigation">
			<h3 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'wpv' ); ?></h3>
			<div class="nav-links">
				<?php 
				    if($previous) { ?>
				        <div class="nav-previous">
				        	<a href="<?php echo get_permalink($previous->ID); ?>" class="nav-image"><?php echo get_the_post_thumbnail( $previous->ID, 'map'); ?><span><i class="fa fa-angle-left"></i>&nbsp;&nbsp;<?php echo get_the_title($previous->ID); ?></span></a>
				        </div>
				    <?php
			        } // end if
			       
			        if($next) { ?>
				        <div class="nav-next">
				        	<a href="<?php echo get_permalink($next->ID); ?>" class="nav-image"><?php echo get_the_post_thumbnail( $next->ID, 'map'); ?><span><?php echo get_the_title($next->ID); ?>&nbsp;&nbsp;<i class="fa fa-angle-right"></i></span></a>
				        </div>
				    <?php
			        } // end if
			    ?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
	<?php } else {
	?>
		<div class="clearfix"></div>
		<div class="margin-top-50"></div>
		<nav class="navigation post-navigation" role="navigation">
			<h3 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'wpv' ); ?></h3>
			<div class="nav-links">
				<?php
					previous_post_link( '<div class="nav-previous">%link</div>','<i class="fa fa-angle-left"></i>&nbsp;&nbsp;%title' );
					next_post_link(     '<div class="nav-next">%link</div>',     '%title&nbsp;&nbsp;<i class="fa fa-angle-right"></i>' );
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
	<?php
	}
}
endif;


if ( ! function_exists( 'wpvoyager_posted_on' ) ) :

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since astrum 1.0
 */

function wpvoyager_posted_on() {
	echo '<header class="meta">';
  if(is_single()) {
    $metas = ot_get_option('pp_meta_single',array());
    if (in_array("author", $metas)) {
        echo '<span itemscope itemtype="http://data-vocabulary.org/Person">';
        echo '<i class="fa fa-user"></i>'. __('By','trizzy'). ' <a class="author-link" itemprop="url" rel="author" href="'.esc_url(get_author_posts_url(get_the_author_meta('ID' ))).'">'; the_author_meta('display_name'); echo'</a>';
        echo '</span>';
    }
    if (in_array("date", $metas)) {
	    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

	    echo '<span><i class="fa fa-calendar"></i>'; echo $time_string; echo '</span>';
	    
	}
    if (in_array("cat", $metas)) {
      if(has_category()) { echo '<span><i class="fa fa-tag"></i>'; the_category(', '); echo '</span>'; }
    }
    if (in_array("tags", $metas)) {
      if(has_tag()) { echo '<span><i class="fa fa-tag"></i>'; the_tags('',', '); echo '</span>'; }
    }
    if (in_array("com", $metas)) {
      echo '<span><i class="fa fa-comment"></i>'; comments_popup_link( __('With 0 comments','trizzy'), __('With 1 comment','trizzy'), __('With % comments','trizzy'), 'comments-link', __('Comments are off','trizzy')); echo '</span>';
    }
  } else {
    $metas = ot_get_option('pp_blog_meta',array());

   if (in_array("author", $metas)) {
      echo '<span itemscope itemtype="http://data-vocabulary.org/Person">';
      if (in_array("author", $metas)) {
        echo '<i class="fa fa-user"></i>'. __('By','trizzy'). ' <a class="author-link" itemprop="url" rel="author" href="'.esc_url(get_author_posts_url(get_the_author_meta('ID' ))).'">'; the_author_meta('display_name'); echo'</a>';
      }
      echo '</span>';
    }
    if (in_array("date", $metas)) {
	    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

	    echo '<span><i class="fa fa-calendar"></i>'; echo $time_string; echo '</span>';
	    
	}
    if (in_array("categories", $metas)) {
      if(has_category()) { echo '<span><i class="fa fa-tag"></i>'; the_category(', '); echo '</span>'; }
    }
    if (in_array("tags", $metas)) {
      if(has_tag()) { echo '<span><i class="fa fa-tag"></i>'; the_tags('',', '); echo '</span>'; }
    }
    if (in_array("comments", $metas)) {
      echo '<span><i class="fa fa-comment"></i>'; comments_popup_link( __('With 0 comments','trizzy'), __('With 1 comment','trizzy'), __('With % comments','trizzy'), 'comments-link', __('Comments are off','trizzy')); echo '</span>';
    }
  }
  echo '</header>';
}
endif;


if ( ! function_exists( 'wpvoyager_full_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function wpvoyager_full_posted_on() {
 	$metas = ot_get_option('pp_meta_single',array());

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'wpvoyager' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = '<span class="author vcard">By <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
		

	$output = '';

	if (in_array("date", $metas)) {  $output .='<span class="posted-on">' . $posted_on . '</span> '; }
	if (in_array("author", $metas)) {  $output .= $byline ; }
	echo $output;
}
endif;


if ( ! function_exists( 'wpvoyager_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function wpvoyager_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'wpvoyager' ) );
		if ( $categories_list && wpvoyager_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'wpvoyager' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'wpvoyager' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'wpvoyager' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'wpvoyager' ), esc_html__( '1 Comment', 'wpvoyager' ), esc_html__( '% Comments', 'wpvoyager' ) );
		echo '</span>';
	}

	edit_post_link( esc_html__( 'Edit', 'wpvoyager' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'wpvoyager' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'wpvoyager' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'wpvoyager' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'wpvoyager' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'wpvoyager' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'wpvoyager' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'wpvoyager' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'wpvoyager' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'wpvoyager' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'wpvoyager' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'wpvoyager' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'wpvoyager' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'wpvoyager' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'wpvoyager' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'wpvoyager' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'wpvoyager' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'wpvoyager' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'wpvoyager' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'wpvoyager' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'wpvoyager' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'wpvoyager' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;  // WPCS: XSS OK.
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function wpvoyager_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'wpvoyager_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'wpvoyager_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so wpvoyager_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so wpvoyager_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in wpvoyager_categorized_blog.
 */
function wpvoyager_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'wpvoyager_categories' );
}
add_action( 'edit_category', 'wpvoyager_category_transient_flusher' );
add_action( 'save_post',     'wpvoyager_category_transient_flusher' );


/**
 * Limits number of words from string
 *
 * @since astrum 1.0
 */
if ( ! function_exists( 'string_limit_words' ) ) :
function string_limit_words($string, $word_limit) {
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
        //add a ... at last article when more than limit word count
        return implode(' ', $words) ;
    } else {
        //otherwise
        return implode(' ', $words);
    }
}
endif;

function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');


/**
 * Limits number of words from string
 *
 * @since astrum 1.0
 */
if ( ! function_exists( 'wpv_related_posts' ) ) :
function wpv_related_posts($post) {
	$status = ot_get_option('pp_related','on');
	if($status == 'on'){
	    $orig_post = $post;
	    global $post;
	    $categories = get_the_category($post->ID);
	    echo '<div class="related-posts">';
	    if ($categories) {
	        $category_ids = array();
	        foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
	        $args=array(
	            'category__in' => $category_ids,
	            'post__not_in' => array($post->ID),
	            'meta_key'    => '_thumbnail_id',
	            'posts_per_page'=> 3, // Number of related posts that will be shown.
	            'ignore_sticky_posts'=>1
	        );
	        $my_query = new wp_query( $args );
	        if( $my_query->have_posts() ) { ?>
	        <h4><?php echo ot_get_option('pp_relatedtext','Related Posts'); ?></h4>
	        <ul>
	        <?php
	            while( $my_query->have_posts() ) {
	                $my_query->the_post();?>
	                <li>
						<a href="<?php the_permalink(); ?>" class="overlay"> <?php the_post_thumbnail(); ?></a>
						<div>
							<a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
							<?php 	
							$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
							if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
								$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
							}

							$time_string = sprintf( $time_string,
								esc_attr( get_the_date( 'c' ) ),
								esc_html( get_the_date() ),
								esc_attr( get_the_modified_date( 'c' ) ),
								esc_html( get_the_modified_date() )
							);
							?>
							<span><?php echo $time_string; ?></span>
						</div>
					</li>
	                <?php
	            }

	        }
	    }
	    $post = $orig_post;
	    wp_reset_query();
	    echo '</ul></div>';
	} //eof if on
}
endif;


function wpv_header_class( $class = '' ) {
        // Separates classes with a single space, collates classes for body element
        echo 'class="' . join( ' ', get_wpv_header_class( $class ) ) . '"';
}


/**
 * Retrieve the classes for the body element as an array.
 *
 * @since 2.8.0
 *
 * @param string|array $class One or more classes to add to the class list.
 * @return array Array of classes.
 */
function get_wpv_header_class( $class = '' ) {
	global $wp_query;

	$classes = array();
	$home = ot_get_option('pp_front_page_setup','global');
	
	$colorscheme = get_theme_mod('wpvoyager_header_scheme','black');
	$classes[] = $colorscheme;
	if(ot_get_option('pp_header_boxed','off') == 'on') {
		if ( class_exists( 'TravellerPress' ) ) {
			if(is_home() || is_front_page()){
				if ($home == 'global' || $home == 'single') {
					$classes[] = 'boxed';
				}
			}
			if(is_singular()) {
				$post_id = $wp_query->get_queried_object_id();
				$type = get_post_meta($post_id, "pp_post_type", $single = true); 
				if ($type == 'map' || $type == 'photo') {
					$classes[] = 'boxed';
				}
			}
		}
	}
	if(is_singular()) {
			$post_id = $wp_query->get_queried_object_id();
			
			$type = get_post_meta($post_id, "pp_post_type", $single = true); 
			
			if ($type == 'map' || $type == 'photo') {
				$classes[] = 'abs';
			}
	}

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	$classes = apply_filters( 'wpv_class', $classes, $class );

	return array_unique( $classes );
}

add_filter('wpv_class','fix_header_template_class_names');
function fix_header_template_class_names($classes) {
	if(is_page_template('template-map-sidebar.php') || is_page_template('template-slider.php') ) {
    	return array('black');
    } else {
    	return $classes;
    }
}


/**
 * The Breadcrumbs function
*/
if ( ! function_exists( 'dimox_breadcrumbs' ) ) :
  function dimox_breadcrumbs() {
    $showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = ''; // delimiter between crumbs
    $introtext = __('You are here:','wpvoyager');
    $home = __('Home','wpvoyager'); // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<li class="current_element">'; // tag before the current crumb
    $after = '</li>'; // tag after the current crumb

    global $post;
    $homeLink = home_url();
    $frontpageuri = wpvoyager_get_posts_page('url');
    $frontpagetitle = ot_get_option('pp_blog_page');
    $output = '';
    if (is_home() || is_front_page()) {
      if ($showOnHome == 1)
        echo '<nav id="breadcrumbs"><ul>';
    	echo '<li id="bc_intro">'.$introtext.'</li>';
        echo '<li id="bc_home"><a href="' . esc_url($homeLink) . '">' . $home . '</a></li>';
        echo '<li>' . $frontpagetitle . '</li>';
        echo '</ul></nav>';
    } else {

      $output .= '<nav id="breadcrumbs"><ul><li>'.$introtext.'</li><li><a href="' . $homeLink . '">' . $home . '</a>' . $delimiter . '</li> ';
      if(function_exists('is_shop')) {
        if(is_shop()) {
          $shop_page_id = wc_get_page_id( 'shop' );
          $output .= '<li><a href="'.get_permalink( $shop_page_id) .'">'.__('Shop','wpvoyager').'</a></li>';
        }
      }
      if ( is_category() ) {
        $thisCat = get_category(get_query_var('cat'), false);
        if ($thisCat->parent != 0) $output .= '<li>'.get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ').'<li>';
        $output .= $before . __('Archive by category','wpvoyager').' "' . single_cat_title('', false) . '"' . $after;

      } elseif ( is_search() ) {
        $output .= $before . __('Search results for','wpvoyager').' "' . get_search_query() . '"' . $after;

      } elseif ( is_day() ) {
        $output .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . '</li> ';
        $output .= '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . '</li> ';
        $output .= $before . get_the_time('d') . $after;

      } elseif ( is_month() ) {
        $output .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' </li>';
        $output .= $before . get_the_time('F') . $after;

      } elseif ( is_year() ) {
        $output .= $before . get_the_time('Y') . $after;

      } elseif ( is_single() && !is_attachment() ) {
        if ( get_post_type() != 'post' ) {
          $post_type = get_post_type_object(get_post_type());
          $slug = $post_type->rewrite;
          $output .= '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li>';
          if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
        } else {
          $cat = get_the_category(); $cat = $cat[0];
          $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
          if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
          $output .= '<li>'.$cats.'</li>';
          if ($showCurrent == 1) $output .= $before . get_the_title() . $after;
        }

      } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
        $post_type = get_post_type_object(get_post_type());
        $output .= $before . $post_type->labels->singular_name . $after;

      } elseif ( is_attachment() ) {
        $parent = get_post($post->post_parent);
        //$cat = get_the_category($parent->ID); $cat = $cat[0];
        //$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        $output .= '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>';
        if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

      } elseif ( is_page() && !$post->post_parent ) {
        if ($showCurrent == 1) $output .= $before . get_the_title() . $after;

      } elseif ( is_page() && $post->post_parent ) {
        $parent_id  = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
          $page = get_page($parent_id);
          $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
          $parent_id  = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        for ($i = 0; $i < count($breadcrumbs); $i++) {
          $output .= $breadcrumbs[$i];
          if ($i != count($breadcrumbs)-1) $output .= ' ' . $delimiter . ' ';
        }
        if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

      } elseif ( is_tag() ) {
        $output .= $before . __('Posts tagged','wpvoyager').' "' . single_tag_title('', false) . '"' . $after;

      } elseif ( is_author() ) {
       global $author;
       $userdata = get_userdata($author);
       $output .= $before . __('Articles posted by ','wpvoyager') . $userdata->display_name . $after;

     } elseif ( is_404() ) {
      $output .= $before .  __('Error 404','wpvoyager') . $after;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ' (';
        $output .= '<li>'.__('Page','wpvoyager') . ' ' . get_query_var('paged').'</li>';
        if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ')';
  }

  $output .= '</ul></nav>';
  return $output;
  }
  } // end dimox_breadcrumbs()
endif;

if ( ! function_exists( 'wpvoyager_get_posts_page' ) ) :

function wpvoyager_get_posts_page($info) {
  if( get_option('show_on_front') == 'page') {
    $posts_page_id = get_option( 'page_for_posts');
    $posts_page = get_page( $posts_page_id);
    $posts_page_title = $posts_page->post_title;
    $posts_page_url = get_page_uri($posts_page_id  );
  }
  else $posts_page_title = $posts_page_url = '';

  if ($info == 'url') {
    return $posts_page_url;
  } elseif ($info == 'title') {
    return $posts_page_title;
  } else {
    return false;
  }
}
endif;


if ( ! function_exists( 'wpv_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since astrum 1.0
 */
function wpv_comment( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
  switch ( $comment->comment_type ) :
    case 'pingback' :
    case 'trackback' :
  ?>
  <li class="post pingback">
    <p><?php _e( 'Pingback:', 'wpv' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'wpv' ), ' ' ); ?></p>
  <?php
      break;
    default :
  ?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
       <div id="comment-<?php comment_ID(); ?>" class="comment">
       <?php echo get_avatar( $comment, 70 ); ?>
       <div class="comment-content"><div class="arrow-comment"></div>
            <div class="comment-by"><?php printf( '<strong>%s</strong>', get_comment_author_link() ); ?>  <span class="date"> <?php printf( __( '%1$s at %2$s', 'wpv' ), get_comment_date(), get_comment_time() ); ?></span>
               <?php comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="fa fa-reply"></i> Reply', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div>
            <?php comment_text(); ?>

        </div>
        </div>
  <?php
      break;
  endswitch;
}
endif; // ends check for wpv_comment()



if ( ! function_exists( 'wpv_welcome' ) ) :
	function wpv_welcome(){
		$status = ot_get_option('pp_welcome_box','off');
		if($status == 'on') {
			$position = ot_get_option('pp_front_map_position','behind');
			$title = ot_get_option('pp_welcome_title');
			$text = ot_get_option('pp_welcome_text');
			$output = '<div class="container"><div class="sixteen columns"><div class="welcome-box '.esc_attr($position).'"><h2>'.$title.'</h2><div class="welcome-text">'.do_shortcode(wpautop($text)).'</div></div></div></div>';
			return $output;
		}
		return false;
	}
endif;



?>