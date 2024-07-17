<button type="button" class="w-[30px]" data-role="like-product" data-status="{{ var_export($status, true) }}" data-id="{{ $id }}">
    <img src="{{ template_asset('assets/img/icons/heart.svg') }}" data-status="false" width="30" height="30">
    <img src="{{ template_asset('assets/img/icons/heart-filled.svg') }}" data-status="true" width="30" height="30">
</button>

@pushOnce('bottom-scripts')
    <script>
        $('button[data-role="like-product"]').click(function () {
            lockElem(this);

            const id = $(this).data('id');
            const status = this.getAttribute('data-status');
            var api;

            if (status === 'false') {
                $(this).attr('data-status', 'true');
                api = axios.post('{{ route('client.product.ajax.like', '@id')  }}'.replace('@id', id));
            } else {
                $(this).attr('data-status', 'false');
                api = axios.delete('{{ route('client.product.ajax.unlike', '@id')  }}'.replace('@id', id));
            }

            api.then(() => {}).catch((e) => {
                form.catch(e);

                $(this).attr('data-status', status);

                if (e.request.status === 401) {
                    // Redirect to the login/register page
                    window.location = '{{ route('auth.login', [
                        'redirect_to' => url()->current(),
                    ]) }}';
                }
            }).finally(() => {
                unlockElem(this);
            })
        });
    </script>
@endpushonce
