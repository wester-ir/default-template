<form action="{{ route('client.search') }}" onsubmit="return this.search.value.length > 2;" method="GET" class="search mr-3">
    <input type="text" class="default rounded-full z-10 pl-11" name="search" value="{{ query('search') }}" maxlength="50" placeholder="جستجو...">
    <i class="fi fi-rr-search flex absolute left-0 ml-4 z-20"></i>
</form>
