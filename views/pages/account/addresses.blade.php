@extends('templates.default.views.layouts.default')

@section('title', 'آدرس ها')

@section('content')
    <div class="container">
        <div class="flex flex-col md:flex-row space-y-5 md:space-y-0">
            <!-- Sidebar -->
            @include('templates.default.views.pages.account.partials.sidebar')

            <!-- Content -->
            <div class="flex-1 md:mr-5">
                <div class="border border-neutral-200 rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <h3>آدرس ها</h3>

                        <a href="#" class="font-light text-sm hidden" data-role="open-address-creation-modal">افزودن</a>
                    </div>

                    <div class="mt-8">
                        <div data-role="addresses" class="space-y-6"></div>
                        <div data-role="addresses-loading-message" class="flex items-center justify-center font-light text-sm">صبر کنید</div>
                        <div data-role="addresses-empty-message" class="flex hidden items-center justify-center font-light text-sm">هیچ آدرسی ندارید.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('templates.default.views.pages.account.addresses.create-edit-modal')
@endsection

@push('bottom-scripts')
    <script>
        var addresses = [];

        function renderAddresses() {
            var html = '';

            addresses.forEach(function (address) {
                html += renderAddress(address);
            });

            $('[data-role="addresses"]').html(html);

            if (addresses.length === 0) {
                $('[data-role="addresses-empty-message"]').removeClass('hidden');
            }

            if (addresses.length >= {{ settingService('address')['limit'] }}) {
                $('[data-role="open-address-creation-modal"]').attr('data-max-limit-reached', true);
            } else {
                $('[data-role="open-address-creation-modal"]').removeAttr('data-max-limit-reached');
            }
        }

        function initAddress() {
            $('[data-role="open-address-creation-modal"], [data-role="addresses"], [data-role="addresses-empty-message"]').addClass('hidden');
            $('[data-role="addresses-loading-message"]').removeClass('hidden');

            axios.get('{{ route('client.account.addresses.ajax.all') }}')
                .then(function (response) {
                    $('[data-role="open-address-creation-modal"], [data-role="addresses"]').removeClass('hidden');
                    $('[data-role="addresses-loading-message"]').addClass('hidden');

                    addresses = response.data.data;

                    renderAddresses();
                })
                .catch(function () {

                });
        }

        ready(function () {
            initAddress();
        });

        function renderAddress(address) {
            var html = '';

            html += '<div data-role="address-item" data-id="'+ address.id +'">';
                html += '<div class="flex items-center justify-between">';
                    html += '<h4>'+ address.address +'</h4>';
                    html += '<div class="flex items-center space-x-3 space-x-reverse">';
                        html += '<button data-role="open-address-edit-modal" data-id="'+ address.id +'"><span class="icon icon-edit bg-primary w-[18px] h-[18px] pointer-events-none"></span></button>';
                        html += '<button data-role="delete-address" data-id="'+ address.id +'"><span class="icon icon-trash bg-danger w-[18px] h-[18px] pointer-events-none"></span></button>';
                    html += '</div>';
                html += '</div>';

                html += '<div class="mt-3 space-y-2">';
                    // Province
                    html += '<div class="flex items-center font-light text-sm">';
                        html += '<span class="icon icon-location w-4 h-4 flex"></span>';
                        html += '<div class="ms-2">'+ address.province.name +'، '+ address.city.name +'</div>';
                    html += '</div>';

                    // Receiver
                    html += '<div class="flex items-center font-light text-sm">';
                        html += '<span class="icon icon-user w-4 h-4 flex"></span>';
                        html += '<div class="ms-2">'+ address.full_name +'</div>';
                    html += '</div>';
                html += '</div>';

            html += '</div>';

            return html;
        }

        function addAddress(address) {
            $('[data-role="addresses-empty-message"]').addClass('hidden');

            addresses.push(address);

            renderAddresses();
        }

        function editAddress(address) {
            addresses = addresses.map(function (item) {
                if (item.id === address.id) {
                    return address;
                }

                return item;
            })

            renderAddresses();
        }

        $(document).on('click', '[data-role="delete-address"]', function (e) {
            const id = $(e.target).parents('[data-role="address-item"]').data('id');

            modal.defaults.confirmDanger(function () {
                axios.delete('{{ route('client.account.addresses.ajax.destroy', '#') }}'.replace('#', id))
                    .then(function () {
                        addresses = addresses.filter(function (address) {
                            return address.id !== id;
                        });

                        renderAddresses();
                    })
                    .catch(function () {

                    });
            });
        });
    </script>
@endpush
