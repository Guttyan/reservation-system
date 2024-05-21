<header class="header">
  <input type="checkbox" class="header__nav-btn" id="header__nav-btn">
  <label for="header__nav-btn" class="header__nav-icon"><span class="header__nav-icon-bar"></span></label>
  <h1 class="header-ttl">Rese</h1>

@if(Route::currentRouteName() === 'index' || Route::currentRouteName() === 'search')
  <form action="/search" class="search-form">
      @csrf
      <div class="search-form__area">
          <select name="area_id" class="search__area-input">
            <option value="">All area</option>
              @foreach($areas as $area)
                <option value="{{ $area->id }}" {{ Request::input('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
              @endforeach
          </select>
        <i class="fa-solid fa-sort-down"></i>
      </div>
      <div class="search-form__genre">
          <select name="genre_id" class="search__genre-input">
            <option value="">All genre</option>
            @foreach($genres as $genre)
              <option value="{{ $genre->id }}" {{ Request::input('genre_id') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
            @endforeach
          </select>
          <i class="fa-solid fa-sort-down"></i>
      </div>
      <div class="search-form__text">
          <button class="search-form__btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
          <input type="text" name="keyword" class="search__text-input" placeholder="Search ..." value="{{ Request::input('keyword') }}">
      </div>
  </form>
@endif

<ul class="header__nav-list">
    <li class="header__nav-item"><a href="/" class="header__nav-item--link">Home</a></li>
    @if(Auth::check())
      <li class="header__nav-item">
        <form class="form" action="/logout" method="post">
          @csrf
          <button class="header__nav-item--link logout-btn">Logout</button>
        </form>
      </li>
      <li class="header__nav-item"><a href="/mypage" class="header__nav-item--link">Mypage</a></li>
      <li class="header__nav-item"><a href="/reservations/completed" class="header__nav-item--link">Write a Review</a></li>
      @if(Auth::user()->hasRole('admin'))
        <li class="header__nav-item"><a href="/admin" class="header__nav-item--link">Create Representative</a></li>
        <li class="header__nav-item"><a href="/create-mail" class="header__nav-item--link">Send Email</a></li>
      @endif
      @if(Auth::user()->hasRole('representative'))
        <li class="header__nav-item"><a href="/create-shop" class="header__nav-item--link">Create Shop</a></li>
        <li class="header__nav-item"><a href="/my-shops" class="header__nav-item--link">My Shops</a></li>
      @endif
    @else
      <li class="header__nav-item"><a href="/register" class="header__nav-item--link">Registration</a></li>
      <li class="header__nav-item"><a href="/login"  class="header__nav-item--link">Login</a></li>
    @endif
</ul>


</header>