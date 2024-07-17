@use('App\Models\Province')

<template id="address-modal-body-template">
    <div id="address-form" class="form">
        <div class="form-row">
            <div class="form-control">
                <label for="province_id">استان *</label>
                <select id="province_id" class="default" name="province_id" onchange="getCities(event.target.value)">
                    @foreach (Province::get() as $province)
                        <option value="{{ $province->id }}" @selected(old('province_id') === (string) $province->id)>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-control">
                <label for="city_id">شهر *</label>
                <select id="city_id" class="default" name="city_id"></select>
            </div>

            <div class="form-control">
                <label for="postal_code">کد پستی</label>
                <input id="postal_code" type="text" name="postal_code" class="default text-center">
            </div>
        </div>

        <div class="form-control">
            <label for="address">آدرس *</label>
            <input id="address" type="text" name="address" class="default">
        </div>

        <hr>

        <div class="form-group">
            <div>
                <label class="inline-flex items-center cursor-pointer select-none">
                    <input type="checkbox" id="is_self" name="is_self" value="1">
                    <span class="mr-2 text-sm">گیرنده خودم هستم</span>
                </label>
            </div>
        </div>

        <div class="form-row">
            <div class="form-control">
                <label for="first_name">نام *</label>
                <input id="first_name" type="text" name="first_name" class="default">
            </div>

            <div class="form-control">
                <label for="last_name">نام خانوادگی *</label>
                <input id="last_name" type="text" name="last_name" class="default">
            </div>
        </div>

        <div class="form-control">
            <label for="number">شماره موبایل گیرنده *</label>
            <input id="number" type="text" name="number" class="default">
        </div>
    </div>
</template>

@push('bottom-scripts')
    <script>
        $('[data-role="open-address-creation-modal"]').click(function (e) {
            if ($(this).attr('data-max-limit-reached')) {
                toast.error('تعداد آدرس های شما به حداکثر تعداد رسیده است.');

                return;
            }

            const container = document.createElement('div');

            container.innerHTML = document.getElementById('address-modal-body-template').innerHTML;

            modal.create({
                title: 'افزودن آدرس',
                size: 'semi-large modal-full-height',
                body: container,
                buttons: [{
                    id: 'add-address-btn',
                    label: 'افزودن',
                    className: 'btn btn-success btn-sm',
                    onClick: function () {
                        const btn = document.getElementById('add-address-btn');

                        lockElem(btn);
                        form.resetFormErrors('#address-form');

                        axios.post('{{ route('client.account.addresses.ajax.create') }}', {
                            province_id: $('#province_id').val(),
                            city_id: $('#city_id').val(),
                            postal_code: $('#postal_code').val(),
                            address: $('#address').val(),
                            is_self: $('#is_self').is(':checked'),
                            first_name: $('#first_name').val(),
                            last_name: $('#last_name').val(),
                            number: $('#number').val(),
                        })
                            .then((response) => {
                                const data = response.data;

                                toast.success('آدرس با موفقیت ایجاد شد.');
                                modal.dismissAll();

                                addAddress(data.data);
                            })
                            .catch((e) => {
                                switch (e.request.status) {
                                    case 422:
                                        form.setFormErrors('#address-form', e.response.data.errors);
                                        break;
                                    case 403:
                                        toast.error('تعداد آدرس های شما به حداکثر تعداد رسیده است.');
                                        break;
                                }
                            })
                            .finally(() => {
                                unlockElem(btn);
                            });
                    },
                }]
            });

            setIsSelfEvent();

            $('#province_id, #city_id').selectbox();
        });

        $(document).on('click', '[data-role="open-address-edit-modal"]', function (e) {
            const id = $(e.target).data('id');
            const address = addresses.find(function (item) {
                return item.id === id;
            });
            const container = document.createElement('div');

            container.innerHTML = document.getElementById('address-modal-body-template').innerHTML;

            modal.create({
                title: 'ویرایش آدرس',
                size: 'semi-large modal-full-height',
                body: container,
                buttons: [{
                    id: 'edit-address-btn',
                    label: 'ویرایش',
                    className: 'btn btn-success btn-sm',
                    onClick: function () {
                        const btn = document.getElementById('add-address-btn');

                        lockElem(btn);
                        form.resetFormErrors('#address-form');

                        axios.put('{{ route('client.account.addresses.ajax.update', '#') }}'.replace('#', id), {
                            province_id: $('#province_id').val(),
                            city_id: $('#city_id').val(),
                            postal_code: $('#postal_code').val(),
                            address: $('#address').val(),
                            is_self: $('#is_self').is(':checked'),
                            first_name: $('#first_name').val(),
                            last_name: $('#last_name').val(),
                            number: $('#number').val(),
                        })
                            .then((response) => {
                                const data = response.data;

                                toast.success('آدرس با موفقیت ویرایش شد.');
                                modal.dismissAll();

                                editAddress(data.data);
                            })
                            .catch((e) => {
                                switch (e.request.status) {
                                    case 422:
                                        form.setFormErrors('#address-form', e.response.data.errors);
                                        break;
                                }
                            })
                            .finally(() => {
                                unlockElem(btn);
                            });
                    },
                }],
            });

            setIsSelfEvent();

            // Set
            $('#province_id').find('option').each(function () {
                if (Number(this.value) === address.province.id) {
                    $(this).attr('selected', true);
                }
            });
            $('#city_id').attr('data-selected-value', address.city.id);
            getCities(address.province.id);
            $('#postal_code').val(address.postal_code);
            $('#address').val(address.address);
            $('#first_name').val(address.first_name);
            $('#last_name').val(address.last_name);
            $('#number').val(address.number);
            if (address.is_self) {
                $('#is_self').attr('checked', true).change();
            }

            $('#province_id, #city_id').selectbox();
        });

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
    </script>
@endpush
