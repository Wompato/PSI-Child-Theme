<?php
/* Template Name: User Profile */
get_header();

$user_slug = get_query_var('user_nicename');

// Retrieve the user based on the 'user_slug' meta field
$user= get_users(array(
    'meta_key'   => 'user_slug',
    'meta_value' => $user_slug,
    'number'     => 1, // Limit the result to one user
));

if($user && $user[0]){
    $user_data = $user[0]->data;
} else {
    return "";
}

// Ensure user data is available
if ($user_data) {
    $user_id = $user_data->ID;
    $user_nickname = get_user_meta($user_id, 'nickname', true);
    $user_display_name = $user_data->display_name;
    $position = get_field('position', 'user_' . $user_id);
    $location = get_field('location', 'user_' . $user_id);
    $email = $user_data->user_email;
    $cv = get_field('cv', 'user_' . $user_id);
    $personal_page_link = get_field('personal_page', 'user_' . $user_id);
    $publications_file = get_field('publications_file', 'user_' .$user_id);
    if(!$publications_file){
        $publications_url = get_field('publications_url', 'user_' .$user_id);
    }
    $targets_of_interests = get_field('targets_of_interests', 'user_' . $user_id);
    $disciplines_techniques = get_field('disciplines_techniques', 'user_' . $user_id);
    $missions = get_field('missions', 'user_' . $user_id);
    $mission_roles = get_field('mission_roles', 'user_' . $user_id);
    $instruments = get_field('instruments', 'user_' . $user_id);
    $facilities = get_field('facilities', 'user_' . $user_id);

    $twitter_x = get_field('field_65ca58de9e649', 'user_' . $user_id);
    $facebook = get_field('field_65ca59099e64b', 'user_' . $user_id);
    $youtube = get_field('field_65ca59179e64c', 'user_' . $user_id);
    $linkedin = get_field('field_65ca58f69e64a', 'user_' . $user_id);
    $instagram = get_field('field_65ca59209e64d', 'user_' . $user_id);
    $github = get_field('field_65d905aa86ece', 'user_' . $user_id);
    $orchid = get_field('field_65d932851c5fa', 'user_' . $user_id);
    $gscholar = get_field('field_65d932951c5fb', 'user_' . $user_id);

    $professional_history = get_field('professional_history', 'user_' . $user_id);
    $honors_and_awards = get_field('honors_and_awards', 'user_' . $user_id);
  
    $profile_images = get_field('profile_pictures', 'user_' .$user_id);

    if($profile_images){
        $profile_img = $profile_images["primary_picture"] ? $profile_images["primary_picture"] : null;
        
        if(!empty($profile_img)){
            $profile_img_url = $profile_img['url'];
            $profile_img_alt = $profile_img['alt'];
        }
    } 

    if(empty($profile_img)) {
        $default_image = get_field('default_user_picture', 'option');
        $profile_img_url = $default_image['url'];
        $profile_img_alt = $default_image['alt'];
    }

    $professional_interests = get_field('professional_interests', 'user_' . $user_id);
    if($professional_interests) {
        $professional_interests_text = $professional_interests["professional_interests_text"];
        $professional_interests_images = $professional_interests["professional_interests_images"];
        $professional_interests_image_caption = $professional_interests["professional_interests_image_caption"];
    }

    function compare_posts_by_date_desc($a, $b) {
        $date_a = strtotime($a->post_date);
        $date_b = strtotime($b->post_date);
    
        // Compare the dates in descending order
        return ($date_a < $date_b) ? 1 : -1;
    }
    
    $related_posts = get_field('related_posts', 'user_' . $user_id);    
    
    $related_projects = get_field('related_projects_and_initiatives', 'user_' .$user_id);

    // Display user information and related posts
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-user="<?php echo $user_id; ?>">
        <div class="entry-content">
            <section class="staff__banner">
                <img class="staff__headshot" src="<?php echo $profile_img_url; ?>" alt="<?php echo $profile_img_alt; ?>">
                <div class="staff-info__container">
                <div>
                        <h1 class="staff__title"><?php echo $user_nickname; ?></h1>
                        <h2 class="staff-info staff__position">
                            <?php echo $position ? $position : ''; ?>
                        </h2>
                        <p class="staff-info">
                            <i>
                            <?php
                            echo $location ? 'Currently resides in ' . $location : '';
                            ?>
                            </i>
                        </p>
                        <?php
                        $current_user = wp_get_current_user();
                        $roles = $current_user->roles;
                        
                        if (is_user_logged_in() && $current_user->data->ID === $user_id || in_array('administrator', $roles) || in_array('staff_member_editor', $roles)) {    
                            $base_url = get_bloginfo('url');
                            $edit_profile_url = trailingslashit($base_url) . 'edit-user';

                            // Append user ID as a query parameter
                            $edit_profile_url .= '?s_m=' . $user_id;
                            ?>
                        
                            <a href="<?php echo esc_url($edit_profile_url); ?>">Edit Profile</a>            
                        <?php } ?>
                    </div>
                    <div>
                        <p class="staff-info">
                            <?php echo $email ? $email : ''; ?>
                        </p>
                        <ul class="staff-info__social">
                            <?php if($orchid) { ?>
                                <li>
                                    <a href="https://orcid.org/<?php echo $orchid; ?>" target="_blank"><i class="fa-brands fa-orcid fa-xl" style="color: #a6ce39;"></i></a>
                                </li>
                            <?php } ?>
                            <?php if($gscholar) { ?>
                                <li>
                                    <a href="https://scholar.google.com/citations?user=<?php echo $gscholar; ?>" target="_blank">
                                        <svg viewBox="0 0 122.88 122.88" xmlns="http://www.w3.org/2000/svg">
                                        <style>
                                            .st0{fill:#356AC3;}
                                            .st1{fill:#A0C3FF;}
                                            .st2{fill:#76A7FA;}
                                            .st3{fill:#4285F4;}
                                        </style>
                                        <g>
                                            <polygon class="st3" points="61.44,98.67 0,48.64 61.44,0 61.44,98.67"/>
                                            <polygon class="st0" points="61.44,98.67 122.88,48.64 61.44,0 61.44,98.67"/>
                                            <path class="st1" d="M97.28,87.04c0-19.79-16.05-35.84-35.84-35.84c-19.79,0-35.84,16.05-35.84,35.84s16.05,35.84,35.84,35.84 C81.23,122.88,97.28,106.83,97.28,87.04L97.28,87.04z"/>
                                            <path class="st2" d="M29.05,71.68C34.8,59.57,47.14,51.2,61.44,51.2c14.3,0,26.64,8.37,32.39,20.48H29.05L29.05,71.68z"/>
                                        </g>
                                        </svg>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if($twitter_x) { ?>
                                <li>
                                    <a href="https://twitter.com/<?php echo $twitter_x; ?>" target="_blank"><span class="social-icon socicon socicon-twitter" ></span></a>
                                </li>
                            <?php } ?>
                            <?php if($facebook) { ?>
                                <li>
                                    <a href="https://facebook.com/<?php echo $facebook; ?>" target="_blank"><span class="social-icon socicon socicon-facebook" ></span></a>
                                </li>
                            <?php } ?>
                            <?php if($linkedin) { ?>
                                <li>
                                    <a href="https://www.linkedin.com/in/<?php echo $linkedin; ?>" target="_blank"><span class="social-icon socicon socicon-linkedin" ></span></a>
                                </li>
                            <?php } ?>
                            <?php if($youtube) { ?>
                                <li>
                                    <a href="https://www.youtube.com/<?php echo $youtube; ?>" target="_blank"><span class="social-icon socicon socicon-youtube" ></span></a>
                                </li>
                            <?php } ?>
                            <?php if($instagram) { ?>
                                <li>
                                    <a href="https://instagram.com/<?php echo $instagram; ?>" target="_blank"><span class="social-icon socicon socicon-instagram" ></span></a>
                                </li>
                            <?php } ?>
                            <?php if($github) { ?>
                                <li>
                                    <a href="https://github.com/<?php echo $github; ?>" target="_blank"><i class="fa-brands fa-github fa-xl"></i></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div>
                        <p class="staff-info">
                            <?php
                                if($cv){
                                    echo $cv['url'] ? '<a href="' . $cv['url'] . '">Curriculum Vitae</a>' : ''; 
                                }
                             ?>
                        </p>
                        <?php if($professional_history["professional_history_text"] || $professional_history["professional_history_images"]) { ?>
                            <p class="staff-info">
                                <a href="<?php echo home_url('/staff/profile/professional-history/' . $user_slug); ?>">Professional History</a>
                            </p>
                        <?php } ?>
                        <?php if($honors_and_awards["honors_and_awards_text"] || $honors_and_awards["honors_and_awards_images"]) { ?>
                            <p class="staff-info">
                                <a href="<?php echo home_url('/staff/profile/honors-and-awards/' . $user_slug); ?>">Honors and Awards</a>
                            </p>
                        <?php } ?>
                        <p class="staff-info">
                            <?php echo $personal_page_link ? '<a href="' . $personal_page_link . '">Personal/Professional Page</a>' : ''; ?>
                        </p>
                        <p class="staff-info">
                            <?php 
                                if($publications_file){
                                    echo $publications_file ? '<a href="' . $publications_file['url'] . '">Publications</a>' : '';
                                } else {
                                    echo $publications_url ? '<a href="' . $publications_url . '">Publications</a>' : '';
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </section>
            <section class="additional-staff-info">
                <?php if($targets_of_interests) {
                    echo '<p class="staff-info"><strong>Targets of Interest:</strong> ' . $targets_of_interests . '</p>';
                } ?>
                <?php if($disciplines_techniques) {
                    echo '<p class="staff-info"><strong>Disciplines/Techniques:</strong> ' . $disciplines_techniques . '</p>';
                } ?>
                <?php if($missions) {
                    echo '<p class="staff-info"><strong>Missions:</strong> ' . $missions . '</p>';
                } ?>
                <?php if($mission_roles) {
                    echo '<p class="staff-info"><strong>Mission Roles:</strong> ' . $mission_roles . '</p>';
                } ?>
                <?php if($instruments) {
                    echo '<p class="staff-info"><strong>Instruments:</strong> ' . $instruments . '</p>';
                } ?>
                <?php if($facilities) {
                    echo '<p class="staff-info"><strong>Facilities:</strong> ' . $facilities . '</p>';
                } ?>
            </section>   
            <?php if($professional_interests) { ?>
                <section class="staff-pi__container">
                    <p>
                        <?php if($professional_interests_text) { ?>                     
                           <?php echo $professional_interests_text; ?>                  
                        <?php } ?>
                    </p>
                    <?php
                        if(!$professional_interests_images) {
                            $grid_style = 'less than 2 images';
                            
                        } else {
                            $grid_style = count($professional_interests_images) > 1 ? 'style="grid-template-columns: 1fr 1fr;"' : 'style="grid-template-columns: 1fr; place-items: center;"';
                        }
                    ?>
                    <div class="optional-image-grid" <?php echo $grid_style; ?>>
                        <?php if($professional_interests_images) { 
                            foreach($professional_interests_images as $image) { ?>
                                <img src="<?php echo $image['sizes']['large'] ;  ?>" alt="<?php echo $image['alt'];  ?>">
                            <?php }                         
                        } ?>
                    </div>
                    <p>
                        <i>
                            <?php if($professional_interests_image_caption) {
                                echo $professional_interests_image_caption;
                            } ?>
                        </i>
                    </p>
                </section>
            <?php } ?>
            <?php if ($related_projects) { 
                $post_data = $psi_theme->generate_initial_related_posts(4, 'related_projects_and_initiatives', 'user_' . $user_id);
              
                ?>
                <section class="related-projects">
                    <div class="section-headline">
                        <h2>ACTIVE PROJECTS</h2>
                        
                        <?php if($post_data['has_past_projects']) { ?>
                            <span id="past-projects">Past Projects</span>
                        <?php } ?>
                    </div>
                    <div id="related-projects-container">
                        <?php if($post_data['markup']) {
                            echo $post_data['markup'];
                        }?>
                    </div>
                    <div class="project-loader-container">
                        <?php if($post_data['has_more_posts']) { ?>
                            <div id="load-more-related-projects">Load More<i class="fa-solid fa-angle-right"></i></div>
                        <?php } ?>
                    </div>  
                </section>
            <?php } ?>
            <?php if($related_posts) { 
                $post_data = $psi_theme->generate_initial_related_posts(6, 'related_posts', 'user_' . $user_id);
                ?>
                <section class="related-posts">
                    <h2 class="section-headline">RELATED COVER STORIES & PRESS RELEASES</h2>
                    <div id="related-posts-grid">
                        <?php echo $post_data['markup']; ?>
                    </div>
                    <div class="loader-container"></div>
                    
                    <?php if($post_data['has_more_posts']) { ?>
                        <div id="load-more-related-posts">Load More<i class="fa-solid fa-angle-right"></i></div>
                    <?php } ?>
                </section>
            <?php } ?>
        </div>
    </article>
    <?php
    	
}
get_footer();
?>
<!-- 'related_projects_and_initiatives' -->