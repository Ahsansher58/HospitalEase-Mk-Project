<!--HEADER-->
<header class="fixed-top" id="header">
    <nav class="navbar navbar-expand-xl" aria-label="Offcanvas navbar large">
        <div class="container position-relative pe-0">
            <a class="navbar-brand me-0 me-sm-4" href="/">
                <img src="{{ asset('assets/frontend/images/logo.svg') }}" class="img-fluid logo" alt="" />
                <img src="{{ asset('assets/frontend/images/logo-active.svg') }}" class="img-fluid logo-active"
                    alt="" />
            </a>

            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar2"
                aria-labelledby="offcanvasNavbar2Label">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <!--Top Navbar-->
                @include('frontend.includes.top-nav-bar')
                <!--Top Navbar-->
            </div>
            <div class="ms-auto d-flex align-items-center">
                <ul class="navbar-nav navbar-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#services" data-bs-toggle="dropdown"
                            aria-expanded="false" data-bs-dismiss="offcanvas">Login</a>
                        <ul class="dropdown-menu ">
                            <li>
                                <a class="dropdown-item" href="{{ route('users.login') }}">User Login</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('hospital.login') }}">Hospital Login</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <a href="{{ route('users.register') }}" class="btn btn-secondary btn-sm me-0 me-xl-3">Register</a>
                <button class="navbar-toggler " type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar2" aria-controls="offcanvasNavbar2">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

        </div>
    </nav>
</header>
<!--/HEADER-->
