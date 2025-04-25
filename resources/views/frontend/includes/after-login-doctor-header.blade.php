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
                        <a class="nav-link dropdown-toggle user-nav" href="#services" data-bs-toggle="dropdown"
                            aria-expanded="false" data-bs-dismiss="offcanvas">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="me-2">
                                <circle cx="7.99992" cy="4.66667" r="2.66667" stroke="#242B37" stroke-opacity="0.9"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M4 14V12.6667C4 11.1939 5.19391 10 6.66667 10H9.33333C10.8061 10 12 11.1939 12 12.6667V14"
                                    stroke="#242B37" stroke-opacity="0.9" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>Hello, {{ Auth::user()->name }}</a>
                        <ul class="dropdown-menu ">
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center"
                                    href="{{ route('doctor.dashboard') }}">
                                    <span><img src="{{ asset('assets/frontend/images/icons/file-dollar.svg') }}"
                                            class="me-2" />Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center"
                                    href="{{ route('doctor.editPersonnelInfo') }}">
                                    <span> <img src="{{ asset('assets/frontend/images/icons/settings.svg') }}"
                                            class="me-2" />Setting</span>

                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('doctors.logout') }}"><img
                                        src="{{ asset('assets/frontend/images/icons/logout.svg') }}"
                                        class="me-2" />Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <button class="navbar-toggler " type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar2" aria-controls="offcanvasNavbar2">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

        </div>
    </nav>
</header>
<!--/HEADER-->
