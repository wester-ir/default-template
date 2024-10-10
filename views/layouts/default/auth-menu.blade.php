@if (auth()->check())
    <div class="flex">
        <div data-role="dropdown" class="relative">
            <button data-role="dropdown-trigger" type="button" data-is-active="false" class="flex items-center btn text-sm font-normal data-[is-active=true]:bg-white">
                <span class="hidden md:block" data-role="users-full-name">{{ auth()->user()->full_name ?? 'کاربر' }}</span>
                <i class="fi fi-rr-user flex md:hidden text-2xl"></i>

                <div class="mr-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>

                @if ($unreadNotifications)
                    <div class="absolute left-0 top-[14px] w-2 h-2 rounded-full bg-red-400 ring ring-red-200 animate-pulse"></div>
                @endif
            </button>

            <div data-role="dropdown-content" class="absolute hidden z-[10000] w-60 mt-[6px] rounded-md shadow-lg origin-top-left left-0 bg-white">
                <div class="flex flex-col rounded-md ring-1 ring-black ring-opacity-5">
                    <a href="{{ route('client.account.index') }}" class="flex items-center w-full px-4 py-4 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                        <img src="{{ template_asset('assets/img/user.png') }}" class="w-7 h-7 rounded-full me-3 shadow">
                        <span>حساب کاربری</span>
                    </a>

                    <hr class="my-1 border-neutral-100">

                    <a href="{{ route('client.account.notifications.index') }}" class="flex items-center w-full px-4 py-3 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                        <div class="flex items-center justify-center w-7 ml-3">
                            <i class="fi fi-rr-bell text-lg flex"></i>
                        </div>
                        <span>اعلان ها</span>

                        @if ($unreadNotifications)
                            <span class="badge badge-danger mr-auto">{{ $unreadNotifications > 100 ? '+100' : $unreadNotifications }}</span>
                        @endif
                    </a>

                    @if (auth()->user()->is_staff)
                        <a href="{{ route('admin.index') }}" class="flex items-center w-full px-4 py-3 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            <div class="flex items-center justify-center w-7 ml-3">
                                <div class="bg-red-500 rounded-full w-2 h-2 ring-4 ring-red-100"></div>
                            </div>
                            <span class="text-red-500">مدیریت</span>
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="flex items-center w-full px-4 py-3 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            <div class="flex items-center justify-center w-7 ml-3">
                                <div class="bg-red-500 rounded-full w-2 h-2 ring-4 ring-red-100"></div>
                            </div>
                            <span class="text-red-500">خروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="hidden md:block">
        <a href="{{ route('auth.login') }}" class="ml-3 text-green-600">وارد شوید</a>
        <a href="{{ route('auth.login') }}" class="btn btn-success px-5 rounded-full">ثبت نام</a>
    </div>

    <div class="block md:hidden">
        <a href="{{ route('auth.login') }}">
            <i class="fi fi-rr-user flex text-2xl"></i>
        </a>
    </div>
@endif
