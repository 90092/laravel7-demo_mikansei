<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="/">Demo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active" href="/">{{ __('Home') }}</a></li>
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" v-pre>{{ Auth::user()->name }}</a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">{{ __('User info') }}</a></li>
                            <li>
                                <a class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            </li>

                            <form class="d-none" id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                        </ul>
                    </li>
                @endguest
            </ul>
            <div class="d-flex">
                <a class="btn btn-outline-light" href="{{ route('cart') }}">
                    <i class="bi-cart-fill me-1"></i>
                    {{ __('Cart') }}
                    <span class="badge bg-light text-black ms-1 rounded-pill" id="cart_count">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>