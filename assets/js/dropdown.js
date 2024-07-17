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
