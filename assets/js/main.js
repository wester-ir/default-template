// Navbar indicator
!(function () {
    const list = $(".navbar-indicator-triggers");
    const indicator = $("#navbar-indicator");

    list.on("mouseleave", function() {
        indicator.animate({
            width: 0,
        }, 300);
    });

    const items = list.find(".navbar-indicator-trigger");

    items.on("mouseenter", function() {
        const leftPos = $(this).position().left;
        const width = $(this).width();

        indicator.animate({
            "left": leftPos + "px",
            "width": width + "px"
        }, 300);
    });
})();

// Dropdown
!(function () {
    $(document).on('click', '[data-role="dropdown-trigger"]', function () {
        const content = $(this).next('[data-role="dropdown-content"]');
    
        if (content.hasClass('hidden')) {
            $(this).attr('data-is-active', true);
            content.removeClass('hidden');
        } else {
            $(this).attr('data-is-active', false);
            content.addClass('hidden');
        }
    });
    
    $(document).click(function (e) {
        if ($(e.target).closest('[data-role="dropdown"]').length === 0) {
            $('[data-role="dropdown-content"]').addClass('hidden');
            $('[data-role="dropdown-trigger"]').attr('data-is-active', 'false');
        }
    });
})();
