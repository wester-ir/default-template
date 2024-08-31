@extends('templates.default.views.layouts.basic')

@title('ورود', false)

@section('content')
    <div class="flex flex-col w-screen h-screen">
        <div class="md:border border-neutral-200 w-full md:w-[420px] rounded-xl m-auto">
            <div class="p-8">
                <div class="flex justify-center mb-5">
                    <a href="{{ route('client.index') }}" class="flex items-center justify-center">
                        <div class="flex items-center justify-center font-medium text-white w-10 h-10 rounded-lg bg-green-400">L</div>
                        <span class="font-medium ms-3 hidden md:block">{{ settingService('general')['name'] }}</span>
                    </a>
                </div>

                <div class="mt-9">
                    <h1 class="h2 text-center">ورود یا ثبت نام</h1>
                    <p class="text-center text-sm mt-2">شماره موبایل خود را وارد کنید.</p>
                </div>

                <form action="{{ route('auth.login') }}" method="POST" class="mt-5 form">
                    @csrf

                    <div class="form-control">
                        <input type="text" id="number" class="default ltr-direction text-center px-4 h-[42px]" name="number" value="" placeholder="09×××××××">
                        @include('templates.default.views.components.input-error', [
                            'messages' => $errors->get('number'),
                        ])
                    </div>

                    <button class="btn btn-success btn-lg w-full">ادامه</button>
                </form>
            </div>
        </div>
    </div>
@endsection
