<div class="account-sidebar">
    <div class="border border-neutral-200 rounded-lg overflow-hidden">
        <div class="p-5">
            <div class="flex items-center">
                <img src="{{ template_asset('assets/img/user.png') }}" class="w-12 h-12 rounded-full shadow">
                <div class="flex-1 ms-5">
                    <div class="flex items-center justify-between">
                        <span class="font-medium" data-role="users-full-name">{{ auth()->user()->full_name ?: 'بدون نام' }}</span>

                        <button id="open-edit-name-modal">
                            <span class="icon icon-edit bg-neutral-500 hover:bg-sky-500 transition-colors"></span>
                        </button>
                    </div>
                    <div class="mt-1">
                        <span class="font-light text-neutral-400">{{ auth()->user()->number }}</span>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <ul class="mt-8 space-y-5 mr-5 list-disc font-light marker:text-gray-400">
                <li>
                    <div class="flex items-center justify-between">
                        <span>کلاب</span>
                        <span>{{ auth()->user()->points }} امتیاز</span>
                    </div>
                </li>
            </ul>
        </div>

        <div class="border-t border-neutral-200">
            <ul class="navigation">
                <li>
                    <a href="{{ route('client.account.index') }}" class="item {{ request()->routeIs('client.account.index') ? 'active' : null }}">
                        <i class="fi fi-rr-home flex"></i>
                        <span class="label">داشبورد</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.account.orders.index') }}" class="item {{ request()->routeIs('client.account.orders.*') ? 'active' : null }}">
                        <i class="fi fi-rr-shopping-bag flex"></i>
                        <span class="label">سفارش‌ها</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.account.addresses.index') }}" class="item {{ request()->routeIs('client.account.addresses.index') ? 'active' : null }}">
                        <i class="fi fi-rr-address-book flex"></i>
                        <span class="label">آدرس‌ها</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.account.favorites.index') }}" class="item {{ request()->routeIs('client.account.favorites.index') ? 'active' : null }}">
                        <i class="fi fi-rr-heart flex"></i>
                        <span class="label">علایق</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.account.notifications.index') }}" class="item {{ request()->routeIs('client.account.notifications.index') ? 'active' : null }}">
                        <i class="fi fi-rr-bell flex"></i>
                        
                        <div class="flex items-center justify-between flex-1 label">
                            <span>اعلان</span>

                            @if ($unreadNotifications)
                                <span class="badge badge-danger mr-2 md:-ml-2 px-2 py-[2px] md:px-[10px] md:py-1">{{ $unreadNotifications > 100 ? '+100' : $unreadNotifications }}</span>
                            @endif
                        </div>
                    </a>
                </li>
{{--                <li>--}}
{{--                    <button href="#" class="item" onclick="document.getElementById('logout-form').submit();">--}}
{{--                        <span class="w-2 h-2 ring ring-red-100 rounded-full bg-red-500"></span>--}}
{{--                        <span class="label">خروج</span>--}}
{{--                    </button>--}}
{{--                </li>--}}
            </ul>
        </div>
    </div>
</div>

<template id="edit-name-modal-body-template">
    <div id="edit-name-form" class="form">
        <div class="form-row">
            <div class="form-control" data-form-field-id="first_name">
                <label for="first_name">نام</label>
                <input id="first_name" type="text" name="first_name" class="default">
            </div>

            <div class="form-control" data-form-field-id="last_name">
                <label for="last_name">نام خانوادگی</label>
                <input id="last_name" type="text" name="last_name" class="default">
            </div>
        </div>
    </div>
</template>

@push('bottom-scripts')
    <script>
        $('#open-edit-name-modal').click(function () {
            const container = document.createElement('div');

            container.innerHTML = document.getElementById('edit-name-modal-body-template').innerHTML;

            // Set initial data
            $(container).find('#first_name').val(window.user.first_name);
            $(container).find('#last_name').val(window.user.last_name);

            modal.create({
                title: 'ویرایش نام',
                size: 'semi-large',
                body: container,
                buttons: [{
                    id: 'edit-name-btn',
                    label: 'ویرایش',
                    className: 'btn btn-success btn-sm',
                    onClick: function () {
                        const btn = document.getElementById('edit-name-btn');

                        lockElem(btn);
                        form.resetFormErrors('#edit-name-form');

                        axios.patch('{{ route('client.account.ajax.user.update-name') }}', {
                            first_name: $('#first_name').val(),
                            last_name: $('#last_name').val(),
                        })
                            .then((response) => {
                                const data = response.data;

                                toast.success('نام و نام خانوادگی با موفقیت تغییر یافت.');
                                modal.dismissAll();

                                // Update doms
                                $('[data-role="users-full-name"]').text(data.data.full_name);

                                // Update data
                                window.user.first_name = data.data.first_name;
                                window.user.last_name = data.data.last_name;
                                window.user.full_name = data.data.full_name;
                            })
                            .catch((e) => {
                                switch (e.request.status) {
                                    case 422:
                                        form.setFormErrors('#edit-name-form', e.response.data.errors);
                                        break;
                                }
                            })
                            .finally(() => {
                                unlockElem(btn);
                            });
                    },
                }],
            });
        });
    </script>
@endpush
