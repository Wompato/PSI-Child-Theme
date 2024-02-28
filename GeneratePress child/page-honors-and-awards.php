<?php

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

$user_id = $user_data->ID;
$name = $user_data->display_name;

$position = get_field('position', 'user_'.$user_id);

$profile_images = get_field('profile_pictures', 'user_' .$user_id);

    if($profile_images){
        $profile_img = $profile_images['honors_and_awards_picture'] ? $profile_images['honors_and_awards_picture'] : null;
        if(!empty($profile_img)){
            $profile_img_url = $profile_img['url'];
            $profile_img_alt = $profile_img['alt'];
        } else {
            $profile_img = $profile_images["primary_picture"] ? $profile_images["primary_picture"] : null;
            if(!empty($profile_img)){
                $profile_img_url = $profile_img['url'];
                $profile_img_alt = $profile_img['alt'];
            }
        }
            
    } 
    
    if(empty($profile_img)) {
        $default_image = get_field('default_user_picture', 'option');
        $profile_img_url = $default_image['url'];
        $profile_img_alt = $default_image['alt'];
    }

$honors_and_awards = get_field('honors_and_awards', 'user_' . $user_id);

if($honors_and_awards) {
    $honors_and_awards_data = $honors_and_awards["honors_and_awards_text"];
    $honors_and_awards_text = '';
    $honors_and_awards_images = $honors_and_awards['honors_and_awards_images'];
    $honors_and_awards_image_caption = $honors_and_awards["honors_and_awards_image_caption"] ? $honors_and_awards["honors_and_awards_image_caption"] : '';
}

?>

<div id="primary" class="content-area honors-and-awards">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content">
                <section class="honors-and-awards__header">
                <img class="staff__headshot" src="<?php echo $profile_img_url;?>" alt="<?php echo $profile_img_alt; ?>">
                    <h1 class="staff__title"><?php echo $name; ?></h1>
                    <h2 class="staff-info staff__position"><?php echo $position ? $position : ''; ?></h2>
                    
                </section class="honors-and-awards__header">
                <section class="honors-and-awards" <?php echo empty($honors_and_awards_data) ? 'style="text-align:center;"' : ''; ?>>
                    <?php                 
                    if ($honors_and_awards) { ?>
                        <h2><?php the_title(); ?></h2>
                        <div class="honors-and-awards__content">
                            <?php 
                            if ($honors_and_awards_data) {
                                echo $honors_and_awards_data;
                            
                            } else {
                                echo 'No Honors or Awards Found.';
                            }
                            
                            if(!$honors_and_awards_images) {
                                $grid_style = 'less than 2 images';
                                
                            } else {
                                $grid_style = count($honors_and_awards_images) > 1 ? 'style="grid-template-columns: 1fr 1fr;"' : 'style="grid-template-columns: 1fr; place-items: center;"';
                            }
                            ?> 
                        </div>               
                        <div class="optional-image-grid" <?php echo $grid_style; ?>>
                            <?php if ($honors_and_awards_images) {
                                foreach($honors_and_awards_images as $image) { ?>
                                    <img src="<?php echo $image['sizes']['large'] ;  ?>" alt="<?php echo $image['alt'];  ?>">
                                <?php }
                            } ?>
                        </div>
                        <p class="optional-image-caption"><i><?php echo $honors_and_awards_image_caption; ?></i></p>
                    <?php } ?>
                </section>  
            </div>
        </article>
    </main>
</div>

<?php
get_footer();
?>
