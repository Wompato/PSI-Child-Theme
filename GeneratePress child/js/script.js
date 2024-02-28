// Initialize additional sliders as needed

let articleArgs = {
    small: 2,
    medium: 3,
    large: 4,
    xl: 2,
    responsive: [
        {
            breakpoint: 1224,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              infinite: true,
            }
        },
        {
            breakpoint: 741,
            settings: {
              slidesToShow: 4,
              slidesToScroll: 4,
              infinite: true,
            }
        },
        {
            breakpoint: 540,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
            }
        },
        {
            breakpoint: 460,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              infinite: true,
            }
          }
    ]
}

let projectArgs = {
    small: 2,
    medium: 4,
    large: 4,
    xl: 3,
    responsive: [
        {
            breakpoint: 1224,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
            }
        },
        {
            breakpoint: 750,
            settings: {
              slidesToShow: 4,
              slidesToScroll: 4,
              infinite: true,
            }
        },
        {
            breakpoint: 540,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
            }
        },
        {
            breakpoint: 460,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              infinite: true,
            }
          }
    ]
}

function getInitialPastProjects(buttonID) {
    console.log('working')
    let swapProjectsBtn = jQuery(buttonID);
    let headline = jQuery('.related-projects .section-headline h2');
    let urlSegments = window.location.pathname.split('/');
    let userSlug = urlSegments[urlSegments.length - 2];

    let resultsContainer = jQuery('#related-projects-container');
    let loaderContainer = jQuery('.project-loader-container');
    
    let page = 0;

    // Add a loading spinner element
    let loadingSpinner = jQuery('<div class="loading-dual-ring"></div>');

    swapProjectsBtn.on('click', function(e) {
        e.preventDefault();

        let endpoint;

        const loadMoreButton = jQuery("<div>", {
            
            text: "Load More"
        });

        if(swapProjectsBtn.attr('id') === 'past-projects') {
            endpoint = 'past-user-projects';
            page = 0;
            headline.text('Past Projects');
            swapProjectsBtn.text('Active Projects');
            swapProjectsBtn.attr('id', 'active-projects');

            loadMoreButton.attr('id', 'load-more-past-projects');
           
            const angleRightIcon = jQuery("<i>", {
                class: "fa-solid fa-angle-right"
            });

            // Append the angleRightIcon to the loadMoreDiv
            loadMoreButton.append(angleRightIcon);
    
            // Empty the grid of projects and the container for load more
            resultsContainer.empty();
            loaderContainer.empty();
            
            // Add new button for loading more posts and loading spinner
            loaderContainer.prepend(loadMoreButton);
            loaderContainer.prepend(loadingSpinner);
        } else {
            endpoint = 'active-user-projects';
            page = 0;
            headline.text('Active Projects');
            swapProjectsBtn.text('Past Projects');
            swapProjectsBtn.attr('id', 'past-projects');

            loadMoreButton.attr('id', 'load-more-related-projects');
            
            const angleRightIcon = jQuery("<i>", {
                class: "fa-solid fa-angle-right"
            });

            // Append the angleRightIcon to the loadMoreDiv
            loadMoreButton.append(angleRightIcon);
    
            // Empty the grid of projects and the container for load more
            resultsContainer.empty();
            loaderContainer.empty();
            
            // Add new button for loading more posts and loading spinner
            loaderContainer.prepend(loadMoreButton);
            loaderContainer.prepend(loadingSpinner);
        }

        jQuery.ajax({
            url: `/wp-json/psi/v1/${endpoint}/`,
            method: 'GET',
            data:{
                userSlug: userSlug,
                page: page,
                past: true
            },
            success: function (data) {
                // Remove the loading spinner
                loadingSpinner.remove();
               
                // Check if there is HTML content
                if (data && data.html) {
                    
                    resultsContainer.append(data.html);
                   
                    page++;
                    
                    // Check if there are more posts
                    if (!data.has_more) {
                        // Remove the load more button if there are no more posts
                        loadMoreButton.remove();
                    } else {
                        loadMoreButton.on('click', function(e) {
                            e.preventDefault();
                            // Add a loading spinner element
                            let loadingSpinner = jQuery('<div class="loading-dual-ring"></div>');

                            let urlSegments = window.location.pathname.split('/');
                            let userSlug = urlSegments[urlSegments.length - 2];

                            // Append the loading spinner to the results container
                            let resultsContainer = jQuery('#related-projects-container');
                            let loaderContainer = jQuery('.project-loader-container');
                                
                            loaderContainer.prepend(loadingSpinner);
                            
                            jQuery.ajax({
                                url: `/wp-json/psi/v1/${endpoint}/`,
                                method: 'GET',
                                data:{
                                        userSlug: userSlug,
                                        page: page,
                                        past: true
                                },
                                success: function (data) {
                                    // Remove the loading spinner
                                    loadingSpinner.remove();
                                        
                                    // Check if there is HTML content
                                    if (data && data.html) {
                                        
                                        resultsContainer.append(data.html);
                                        page++;
                                        
                                        if (!data.has_more) {
                                            loadMoreButton.remove();
                                        }
                                    }
                                    
                                },
                                error: function (error) {
                                    // Remove the loading spinner in case of an error
                                    loadingSpinner.remove();
                                    console.log('Error:', error);
                                },
                            });
                        });
                        
                    }
                }
            },
            error: function (error) {
                // Remove the loading spinner in case of an error
                loadingSpinner.remove();
                console.log('Error:', error);
            },
        });
    });
}

function getRelatedPosts(buttonID) {
    let loadMoreButton = jQuery(buttonID);
    let page = 2;
    
    // Add a loading spinner element
    let loadingSpinner = jQuery('<div class="loading-dual-ring"></div>');

    loadMoreButton.on('click', function (e) {
        e.preventDefault();
        
        let urlSegments = window.location.pathname.split('/');
        let userSlug = urlSegments[urlSegments.length - 2];

        const bodyElement = document.body;

        // Get the class attribute value
        const classAttributeValue = bodyElement.getAttribute('class');

        // Use a regular expression to extract the post ID from the class
        const match = classAttributeValue.match(/postid-(\d+)/);

        // Check if a match is found and get the post ID
        const postId = match ? match[1] : null;

        // Append the loading spinner to the results container
        let resultsContainer = jQuery('#related-posts-grid');
        let loaderContainer = jQuery('.loader-container');
        loaderContainer.append(loadingSpinner);

        jQuery.ajax({
            url: '/wp-json/psi/v1/related-posts/',
            method: 'POST',
            contentType: 'application/json;charset=UTF-8',
            data: JSON.stringify({
                userSlug: userSlug,
                page: page,
                postId: postId
            }),
            success: function (data) {
                // Remove the loading spinner
                loadingSpinner.remove();

                // Check if there is HTML content
                
                if (data && data.html) {
                    
                    if (resultsContainer.length) {
                        resultsContainer.append(data.html);
                        initializeSlickSlider(`.related-staff-container.page${page - 1}`,articleArgs);
                    }

                    // Check if there are more posts
                    if (!data.has_more) {
                        // Remove the load more button if there are no more posts
                        loadMoreButton.remove();
                    }
                }
            },
            error: function (error) {
                // Remove the loading spinner in case of an error
                loadingSpinner.remove();
                console.log('Error:', error);
            },
        });
        page++;
        
    });
}

function getRelatedProjects(buttonID) {
    let loadMoreButton = jQuery(buttonID);
    let page = 1;
    
    // Add a loading spinner element
    let loadingSpinner = jQuery('<div class="loading-dual-ring"></div>');

    loadMoreButton.on('click', function (e) {
        e.preventDefault();
        
        let urlSegments = window.location.pathname.split('/');
        let userSlug = urlSegments[urlSegments.length - 2];

        // Append the loading spinner to the results container
        let resultsContainer = jQuery('#related-projects-container');
        let loaderContainer = jQuery('.project-loader-container');
        loaderContainer.prepend(loadingSpinner);

        

        jQuery.ajax({
            url: '/wp-json/psi/v1/active-user-projects/',
            method: 'GET',
            contentType: 'application/json;charset=UTF-8',
            data:{
                userSlug: userSlug,
                page: page,
            },
            success: function (data) {
                // Remove the loading spinner
                loadingSpinner.remove();
                
               
                // Check if there is HTML content
                if (data && data.html) {
                    
                    if (resultsContainer.length) {
                        resultsContainer.append(data.html);
                    }

                    // Check if there are more posts
                    if (!data.has_more) {
                        // Remove the load more button if there are no more posts
                        loadMoreButton.remove();
                    }
                }
            },
            error: function (error) {
                // Remove the loading spinner in case of an error
                loadingSpinner.remove();
                console.log('Error:', error);
            },
        });
        page++;
    })
}

// Call the setupPagination function for loading more related posts
jQuery(document).ready(function ($) {
    getRelatedPosts('#load-more-related-posts');
    getRelatedProjects('#load-more-related-projects');
    getInitialPastProjects('#past-projects');
});

jQuery(document).ready(function ($) {
    // Initialize the first slider
    initializeSlickSlider('.related-staff-container', articleArgs);
    initializeSlickSlider('.collaborators-container', projectArgs);
});

function initializeSlickSlider(target, slideArgs) {
    jQuery(target).slick({
        infinite: true,
        slidesToShow: slideArgs.medium,
        slidesToScroll: slideArgs.medium,
        prevArrow: '<i class="fa-solid fa-angle-left prev"></i>',
        nextArrow: '<i class="fa-solid fa-angle-right next"></i>',
        responsive: slideArgs.responsive
    });
}