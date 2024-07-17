<div class="md:w-[280px] lg:w-[340px]  md:block">
    <div class="border border-neutral-200 rounded-lg overflow-hidden">
        <div class="p-5">
            <div class="flex items-center">
                <img src="{{ template_asset('assets/img/user.png') }}" class="w-12 h-12 rounded-full shadow">
                <div class="flex-1 ms-5">
                    <div class="flex items-center justify-between">
                        <span class="font-medium" data-role="users-full-name">{{ auth()->user()->full_name }}</span>

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
            <ul class="divide-y divide-neutral-200">
                <li>
                    <a href="{{ route('client.account.index') }}" class="flex items-center px-5 py-3 hover:bg-neutral-50">
                        <i class="fi fi-rr-home flex"></i>
                        <span class="ms-5">داشبورد</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.account.orders.index') }}" class="flex items-center px-5 py-3 hover:bg-neutral-50">
                        <i class="fi fi-rr-shopping-bag flex"></i>
                        <span class="ms-5">سفارش ها</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.account.addresses.index') }}" class="flex items-center px-5 py-3 hover:bg-neutral-50">
                        <i class="fi fi-rr-address-book flex"></i>
                        <span class="ms-5">آدرس ها</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.account.favorites.index') }}" class="flex items-center px-5 py-3 hover:bg-neutral-50">
                        <i class="fi fi-rr-heart flex"></i>
                        <span class="ms-5">مورد علاقه</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.account.notifications.index') }}" class="flex items-center px-5 py-3 hover:bg-neutral-50">
                        <i class="fi fi-rr-bell flex"></i>
                        <span class="ms-5">اعلان ها</span>

                        @if ($unreadNotifications = auth()->user()->unreadNotifications()->count())
                            <span class="badge badge-danger mr-auto -ml-2">{{ $unreadNotifications > 100 ? '+100' : $unreadNotifications }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-5 py-3 hover:bg-neutral-50">
                        <span class="w-2 h-2 ring ring-red-100 rounded-full bg-red-500"></span>
                        <span class="ms-5">خروج</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<template id="edit-name-modal-body-template">
    <div id="edit-name-form" class="form">
        <div class="form-row">
            <div class="form-control">
                <label for="first_name">نام</label>
                <input id="first_name" type="text" name="first_name" class="default">
            </div>

            <div class="form-control">
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
