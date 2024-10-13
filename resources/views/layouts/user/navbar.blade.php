<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand logo_h" href="{{ route('user.dashboard') }}">
                    <img src="{{ asset('assets/templates/user/img/logo.png') }}" alt="Logo">
                </a>

                <!-- Poin Pengguna di Ujung Kanan -->
                @if(Auth::check())
                    <div class="nav-item d-none d-lg-block ml-auto">
                        <a class="nav-link" href="#">Poin: <strong>{{ Auth::user()->point }}</strong></a>
                    </div>
                @endif

                <!-- Toggle button for mobile view -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" 
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Navbar Links -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('user.dashboard') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.logout') }}">Logout</a>
                        </li>
                    </ul>

                    <!-- Poin Pengguna di Mobile View -->
                    @if(Auth::check())
                        <ul class="nav navbar-nav d-lg-none">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Poin: <strong>{{ Auth::user()->point }}</strong></a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- End Header Area -->
