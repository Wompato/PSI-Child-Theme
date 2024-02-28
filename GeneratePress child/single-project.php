<?php get_header(); 

function trimmed_content($content) {
  // Get the full post content
  $full_content = $content;

  // Apply wpautop to preserve paragraph structure
  $full_content = wpautop($full_content);

  // Split the content into words
  $words = preg_split("/[\s,]+/", $full_content);

  // Get the first 300 words
  $initial_content = implode(" ", array_slice($words, 0, 300));
  // Get the remaining content after the initial content
  $remaining_content = implode(" ", array_slice($words, 300));

  // Check if there are more than 300 words
  $more_than_300_words = count($words) > 300;

  // Output the content based on the word count
  if ($more_than_300_words) {
    $full_output = $initial_content .'<span class="hidden-ellipses">... </span class="hidden-ellipses">' . '<i class="ending-content hide">' . $remaining_content . '</i>';
    echo $full_output;
    echo '<p id="show-hidden-content" class="">Show More</p>';
  } else {
    echo $full_content;
  }

  return $full_content;
}

$title = get_the_title();
$post_id = get_the_ID();

$primary_investigator_data = get_field('primary_investigator');
$primary_investigator_id = $primary_investigator_data ? $primary_investigator_data->ID : '';

$pi_slug = get_field('user_slug', 'user_' . $primary_investigator_id);

$primary_investigator_picture_data = get_field('profile_pictures', 'user_' . $primary_investigator_id);



if($primary_investigator_picture_data && isset($primary_investigator_picture_data['primary_picture']) && $primary_investigator_picture_data['primary_picture'] == true){
  $profile_image_url = $primary_investigator_picture_data['primary_picture']["url"];
  $profile_image_alt = $primary_investigator_picture_data['primary_picture']["alt"]; 
} else {
  $default_image = get_field('default_user_picture', 'option');
  $profile_image_url = $default_image['url'];
  $profile_image_alt = $default_image['alt'];
}

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


$non_psi_personel_data = get_field('non-psi_personnel');
$non_psi_personel = ''; // Initialize an empty string

if ($unserialized_data = unserialize($non_psi_personel_data)) {
  $total_personel = count($unserialized_data);

  foreach ($unserialized_data as $i => $personel) {
      $name = isset($personel['Name']) ? $personel['Name'] : '';
      $role = isset($personel['Role']) ? $personel['Role'] : '';
      $institution = isset($personel['Institution']) ? $personel['Institution'] : '';

      $non_psi_personel .= "$name";

      // Add Role if it exists
      if (!empty($role)) {
          $non_psi_personel .= " ($role)";
      }

      // Add Institution if it exists
      if (!empty($institution)) {
          // Check if Role is also present before adding a comma
          if (!empty($role)) {
              $non_psi_personel .= ', ';
          }
          $non_psi_personel .= " ($institution)";
      }

      // Add a comma if it's not the last iteration
      if ($i < $total_personel - 1) {
          $non_psi_personel .= ', ';
      }
  }
}

// Get Program (category) for this project
$programs = get_the_terms(get_the_ID(), 'funding-program');
$funding_sources = get_the_terms(get_the_ID(), 'funding-agency');
$program = $programs ? $programs[0]->name : '';
$funding_source = $funding_sources ? $funding_sources[0]->name : '';

$funding_instrument = get_field('funding_instrument');

$pass_through_entity = get_field('pass_through_entity');

if($pass_through_entity){
  $decodedData = unserialize($pass_through_entity);

  if (is_array($decodedData) && !empty($decodedData)) {
      $passThroughEntities = array_column($decodedData, 'Pass Through Entity');
  
      // If there are multiple entities, join them with commas
      $pass_through_entity = implode(', ', $passThroughEntities);

      // Extract "PTE Primary Investigator" values
      $primaryInvestigators = array_column($decodedData, 'PTE Principal Investigator');
      $primary_investigators = implode(', ', $primaryInvestigators);
  } 
}

$nickname = get_field('nickname');
$start_date = get_field('start_date');
$end_date = get_field('end_date');
$project_num = get_field('project_number');
$award_num = get_field('agency_award_number');
$project_website = get_field('project_website');

$related_posts = get_field('related_articles');

$current_user = wp_get_current_user();

?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <?php
    while (have_posts()) :
      the_post(); 
      ?>
      <section class="project-banner">
        <div class="pi-container">
          <a href="<?php echo home_url('/staff/profile/' . $pi_slug); ?>">
            <img src="<?php echo $profile_image_url;?>" alt="<?php echo $profile_image_alt;?>">
            <p><?php echo $primary_investigator_data->display_name;?></p>
            <span>Principal Investigator</span>
          </a>
        </div>
        <div class="project-info-container">
          <div>
            <div class="project-title-container">
              <h1>
                <?php echo $title;?>
                <?php echo $nickname ? "($nickname)" : ''; ?>

                <?php if($project_website){ ?>
                  <a target="_blank" class ="project-website" href="<?php echo $project_website ? $project_website : '#'; ?>" data-tooltip="Link to project's website">
                    <i style="font-size:1em;" class="wpmi__icon wpmi__position-before wpmi__align-middle wpmi__size-1 fa fa-external-link "></i>
                  </a>
                <?php } ?>  
              </h1>
            </div>
          
            <?php 
            if (is_user_logged_in()) {
                // Check if the user has administrator or staff member editor role
                if (current_user_can('activate_plugins') || in_array('staff_member_editor', (array) $current_user->roles) || $current_user->ID === $primary_investigator_id) {
                    $base_url = get_bloginfo('url');
                    $edit_project_url = trailingslashit($base_url) . 'edit-project?project-name=' . get_the_ID();
                    ?>
                    <a class="edit-project" href="<?php echo $edit_project_url;?>">Edit Project</a>
                <?php } 
            } 
            ?>
          </div>
          
          
          <div class="project-tax-container">
            <h3><?php echo $funding_source . ' '; ?><?php echo $program ?></h3>
            <?php 
            if($funding_instrument == 'Subcontract' || $funding_instrument == 'Subaward'){ ?>
              <p><?php echo $funding_instrument; ?> to PSI from <?php echo $pass_through_entity; ?></p>
            <?php } ?>
            <div class="project-meta-container">
              <div class="project-meta">
                <div>Start Date: <?php echo $start_date; ?></div>
                <div>Project #: <?php echo $project_num; ?></div>
              </div>
              <div class="project-meta">
                <div>End Date: <?php echo $end_date; ?></div>
                <div>Award #: <?php echo $award_num; ?></div>
              </div>
            </div>
          </div>
          
        </div>
      </section>
      
        <section>
        <?php if($all_coi_collabs) { ?>
          <h3>PSI Co-Investigators and Collaborators</h3>
          <div class="coi-collab-track related-staff-carousel-large">
            <?php
            foreach($all_coi_collabs as $coi_collab){
              get_template_part('template-parts/related-staff-member', '', array(
                'staff-member' => $coi_collab
              ));
            } 
            ?>
          </div>
        <?php } ?>
          <?php
          
          if($funding_instrument == 'Subcontract' || $funding_instrument == 'Subaward'){ ?>
            <p>PTE PI: <?php echo $primary_investigators; ?></p>
          <?php } else {
            if($non_psi_personel) { ?>
              <p>Other Non PSI Personnel: <?php echo $non_psi_personel;?></p>
            <?php }
          } ?>
            
        </section>
      
      <?php 
      
      $content = get_the_content(); 

      if($content) {
        echo '<h5>Project Description</h5>';
        trimmed_content($content);
      }

      
      
      
      
      if($related_posts) { 
        $post_data = $psi_theme->generate_initial_related_posts(6, 'related_articles', $post_id);
        
        ?>
        <section class="related-posts">
            <h2 class="section-headline">RELATED COVER STORIES & PRESS RELEASES</h2>
            <div id="related-posts-grid">
              <?php echo $post_data['markup'];?>
            </div>
            <div class="loader-container"></div>
            
            <?php if($post_data['has_more_posts']) { ?>
                <div id="load-more-related-posts">Load More<i class="fa-solid fa-angle-right"></i></div>
            <?php } ?>
        </section>
      <?php }
      
      endwhile; ?>
  </main>
</div>


<?php get_footer(); ?>
