@template_extends('views.layouts.default')

@php
    $itemsTotalQuantity = get_cart_total_quantity();
@endphp

@title('سبد خرید')

@section('content')
    <div class="container flex flex-col flex-1 min-h-[115px]">
        <!-- Empty -->
        <div data-role="empty-cart-message" class="flex hidden items-center justify-center flex-1">
            هیچ محصولی در سبد خرید ندارید!
        </div>

        <!-- No purchase permission -->
        <div data-role="permission-purchase-error" class="hidden border border-red-300 text-danger rounded-lg px-4 py-4 mb-3 space-y-2">
            <div>امکان خرید محصول برای شما وجود ندارد.</div>
        </div>

        <!-- Some of the products are unavailable -->
        <div data-role="unavailable-error" class="hidden border border-red-300 text-danger rounded-lg px-4 py-4 mb-3 space-y-2">
            <div>تعدادی از محصولات در سبد خرید، ناموجود شده اند.</div>
            <div>لطفاً جهت ادامه سفارش این محصولات را حذف نمایید.</div>
        </div>

        <!-- Some of the products have the maximum quantity exceeded error -->
        <div data-role="max-qty-error" class="hidden border border-red-300 text-danger rounded-lg px-4 py-4 mb-3 space-y-2">
            <div>موجودی تعدادی از محصولات در سبد خرید بیش از موجودی انبار است.</div>
            <div>لطفاً جهت ادامه سفارش این محصولات را حذف و یا اصلاح نمایید.</div>
        </div>

        <!-- Some of the products are deleted -->
        <div data-role="products-deleted-error" class="hidden border border-red-300 text-danger rounded-lg px-4 py-4 mb-3 space-y-2">
            <div>تعدادی از محصولات از سایت حذف شده اند.</div>
            <div>لطفاً جهت ادامه سفارش این محصولات را از سبد خرید خود حذف نمایید.</div>
        </div>

        <div data-role="cart-details" class="flex flex-col md:flex-row">
            <div class="border border-neutral-200 rounded-lg p-5 flex-1 ml-0 md:ml-5">
                <div>
                    <h3>سبد خرید شما</h3>
                    <div class="flex items-center justify-between">
                        <span class="font-light text-sm"><span data-role="items-total-quantity-count">{{ $itemsTotalQuantity }}</span> کالا</span>
                        <button class="text-danger text-sm font-light" onclick="modal.defaults.confirmDanger(() => { clearCart(); })">حذف همه</button>
                    </div>
                </div>

                <!-- Items -->
                <div data-role="items" class="mt-5 space-y-6"></div>
            </div>

            <div class="w-full md:w-80 mt-5 md:mt-0">
                <div data-role="stats" class="border border-neutral-200 rounded-lg p-5 space-y-4">
                    <div class="flex items-center justify-between text-neutral-600 text-sm">
                        <div class="font-light">قیمت کالا ها (<span data-role="items-total-quantity-count">{{ $itemsTotalQuantity }}</span>)</div>
                        <div>
                            <span data-role="stats-total-price"></span> {{ productCurrency()->label() }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <div class="font-light">جمع سبد خرید</div>
                        <div class="font-medium">
                            <span data-role="stats-total-final-price"></span> {{ productCurrency()->label() }}
                        </div>
                    </div>

                    <div>
                        <a data-role="continue-btn" href="{{ route('client.cart.finalizing.index') }}" class="block btn btn-success text-center text-sm">ادامه سفارش</a>
                    </div>
                </div>

                <div class="text-neutral-600 text-xs font-light leading-6 mt-2">جهت جلوگیری از اتمام موجودی هر چه سریعتر نسبت به پرداخت هزینه سفارش خود اقدام کنید.</div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        var cartDetails = {{ Js::from($cartDetails) }};

        function renderItems() {
            var items = '';

            cartDetails.items.forEach(function (item) {
                items += '<div data-role="cart-item" data-id="'+ item.product_combination_id +'" data-amount-per-sale="'+ item.amount_per_sale +'">';
                    items += '<div class="flex">';
                        // Image
                        items += '<a href="'+ (item.url || '#') +'" class="block w-32 min-w-[8rem] h-32 min-h-[8rem]">';
                            if (item.image) {
                                items += '<img src="'+ item.image.url['thumbnail'] +'" class="bg-neutral-100 object-cover w-full h-full rounded-lg">';
                            } else {
                                items += '<img src="{{ template_asset('assets/img/no-image.jpg') }}" class="bg-neutral-100 object-cover w-full h-full rounded-lg">';
                            }
                        items += '</a>';

                        items += '<div class="flex-1 ms-5">';
                            items += '<div class="flex items-center justify-between">';
                                items += '<div class="flex items-center '+ ((item.is_deleted || ! item.is_lowest_qty_available || item.is_max_qty_exceeded) && 'text-danger') +'">';
                                    if (item.title) {
                                        items += '<a href="'+ (item.url || '#') +'">';
                                            items += '<h4>'+ item.title +'</h4>';
                                        items += '</a>';
                                    } else {
                                        items += '<h4>حذف شده</h4>';
                                    }

                                    if (item.is_deleted || ! item.is_lowest_qty_available || item.is_max_qty_exceeded) {
                                        items += '<i class="fi fi-rr-triangle-warning text-lg flex ms-2"></i>';
                                    }
                                items += '</div>';
                                items += '<button class="text-danger text-xs md:text-sm font-light mr-2" onclick="modal.defaults.confirmDanger(() => { $(\'[data-role=remove-item]\').click(); })">حذف</button>';
                            items += '</div>';

                            items += '<div class="mt-3 space-y-2 font-light text-sm">';
                            if (item.variants) {
                                item.variants.forEach(function (variant) {
                                    items += '<div class="flex items-center">';
                                        items += '<div class="text-neutral-600 w-20">'+ variant.variant +' :</div>';
                                        items += '<div class="font-medium">';

                                        if (variant.type === 'color' && variant.variant) {
                                            items += '<div class="flex items-center">';
                                                items += '<div class="rounded-full w-4 h-4 shadow" style="background-color: '+ variant.value +';"></div>';
                                                items += '<div class="mr-2">'+ variant.item +'</div>';
                                            items += '</div>';
                                        } else {
                                            items += variant.item;
                                        }

                                        items += '</div>';
                                    items += '</div>';
                                });
                            }
                            items += '</div>';
                        items += '</div>';
                    items += '</div>';

                    items += '<div class="flex items-center mt-2">';
                        items += '<div class="w-32 min-w-[8rem]">';
                            var increaseBtnClass = '';
                            var quantityInputClass = '';
                            var decreaseBtnClass = 'hidden';
                            var quantityControlContainerClass = 'hidden';
                            var removeBtnClass = 'hidden';
                            var refreshBtnClass = 'hidden';

                            if (item.is_lowest_qty_available) {
                                quantityControlContainerClass = '';

                                if (item.is_max_qty_exceeded) {
                                    refreshBtnClass = '';
                                } else if (item.quantity_in_cart <= item.amount_per_sale) {
                                    removeBtnClass = '';
                                } else {
                                    decreaseBtnClass = '';
                                }
                            }

                            // Quantity Control
                            items += '<div data-role="quantity-control-container" class="flex w-full '+ quantityControlContainerClass +'">';
                                // Increase
                                items += '<button type="button" data-role="increase-quantity" data-is-active="'+ (item.is_max_qty_reached ? 'false' : 'true') +'" class="btn btn-white text-sm rounded-e-none w-[42px] data-[is-active=false]:opacity-50 data-[is-active=false]:pointer-events-none '+ increaseBtnClass +'"><i class="fi fi-rr-plus flex"></i></button>';

                                // Input
                                items += '<input type="text" data-role="cart-item-quantity-value" value="'+ item.quantity_in_cart +'" class="default rounded-none text-center flex-1 -mx-px z-10 px-0 '+ quantityInputClass +'" readonly>';

                                // Decrease
                                items += '<button type="button" data-role="decrease-quantity" class="btn btn-white text-sm rounded-s-none w-[42px] '+ decreaseBtnClass +'"><i class="fi fi-rr-minus flex"></i></button>';

                                // Remove
                                items += '<button type="button" data-role="remove-item" class="btn btn-white text-danger rounded-s-none w-[42px] '+ removeBtnClass +'"><i class="fi fi-rr-trash flex"></i></button>';

                                // Refresh
                                items += '<button type="button" data-role="refresh-quantity" class="btn btn-white text-sm rounded-s-none w-[42px] '+ refreshBtnClass +'"><i class="fi fi-rr-refresh flex"></i></button>';
                            items += '</div>';

                            // Remove
                            items += '<div class="'+ (item.is_deleted || item.is_lowest_qty_available && 'hidden') +'">';
                                items += '<button data-role="remove-item" class="btn bg-red-500 w-full text-white">حذف</button>';
                            items += '</div>';
                        items += '</div>';

                        // Price
                        items += '<div class="flex items-center ms-5">';
                            // Total Final Price
                            items += '<div class="text-green-600 text-lg '+ ((item.is_deleted || ! item.is_lowest_qty_available || item.is_max_qty_exceeded) && 'hidden') +'">';
                                items += '<span data-role="total-final-price">'+ formatNumber(item.total_final_price) +'</span> {{ productCurrency()->label() }}';
                            items += '</div>';

                            // Total Final Price
                            items += '<div class="ms-3 text-neutral-400 line-through '+ ((item.is_deleted || ! item.is_lowest_qty_available || item.is_max_qty_exceeded || item.discount === 0) && 'hidden') +'">';
                                items += '<span data-role="total-price">'+ formatNumber(item.total_price) +'</span> {{ productCurrency()->label() }}';
                            items += '</div>';

                            // Unavailable
                            items += '<div class="text-red-500 '+ ((! item.is_max_qty_exceeded || ! item.is_lowest_qty_available) && 'hidden') +'">موجودی محصول از موجودی انبار رد شده است.</div>';

                            // Unavailable
                            items += '<div class="text-red-500 '+ ((item.is_deleted || item.is_lowest_qty_available) && 'hidden') +'">محصول مورد نظر موجود نمی باشد.</div>';

                            // Deleted
                            items += '<div class="text-red-500 '+ (! item.is_deleted && 'hidden') +'">محصول مورد نظر حذف شده است.</div>';
                        items += '</div>';
                    items += '</div>';
                items += '</div>';
            });

            $('[data-role="items"]').html(items);
        }

        function renderStats() {
            const stats = cartDetails.stats;

            if (stats.total_quantity_in_cart > 0) {
                $('[data-role="empty-cart-message"]').addClass('hidden');
                $('[data-role="cart-details"]').removeClass('hidden');
            } else {
                $('[data-role="empty-cart-message"]').removeClass('hidden');
                $('[data-role="cart-details"]').addClass('hidden');
            }

            // Errors
            if (! stats.errors.can_purchase) {
                $('[data-role="permission-purchase-error"]').removeClass('hidden');
            } else {
                $('[data-role="permission-purchase-error"]').addClass('hidden');
            }

            if (stats.errors.has_unavailable_products) {
                $('[data-role="unavailable-error"]').removeClass('hidden');
            } else {
                $('[data-role="unavailable-error"]').addClass('hidden');
            }

            if (stats.errors.has_max_qty_exceeded_error) {
                $('[data-role="max-qty-error"]').removeClass('hidden');
            } else {
                $('[data-role="max-qty-error"]').addClass('hidden');
            }

            if (stats.errors.has_deleted_products) {
                $('[data-role="products-deleted-error"]').removeClass('hidden');
            } else {
                $('[data-role="products-deleted-error"]').addClass('hidden');
            }

            if (stats.errors.count > 0) {
                $('[data-role="continue-btn"]').addClass('opacity-60').addClass('pointer-events-none');
            } else {
                $('[data-role="continue-btn"]').removeClass('opacity-60').removeClass('pointer-events-none');
            }

            $('[data-role="stats-total-price"]').html(formatNumber(stats.total_price));
            $('[data-role="stats-total-final-price"]').html(formatNumber(stats.total_final_price));

            elements.setItemsTotalQuantity(stats.total_quantity_in_cart);
        }

        function render() {
            renderItems();
            renderStats();
        }

        render();

        function clearCart() {
            const parent = $(document);

            lockCart(parent);
            requestStore.clear()
                .then(function (response) {
                    const data = response.data;

                    cartDetails = data.details;
                })
                .catch(function () {})
                .finally(function () {
                    render();
                    unlockCart(parent);
                });
        }

        function lockCart(e) {
            lockElem(e.find('[data-role="quantity-control-container"] button'));
            lockElem($('[data-role="continue-btn"]'));
        }

        function unlockCart(e) {
            unlockElem(e.find('[data-role="quantity-control-container"] button'));
            unlockElem($('[data-role="continue-btn"]'));
        }

        function handleErrors(result, ignore = []) {
            const reason = result.reason;

            // Ignore
            if (ignore.indexOf(reason) > -1) {
                return;
            }

            switch (reason) {
                case 'maximum_allowed_quantity_exceeded':
                    toast.error('موجودی محصول در سبد خرید از حداکثر گذشته است.');
                    break;
                case 'maximum_allowed_quantity_reached':
                    toast.error('موجودی محصول در سبد خرید به حداکثر رسیده است.');
                    break;
                case 'product_deleted':
                    toast.error('محصول مورد نظر حذف شده است.');
                    break;
            }
        }

        // Increase
        $(document).on('click', '[data-role="increase-quantity"]', function () {
            const parent = $(this).parents('[data-role="cart-item"]');
            const id = parent.data('id');

            lockCart(parent);
            requestStore.addToCart(id)
                .then(function (response) {
                    const data = response.data;

                    cartDetails = data.details;
                })
                .catch((e) => {
                    if (e.request.status === 400) {
                        const data = e.response.data;

                        cartDetails = data.details;

                        handleErrors(data.result);
                    }
                })
                .finally(() => {
                    render();
                    unlockCart(parent);
                });
        });

        // Decrease
        $(document).on('click', '[data-role="decrease-quantity"]', function () {
            const parent = $(this).parents('[data-role="cart-item"]');
            const id = parent.data('id');

            lockCart(parent);
            requestStore.updateCartItem(id, Number(parent.find('[data-role="cart-item-quantity-value"]').val()) - parent.data('amount-per-sale'))
                .then(function (response) {
                    const data = response.data;

                    cartDetails = data.details;
                })
                .catch((e) => {
                    if (e.request.status === 400) {
                        const data = e.response.data;

                        cartDetails = data.details;

                        handleErrors(data.result);
                    }
                })
                .finally(() => {
                    render();
                    unlockCart(parent);
                });
        });

        // Remove
        $(document).on('click', '[data-role="remove-item"]', function () {
            const parent = $(this).parents('[data-role="cart-item"]');
            const id = parent.data('id');

            lockCart(parent);
            requestStore.removeCartItem(id)
                .then(function (response) {
                    const data = response.data;

                    cartDetails = data.details;
                })
                .catch((e) => {
                    if (e.request.status === 400) {
                        const data = e.response.data;

                        cartDetails = data.details;

                        handleErrors(data.result);
                    }
                })
                .finally(() => {
                    render();
                    unlockCart(parent);
                });
        });
    </script>
@endpush
