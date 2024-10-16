@extends('templates.default.views.layouts.basic')

@title('ورود با رمز عبور', false)

@section('content')
    <div class="flex flex-col w-screen h-screen">
        <div class="md:border border-neutral-200 w-full md:w-[420px] rounded-xl m-auto">
            <div class="p-8">
                <div class="flex justify-center mb-5">
                    <!-- Logo -->
                    @template_include('views.layouts.default.logo', ['withName' => false])
                </div>

                <div class="mt-9">
                    <h1 class="h2 text-center">ورود با رمز عبور</h1>
                    <p class="text-center text-sm mt-2">رمز عبور خود را وارد کنید.</p>
                </div>

                <form action="{{ route('auth.login.password') }}" method="POST" class="mt-5 form" onsubmit="return form.lock(this)">
                    @csrf
                    <input type="hidden" name="number" value="{{ $number }}">

                    <div class="form-control">
                        <input type="password" id="password" class="default ltr-direction text-center px-4 h-[42px]" name="password" value="" placeholder="×××××××">
                        @template_include('views.components.input-error', [
                            'messages' => $errors->get('password'),
                        ])
                    </div>

                    <button class="btn btn-success btn-lg w-full">ادامه</button>
                </form>

                <form action="{{ route('auth.login') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="login_method" value="sms">
                    <input type="hidden" name="number" value="{{ $number }}">

                    <button class="btn btn-light text-sm btn-lg w-full">ورود با کد تایید</button>
                </form>
            </div>
        </div>
    </div>
@endsection
