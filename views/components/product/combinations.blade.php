@if ($product->is_variable)
    <div class="space-y-6 mt-5">
        @foreach ($product->variants as $variant)
            <div>
                <div class="font-medium text-xl">{{ $variant->name }}</div>

                @if ($variant->style->name === 'Default')
                    <div class="flex space-x-3 rtl:space-x-reverse mt-3">
                        @if ($variant->type->name === 'Color')
                            @foreach ($product->getVariantItems($variant->id) as $item)
                                <div data-itooltip="{{ $item->name }}" data-role="variant-item" data-variant-id="{{ $variant->id }}" data-item-id="{{ $item->id }}" data-selected="false" data-is-available="true" class="bg-white flex items-center justify-center w-10 h-10 rounded-full border border-neutral-300 hover:border-indigo-400 text-center select-none cursor-pointer transition-colors data-[selected=true]:bg-indigo-400 data-[selected=true]:shadow-md data-[is-available=false]:opacity-50">
                                    <div class="w-8 h-8 border border-white rounded-full" style="background-color: {{ $item->value }}"></div>
                                </div>
                            @endforeach
                        @else
                            @foreach ($product->getVariantItems($variant->id) as $item)
                                <div data-role="variant-item" data-variant-id="{{ $variant->id }}" data-item-id="{{ $item->id }}" data-selected="false" data-is-available="true" class="w-16 py-3 border border-neutral-300 hover:border-indigo-400 hover:ring-2 rounded-lg text-center select-none cursor-pointer transition-colors ring-indigo-400 data-[selected=true]:border-indigo-400 data-[selected=true]:ring-2 data-[is-available=false]:opacity-50">
                                    {{ $item->name }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <select data-role="variant-item-selectbox" data-variant-id="{{ $variant->id }}" class="default mt-2">
                        @foreach ($product->getVariantItems($variant->id) as $item)
                            <option value="{{ $item->id }}" data-selected="false" data-is-available="true">{{ $item->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        @endforeach
    </div>
@endif

@push('bottom-scripts')
    <script>
        const cartItem = {
            product_id: {{ $product->id }},
            product_combination_id: null,
            uid: null,
        };

        const productDetails = {
            variant_styles: {{ Js::from($product->variants->keyBy('id')->map(fn ($variant) => $variant->style->slug())) }},
            variants: {{ Js::from($product->variants->pluck('id')) }},
            combinations: {{ Js::from($product->combinations->select(['id', 'uid', 'cart', 'max_available_quantity', 'final_price', 'price', 'image_ids', 'variant_ids'])) }},
        };

        $('[data-role="variant-item"]').click(function () {
            const variantId = $(this).data('variant-id');
            const itemId = $(this).data('item-id');

            selectVariantItem(variantId, itemId);
            findCombination();
        });

        $('[data-role="variant-item-selectbox"]').on('change', function () {
            const variantId = $(this).data('variant-id');
            const itemId = Number($(this).val());

            selectVariantItem(variantId, itemId);
            findCombination();
        });

        function selectVariantItem(variantId, itemId) {
            var style = productDetails.variant_styles[variantId];

            if (style === 'default') {
                $('[data-role="variant-item"][data-variant-id="'+ variantId +'"]').attr('data-selected', 'false');
                $('[data-role="variant-item"][data-item-id="'+ itemId +'"]').attr('data-selected', 'true');
            } else if (style === 'select_box') {
                $('[data-role="variant-item-selectbox"][data-variant-id="'+ variantId +'"]').val(itemId);
            }

            productDetails.combinations.forEach(function (combination) {
                const values = Object.values(combination.variant_ids);

                if (! combination.cart.is_lowest_qty_available) {
                    if (values.length === 1) {
                        if (style === 'default') {
                            $('[data-role="variant-item"][data-item-id="' + values[0] + '"]').attr('data-is-available', 'false');
                        } else if (style === 'select_box') {
                            $('[data-role="variant-item-selectbox"] > option[value="' + values[0] + '"]').prop('disabled', true);
                        }
                    } else if (combination.variant_ids[variantId] === itemId) {
                        values.filter(function (id) {
                            return id !== itemId;
                        }).forEach(function (itemId) {
                            if (style === 'default') {
                                $('[data-role="variant-item"][data-item-id="' + itemId + '"]').attr('data-is-available', 'false');
                            } else if (style === 'select_box') {
                                $('[data-role="variant-item-selectbox"] > option[value="' + itemId + '"]').prop('disabled', true);
                            }
                        });
                    }
                }
            });
        }

        function findCombination() {
            const isComplete = productDetails.variants.every(function (variantId) {
                var style = productDetails.variant_styles[variantId];

                if (style === 'default') {
                    return $('[data-role="variant-item"][data-variant-id="'+ variantId +'"][data-selected="true"]').length === 1;
                } else if (style === 'select_box') {
                    var val = $('[data-role="variant-item-selectbox"][data-variant-id="'+ variantId +'"]').val();

                    return val !== '' && val;
                }
            });

            if (isComplete) {
                var uid = $('[data-role="variant-item"][data-selected="true"], [data-role="variant-item-selectbox"]').map(function () {
                    var role = $(this).data('role');

                    if (role === 'variant-item') {
                        return $(this).data('item-id');
                    } else {
                        return $(this).val();
                    }
                }).get().sort((a, b) => a - b).join('-');

                cartItem.uid = uid;

                selectCombination(uid);
            } else {
                console.log(':(')
            }
        }

        function hideUnavailableMessage() {
            $('[data-role="unavailable"]').remove();
        }

        function showUnavailableMessage() {
            hideUnavailableMessage();

            const templateId = '#template-unavailable-message';
            const html = $(templateId).html();

            $(html).insertAfter(templateId);
        }

        function hideMaxAvailableQuantity() {
            $('[data-role="max-available-quantity"]').remove();
        }

        function showMaxAvailableQuantity(quantity) {
            hideMaxAvailableQuantity();

            const templateId = '#template-max-available-quantity-message';
            const html = $(templateId).html();

            if (html && quantity) {
                $(html).insertAfter(templateId);

                const elem = $('[data-role="max-available-quantity"]').find('[data-role="quantity"]');

                elem.text(formatNumber(quantity));
            }
        }

        function selectCombination(uid) {
            // Reset
            $('[data-role="variant-item"]').attr('data-is-available', 'true');
            $('[data-role="variant-item-selectbox"] > option').prop('disabled', false);

            $('[data-role="add-to-cart"]').addClass('hidden').removeClass('flex');
            $('[data-role="quantity-control-container"]').addClass('hidden').removeClass('flex');
            $('[data-role="decrease-quantity"]').addClass('hidden');
            $('[data-role="remove-item"]').addClass('hidden');
            $('[data-role="refresh-quantity"]').addClass('hidden');
            $('[data-role="price-container"]').addClass('hidden');
            $('[data-role="points"]').addClass('hidden');
            hideMaxAvailableQuantity();
            hideUnavailableMessage();

            const combination = productDetails.combinations.find(function (combination) {
                return combination.uid === uid;
            });

            cartItem.uid = uid;
            window.combination = combination;
            cartItem.product_combination_id = combination.id;

            if (combination.variant_ids) {
                Object.keys(combination.variant_ids).forEach(function (variantId) {
                    const itemId = combination.variant_ids[variantId];

                    selectVariantItem(variantId, itemId);
                });
            }

            // Set image
            setImage(combination.image_ids);

            const cart = combination.cart;

            // Check if the combination is available
            if (cart.is_lowest_qty_available) {
                // Already in cart?
                if (cart.quantity > 0) {
                    $('[data-role="quantity-control-container"]').removeClass('hidden').addClass('flex');

                    // Set cart quantity
                    $('[data-role="cart-item-quantity-value"]').val(cart.quantity);

                    // Increase Button
                    $('[data-role="increase-quantity"]').attr('data-is-active', ! cart.is_max_qty_reached ? 'true' : 'false');

                    if (cart.is_max_qty_exceeded) {
                        // Show the refresh button
                        $('[data-role="refresh-quantity"]').removeClass('hidden');
                    } else {
                        if (cart.quantity === {{ $product->amount_per_sale }}) {
                            // Show the remove button
                            $('[data-role="remove-item"]').removeClass('hidden');
                        } else {
                            // Show the decrease button
                            $('[data-role="decrease-quantity"]').removeClass('hidden');
                        }
                    }
                } else {
                    // Show the add to cart button
                    $('[data-role="add-to-cart"]').removeClass('hidden').addClass('flex');
                }

                // Show other elements for available combinations
                $('[data-role="points"], [data-role="price-container"]').removeClass('hidden');

                showMaxAvailableQuantity(combination.max_available_quantity);
                setPrice(combination.price, combination.final_price);
            } else {
                // Show the unavailable message
                showUnavailableMessage();
            }
        }

        function setPrice(price, finalPrice) {
            const currency = ' {{ productCurrency()->label() }}';

            $('[data-role="raw-price"]').text(formatNumber(price) + currency);
            $('[data-role="final-price"]').text(formatNumber(finalPrice) + currency);
        }

        function getSlideIndexByImageId(imageId) {
            const $slides = $('.product-image-thumb-slider').find('.swiper-slide');
            const $targetSlide = $slides.filter(`[data-image-id="${imageId}"]`);

            return $slides.index($targetSlide);
        }

        function setImage(imageIds) {
            if (Array.isArray(imageIds) && imageIds.length > 0) {
                const id = imageIds[0];
                const index = getSlideIndexByImageId(id);

                swiper.slideTo(index, 10);
            }
        }

        function updateCombinationCart(id, cart) {
            const combination = productDetails.combinations.find(function (item) {
                return item.id === id;
            });

            if (combination) {
                combination.cart = cart;
            }
        }
    </script>
@endpush
