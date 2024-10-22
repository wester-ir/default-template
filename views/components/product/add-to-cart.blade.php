<div>
    <button type="button" data-role="add-to-cart" data-quantity="2" class="flex hidden items-center justify-center btn btn-lg btn-success w-full lg:w-96">
        <i class="fi fi-rr-shopping-cart flex text-2xl"></i>
        <span class="ms-3 text-lg">افزودن به سبد خرید</span>
    </button>

    <div data-role="quantity-control-container" class="flex hidden">
        <div class="flex items-center bg-gray-200 rounded-lg p-5">
            <div class="flex w-40 h-11">
                <button type="button" data-role="increase-quantity" data-is-active="true" class="btn btn-white text-sm rounded-e-none flex justify-center w-[48px] h-full data-[is-active=false]:opacity-50 data-[is-active=false]:pointer-events-none">
                    <i class="fi fi-rr-plus flex"></i>
                </button>
                <input type="text" data-role="cart-item-quantity-value" value="1" class="default rounded-none text-center flex-1 h-full -mx-px z-10" readonly>
                <button type="button" data-role="decrease-quantity" class="btn btn-white flex justify-center text-sm rounded-s-none w-[48px] h-full hidden">
                    <i class="fi fi-rr-minus flex"></i>
                </button>
                <button type="button" data-role="remove-item" class="btn btn-white text-danger flex justify-center rounded-s-none w-[48px] h-full hidden">
                    <i class="fi fi-rr-trash flex"></i>
                </button>
                <button type="button" data-role="refresh-quantity" class="btn btn-white flex justify-center text-sm rounded-s-none w-[48px] h-full hidden">
                    <i class="fi fi-rr-refresh flex"></i>
                </button>
            </div>
    
            <a href="{{ route('client.cart.index') }}" class="mr-5">
                <div class="font-medium text-sm">در سبد خرید شما</div>
                <div class="font-light text-xs mt-1">جهت مشاهده کلیک کنید</div>
            </a>
        </div>
    </div>
</div>

@pushOnce('bottom-scripts')
    <script>
        const quantityControlContainer = $('[data-role="quantity-control-container"]');

        function lockQuantityControl() {
            quantityControlContainer.find('button').attr('data-locked', 'true');
        }

        function unlockQuantityControl() {
            quantityControlContainer.find('button').attr('data-locked', 'false');
        }

        $('[data-role="add-to-cart"]').click(function () {
            lockElem(this);
            requestStore.addToCart(cartItem.product_combination_id)
                .then(function (response) {
                    const data = response.data;

                    updateCombinationCart(cartItem.product_combination_id, data.result.data);

                    if (data.result.data.quantity === 1) {
                        toast.dismissAll();
                        toast.success('محصول مورد نظر به سبد خرید اضافه شد.', {
                            buttons: [
                                {
                                    innerHTML: 'مشاهده سبد',
                                    className: 'btn btn-success btn-md font-light text-sm text-center flex-1',
                                    href: '{{ route('client.cart.index') }}',
                                }, {
                                    innerHTML: 'بستن',
                                    className: 'btn btn-light btn-md font-light text-sm text-center cursor-pointer',
                                    onclick: function () {
                                        toast.dismiss();
                                    },
                                },
                            ],
                        });
                    }

                    elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);
                })
                .catch((e) => {
                    if (e.request.status === 400) {
                        const data = e.response.data;

                        updateCombinationCart(cartItem.product_combination_id, data.result.data);
                        elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);

                        handleCartErrors(data.result);
                    }
                })
                .finally(() => {
                    selectCombination(cartItem.uid);
                    unlockElem(this);
                });
        });

        $('[data-role="increase-quantity"]').click(function () {
            lockQuantityControl();
            requestStore.addToCart(cartItem.product_combination_id)
                .then(function (response) {
                    const data = response.data;

                    updateCombinationCart(cartItem.product_combination_id, data.result.data);
                    elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);
                })
                .catch((e) => {
                    if (e.request.status === 400) {
                        const data = e.response.data;

                        updateCombinationCart(cartItem.product_combination_id, data.result.data);
                        elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);

                        handleCartErrors(data.result);
                    }
                })
                .finally(() => {
                    selectCombination(cartItem.uid);
                    unlockQuantityControl();
                });
        });

        $('[data-role="decrease-quantity"]').click(function () {
            lockQuantityControl();
            requestStore.updateCartItem(cartItem.product_combination_id, Number($('[data-role="cart-item-quantity-value"]').val()) - {{ $product->amount_per_sale }})
                .then(function (response) {
                    const data = response.data;

                    updateCombinationCart(cartItem.product_combination_id, data.result.data);
                    elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);
                })
                .catch((e) => {
                    if (e.request.status === 400) {
                        const data = e.response.data;

                        updateCombinationCart(cartItem.product_combination_id, data.result.data);
                        elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);

                        handleCartErrors(data.result);
                    }
                })
                .finally(() => {
                    selectCombination(cartItem.uid);
                    unlockQuantityControl();
                });
        });

        $('[data-role="remove-item"]').click(function () {
            lockQuantityControl();
            requestStore.removeCartItem(cartItem.product_combination_id)
                .then(function (response) {
                    const data = response.data;

                    updateCombinationCart(cartItem.product_combination_id, data.result.data);
                    elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);
                })
                .catch((e) => {
                    if (e.request.status === 400) {
                        const data = e.response.data;

                        updateCombinationCart(cartItem.product_combination_id, data.result.data);
                        elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);

                        handleCartErrors(data.result, ['product_deleted']);
                    }
                })
                .finally(() => {
                    selectCombination(cartItem.uid);
                    unlockQuantityControl();
                })
        });

        $('[data-role="refresh-quantity"]').click(function () {
            lockQuantityControl();
            requestStore.updateCartItem(cartItem.product_combination_id, 1)
                .then(function (response) {
                    const data = response.data;

                    updateCombinationCart(cartItem.product_combination_id, data.result.data);
                    elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);

                    toast.success('موجودی محصول در سبد خرید تصحیح شد.');
                })
                .catch((e) => {
                    if (e.request.status === 400) {
                        const data = e.response.data;

                        updateCombinationCart(cartItem.product_combination_id, data.result.data);
                        elements.setItemsTotalQuantity(data.details.stats.total_quantity_in_cart);

                        handleCartErrors(data.result);
                    }
                })
                .finally(() => {
                    selectCombination(cartItem.uid);
                    unlockQuantityControl();
                })
        });

        function handleCartErrors(result, ignore = []) {
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
    </script>
@endpushonce
