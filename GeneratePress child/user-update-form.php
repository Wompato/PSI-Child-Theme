<?php
/**
* Template Name: User Update Profile Form
*/

acf_form_head(); // Include ACF form CSS and JS
get_header();

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

if (isset($_GET['s_m'])) {
    $s_m_id = intval($_GET['s_m']); // Convert to integer for security
    
    if($s_m_id == $user_id) {
        
        $user_nicename = get_field('user_slug', 'user_' . $user_id);
        
        $base_url = get_bloginfo('url');
        $user_profile_url = '/staff/profile/' . $user_nicename;
        
        $profile_url = $user_profile_url;
    } else {
        $user_id = $s_m_id;
        $user_nicename = get_field('user_slug', 'user_' . $user_id);
        
        $base_url = get_bloginfo('url');
        $user_profile_url = '/staff/profile/' . $user_nicename;
        
        $profile_url = $user_profile_url;
    }

} else {
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $user_nicename = get_field('user_slug', 'user_' . $user_id);
    
    $base_url = get_bloginfo('url');
    $user_profile_url = '/staff/profile/' . $user_nicename;
    
    $profile_url = $user_profile_url;
}
 

function toggle_publications( $field ) {
    echo '<span>Enter URL instead of uploading file.</span>';
}

add_action('acf/input/admin_footer', function () use ($current_user) {
    my_acf_admin_footer($current_user);
});

//add_action('acf/render_field/key=field_6579fb74c2164', 'toggle_publications');

function my_acf_admin_footer($current_user) {

    if ( ! current_user_can( 'manage_options' ) /* && ! in_array( 'editor', (array) $current_user->roles ) */ ) { ?>
    <script type="text/javascript">
        (function($) {
            
            // Find all elements with class acf-gallery-add and change the text
            $('.acf-gallery-add').text('Add Image');
            
        })(jQuery);	
        (function($) {
        $(document).ready(function() {
            var maxCharacters = 50; // Set your desired character limit

            // Replace 'your_wysiwyg_field' with the actual name of your ACF WYSIWYG field
            var wysiwygField = document.querySelector('#mceu_76');
            console.log(wysiwygField);

            if (wysiwygField) {
                wysiwygField.addEventListener('onkeydown', function(event) {
                    let content = event.target.value;
                    let strippedContent = content.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim();
                    var currentLength = strippedContent.length;
                    console.log(currentLength);

                    // Check if the entered text exceeds the maximum character limit
                    if (currentLength >= maxCharacters && event.keyCode !== 8) {
                        event.preventDefault();
                    }
                });
            }

                    function hideMediaModalElements() {
                        if ($('.media-modal').length) {
                            // Your custom JavaScript for the media modal
                            $('.media-modal').find('[data-setting="caption"]').hide();
                            $('.media-modal').find('[data-setting="description"]').hide();
                        }
                    }

                    // Function to hide elements inside .acf-gallery-side
                    function hideAcfGallerySideElements() {
                        if ($('.acf-gallery-side').length) {
                            // Your custom JavaScript for .acf-gallery-side
                            $('.acf-gallery-side').find('[data-name="caption"]').hide();
                            $('.acf-gallery-side').find('[data-name="description"]').hide();
                            $('.acf-gallery-side').find('.acf-gallery-edit').hide();
                            $('.acf-gallery-side').find('.compat-field-enable-media-replace').hide();
                            $('.acf-gallery-side').find('.compat-field-emr-remove-background').hide();
                        }
                    }

                    
                    function hideCompactAttFields() {
                        let comptAttFields = $('.compat-attachment-fields');
                        if(comptAttFields) {
                            comptAttFields.hide();
                        }
                    }

                    function hideAttDetails() {
                        let attDetails = $('.attachment-details');
                        if(attDetails){
                            attDetails.find('.edit-attachment').hide();
                        }
                    }

                    // Initial call to hide elements
                    hideMediaModalElements();
                    hideAcfGallerySideElements();
                    hideCompactAttFields();
                    hideAttDetails();

                    // Use Mutation Observer to detect changes in the DOM
                    var observer = new MutationObserver(function (mutations) {
                        mutations.forEach(function (mutation) {
                            // Check if nodes are added or removed
                            if (mutation.addedNodes.length || mutation.removedNodes.length) {
                                // Call the functions to hide elements whenever there is a change
                                hideMediaModalElements();
                                hideAcfGallerySideElements();
                                hideCompactAttFields();
                                hideAttDetails();
                            }
                        });
                    });

                    // Specify the target node and options for the observer
                    var targetNode = document.body; // You can change this to a more specific container if needed
                    var config = { childList: true, subtree: true };

                    // Start observing the target node for mutations
                    observer.observe(targetNode, config);
        });
        })(jQuery);

        </script>
    <?php } 
    
}

// ACF form settings
 $form_settings = array(
     'post_id' => 'user_' . $user_id, // User ID as post ID for ACF fields
     'new_post' => false,
     'fields' => ['profile_pictures', 'cv', 'publications_file', 'location', 'professional_interests_professional_interests_text', 'professional_interests_professional_interests_images',
     'professional_interests_professional_interests_image_caption', 'professional_history_professional_history_text', 'professional_history_professional_history_images',
     'professional_history_professional_history_image_caption', 'honors_and_awards_honors_and_awards_text', 'honors_and_awards_honors_and_awards_images', 'honors_and_awards_honors_and_awards_image_caption',
     'personal_page', 'display_in_directory', 'targets_of_interests', 'disciplines_techniques', 'missions', 'mission_roles', 'instruments', 'facilities', 'facilities', 'twitter_x', 'linkedin', 'facebook', 'youtube', 'instagram', 'github', 'orchid', 'google_scholar'], // Include all field groups assigned to the user
     'field_groups' => ['profile_pictures', 'cv', 'publications_file', 'location', 'professional_interests_professional_interests_text', 'professional_interests_professional_interests_images',
                  'professional_interests_professional_interests_image_caption', 'professional_history_professional_history_text', 'professional_history_professional_history_images',
                  'professional_history_professional_history_image_caption', 'honors_and_awards_honors_and_awards_text', 'honors_and_awards_honors_and_awards_images', 'honors_and_awards_honors_and_awards_image_caption',
                  'personal_page', 'display_in_directory','targets_of_interests', 'disciplines_techniques', 'missions', 'mission_roles', 'instruments', 'facilities', 'twitter_x', 'linkedin', 'facebook', 'youtube', 'instagram', 'github', 'orchid', 'google_scholar'],
     'form' => false,
     'submit_value' => 'Update Profile',
     'return' => $profile_url,
     'updated_message' => 'Profile updated successfully!',
     'html_submit_spinner' => '<div class="loading-dual-ring"></div>',
 );
?>
 <form id="acf-user-edit" action="" method="post">
    <?php acf_form($form_settings); ?>
    <button type="submit">Update Profile</button>
 </form>
 
<?php
get_footer();
?>
