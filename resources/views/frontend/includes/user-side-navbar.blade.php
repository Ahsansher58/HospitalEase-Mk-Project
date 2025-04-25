@php
    $check_disabled = Request::is('user/user-profile') ? 'disabled' : '';
@endphp
<nav class="nav nav-tab flex-xl-column mb-3 mb-xl-0 justify-content-center">
    <a class="nav-link {{ Request::is('user/user-personnel-info') ? 'active' : '' }} {{ $check_disabled }}"
        aria-current="page" href="{{ route('user.personnelInfo') }}">
        <img src="{{ asset('assets/frontend/images/icons/user.svg') }}" alt="" class="img-fluid me-2" />My
        Personnel Info
    </a>
    <a class="nav-link {{ Request::is('user/user-fav') ? 'active' : '' }} {{ $check_disabled }}"
        href="{{ route('user.profileFav') }}">
        <img src="{{ asset('assets/frontend/images/icons/building-hospital.svg') }}" class="img-fluid me-2"
            alt="" />My Favourite
        Hospitals
    </a>
    <a class="nav-link {{ Request::is('user/user-medical-records') ? 'active' : '' }} {{ $check_disabled }}"
        href="{{ route('user.medicalRecords') }}">
        <img src="{{ asset('assets/frontend/images/icons/report-medical.svg') }}" class="img-fluid me-2"
            alt="" />My Medical
        Records
    </a>
    <a class="nav-link {{ Request::is('user/user-appointments') ? 'active' : '' }} {{ $check_disabled }}"
        href="{{ route('user.appointments') }}">
        <img src="{{ asset('assets/frontend/images/icons/calendar-event.svg') }}" class="img-fluid me-2"
            alt="" />My
        Appointments
    </a>
    <a class="nav-link {{ Request::is('user/user-allergic-medicine') ? 'active' : '' }} {{ $check_disabled }}"
        href="{{ route('user.allergicMedicine') }}">
        <img src="{{ asset('assets/frontend/images/icons/drug.svg') }}" class="img-fluid me-2"
            alt="" />Allergic to Medicine
    </a>
    <a class="nav-link {{ Request::is('user/user-allergic-food') ? 'active' : '' }} {{ $check_disabled }}"
        href="{{ route('user.allergicFood') }}">
        <img src="{{ asset('assets/frontend/images/icons/food.svg') }}" class="img-fluid me-2"
            alt="" />Allergic to Food
    </a>
    <a class="nav-link {{ Request::is('user/user-family-health-history') ? 'active' : '' }} {{ $check_disabled }}"
        href="{{ route('user.familyHealthHistory') }}">
        <img src="{{ asset('assets/frontend/images/icons/user.svg') }}" class="img-fluid me-2" alt="" />Family
        Health History
    </a>
</nav>

@php
    use App\Models\Advertisements;
    use Carbon\Carbon;
    $currentDate = Carbon::now()->toDateString();
    $advertisement = Advertisements::where('placement', 3)
        ->where('status', 1)
        ->whereDate('start_date', '<=', $currentDate)
        ->whereDate('end_date', '>=', $currentDate)
        ->inRandomOrder()
        ->first();
@endphp

@if ($advertisement)
    @php
        $imageTag =
            $advertisement->option == 1
                ? '<img src="' .
                    asset('storage/' . $advertisement->image_code) .
                    '" alt="' .
                    $advertisement->campaign_name .
                    '" class="img-fluid rounded-12 d-none d-xl-block" >'
                : $advertisement->image_code;
    @endphp
    <p>{!! $imageTag !!}</p>
@endif
