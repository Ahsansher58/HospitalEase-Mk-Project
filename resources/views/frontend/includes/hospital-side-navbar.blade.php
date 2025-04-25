<div class="sidebar">
    <nav class="nav nav-tab">
        <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="#">
            <img src="{{ asset('assets/frontend/images/icons/dashboard.svg') }}" class="img-fluid me-0 me-lg-2"
                alt="" />
            <span class="d-none d-lg-block">Dashboard</span>
        </a>
        <a class="nav-link {{ Request::routeIs('hospital.appointment') ? 'active' : '' }}"
            href="{{ route('hospital.appointment') }}">
            <img src="{{ asset('assets/frontend/images/icons/calendar-event.svg') }}" class="img-fluid me-0 me-lg-2"
                alt="" />
            <span class="d-none d-lg-block">Appointments</span>
        </a>
        <a class="nav-link {{ Request::routeIs('hospital.review') ? 'active' : '' }}"
            href="{{ route('hospital.review') }}">
            <img src="{{ asset('assets/frontend/images/icons/report-medical.svg') }}" class="img-fluid me-0 me-lg-2"
                alt="" />
            <span class="d-none d-lg-block">Patient Reviews</span>
        </a>
        <a class="nav-link {{ Request::routeIs('hospital.profile') ? 'active' : '' }}"
            href="{{ route('hospital.profile') }}">
            <img src="{{ asset('assets/frontend/images/icons/building-hospital.svg') }}" class="img-fluid me-0 me-lg-2"
                alt="" />
            <span class="d-none d-lg-block">Hospital Profile</span>
        </a>
        <a class="nav-link {{ Request::routeIs('hospital.doctor') ? 'active' : '' }}"
            href="{{ route('hospital.doctor') }}">
            <img src="{{ asset('assets/frontend/images/icons/stethoscope.svg') }}" alt=""
                class="img-fluid me-0 me-lg-2" />
            <span class="d-none d-lg-block">Doctors</span>
        </a>
        <a class="nav-link {{ Request::routeIs('hospital.setting') ? 'active' : '' }}"
            href="{{ route('hospital.setting') }}">
            <img src="{{ asset('assets/frontend/images/icons/settings-blue.svg') }}" alt=""
                class="img-fluid me-0 me-lg-2" />
            <span class="d-none d-lg-block">Setting</span>
        </a>
    </nav>
</div>
