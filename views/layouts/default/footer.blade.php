<footer class="footer mt-2">
    <div class="bg-neutral-100 py-10">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-x-5 gap-y-10">
                <div>
                    <h3>دسترسی سریع</h3>

                    <ul class="mt-3 space-y-1 font-light">
                        <li>
                            <a href="#">صفحه اصلی</a>
                        </li>
                        <li>
                            <a href="#">جدیدترین ها</a>
                        </li>
                        <li>
                            <a href="#">پرفروش ترین ها</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3>تماس با ما</h3>

                    <ul class="mt-3 space-y-1 font-light">
                        <li>
                            <a href="#">صفحه اصلی</a>
                        </li>
                        <li>
                            <a href="#">جدیدترین ها</a>
                        </li>
                        <li>
                            <a href="#">پرفروش ترین ها</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3>شبکه های اجتماعی</h3>

                    <ul class="mt-3 space-y-1 font-light">
                        <li>
                            <a href="#">صفحه اصلی</a>
                        </li>
                        <li>
                            <a href="#">جدیدترین ها</a>
                        </li>
                        <li>
                            <a href="#">پرفروش ترین ها</a>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-center md:justify-end">
                    <div class="w-32 h-32 border border-neutral-200 rounded-md"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white border-t border-neutral-200 py-5">
        <div class="container">
            <h1 class="h2 text-green-500">{{ settingService('general')['title'] }}</h1>
            <div class="text-sm leading-loose mt-2">
                {!! wrap_paragraph(settingService('general')['description']) !!}
            </div>
        </div>
    </div>
    <div class="bg-white border-t border-neutral-200 py-3">
        <div class="container text-sm text-center">
            کلیه  حقوق این سایت محفوظ است.
        </div>
    </div>
</footer>
