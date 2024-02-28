<?php

$post = $args['post'];

if(isset($args['page'])) {
    $page = $args['page'];
} else {
    $page = '';
}

$pi_data = get_field('primary_investigator', $post->ID);
$pi_id = 0;
if($pi_data){
    $pi_id =  $pi_data->ID;
}

$pi_images = get_field('profile_pictures', 'user_' . $pi_id);
$pi_slug = get_field('user_slug', 'user_' . $pi_id);

$nickname = get_field('nickname', $post->ID);
$title = $post->post_title;



// Get Program (category) for this project
$programs = get_the_terms(get_the_ID(), 'funding-program');
$funding_sources = get_the_terms(get_the_ID(), 'funding-agency');
$program = $programs ? $programs[0]->name : '';
$funding_source = $funding_sources ? $funding_sources[0]->name : '';

// Possibly combine co-investigators and collaborators in ACF? 
$co_investigators_data = get_field('co-investigators');
$collaborators_data = get_field('collaborators');

// merge for now?
$all_coi_collabs = [];

if($co_investigators_data && !$collaborators_data){
  $all_coi_collabs = $co_investigators_data;
} elseif($collaborators_data && !$co_investigators_data) {
  $all_coi_collabs = $collaborators_data;
} elseif($co_investigators_data && $collaborators_data) {
  $all_coi_collabs = [...$co_investigators_data, ...$collaborators_data];
}


if($pi_images){
    $pi_img = $pi_images["icon_picture"] ? $pi_images["icon_picture"] : null;
        
    if(!empty($pi_img)){
        $pi_img_url = $pi_img['url'];
        $pi_img_alt = $pi_img['alt'];
    } else {
        $pi_img = $pi_images["primary_picture"] ? $pi_images["primary_picture"] : null;
        if(!empty($pi_img)){
            $pi_img_url = $pi_img['url'];
            $pi_img_alt = $pi_img['alt'];
        }
    }

    if(empty($pi_img)) {
        $default_img = get_field('default_user_picture', 'option');
        $pi_img_url = $default_img['url'];
        $pi_img_alt = $default_img['alt'];
    }  
    
} else {
    
        $default_img = get_field('default_user_picture', 'option');
        $pi_img_url = $default_img['url'];
        $pi_img_alt = $default_img['alt'];

}

// Get the featured image
$featured_image = get_the_post_thumbnail_url($post->ID, 'full');
if(!$featured_image) {
    $default_image = get_field('default_post_image', 'option');
    $featured_image = $default_image['url'];
}
// Get the project permalink
$project_permalink = get_permalink();

$user_slug = get_field('user_slug', 'user_' . $pi_id);
$user_permalink = home_url() . '/staff/profile/' . $user_slug;
?>
<div class="activity-banner <?php echo $page ? 'page' . $page : '';?>">
    <div class="project-image-container">
        <img class="project-image" src="<?php echo $featured_image; ?>" alt="">
    </div>
    <div class="activity-banner__content">
        <h3>
            <a href="<?php echo $project_permalink; ?>">
                <?php
                $limit = 150;
                $limitedTitle = substr($title, 0, $limit);

                // Display the limited title
                echo $limitedTitle;

                // If the title is longer than 200 characters, display ellipsis
                if (strlen($title) > $limit) {
                    echo '...';
                }
                if($nickname) { ?>
                    <span>(<?php echo $nickname; ?>)</span>
                <?php } ?>
            </a>     
        </h3>
        <div>
            <h4>
            <?php echo $funding_source; ?> <?php echo $program; ?>
            </h4>
        </div>
        <a href="<?php echo $project_permalink; ?>">Learn More</a>
    </div>
    <div class="activity-banner__primary-investigator">
        <h4>Principal Investigator</h4>
        <a href="<?php echo home_url('/staff/profile/' . $pi_slug); ?>">
            <img src="<?php echo $pi_img_url; ?>" alt="<?php echo $pi_img_alt;?>">
            <div><?php echo $pi_data? $pi_data->display_name: ''; ?></div>  
        </a>
    </div>
    <div class="project-team">
        <h4>Project Team</h4>
        <div class="project-team-member-container">
        <?php
            foreach ($all_coi_collabs as $coi_collabs) { 
                get_template_part('template-parts/related-staff-member', '', array(
                    'staff-member' => $coi_collabs
                ));
            }
        ?>
        </div>
    </div>
</div>