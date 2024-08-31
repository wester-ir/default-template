<form action="{{ route('client.search') }}" method="GET" class="search mr-3">
    <input type="text" class="default" name="search" value="{{ query('search') }}" placeholder="جستجو...">
</form>
