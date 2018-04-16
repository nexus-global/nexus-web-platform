<?php

// if no title then add widget content wrapper to before widget
add_filter( 'dynamic_sidebar_params', 'check_sidebar_params' );
function check_sidebar_params( $params ) {
    global $wp_registered_widgets;

    $settings_getter = $wp_registered_widgets[ $params[0]['widget_id'] ]['callback'][0];
    $settings = $settings_getter->get_settings();
    $settings = $settings[ $params[1]['number'] ];
    
    if ( isset( $settings[ 'title' ] ) && !empty( $settings[ 'title' ] ) ) {
    
        $params[0][ 'after_widget' ] = '</div></aside>';
        $params[0][ 'after_title' ] = '</h4><div class="widget-inside">';
        $new_before_widget = str_replace("widget", "widget widget-out-title", $params[0][ 'before_widget' ]);
        $params[0][ 'before_widget' ] = $new_before_widget;
    }
    
    return $params;
}


add_action('widgets_init', 'wpvoyager_load_widgets'); // Loads widgets here
function wpvoyager_load_widgets() {
    register_widget('wpvoyager_author');
    register_widget('wpvoyager_popular');
    if(class_exists('NS_MC_Plugin')){ register_widget('WPVoyager_NS_Widget_MailChimp'); }
}


class wpvoyager_author extends WP_Widget {

  private $users_split_at = 200; //Do not run get_users() if there are more than 200 users on the website
  var $defaults;

  function wpvoyager_author() {
    $widget_ops = array( 'classname' => 'wpvoyager_author', 'description' => __('Use this widget to display author/user profile info', 'wpvoyager') );
    $control_ops = array( 'id_base' => 'wpvoyager_author' );
    parent::__construct( 'wpvoyager_author', __('WPVoyager Author Widget', 'wpvoyager'), $widget_ops, $control_ops );


    $this->defaults = array(
        'title' => __('About Author', 'wpvoyager'),
        'author' => 0,
        'single_author' => 0,
        'display_all_posts' => 1,
        'display_email' => 1,
        'link_text' => __('View all posts', 'wpvoyager'),
      );

    //Allow themes or plugins to modify default parameters
    $this->defaults = apply_filters('wpvoyager_author_modify_defaults', $this->defaults);

  }

  function widget( $args, $instance ) {

    extract( $args );

    $instance = wp_parse_args( (array) $instance, $this->defaults );

    //Check for user_id
    $user_id = $instance['author'];
    if($instance['single_author']){
      if(is_author()){
        $obj = get_queried_object();
        $user_id = $obj->data->ID;
      } elseif(is_single()){
        $obj = get_queried_object();
        $user_id = $obj->post_author;
      }
    }

    $author_link = get_author_posts_url(get_the_author_meta('ID',$user_id));
    $title =  apply_filters('widget_title', $instance['title'] );

    

    ?>
    <div class="widget">
      <div class="author-box">
        <?php echo get_avatar( get_the_author_meta('ID', $user_id), 95 ); ?>
        <span class="title"> <?php if ( !empty($title) ) { echo esc_html($title); } ?></span>
        <span class="name"><?php echo '<a href="'.esc_url($author_link).'">' . esc_html(get_the_author_meta('display_name', $user_id)) . '</a>'; ?></span>

        <?php if($instance['display_email']) : ?>
          <?php $email = get_the_author_meta( 'user_email', $user_id ) ?>
          <a href="mailto:<?php echo esc_url($email); ?>"><span class="contact"><?php echo esc_html($email); ?></span></a>
        <?php endif; ?>
        
        <?php echo wpautop(get_the_author_meta('description',$user_id)); ?>
        <a href="<?php echo esc_url($author_link); ?>" class="author_link"><?php echo esc_html($instance['link_text']); ?></a>
      </div>
    </div>
    <?php
   
  }


  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['author'] = absint( $new_instance['author'] );
    $instance['single_author'] = isset($new_instance['single_author']) ? 1 : 0;
    $instance['display_email'] = isset($new_instance['display_email']) ? 1 : 0;
    $instance['display_all_posts'] = isset($new_instance['display_all_posts']) ? 1 : 0;
    $instance['link_text'] = strip_tags( $new_instance['link_text'] );

    return $instance;
  }

  function form( $instance ) {

    $instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e('Title', 'wpvoyager'); ?>:</label>
      <input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" />
    </p>

    <p>

      <?php if( $this->count_users() <= $this->users_split_at ) : ?>

      <?php $authors = get_users(); ?>
      <label for="<?php echo esc_attr($this->get_field_id( 'author' )); ?>"><?php _e('Choose author/user', 'wpvoyager'); ?>:</label>
      <select name="<?php echo esc_attr($this->get_field_name( 'author' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'author' )); ?>" class="widefat">
      <?php foreach($authors as $author) : ?>
        <option value="<?php echo esc_attr($author->ID); ?>" <?php selected($author->ID, $instance['author']); ?>><?php echo esc_html($author->data->user_login); ?></option>
      <?php endforeach; ?>
      </select>

      <?php else: ?>

      <label for="<?php echo esc_attr($this->get_field_id( 'author' )); ?>"><?php _e('Enter author/user ID', 'wpvoyager'); ?>:</label>
      <input id="<?php echo esc_attr($this->get_field_id( 'author' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'author' )); ?>" value="<?php echo esc_attr($instance['author']); ?>" class="small-text" />

      <?php endif; ?>

    </p>

    <p>
      <input id="<?php echo esc_attr($this->get_field_id( 'single_author' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'single_author' )); ?>" value="1" <?php checked(1, $instance['single_author']); ?>/>
      <label for="<?php echo esc_attr($this->get_field_id( 'single_author' )); ?>"><?php _e('Automatically detect author', 'wpvoyager'); ?></label>
      <small class="howto"><?php _e('Use this option to show author of single post instead of pre-selected author', 'wpvoyager'); ?></small>
    </p>
    <h4><?php _e('Display Options', 'wpvoyager'); ?></h4>


    <ul>
      <li>
        <input id="<?php echo esc_attr($this->get_field_id( 'display_email' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'display_email' )); ?>" value="1" <?php checked(1, $instance['display_email']); ?>/>
        <label for="<?php echo esc_attr($this->get_field_id( 'display_email' )); ?>"><?php _e('Display email of author in widget', 'wpvoyager'); ?></label>
      </li>
      <li>
        <input id="<?php echo esc_attr($this->get_field_id( 'display_all_posts' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'display_all_posts' )); ?>" value="1" <?php checked(1, $instance['display_all_posts']); ?>/>
        <label for="<?php echo esc_attr($this->get_field_id( 'display_all_posts' )); ?>"><?php _e('Display "view all posts" link', 'wpvoyager'); ?></label>
      </li>

      <li>
        <label for="<?php echo esc_attr($this->get_field_id( 'link_text' )); ?>"><?php _e('Link text:', 'wpvoyager'); ?></label>
        <input id="<?php echo esc_attr($this->get_field_id( 'link_text' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'link_text' )); ?>" value="<?php echo esc_attr($instance['link_text']); ?>" class="widefat"/>
        <small class="howto"><?php _e('Specify text for "all posts" link', 'wpvoyager'); ?></small>
      </li>
    </ul>


  <?php
  }

  /* Check total number of users on the website */
  function count_users(){
    $user_count = count_users();
    if(isset($user_count['total_users']) && !empty($user_count['total_users'])){
      return $user_count['total_users'];
    }
    return 0;
  }
}


class wpvoyager_popular extends WP_Widget {

    function wpvoyager_popular() {
        $widget_ops = array('classname' => 'wpvoyager-popular', 'description' => 'Widget to display posts by selected order');
        $control_ops = array('width' => 300, 'height' => 350);
        parent::__construct('wpvoyager_popular', 'WPVoyager Posts', $widget_ops, $control_ops);

    $this->defaults = array(
        'title' => __('Popular Recipes', 'wpvoyager'),
        'number' => 3
      );
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $number = $instance['number'];
        $cat            = $instance['cat'];
        $sort_by        = $instance['sort_by'];
        $asc_sort_order = $instance['asc_sort_order'];
        $valid_sort_orders = array('date', 'title', 'comment_count', 'rand');
        if ( in_array($instance['sort_by'], $valid_sort_orders) ) {
          $sort_by = $instance['sort_by'];
          $sort_order = (bool) isset( $instance['asc_sort_order'] ) ? 'ASC' : 'DESC';
        } else {
          // by default, display latest first
          $sort_by = 'date';
          $sort_order = 'DESC';
        }
        $cat = $instance["cat"];
        echo $before_widget . $before_title . $instance['title'] . $after_title;


        ?>
        <ul class="recent-posts-widget">
        <?php echo self::showLatest($posts = $number, $cat, $sort_by, $sort_order); ?>
        </ul>
        <?php echo $after_widget;
    }


    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['number'] = strip_tags($new_instance['number']);
        $instance['cat'] = strip_tags( $new_instance['cat'] );
        $instance['sort_by'] = strip_tags( $new_instance['sort_by'] );
        $instance['asc_sort_order'] = strip_tags($new_instance['asc_sort_order']);
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => ''));
        $title = strip_tags($instance['title']);
        $number = esc_attr($instance['number']);
        $cat = esc_attr($instance['cat']);
        $sort_by = esc_attr($instance['sort_by']);
        $asc_sort_order = esc_attr($instance['asc_sort_order']);

        ?>
        <br>
         <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo  __('Title :', 'wpvoyager'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
          <label>
            <?php _e( 'Category' ); ?>:
            <?php wp_dropdown_categories( array( 'show_option_none' => __( 'Select optional category','wpvoyager' ), 'name' => $this->get_field_name("cat"), 'selected' => $instance["cat"] ) ); ?>
          </label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id("sort_by"); ?>">
            <?php _e('Sort by' , 'wpvoyager'); ?>:
            <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>">
              <option value="date"<?php selected( $instance["sort_by"], "date" ); ?>>Date</option>
              <option value="title"<?php selected( $instance["sort_by"], "title" ); ?>>Title</option>
              <option value="comment_count"<?php selected( $instance["sort_by"], "comment_count" ); ?>>Number of comments</option>
              <option value="rand"<?php selected( $instance["sort_by"], "rand" ); ?>>Random</option>
            </select>
          </label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id("asc_sort_order"); ?>">
            <input type="checkbox" class="checkbox" 
              id="<?php echo $this->get_field_id("asc_sort_order"); ?>" 
              name="<?php echo $this->get_field_name("asc_sort_order"); ?>"
              <?php checked( (bool) $instance["asc_sort_order"], true ); ?> />
                <?php _e( 'Reverse sort order (ascending)' , 'wpvoyager'); ?>
          </label>
        </p>
        <p><label>Set number of items to display
            <select id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>">
                <?php for ($i=1; $i < 10; $i++) { ?>
                <option <?php if ($number == $i) echo 'selected'; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </label>
      </p>
        <?php
    }

/**
     * Display Latest posts
     */
static function showLatest( $posts = 3,$cat, $sort_by, $sort_order ) {
    global $post;

    $latest = get_posts(
        array(
            'suppress_filters' => false,
            'ignore_sticky_posts' => 1,
            'numberposts' => $posts,
            'orderby' => $sort_by,
            'order' => $sort_order,
            'category__in' => $cat
            )
        );



    ob_start();

    $date_format = get_option('date_format');
    foreach($latest as $post) :
        setup_postdata($post);
    ?>
    <li>
      <?php if ( has_post_thumbnail() ) { ?>
        <div class="widget-thumb">
          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('widget');  ?></a>
        </div>
      <?php } ?>
      
      <div class="widget-text">
        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        <span><?php echo get_the_date(); ?></span>
      </div>
      <div class="clearfix"></div>
    </li>
   

    <?php endforeach;
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
}
}



/**
 * @author James Lafferty
 * @since 0.1
 */

class WPVoyager_NS_Widget_MailChimp extends WP_Widget {
    private $default_failure_message;
    private $default_loader_graphic = '/images/loader.gif';
    private $default_signup_text;
    private $default_success_message;
    private $default_title;
    private $successful_signup = false;
    private $subscribe_errors;
    private $wpvoyager_ns_mc_plugin;

    /**
     * @author James Lafferty
     * @since 0.1
     */
    public function WPVoyager_NS_Widget_MailChimp () {
        $this->default_failure_message = __('There was a problem processing your submission.','wpvoyager');
        $this->default_signup_text = __('Join','wpvoyager');
        $this->default_success_message = __('Thank you for joining our mailing list. Please check your email for a confirmation link.','wpvoyager');
        $this->default_title = __('Newsletter.','wpvoyager');
        $widget_options = array('classname' => 'widget_ns_mailchimp', 'description' => __( "Displays a sign-up form for a MailChimp mailing list.", 'wpvoyager'));
        parent::__construct('wpvoyager_ns_widget_mailchimp', __('WPVoyager MailChimp List Signup', 'wpvoyager'), $widget_options);
        $this->wpvoyager_ns_mc_plugin = NS_MC_Plugin::get_instance();
        $this->default_loader_graphic = get_template_directory_uri() . $this->default_loader_graphic;
        add_action('init', array(&$this, 'add_scripts'));
        add_action('parse_request', array(&$this, 'process_submission'));
    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function add_scripts () {
        wp_dequeue_script('ns-mc-widget');
        wp_enqueue_script('ns-mc-widget1', get_template_directory_uri() . '/js/mailchimp-widget.js', array('jquery'), false);
    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function form ($instance) {
        $mcapi = $this->wpvoyager_ns_mc_plugin->get_mcapi();
        if (false == $mcapi) {
            echo $this->wpvoyager_ns_mc_plugin->get_admin_notices();
        } else {
            $this->lists = $mcapi->lists();
            $defaults = array(
                'failure_message' => $this->default_failure_message,
                'title' => $this->default_title,
                'signup_text' => $this->default_signup_text,
                'success_message' => $this->default_success_message,
                'collect_first' => false,
                'collect_last' => false,
                'old_markup' => false
                );
            $vars = wp_parse_args($instance, $defaults);
            extract($vars);
            ?>
            <h3><?php echo  __('General Settings', 'wpvoyager'); ?></h3>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo  __('Title :', 'wpvoyager'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('desc'); ?>"><?php echo  __('Description :', 'wpvoyager'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>"><?php echo $desc; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('current_mailing_list'); ?>"><?php echo __('Select a Mailing List :', 'wpvoyager'); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id('current_mailing_list');?>" name="<?php echo $this->get_field_name('current_mailing_list'); ?>">
                    <?php
                    foreach ($this->lists['data'] as $key => $value) {
                        $selected = (isset($current_mailing_list) && $current_mailing_list == $value['id']) ? ' selected="selected" ' : '';
                        ?>
                        <option <?php echo $selected; ?>value="<?php echo $value['id']; ?>"><?php echo __($value['name'], 'wpvoyager'); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </p>
            <p><strong>N.B.</strong><?php echo  __('This is the list your users will be signing up for in your sidebar.', 'wpvoyager'); ?></p>
            <p>
                <label for="<?php echo $this->get_field_id('signup_text'); ?>"><?php echo __('Sign Up Button Text :', 'wpvoyager'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('signup_text'); ?>" name="<?php echo $this->get_field_name('signup_text'); ?>" value="<?php echo esc_attr($signup_text); ?>" />
            </p>
            <h3><?php echo __('Personal Information', 'wpvoyager'); ?></h3>
            <p><?php echo __("These fields won't (and shouldn't) be required. Should the widget form collect users' first and last names?", 'wpvoyager'); ?></p>
            <p>
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('collect_first'); ?>" name="<?php echo $this->get_field_name('collect_first'); ?>" <?php echo  checked($collect_first, true, false); ?> />
                <label for="<?php echo $this->get_field_id('collect_first'); ?>"><?php echo  __('Collect first name.', 'wpvoyager'); ?></label>
                <br />
                <input type="checkbox" class="checkbox" id="<?php echo  $this->get_field_id('collect_last'); ?>" name="<?php echo $this->get_field_name('collect_last'); ?>" <?php echo checked($collect_last, true, false); ?> />
                <label><?php echo __('Collect last name.', 'wpvoyager'); ?></label>
            </p>
            <h3><?php echo __('Notifications', 'wpvoyager'); ?></h3>
            <p><?php echo  __('Use these fields to customize what your visitors see after they submit the form', 'wpvoyager'); ?></p>
            <p>
                <label for="<?php echo $this->get_field_id('success_message'); ?>"><?php echo __('Success :', 'wpvoyager'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('success_message'); ?>" name="<?php echo $this->get_field_name('success_message'); ?>"><?php echo $success_message; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('failure_message'); ?>"><?php echo __('Failure :', 'wpvoyager'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('failure_message'); ?>" name="<?php echo $this->get_field_name('failure_message'); ?>"><?php echo $failure_message; ?></textarea>
            </p>
            <?php

        }
    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function process_submission () {

        if (isset($_GET[$this->id_base . '_email'])) {

            header("Content-Type: application/json");

            //Assume the worst.
            $response = '';
            $result = array('success' => false, 'error' => $this->get_failure_message($_GET['ns_mc_number']));

            $merge_vars = array();

            if (! is_email($_GET[$this->id_base . '_email'])) { //Use WordPress's built-in is_email function to validate input.

                $response = json_encode($result); //If it's not a valid email address, just encode the defaults.

            } else {

                $mcapi = $this->wpvoyager_ns_mc_plugin->get_mcapi();

                if (false == $this->wpvoyager_ns_mc_plugin) {

                    $response = json_encode($result);

                } else {

                    if (isset($_GET[$this->id_base . '_first_name']) && is_string($_GET[$this->id_base . '_first_name'])) {

                        $merge_vars['FNAME'] = $_GET[$this->id_base . '_first_name'];

                    }

                    if (isset($_GET[$this->id_base . '_last_name']) && is_string($_GET[$this->id_base . '_last_name'])) {

                        $merge_vars['LNAME'] = $_GET[$this->id_base . '_last_name'];

                    }

                    $subscribed = $mcapi->listSubscribe($this->get_current_mailing_list_id($_GET['ns_mc_number']), $_GET[$this->id_base . '_email'], $merge_vars);

                    if (false == $subscribed) {

                        $response = json_encode($result);

                    } else {

                        $result['success'] = true;
                        $result['error'] = '';
                        $result['success_message'] =  $this->get_success_message($_GET['ns_mc_number']);
                        $response = json_encode($result);

                    }

                }

            }

            exit($response);

        } elseif (isset($_POST[$this->id_base . '_email'])) {

            $this->subscribe_errors = '<div class="notification closeable error"><p>'  . $this->get_failure_message($_POST['ns_mc_number']) .  '</p></div>';

            if (! is_email($_POST[$this->id_base . '_email'])) {

                return false;

            }

            $mcapi = $this->wpvoyager_ns_mc_plugin->get_mcapi();

            if (false == $mcapi) {

                return false;

            }

            if (is_string($_POST[$this->id_base . '_first_name'])  && '' != $_POST[$this->id_base . '_first_name']) {

                $merge_vars['FNAME'] = strip_tags($_POST[$this->id_base . '_first_name']);

            }

            if (is_string($_POST[$this->id_base . '_last_name']) && '' != $_POST[$this->id_base . '_last_name']) {

                $merge_vars['LNAME'] = strip_tags($_POST[$this->id_base . '_last_name']);

            }

            $subscribed = $mcapi->listSubscribe($this->get_current_mailing_list_id($_POST['ns_mc_number']), $_POST[$this->id_base . '_email'], $merge_vars);

            if (false == $subscribed) {

                return false;

            } else {

                $this->subscribe_errors = '';

                //setcookie($this->id_base . '-' . $this->number, $this->hash_mailing_list_id(), time() + 31556926);

                $this->successful_signup = true;

                $this->signup_success_message = '<p>' . $this->get_success_message($_POST['ns_mc_number']) . '</p>';

                return true;

            }

        }

    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function update ($new_instance, $old_instance) {

        $instance = $old_instance;

        $instance['collect_first'] = ! empty($new_instance['collect_first']);

        $instance['collect_last'] = ! empty($new_instance['collect_last']);

        $instance['current_mailing_list'] = esc_attr($new_instance['current_mailing_list']);

        $instance['failure_message'] = esc_attr($new_instance['failure_message']);

        $instance['signup_text'] = esc_attr($new_instance['signup_text']);

        $instance['success_message'] = esc_attr($new_instance['success_message']);

        $instance['title'] = esc_attr($new_instance['title']);

        $instance['desc'] = esc_attr($new_instance['desc']);

        return $instance;

    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function widget ($args, $instance) {

        extract($args);

        echo $before_widget . $before_title . $instance['title'] . $after_title;

        if ($this->successful_signup) {
            echo $this->signup_success_message;
        } else {
            ?>
            <p><?php echo $instance['desc']; ?></p>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="<?php echo $this->id_base . '_form-' . $this->number; ?>" method="post">
                <?php echo $this->subscribe_errors;?>
                <?php
                if ($instance['collect_first']) {
                    ?>
                    <input value="<?php echo __('First Name :', 'wpvoyager'); ?>" onblur="if(this.value=='')this.value='<?php echo __('First Name :', 'wpvoyager'); ?>';" onfocus="if(this.value=='<?php echo __('First Name :', 'wpvoyager'); ?>')this.value='';" type="text" name="<?php echo $this->id_base . '_first_name'; ?>" />
                    <br />
                    <br />
                    <?php
                }
                if ($instance['collect_last']) {
                    ?>
                    <input value="<?php echo __('Last Name :', 'wpvoyager'); ?>" onblur="if(this.value=='')this.value='<?php echo __('Last Name :', 'wpvoyager'); ?>';" onfocus="if(this.value=='<?php echo __('Last Name :', 'wpvoyager'); ?>')this.value='';" type="text" name="<?php echo $this->id_base . '_last_name'; ?>" />
                    <br />
                    <br />
                    <?php
                }
                ?>
                <input type="hidden" name="ns_mc_number" value="<?php echo $this->number; ?>" />
                <input class="newsletter-btn" type="submit" name="<?php echo __($instance['signup_text'], 'wpvoyager'); ?>" value="<?php echo __($instance['signup_text'], 'wpvoyager'); ?>" />
                <input class="newsletter" onblur="if(this.value=='')this.value='mail@example.com';" onfocus="if(this.value=='mail@example.com')this.value='';" value="mail@example.com" id="<?php echo $this->id_base; ?>-email-<?php echo $this->number; ?>" type="text" name="<?php echo $this->id_base; ?>_email" />
                
            </form>
            <script>
            /* ----------------- Start Document ----------------- */
            (function($){
              "use strict";
              $(document).ready(function(){
                $('#<?php echo $this->id_base; ?>_form-<?php echo $this->number; ?>').ns_mc_widget({"url" : "<?php echo $_SERVER['PHP_SELF']; ?>", "cookie_id" : "<?php echo $this->id_base; ?>-<?php echo $this->number; ?>", "cookie_value" : "<?php echo $this->hash_mailing_list_id(); ?>", "loader_graphic" : "<?php echo $this->default_loader_graphic; ?>"}); 
              });
            })(this.jQuery);

            </script>
            <?php
        }
        echo $after_widget;


    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    private function hash_mailing_list_id () {

        $options = get_option($this->option_name);

        $hash = md5($options[$this->number]['current_mailing_list']);

        return $hash;

    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    private function get_current_mailing_list_id ($number = null) {

        $options = get_option($this->option_name);

        return $options[$number]['current_mailing_list'];

    }

    /**
     * @author James Lafferty
     * @since 0.5
     */

    private function get_failure_message ($number = null) {

        $options = get_option($this->option_name);

        return $options[$number]['failure_message'];

    }

    /**
     * @author James Lafferty
     * @since 0.5
     */

    private function get_success_message ($number = null) {

        $options = get_option($this->option_name);

        return $options[$number]['success_message'];

    }

}


function WPRCWA_recent_comments() {
    //unregister_widget("WP_Widget_Recent_Comments");
    register_widget("WPV_Widget_Recent_Comments");
}
add_action("widgets_init", "WPRCWA_recent_comments");

class WPV_Widget_Recent_Comments extends WP_Widget_Recent_Comments {

    function widget( $args, $instance ) {
        global $comments, $comment;

        $cache = wp_cache_get('widget_recent_comments', 'widget');

        if ( ! is_array( $cache ) )
            $cache = array();

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }

         extract($args, EXTR_SKIP);
         $output = '';
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Recent Comments','wpvoyager' ) : $instance['title'], $instance, $this->id_base );

        if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
             $number = 5;

        $comments = get_comments( apply_filters( 'widget_comments_args', array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish', 'type' => 'comment' ) ) );
        $output .= $before_widget;
        if ( $title )
            $output .= $before_title . $title . $after_title;

        $output .= '<ul id="recentcomments">';
        if ( $comments ) {
            // Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
            $post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
            _prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

            foreach ( (array) $comments as $comment) {
                $output .=  '
                <li class="recentcomments">
                 <div class="widget-thumb">'. get_avatar($comment->comment_author_email, 80). '</div>'.
                '<div class="widget-text"><h4>'. get_comment_author_link(). ':</h4>'.
                '<a href="' . esc_url( get_comment_link($comment->comment_ID) ). '">'. string_limit_words(strip_tags($comment->comment_content), 10).'</a></div><div class="clearfix"></div></li>';
            }
         }
          
  
      
     
        $output .= '</ul>';
        $output .= $after_widget;

        echo $output;
        $cache[$args['widget_id']] = $output;
        wp_cache_set('widget_recent_comments', $cache, 'widget');
    }

}

add_filter('wp_list_categories', 'cat_count_inline');
  function cat_count_inline($links) {
  $links = str_replace('</a> (', '</a><span class="count">(', $links);
  $links = str_replace(')', ')</span>', $links);
  return $links;
}