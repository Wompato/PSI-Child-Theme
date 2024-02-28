jQuery(function ($) {
    let page = 0;
    const container = $('#load-more-posts-container');
    const loaderContainer = $('.loader-container');
    const button = $('#load-more-posts-button');
    const loadingSpinner = $('<div class="loading-dual-ring"></div>');

    // Function to show loading spinner
    function showLoadingSpinner() {
        loaderContainer.append(loadingSpinner);
    }

    // Function to hide loading spinner
    function hideLoadingSpinner() {
        loadingSpinner.remove();
    }

    function initSlick(target) {
        jQuery(target).slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            prevArrow: '<i class="fa-solid fa-angle-left prev"></i>',
            nextArrow: '<i class="fa-solid fa-angle-right next"></i>',
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
          });
    }

   

    // Function to load posts
    function loadPosts() {
        showLoadingSpinner(); // Show loading spinner before making the AJAX request

        $.ajax({
            url: '/wp-json/psi/v1/load-more-posts/',
            data: {
                post_type: load_more_params.post_type,
                posts_per_page: load_more_params.posts_per_page,
                category: load_more_params.category,
                page: page,
            },
            type: 'post',
            success: function (response) {
                if (response && response.html) {
                    container.append(response.html);
                    var pageNumber = parseInt(page, 10);
                    
                    initSlick(`.related-staff-carousel.page${pageNumber + 1}`);

                    page++;
                    if (!response.has_more) {
                        button.remove();
                    }
                    hideLoadingSpinner(); // Hide loading spinner after posts have been loaded
                    button.css('display', 'block');
                }
            },
            error: function (error) {
                console.log('Error:', error);
                hideLoadingSpinner(); // Hide loading spinner in case of an error
            },
        });
    }

    // Load initial posts on page load
    
    loadPosts();

    // Handle click event for "Load More" button
    button.on('click', function () {
        // Load more posts, pass initializeSlickAfterLoad as a callback
        loadPosts();
    });
});