<form action="{{ route('client.search') }}" onsubmit="return this.search.value.length > 2;" method="GET" class="search mr-3">
    <input type="text" class="default" name="search" value="{{ query('search') }}" maxlength="50" placeholder="جستجو...">
    <i class="fi fi-rr-search flex ml-3"></i>
</form>
