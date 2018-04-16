<?php

// action - CREATE new slider
if ( array_key_exists( 'action', $_GET ) && 'save_slides' == $_GET['action'] && array_key_exists( '_wpnonce', $_REQUEST ) ) {

    if ( wp_verify_nonce( $_REQUEST['_wpnonce'], 'posts' ) ) {

        if (  ! empty( $_POST['slider_name'] ) ) {

            if(isset($_POST['cats']) && !empty($_POST['cats'])) { $cats = $_POST['cats'];} else { $cats = '';}
            if(isset($_POST['tags']) && !empty($_POST['tags'])) { $tags = $_POST['tags'];} else { $tags = '';}
            // add the new slide group
            //check if slider with that name exists
            $slidersettings = array(
                'slidername' => $_POST['slider_name'],
                'slidertype' => 'posts',
                'posts_type' => $_POST['posts_type'],
                'posts_order' => $_POST['posts_order'],
                'posts_number' => $_POST['posts_number'],
                'cats' =>  $cats,
                'tags' => $tags,
                'autoPlay' => $_POST['autoPlay'],
                'paginationSpeed' => $_POST['paginationSpeed'],
                'slideSpeed' => $_POST['slideSpeed'],
       
                );
            update_option( 'cp_slider_'.$_POST['slider_name'], $slidersettings );
        }
    }
}

$current_slider = get_option( 'cp_slider_'.$_GET['slider']);
if($current_slider) {
    $selectedcats = $current_slider['cats'];
    $selectedtags = $current_slider['tags'];
} else {
    $selectedcats =  array();
    $selectedtags =  array();
}
?>

<form  name="new-slider-form" id="new-slider-form" method="post" action="admin.php?page=cp-slider&slider=<?php echo esc_attr($_GET['slider']); ?>&action=save_slides">
    <p>This slider will displayed featured images from selected posts:</p>
    <h2>Slider content settings</h2>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Posts:</th>
            <td>
                <select name="posts_type" id="posts_type">
                    <option <?php selected( $current_slider['posts_type'], 'latest' ); ?> value="latest">Latest</option>
                    <option <?php selected( $current_slider['posts_type'], 'random' ); ?> value="random">Random</option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Number of posts</th>
            <td>
                <select name="posts_number" id="posts_number">
                    <?php for ($i=0; $i < 20; $i++) { ?>
                    <option <?php selected( $current_slider['posts_number'], $i ); ?> value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></option>
                    <?php } ?>

                </select>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">Order posts by</th>
            <td>
                <?php
                $orderby = array(
                    'none' => 'none' ,
                    'ID' => 'ID' ,
                    'author' => 'author' ,
                    'title' => 'title' ,
                    'name' => 'name' ,
                    'date' => 'date' ,
                    'modified' => 'modified' ,
                    'comment_count' => 'comment_count' ,
                    );
                    ?>
                    <select name="posts_order" id="posts_order">
                        <?php foreach ($orderby as $key => $value) { ?>
                        <option <?php selected( $current_slider['posts_order'], $key ); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Specify categories</th>
                <td>
                    <select id="cpsliderselect" multiple="multiple" name="cats[]" title="Click to select categories for slider">
                        <?php
                        $args = array(
                          'hide_empty' => '0',
                          );
                        $categories = get_categories($args);
                        if ($categories) {
                            foreach($categories as $category) {
                                if ($category->count > 0) { ?>
                                <option <?php if (is_array($selectedcats) && in_array( $category->term_id, $selectedcats)) { echo "selected "; } ?> value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?></option>
                                <?php }
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top" class="cpslider_tags_sel">
                <th scope="row">Specify tags</th>
                <td>
                    <select id="cpsliderselecttags" multiple="multiple" name="tags[]" title="Click to select categories for slider">
                        <?php

                        $categories = get_tags();
                        if ($categories) {
                            foreach($categories as $tag) {
                                if ($tag->count > 1) { ?>
                                <option <?php if (is_array($selectedtags) && in_array( $tag->term_id, $selectedtags)) { echo "selected "; } ?> value="<?php echo esc_attr($tag->term_id); ?>"><?php echo esc_html($tag->name); ?></option>
                                <?php }
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th></th>
                <td>
                    <?php submit_button(); ?>
                </td>
            </tr>
        </table>

        <h2>Slider visual settings - W -P -L -O -C -K -E -R -. -C -O -M</h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Auto Play <br> <small>Change to any integrer for example 5000 to play every 5 seconds. Set false to disable</small></th>
                <td>
                    <input type="text" name="autoPlay" value="<?php if( !empty($current_slider['autoPlay'])) {
                        echo esc_attr($current_slider['autoPlay']);
                    } else {
                        echo 'false';
                    } ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Slides speed, in ms.</th>
                <td>
                    <input type="text" name="slideSpeed" value="<?php if( !empty($current_slider['slideSpeed'])) {
                        echo esc_attr($current_slider['slideSpeed']);
                    } else {
                        echo '200';
                    } ?>">
                </td>
            </tr>        
            <tr valign="top">
                <th scope="row">Pagination speed, in ms.</th>
                <td>
                    <input type="text" name="paginationSpeed" value="<?php if( !empty($current_slider['paginationSpeed'])) {
                        echo esc_attr($current_slider['paginationSpeed']);
                    } else {
                        echo '800';
                    } ?>">
                </td>
            </tr>
            
            <tr valign="top">
                <th></th>
                <td>
                    <?php submit_button(); ?>
                </td>
            </tr>
        <?php wp_nonce_field( 'posts' ); ?>
        <input type="hidden" name="slider_name" value="<?php echo esc_attr($_GET['slider']); ?>">
    </table>
</form>