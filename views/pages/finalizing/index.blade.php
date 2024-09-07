@extends('templates.default.views.layouts.default')
@inject('cartService', 'App\Services\CartService')
@use('App\Models\Province')
@use('App\Models\Courier')

@php
    $itemsTotalQuantity = $cartService->getTotalQuantity();
    $cart = \App\Repositories\CartRepository\UserCartRepository::get();
@endphp

@title('نهایی سازی سفارش')

@section('content')
    <div class="container">
        <form id="finalize-form" action="{{ route('client.cart.finalizing.finalize') }}" method="POST" class="flex flex-col md:flex-row" onsubmit="return validate()">
            @csrf
            <input type="hidden" name="invoice_key" value="">
            <input type="hidden" name="payment_gateway" value="{{ $paymentGateways->isEmpty() ? null : $paymentGateways[0]->id }}">

            <!-- Content -->
            <div class="space-y-5 flex-1 ml-0 md:ml-5">
                @if (auth()->check() && settingService('cart')['discount'])
                    <!-- Discount -->
                    <div id="discount-form" class="form border border-neutral-200 rounded-lg p-5">
                        <h3>کد تخفیف</h3>
                        <div class="text-sm font-light mt-1">پس از اعمال کد تخفیف، درصورت هرگونه تغییر در سبد کالا، کد تخفیف حذف خواهد شد.</div>

                        <div class="mt-5">
                            <div id="code" data-role="discount-form" class="flex mt-2 {{ $cart->has_discount ? 'hidden' : '' }}">
                                <input data-role="discount-code" type="text" class="default rounded-e-none flex-1 font-latin uppercase" name="discount_code" value="">
                                <button type="button" data-role="apply-discount-btn" class="btn btn-default rounded-s-none -ms-px text-sm">ثبت کد تخفیف</button>
                            </div>
                            <div data-role="discount-success" class="flex mt-2 text-green-500 {{ ! $cart->has_discount ? 'hidden' : '' }}">
                                کد تخفیف اعمال شده است.
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Address -->
                <div class="border border-neutral-200 rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <h3>آدرس</h3>
                        
                        <a href="{{ route('client.account.addresses.index') }}" class="text-sm">مدیریت آدرس ها</a>
                    </div>
                    <div class="text-sm font-light mt-1">لطفاً آدرس مورد نظر را وارد کنید.</div>

                    @if (! $addresses->isEmpty())
                        <div class="mt-5 space-y-3">
                            @foreach ($addresses as $address)
                                <label class="flex items-center border border-neutral-200 hover:ring ring-slate-200 transition-all p-5 rounded-lg cursor-pointer">
                                    <input type="radio" name="address_id" value="{{ $address->id }}">
                                    <div class="flex-1 mr-4">
                                        <div>{{ $address->address }}</div>

                                        <div class="flex items-center text-sm font-medium mt-2">
                                            <i class="fi fi-rr-marker flex"></i>
                                            <span class="mr-3">{{ $address->province->name }}، {{ $address->city->name }}</span>
                                        </div>
                                        <div class="flex items-center text-sm mt-1">
                                            <i class="fi fi-rr-phone-flip flex"></i>
                                            <span class="mr-3">{{ $address->is_self ? auth()->user()->number : $address->number }}</span>    
                                        </div>
                                        <div class="flex items-center text-sm mt-1">
                                            <i class="fi fi-rr-user flex"></i>
                                            <span class="mr-3">{{ $address->is_self ? auth()->user()->full_name : $address->full_name }}</span>    
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                            <label class="flex items-center bg-slate-100 rounded-lg cursor-pointer p-5">
                                <input type="radio" name="address_id" value="0" checked>
                                <div class="flex-1 mr-4">آدرس جدید وارد می کنم</div>
                            </label>
                        </div>
                    @endif

                    <div id="new-address-form" class="form mt-7">
                        <div class="form-row">
                            <div class="form-control">
                                <label for="province_id">استان *</label>
                                <select id="province_id" class="default" name="address[province_id]" onchange="provinceChanged(event)">
                                    @foreach (Province::get() as $province)
                                        <option value="{{ $province->id }}" @selected(old('province_id') === (string) $province->id)>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-control">
                                <label for="city_id">شهر *</label>
                                <select id="city_id" class="default" name="address[city_id]" onchange="cityChanged()"></select>
                            </div>

                            <div class="form-control">
                                <label for="postal_code">کد پستی</label>
                                <input id="postal_code" type="text" maxlength="10" name="address[postal_code]" class="default text-center">
                            </div>
                        </div>

                        <div class="form-control">
                            <label for="address">آدرس *</label>
                            <input id="address" type="text" name="address[address]" class="default">
                        </div>

                        @if (auth()->check())
                            <div class="form-group">
                                <div>
                                    <label class="inline-flex items-center cursor-pointer select-none">
                                        <input type="checkbox" id="is_self" name="address[is_self]" value="1">
                                        <span class="mr-2 text-sm">گیرنده خودم هستم</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="form-row">
                            <div class="form-control">
                                <label for="first_name">نام *</label>
                                <input id="first_name" type="text" name="address[first_name]" class="default">
                            </div>

                            <div class="form-control">
                                <label for="last_name">نام خانوادگی *</label>
                                <input id="last_name" type="text" name="address[last_name]" class="default">
                            </div>

                            <div class="form-control">
                                <label for="number">شماره موبایل *</label>
                                <input id="number" type="text" name="address[number]" class="default">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional -->
                <div class="border border-neutral-200 rounded-lg p-5">
                    <h3>توضیحات اضافی</h3>
                    <div class="text-sm font-light mt-1">لطفاً درصورت داشتن توضیحات اضافی درمورد سفارش و نحوه ارسال در فیلد زیر وارد کنید.</div>
                    <div class="form mt-5">
                        <div class="form-control">
                            <textarea id="additional_details" name="additional_details" class="default min-h-[150px] px-3 py-2"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Sidebar -->
            <div class="w-full md:w-80 mt-5 md:mt-0">
                <div class="space-y-5">
                    <div class="border border-neutral-200 rounded-lg p-5">
                        <h4>حمل و نقل</h4>
                        <div class="mt-4 space-y-4">
                            @foreach (Courier::active()->get() as $courier)
                                <div class="flex items-start">
                                    <div class="pt-1">
                                        <input type="radio" name="courier_id" value="{{ $courier->id }}" checked>
                                    </div>
                                    <div class="flex-1 ms-3">
                                        <div class="flex justify-between">
                                            <div class="text-sm font-medium">{{ $courier->name }}</div>
                                            <div class="text-sm font-medium">{{ number_format($courier->cost) }} {{ productCurrency()->label() }}</div>
                                        </div>
                                        <div class="mt-1">
                                            <div class="text-sm">{{ $courier->type->label() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div data-role="stats" data-is-active="false" class="border border-neutral-200 rounded-lg p-5 space-y-4 data-[is-active=false]:pointer-events-none data-[is-active=false]:opacity-50">
                        <div class="flex items-center justify-between text-neutral-600 text-sm">
                            <div class="font-light">جمع سبد خرید (<span data-role="items-total-quantity-count">{{ $itemsTotalQuantity }}</span>)</div>
                            <div>
                                <span data-role="stats-total-final-price"></span> {{ productCurrency()->label() }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <div class="font-light">هزینه ارسال</div>
                            <div class="font-medium">
                                <span data-role="stats-shipping-cost"></span> {{ productCurrency()->label() }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <div class="font-light">مالیات</div>
                            <div class="font-medium">
                                <span data-role="stats-tax-amount"></span> {{ productCurrency()->label() }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <div class="font-light">تخفیف</div>
                            <div class="font-medium">
                                <span data-role="stats-discount-amount"></span> {{ productCurrency()->label() }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <div class="font-light">قابل پرداخت</div>
                            <div class="font-medium">
                                <span data-role="stats-payable-amount"></span> {{ productCurrency()->label() }}
                            </div>
                        </div>
                    </div>

                    @if ($paymentGateways->count() !== 1)
                        <div class="border border-neutral-200 rounded-lg p-5">
                            <h4>درگاه پرداخت</h4>

                            @if (! $paymentGateways->isEmpty())
                                <div class="grid grid-cols-3 gap-3 mt-4">
                                    @foreach ($paymentGateways as $gateway)
                                        <div data-id="{{ $gateway->id }}" data-role="payment-gateway" data-is-active="{{ var_export($gateway->is_default) }}" class="flex items-center justify-center p-[2px] bg-white border border-neutral-200 rounded-lg overflow-hidden cursor-pointer data-[is-active=true]:border-green-400">
                                            <img src="{{ $gateway->logo_url }}" class="w-full">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-danger text-center mt-4">بدون درگاه پرداخت فعال</div>
                            @endif
                        </div>
                    @endif

                    <button data-role="continue-btn" class="w-full block btn btn-success text-center text-sm" @disabled($paymentGateways->isEmpty())>ادامه سفارش</button>
                </div>

                <div class="text-neutral-600 text-xs font-light leading-6 mt-2">جهت جلوگیری از اتمام موجودی هر چه سریعتر نسبت به پرداخت هزینه سفارش خود اقدام کنید.</div>
            </div>
        </form>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        $('[data-role="apply-discount-btn"]').click(function () {
            const $this = this;
            lockElem($this);
            form.resetFormErrors('#discount-form');

            const code = $('[data-role="discount-code"]').val();

            axios.patch('{{ route('client.cart.finalizing.ajax.apply-discount') }}', {
                code: code,
            })
                .then(function (response) {
                    toast.success('کد تخفیف اعمال شد.');

                    $('[data-role="discount-form"]').addClass('hidden');
                    $('[data-role="discount-success"]').removeClass('hidden');

                    getStats();
                })
                .catch(function (e) {
                    if (e.request.status === 400) {
                        toast.error(e.response.data.message);
                    } else if (e.request.status === 404) {
                        toast.error('کد تخفیف یافت نشد.');
                    } else if (e.request.status === 422) {
                        form.setFormErrors('#discount-form', e.response.data.errors);
                    }
                })
                .finally(function () {
                    unlockElem($this);
                });
        });

        $('input[name="address_id"]').change(function () {
            var id = $(this).val();
            var newAddress = id === '0';

            if (newAddress) {
                $('#new-address-form').removeClass('hidden');
            } else {
                $('#new-address-form').addClass('hidden');
            }
        });

        function provinceChanged(e) {
            getCities(e.target.value);
            getStats();
        }

        function cityChanged() {
            getStats();
        }

        $('input[name="courier_id"]').click(function () {
            getStats();
        });

        $('[data-role="payment-gateway"]').click(function () {
            $('[data-role="payment-gateway"]').attr('data-is-active', false);
            $(this).attr('data-is-active', true);
            $('input[name="payment_gateway"]').val(
                $(this).data('id')
            );
        });

        function setIsSelfEvent() {
            $('#is_self').change(function () {
                const hasFullName = {{ var_export(auth()->user()->has_full_name ?? false, false) }};

                if (this.checked) {
                    if (hasFullName) {
                        $('#first_name, #last_name').prop('disabled', true);
                        $('#first_name').val('{{ auth()->user()->first_name ?? null }}');
                        $('#last_name').val('{{ auth()->user()->last_name ?? null }}');
                    }

                    $('#number').prop('disabled', true).val('{{ auth()->user()->number ?? null }}');
                } else {
                    $('#first_name, #last_name, #number').val('').removeAttr('disabled');
                }
            });
        }

        function validate() {
            form.resetFormErrors('#finalize-form');

            var addressId = $('input[name="address_id"]:checked').val();
            
            if (addressId === '0') {
                addressId = null;
            }

            axios.post('{{ route('client.cart.finalizing.ajax.validate-form') }}', {
                invoice_key: $('[name="invoice_key"]').val(),
                payment_gateway: $('[name="payment_gateway"]').val(),
                courier_id: $('[name="courier_id"]:checked').val(),
                address_id: addressId,
                address: ! addressId ? {
                    province_id: $('#province_id').val(),
                    city_id: $('#city_id').val(),
                    postal_code: $('#postal_code').val(),
                    address: $('#address').val(),
                    is_self: $('#is_self').is(':checked'),
                    first_name: $('#first_name').val(),
                    last_name: $('#last_name').val(),
                    number: $('#number').val(),
                } : null,
                additional_details: $('#additional_details').val(),
            })
                .then(function () {
                    $('input:disabled').removeAttr('disabled');

                    document.getElementById('finalize-form').submit();
                })
                .catch(function (e) {
                    if (e.request.status === 422) {
                        var errors = exceptKeys(e.response.data.errors, ['address']);

                        form.setFormErrors('#finalize-form', errors, {
                            'address.province_id': 'province_id',
                            'address.city_id': 'city_id',
                            'address.postal_code': 'postal_code',
                            'address.address': 'address',
                            'address.is_self': 'is_self',
                            'address.first_name': 'first_name',
                            'address.last_name': 'last_name',
                            'address.number': 'number',
                        });
                    }
                });

            return false;
        }

        function getStats() {
            $('[data-role="stats"]').attr('data-is-active', false);

            axios.get('{{ route('client.cart.ajax.stats') }}', {
                params: {
                    courier_id: $('[name="courier_id"]:checked').val(),
                    province_id: $('#province_id').val(),
                    city_id: $('#city_id').val(),
                },
            })
                .then(function (response) {
                    const data = response.data;

                    $('input[name="invoice_key"]').val(data.invoice.key);
                    $('[data-role="stats-total-final-price"]').html(formatNumber(data.total_final_price));
                    $('[data-role="stats-shipping-cost"]').html(formatNumber(data.invoice.shipping_cost));
                    $('[data-role="stats-discount-amount"]').html(formatNumber(data.invoice.discount_amount));
                    $('[data-role="stats-tax-amount"]').html(formatNumber(data.invoice.tax_amount));
                    $('[data-role="stats-payable-amount"]').html(formatNumber(data.invoice.payable_amount));
                })
                .catch(function () {

                })
                .finally(function () {
                    $('[data-role="stats"]').attr('data-is-active', true);
                });
        }

        setIsSelfEvent();

        function getCities(province) {
            $('.selectbox').addClass('disabled');

            axios.get('{{ route('client.provinces.cities.ajax.all', '#province') }}'.replace('#province', province))
                .then(({data}) => {
                    document.getElementById('city_id').innerHTML = '';

                    const selectedValue = $('#city_id').data('selected-value');

                    data.forEach(function (city) {
                        document.getElementById('city_id').innerHTML+= '<option value="'+ city.id +'" '+ (selectedValue === city.id ? 'selected' : '') +'>'+ city.name +'</option>';
                    });

                    $('#city_id').selectbox();
                })
                .catch(() => {})
                .finally(() => {
                    $('.selectbox').removeClass('disabled');
                });
        }

        $('#province_id, #city_id').selectbox();

        ready(() => {
            getStats();
        });
    </script>
@endpush
