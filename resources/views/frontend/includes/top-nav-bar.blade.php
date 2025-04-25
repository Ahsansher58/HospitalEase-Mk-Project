<div class="offcanvas-body">
    <ul class="navbar-nav flex-grow-1 ms-0 ms-lg-3">

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#medical_system" data-bs-toggle="dropdown"
                aria-expanded="false">Treatments
                and Specializations</a>
            <ul class="dropdown-menu">
                @forelse($headerSubCategory as $option)
                    <li>
                        <a class="dropdown-item"
                            href="{{ route('hospital.list', ['medical_system' => strtolower($option)]) }}">
                            {{ $option }}
                        </a>
                    </li>
                @empty
                    <li>
                        <a class="dropdown-item" href="#">No Options Available</a>
                    </li>
                @endforelse
            </ul>

        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Services</a>
            <ul class="dropdown-menu ">
                <li>
                    <a class="dropdown-item" href="{{ route('business-categories-all') }}">View
                        all</a>
                </li>
                @if (!empty($headerBusinessCategory))
                    @foreach ($headerBusinessCategory as $id => $name)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('business_listing_cat', ['slug' => Str::slug($name)]) }}">{{ $name }}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link contact" href="{{ route('hospital.all') }}">Hospitals</a>
        </li>
        <li class="nav-item">
            <a class="nav-link contact" href="{{ route('contact-us') }}">Contact Us</a>
        </li>
    </ul>
</div>
