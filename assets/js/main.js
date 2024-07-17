(function () {
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
