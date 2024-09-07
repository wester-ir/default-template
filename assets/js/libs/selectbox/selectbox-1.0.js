/**
 * The Selectbox notification library.
 *
 * @github  https://github.com/weska-ir/selectbox-js
 * @version 1.0
 */
(function ($) {
    const localization = {
        lang: document.querySelector('html').getAttribute('lang'),
        translations: {
            en: {},
            fa: {
                'Select an item': 'انتخاب کنید',
                'Search': 'جستجو',
                'There is nothing to show!': 'چیزی برای نمایش وجود ندارد!',
            },
        },
        get(message) {
            return this.translations[this.lang][message] || message;
        },
    };

    $.fn.selectbox = function (opt = {}) {
        opt = Object.assign({
            placeholderText: localization.get('Select an item'),
            showPlaceHolder: true,
            searchable: true,
        }, opt);

        this.each(function () {
            var id = random();

            var selectBox = $(this);
            selectBox.off();

            var prevId = this.getAttribute('data-selectbox-id');

            if (prevId) {
                document.querySelector('[data-selectbox-container-id="'+ prevId +'"]').remove();
            }

            selectBox.css('display', 'none');
            selectBox.attr('data-selectbox-id', id);

            var placeholderContainer = document.createElement('div');
            placeholderContainer.classList.add('selectbox-placeholder');

            // Event
            selectBox.change(function () {
                var option = getSelectedOption(selectBox, false, 'option:selected');

                getPlaceholder(id).innerHTML = option.html;
                getPlaceholder(id).setAttribute('data-value', option.value);
            });

            // Create
            var container = document.createElement('div');
            container.setAttribute('data-selectbox-container-id', id);
            container.classList.add('selectbox');

            var facade = document.createElement('div');
            facade.classList.add('selectbox-facade', 'input', 'default');
            facade.append(placeholderContainer);

            var options = selectBox.find('option');
            var option = getSelectedOption(selectBox, ! opt.showPlaceHolder);

            if (option) {
                placeholderContainer.innerHTML = option.html;
                placeholderContainer.setAttribute('data-value', option.value);
            } else {
                selectBox.prepend('<option value="" data-role="selectbox-null-item" selected style="display: none;"></option>');

                placeholderContainer.innerHTML = opt.placeholderText;
                placeholderContainer.setAttribute('data-value', null);
            }

            facade.innerHTML+= '<span data-role="selectbox arrow-down" class="icon icon-arrow-down w-3 h-3"></span>';

            container.append(facade);

            // List
            var list = document.createElement('div');
            list.classList.add('selectbox-list');
            list.innerHTML = '<div class="selectbox-list-empty '+ (options.length ? 'selectbox-hidden' : '') +'">'+ localization.get('There is nothing to show!') +'</div>';

            var listContainer = document.createElement('div');
            listContainer.classList.add('selectbox-list-container', 'selectbox-hidden');

            options.each(function () {
                if (this.style.display === 'none') {
                    return;
                }

                var item = document.createElement('div');
                item.innerHTML = '<span class="'+ this.classList.toString() +'" style="'+ this.getAttribute('style') +'">'+ this.innerHTML +'</span>';
                item.classList.add('selectbox-item');
                var value = this.value;

                item.onclick = function () {
                    selectBox.val(value).change();

                    close(listContainer);
                };

                list.append(item);
            })

            if (opt.searchable) {
                listContainer.append(createSearch(list));
            }

            listContainer.append(list);

            container.append(listContainer);

            $(container).insertBefore(selectBox);

            facade.onclick = function () {
                if (listContainer.classList.contains('selectbox-hidden')) {
                    open(listContainer);
                } else {
                    close(listContainer);
                }
            };
        });
    };

    document.addEventListener('click', function (e) {
        document.querySelectorAll('.selectbox').forEach(function (selectBox) {
            if (! e.target.isEqualNode(selectBox) && ! $.contains(selectBox, e.target)) {
                close(selectBox.querySelector('.selectbox-list-container'));
            }
        })
    });

    function getSelectedOption(item, forceFind = false, query = 'option[selected]') {
        var option = item.find(query);

        if (forceFind && option.length === 0) {
            option = item.find('option');
        }

        if (option.length > 0) {
            option = option[0];

            if (option.getAttribute('data-role') === 'selectbox-null-item') {
                return null;
            }

            return {
                label: option.text,
                html: option.innerHTML,
                value: option.value,
            };
        } else {
            return null;
        }
    }

    function getPlaceholder(id) {
        return document.querySelector('[data-selectbox-container-id="'+ id +'"] .selectbox-placeholder');
    }

    function createSearch(list) {
        var container = document.createElement('div');
        container.classList.add('selectbox-search-container');

        var input = document.createElement('input');
        input.type = 'text';
        input.placeholder = localization.get('Search') + '...';

        var items = getItems(list);

        input.oninput = function () {
            var emptyList = list.querySelector('.selectbox-list-empty');

            emptyList.classList.add('selectbox-hidden');

            var i = 0;

            Array.from(items).forEach(function (item) {
                if (! item.textContent.includes(input.value)) {
                    item.classList.add('selectbox-hidden');
                } else {
                    i++;
                    item.classList.remove('selectbox-hidden');
                }
            });

            if (i === 0) {
                emptyList.classList.remove('selectbox-hidden');
            }
        };

        var icon = document.createElement('span');
        icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 6.35 6.35" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g transform="matrix(0.9300000000000012,0,0,0.9300000000000012,0.22149570703506027,0.22133752584457111)"><path d="M2.894.511a2.384 2.384 0 0 0-2.38 2.38 2.386 2.386 0 0 0 2.38 2.384c.56 0 1.076-.197 1.484-.523l.991.991a.265.265 0 0 0 .375-.374l-.991-.992a2.37 2.37 0 0 0 .523-1.485C5.276 1.58 4.206.51 2.894.51zm0 .53c1.026 0 1.852.825 1.852 1.85S3.92 4.746 2.894 4.746s-1.851-.827-1.851-1.853.825-1.852 1.851-1.852z" paint-order="stroke fill markers" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>';
        icon.style.paddingLeft = '5px';
        icon.style.paddingRight = '5px';

        container.append(input);
        container.append(icon);

        return container;
    }

    function close(container) {
        container.classList.add('selectbox-hidden');
    }

    function open(container) {
        container.classList.remove('selectbox-hidden');
    }

    function getItems(list) {
        return list.querySelectorAll('.selectbox-item');
    }
})(jQuery);
