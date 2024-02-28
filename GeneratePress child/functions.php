<?php

class PSI_Child_Theme {
    public function __construct() {

        // Single Post Pages get the grid of posts as the bottom.
        add_filter('the_content', array($this, 'display_additional_images_in_single_post'));
        
        // Modify menu items
        add_filter('wp_nav_menu_items', array($this, 'modify_menu_items'), 10, 2);  

        // Handle conflict of Select2 library
        add_action('admin_enqueue_scripts', array($this, 'enqueue_acf_select2_and_dequeue_wcd_select2_admin'), 99);

        // Enqueue custom scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_custom_scripts'));

        // Enqueue Slick.js from CDN
        add_action('wp_enqueue_scripts', array($this, 'enqueue_slick_from_cdn'));

        // Register the related_users shortcode
        add_shortcode('related_users', array($this, 'related_users_shortcode'));
        add_shortcode('load_more_posts', array($this, 'load_more_posts_shortcode'));
        add_shortcode('edit_article_link', array($this, 'edit_article_link_shortcode'));
        
    }

    public function display_additional_images_in_single_post($content) {
        global $post;
    
        // Check if it's a single post
        if (is_single() && $post) {
            $additional_images = get_field('additional_images', $post->ID);
    
            if ($additional_images && is_array($additional_images)) {
                $image_html = '<div class="image-grid">';
    
                foreach ($additional_images as $attachment_id) {
                    $image_html .= '<div class="grid-item">';
                    $image_html .= wp_get_attachment_image($attachment_id, 'medium'); // Display the image with 'medium' size
                    $image_html .= '<p>' . esc_html(get_post_field('post_excerpt', $attachment_id)) . '</p>'; // Display the caption
                    $image_html .= '</div>';
                }
    
                $image_html .= '</div>';
    
                // Append the additional images to the post content
                $content .= $image_html;
            }
        }
    
        return $content;
    }
    
    function modify_menu_items($items, $args) {
        // Check if it's the primary or off-canvas menu and the user is logged in
        if ((($args->theme_location == 'primary' || $args->theme_location == 'off-canvas') && !is_admin()) || ($args->menu->slug == 'primary' && !is_admin())) {
            // Check if "Staff" menu item exists
            $staff_menu_item_position = strpos($items, 'menu-item-22859');
            
            if ($staff_menu_item_position !== false) {
                // Check if the user is logged in
                if (is_user_logged_in()) {
                    // User is logged in, add Logout link
                    $current_user = wp_get_current_user();
                    $logout_url = wp_logout_url(home_url('/')); // Logout URL with redirect to home
                    $logout_link = '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-logout"><a href="' . esc_url($logout_url) . '">Logout</a></li>';

                     // Get the user's slug from the usermeta field
                    $user_slug = get_user_meta($current_user->ID, 'user_slug', true);
                    $profile_url = home_url('/staff/profile/' . $user_slug); // Adjust the URL structure as needed
                    $profile_link = '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-profile"><a href="' . esc_url($profile_url) . '">My Profile</a></li>';

                    // Find the end of "Staff" submenu and insert Logout and My Profile links
                    $submenu_end_position = strpos($items, '</ul>', $staff_menu_item_position);
                    if ($submenu_end_position !== false) {
                        $items = substr_replace($items, $profile_link . $logout_link, $submenu_end_position, 0);
                    }
                } else {
                    // User is logged out, add Login link
                    $login_url = wp_login_url(home_url('/')); // Login URL with redirect to home
                    $login_link = '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-login"><a href="' . esc_url($login_url) . '">Login</a></li>';
                    // Find the end of "Staff" submenu and insert Login link
                    $submenu_end_position = strpos($items, '</ul>', $staff_menu_item_position);
                    if ($submenu_end_position !== false) {
                        $items = substr_replace($items, $login_link, $submenu_end_position, 0);
                    }
                }
            }
        }
        return $items;
    }

    public function enqueue_acf_select2_and_dequeue_wcd_select2_admin() {
        // Dequeue the West Coast Digital version of Select2 in the admin area.
        wp_dequeue_style('select2');
        wp_deregister_style('select2');
        wp_dequeue_script('select2');
        wp_deregister_script('select2');
    
        // Load the proper compatible version for ACF and Social Share
        wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
        // Must be full version
        wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js', array('jquery') );
    }

    public function enqueue_custom_scripts() {
        // JS for the user profile pages (only load if on the /profile/ route)
        if (is_page() && strpos($_SERVER['REQUEST_URI'], '/profile/') !== false) {
            wp_enqueue_script('user-profile-scripts', get_stylesheet_directory_uri() . '/js/script.js', array('jquery'), '', true);
        }
            
        if(!is_singular('post')){
            wp_enqueue_script('slick-init', get_stylesheet_directory_uri() . '/js/slick-init.js', array('jquery'), '1.0', true);
        }

        if(is_singular('project')){
            wp_enqueue_script('single-project-scripts', get_stylesheet_directory_uri() . '/js/projects/project.js', array('jquery'), '1.0', true);
        }

    }
    

    public function enqueue_slick_from_cdn() {
        // Register and enqueue Slick.js from the CDN
        wp_enqueue_script('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);

        // Enqueue Slick.js CSS from the CDN
        wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1', 'all');

        // Enqueue Slick.js theme CSS from the CDN (optional)
        wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array(), '1.8.1', 'all');
    }

    public function related_users_shortcode($atts) {
        // Extract shortcode attributes, including 'ID' and 'class' if provided
        $atts = shortcode_atts(array(
            'ID' => get_the_ID(), // Default to the current post's ID if not provided
            'class' => 'normal', // Default class is 'normal'
            'widget' => false,
            'page' => '',
        ), $atts);
    
        // Get the post ID from the 'ID' attribute
        $post_id = intval($atts['ID']);
       
        // Get the related users from the ACF relationship field
        $related_users = get_field('related_staff', $post_id);
    
        // Determine the appropriate CSS class based on the 'class' attribute
        $carousel_class = 'related-staff-carousel';
        if ($atts['class'] === 'large') {
            $carousel_class = 'related-staff-carousel-large';
        } elseif($atts['class'] === 'medium') {
            $carousel_class = 'related-staff-carousel-medium';
        }
    
        
        // Output the user information
        if (!empty($related_users)) {
            
            $output_class = $carousel_class;

            if (isset($atts['page'])) {
                $page_number = intval($atts['page']);
                $output_class .= ' page' . $page_number;
            }

            if ($atts['widget']) {
                $output = '<h2 class="related-staff-heading">RELATED STAFF</h2>';
                $output .= '<ul class="' . esc_attr($output_class) . '">';
            } else {
                $output = '<ul class="' . esc_attr($output_class) . '">';
            }
            
           
            
            foreach ($related_users as $user) {
                $user_id = $user->ID;
                $user_slug = get_field('user_slug', 'user_' . $user_id);
                $user_permalink = home_url() . '/staff/profile/' . $user_slug;
                
                
                $profile_images = get_field('profile_pictures', 'user_' .$user_id);

                if($profile_images){
                    $profile_img = $profile_images['icon_picture'] ? $profile_images['icon_picture'] : null;
                    if($profile_img){
                        $profile_img_url = $profile_img['url'];
                        $profile_img_alt = $profile_img['alt'];
                    } else {
                        $profile_img = $profile_images["primary_picture"] ? $profile_images["primary_picture"] : null;
                        if($profile_img){
                            $profile_img_url = $profile_img['url'];
                            $profile_img_alt = $profile_img['alt'];
                        }
                    }
                        
                } 

                if(!$profile_img) {
                    $default_image = get_field('default_user_picture', 'option');
                    $profile_img_url = $default_image['url'];
                    $profile_img_alt = $default_image['alt'];
                }
    
                $output .= '<li class="related-user">';
                $output .= '<img class="related-staff__image" src="' . $profile_img_url . '" alt="' . esc_attr($profile_img_alt) . '">';
                $output .= '<a class="related-staff__name" href="' . $user_permalink . '">' . esc_html($user->display_name) . '</a>';
                $output .= '</li>';
            }
            $output .= '</ul>';
            return $output;
        } 
        
    }

    function load_more_posts_shortcode($atts) {
        // Shortcode attributes
        $atts = shortcode_atts(
            array(
                'post_type'      => 'post',
                'posts_per_page' => 6,
                'category'       => '',
            ),
            $atts,
            'load_more_posts'
        );
    
        // Enqueue your script that handles the AJAX request
        wp_enqueue_script('load-more-posts', get_stylesheet_directory_uri() . '/js/loadMorePosts.js', array('jquery'), null, true);
    
        // Localize the script with shortcode attributes
        wp_localize_script('load-more-posts', 'load_more_params', array(
            'post_type'      => $atts['post_type'],
            'posts_per_page' => intval($atts['posts_per_page']),
            'category'       => $atts['category'],
        ));
    
        // Shortcode output
        ob_start();
        ?>
        <div id="load-more-posts-container">
            <!-- Posts will be appended here -->
        </div>
        <div class="loader-container">
                        
        </div>
        <button id="load-more-posts-button">Load More<i class="fa-solid fa-angle-right"></i></button>
        <?php
        return ob_get_clean();
    }

    public function edit_article_link_shortcode() {
        // Check if the user is logged in
        if (is_user_logged_in()) {
            // Check if the user has editor or higher capabilities or the edit_staff_member role
            if (current_user_can('edit_others_posts') || in_array('edit_staff_member', (array) $current_user->roles)) {
                $base_url = get_bloginfo('url');
                $edit_project_url = trailingslashit($base_url) . 'edit-article?article-name=' . get_the_ID();
    
                // Return the link
                return '<a href="' . esc_url($edit_project_url) . '">Edit Press Submission</a>';
            }
        }
    
        // If conditions are not met, return an empty string or other content as needed
        return '';
    }
    
    /**
     * Generates initial related posts markup based on a specified ACF relationship field.
     *
     * @param int $posts_per_page Number of posts to retrieve per page (default: 6).
     * @param string $related_field The custom field name containing related posts (default: 'related_posts').
     * @param int|null $relation_id The ID of the relationship to fetch posts for.
     *
     * @return bool Returns false if there's an error or there are no more posts beyond the posts per page set, otherwise returns true
     */
    public function generate_initial_related_posts($posts_per_page = 6, $related_field = 'related_posts', $relation_id = null) {
        if(!$relation_id){
            // Return some error which says no relationship can be found without ID
            return false;
        } 

        $related_posts = get_field($related_field, $relation_id);

        if(!$related_posts){
            // Return some error which says no posts could be found
            return false;
        }

        $has_past_projects = false;
        $has_active_projects = false;

        // Filter out past projects and set the flag if a past project is found
        $active_related_posts = array_filter($related_posts, function($post) use (&$has_past_projects, &$has_active_projects){
            if ($post->post_type !== 'project') {
                return false;
            }
            $is_past_project = PSI_Child_Theme::is_past_project($post);

          
            if ($is_past_project) {
                $has_past_projects = true;
            } else {
                $has_active_projects = true;
            }
            return !$is_past_project;
        });

        // Sort the related posts by date in descending order
        usort($related_posts, function ($a, $b) {
            $date_a = strtotime($a->post_date);
            $date_b = strtotime($b->post_date);

            return ($date_a < $date_b) ? 1 : -1;
        });

        if($active_related_posts){
            
            $initial_posts = array_slice($active_related_posts, 0, $posts_per_page);
        } else {
            // Slice the array to get the initial posts
            $initial_posts = array_slice($related_posts, 0, $posts_per_page);
        } 

        if(empty($initial_posts)) {
            // Return some error which says that there were no initial posts found  
            return false;
        }

        ob_start();


        if($has_active_projects){

            foreach($initial_posts as $post) {
                get_template_part('template-parts/projects/activity-banner', '', array(
                    'post' => $post,
                ));
            }
            // Check if there are additional posts beyond the initial 6
            $has_more_posts = count($active_related_posts) > $posts_per_page;
            
            
        }
        
        if(!$has_active_projects){
            $markup = 'no posts';
            $has_more_posts = false;
        } 

        if($related_field == 'related_posts' || $related_field == 'related_articles') {
            foreach ($initial_posts as $post) {
                get_template_part('template-parts/related-post', '', array(
                    'post' => $post,
                ));
            }
            // Check if there are additional posts beyond the initial 6
            $has_more_posts = count($related_posts) > $posts_per_page;
        }

        
        $markup = ob_get_clean(); 
       
        return array(
            'markup' => $markup,
            'has_more_posts' => $has_more_posts,
            'has_past_projects' => $has_past_projects,
        );
    }

    /**
    * Check if a given post is a past project based on its end date.
    *
    * @param object $post The WordPress post object.
    * @return bool True if the post is a past project, false otherwise.
    */
    public static function is_past_project($post) {
        // Check if $post is a valid object
        if (!is_object($post) || empty($post->ID)) {
            return false; // Return false if $post is not valid
        }
    
        if ($post->post_type !== 'project') {
            return false;
        }
        
        // Get the end_date
        $end_date = get_field('end_date', $post->ID);
        
        // Check if end_date exists
        if (!$end_date) {
            return false; // Return false if end_date does not exist
        }
        
        // Convert end_date to DateTime object
        $end_date = DateTime::createFromFormat('m/d/Y', $end_date);
    
        // Get the current date
        $current_date = new DateTime();
    
        // Check if end_date is past the current date
        if ($end_date && $end_date < $current_date) {
            
            return true; // Project is past
        } else {
            return false; // Project is ongoing or in the future
        }
    }
    
}

// Instantiate the main theme class
$psi_theme = new PSI_Child_Theme();