/**
 * The iTooltip library.
 *
 * @github  https://github.com/wester-ir/itooltip
 * @version 1.0
 */
(function($) {
    $.fn.itooltip = function () {
        return this.each(function (index, elem) {
            const id = random(10);

            $(elem).hover(function (e) {
                if (document.querySelector(`#itooltip-${id}`)) {
                    $(`#itooltip-${id}`).stop(true, false).fadeIn();

                    return;
                }

                const itooltip = document.createElement('div');

                itooltip.id = `itooltip-${id}`;
                itooltip.classList.add('itooltip');
                itooltip.innerHTML = e.target.getAttribute('data-itooltip');

                var position = e.target.getAttribute('data-position');

                if (! position) {
                    position = 'top';
                }

                document.body.append(itooltip);

                const rect = e.target.getBoundingClientRect();
                const itooltipRect = itooltip.getBoundingClientRect();
                const width = rect.width;
                const top = rect.top;
                const left = rect.left;

                var scrollbarWidth = window.innerWidth - $(document).width();
                scrollbarWidth = document.body.scrollHeight > window.innerHeight ? 0 : scrollbarWidth / 2;

                if (position === 'top') {
                    itooltip.style.top = `${top - itooltipRect.height - 8}px`;
                } else if (position === 'bottom') {
                    itooltip.style.top = `${top + itooltipRect.height + 5}px`;
                }
                itooltip.style.left = `${left - (itooltipRect.width / 2) + (width / 2) + scrollbarWidth}px`;
                itooltip.style.display = 'none';

                $(itooltip).fadeIn();
            }, function () {
                $(`#itooltip-${id}`).fadeOut(function () {
                    $(`#itooltip-${id}`).remove();
                });
            });
        });
    };
}(jQuery));
