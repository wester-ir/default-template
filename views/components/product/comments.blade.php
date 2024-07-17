<div>
    <h3>نظرات کاربران @if (! $product->comments->isEmpty()) <span class="badge badge-light ms-2">{{ $product->comments->total()  }}</span> @endif</h3>

    <div class="flex flex-col lg:flex-row lg:space-x-5 rtl:space-x-reverse mt-4">
        <div class="flex-1 space-y-3">
            @if ($product->comments->isEmpty())
                <div class="border border-neutral-200 px-5 py-4 rounded-lg font-light">هنوز هیچ نظری ثبت نشده است.</div>
            @else
                @foreach ($product->comments as $comment)
                    <div class="comment border border-neutral-200 rounded-lg" data-id="{{ $comment->id }}">
                        <div class="flex items-center justify-between p-3 text-sm">
                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                @if ($comment->is_user_anonymous || ! $comment->user->has_full_name)
                                    <div class="font-light">کاربر سایت</div>
                                @else
                                    <div class="font-light">{{ $comment->user->full_name }}</div>
                                @endif

                                @if ($comment->is_buyer)
                                    <div>
                                        <span class="badge badge-light-lime">خریدار</span>
                                    </div>
                                @endif

                                <div>
                                    <div class="bg-neutral-200 w-[5px] h-[5px] rounded-full mx-1"></div>
                                </div>

                                <div class="font-light">
                                    {{ verta($comment->created_at)->formatDifference() }}
                                </div>

                                @if ($comment->is_author)
                                    <div>
                                        <div class="bg-neutral-200 w-[5px] h-[5px] rounded-full mx-1"></div>
                                    </div>

                                    <div>
                                        <button type="button" class="delete-comment text-red-500" data-id="{{ $comment->id }}">حذف</button>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center">
                                @switch ($comment->recommendation_status->slug())
                                    @case ('recommended')
                                        <span class="badge badge-success">پیشنهاد می کنم</span>
                                    @break

                                    @case ('not_recommended')
                                        <span class="badge badge-danger">پیشنهاد نمی کنم</span>
                                    @break

                                    @case ('no_idea')
                                        <span class="badge badge-secondary">بدون نظر</span>
                                    @break
                                @endswitch
                            </div>
                        </div>
                        <div class="p-3 pt-0">
                            @if ($comment->title)
                                <div class="h3 text-[1.1rem] mb-3">{{ $comment->title }}</div>
                            @endif

                            <div class="font-light">{{ $comment->text }}</div>
                        </div>

                        @if ($comment->reply_text)
                            <div class="m-3 bg-neutral-100 rounded-lg p-3">
                                {{ $comment->reply_text }}
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            {{ $product->comments->links() }}
        </div>

        <!-- Form -->
        @if (auth()->check())
            <form class="form comment-form border border-neutral-200 rounded-lg px-5 py-4 w-full lg:w-[450px] mt-5 lg:mt-0">
                <div class="form-control" data-form-field-id="title">
                    <label for="title">عنوان</label>
                    <input id="title" class="default" type="text" name="title" value="">
                </div>

                <div class="form-control" data-form-field-id="text">
                    <label for="text">نظر *</label>
                    <textarea id="text" class="default min-h-[5rem] py-2" name="text"></textarea>
                </div>

                <div class="form-control" data-form-field-id="recommendation_status">
                    <div class="label">به دیگران ... *</div>
                    <input type="hidden" name="recommendation_status" value="1">
                    <div id="recommendation-status" class="recommendation-status grid grid-cols-3 gap-2 text-center text-sm cursor-pointer">
                        <div class="border border-neutral-100 hover:border-green-400 px-3 py-2 rounded-lg text-green-500 data-[active=true]:border-green-400 data-[active=true]:font-bold" data-active="true" data-value="1">توصیه می کنم</div>
                        <div class="border border-neutral-100 hover:border-red-400 px-3 py-2 rounded-lg text-red-600 data-[active=true]:border-red-400 data-[active=true]:font-bold" data-value="-1">توصیه نمی کنم</div>
                        <div class="border border-neutral-100 hover:border-neutral-400 px-3 py-2 rounded-lg text-neutral-600 data-[active=true]:border-neutral-400 data-[active=true]:font-bold" data-value="0">بدون نظر</div>
                    </div>
                </div>

                <div class="form-control" data-form-field-id="is_user_anonymous">
                    <label class="inline-form-control" for="is_user_anonymous">
                        <input id="is_user_anonymous" type="checkbox" name="is_user_anonymous" value="1">
                        <span>بصورت ناشناس</span>
                    </label>
                </div>

                <button type="button" class="submit btn btn-success w-32">ارسال</button>
            </form>
        @else
            <div class="form comment-form border border-neutral-200 rounded-lg px-5 py-4 font-light w-[450px]">
                @php
                    $loginRoute = route('auth.login', [
                        'redirect_to' => url()->current(),
                    ]);
                @endphp
                جهت ارسال نظر <a class="font-medium link" href="{{ $loginRoute }}" rel="nofollow">وارد شوید</a> یا <a class="font-medium link" href="{{ $loginRoute }}" rel="nofollow">ثبت نام</a> کنید.
            </div>
        @endif
    </div>
</div>

@push('bottom-scripts')
    <script>
        $('.delete-comment').click(function () {
            const id = $(this).data('id');

            axios.delete('{{ route('client.product.ajax.comments.destroy', [
                'product' => $product,
                'comment' => '@id'
            ]) }}'.replace('@id', id)).then(() => {
                $('.comment[data-id="'+ id +'"]').remove();
            }).catch((e) => {

            });
        });

        $('.recommendation-status > div').click(function () {
            $('.recommendation-status > div').attr('data-active', 'false');
            $(this).attr('data-active', 'true');

            $('input[name="recommendation_status"]').val(
                $(this).data('value')
            );
        });

        $('.comment-form button.submit').click(function () {
            lockElem(this);
            form.resetFormErrors('.comment-form');

            axios.post('{{ route('client.product.ajax.comments.create', $product) }}', {
                title: $('.comment-form input[name="title"]').val(),
                text: $('.comment-form textarea[name="text"]').val(),
                recommendation_status: $('.comment-form input[name="recommendation_status"]').val(),
                is_user_anonymous: $('.comment-form input[name="is_user_anonymous"]').is(':checked'),
            }).then(({data}) => {
                if (data.data.is_approved) {
                    location.reload();
                } else {
                    toast.success('نظر شما پس از تایید مدیریت نمایش داده خواهد شد.');
                }

                // Reset
                form.reset('.comment-form');
                $('.recommendation-status > div[data-value="1"]').click();
            }).catch((e) => {
                form.catch(e);

                if (e.request.status === 422) {
                    form.setFormErrors('.comment-form', e.response.data.errors);
                }
            }).finally(() => {
                unlockElem(this);
            });
        });
    </script>
@endpush
