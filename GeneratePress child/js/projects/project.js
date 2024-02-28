jQuery(document).ready(function ($) {
    let hiddenContent = $('.ending-content');
    let showMoreBtn = $('#show-hidden-content');

    // Function to toggle hidden content with animation
    function toggleHiddenContent() {
        hiddenContent.toggleClass('hide show');

        // Toggle button text
        showMoreBtn.text(hiddenContent.hasClass('show') ? 'Show Less' : 'Show More');
        let ellipses = $('.hidden-ellipses');

        // Toggle ellipses based on content visibility
        if (hiddenContent.hasClass('show')) {
            
            // If content is visible, remove ellipses
            ellipses.text(' ');
        } else {
            // If content is hidden, re-add ellipses
            
            ellipses.text('... ');
        }
    }

    // Add click event to the "Show More" button
    showMoreBtn.on('click', toggleHiddenContent);

    const projectWebsite = document.querySelector('.project-website');

    projectWebsite.addEventListener('mouseover', function() {
        showTooltip(this);
    });

    projectWebsite.addEventListener('mouseout', function() {
        hideTooltip(this);
    });
    

    function showTooltip(element) {
        const tooltipText = element.getAttribute('data-tooltip');
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = tooltipText;
        element.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        const top = -tooltip.offsetHeight - 8;
        const left = rect.width / 2 - tooltip.offsetWidth / 2;

        tooltip.style.top = top + 'px';
        tooltip.style.left = left + 'px';
      
       
      }
      
      function hideTooltip(element) {
        const tooltip = document.querySelector('.tooltip');
        if (tooltip) {
          element.removeChild(tooltip);
        }
      }
});
