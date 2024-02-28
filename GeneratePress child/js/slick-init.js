function initializeSlick() {
    if (jQuery('.related-staff-carousel').length > 0) {
      jQuery('.related-staff-carousel').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            prevArrow: '<i class="fa-solid fa-angle-left prev"></i>',
            nextArrow: '<i class="fa-solid fa-angle-right next"></i>',
            responsive: [
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
  
    if (jQuery('.related-staff-carousel-large').length > 0) {
      jQuery('.related-staff-carousel-large').slick({
            infinite: true,
            slidesToShow: 6,
            slidesToScroll: 6,
            prevArrow: '<i class="fa-solid fa-angle-left prev"></i>',
            nextArrow: '<i class="fa-solid fa-angle-right next"></i>',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 5,
                        infinite: true,
                    }
                },
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        infinite: true,
                    }
                },
                {
                    breakpoint: 680,
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
  
    if (jQuery('.related-staff-carousel-medium').length > 0) {
      jQuery('.related-staff-carousel-medium').slick({
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
  }
  
  // Call the function initially on document ready
  jQuery(document).ready(function ($) {
    initializeSlick();
  });
  