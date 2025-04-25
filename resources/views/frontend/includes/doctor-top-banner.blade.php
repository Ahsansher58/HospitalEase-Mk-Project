<section class="py-4">
    <div class="container ">
        <!--Block-->
        <div class="bg-primary-color rounded-10 p-3 p-md-5">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-11">
                    <h3 class="text-white">Hello {{ $user->name }}, Welcome to HospitalEase,</h3>
                    <form action="{{ route('hospital.all') }}" method="GET" id="hospital_search">
                        <div class="row gy-2">
                            <div class="col-xl-3 col-lg-3">
                                <select class="form-select" onchange="window.location.href=this.value;">
                                    @foreach ($headerSubCategory as $option)
                                        @php
                                            $route = route('hospital.list', ['medical_system' => strtolower($option)]);
                                            $selected = request()->is('*' . strtolower($option)) ? 'selected' : '';
                                        @endphp
                                        <option value="{{ $route }}" {{ $selected }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-9 col-lg-9">
                                <div class="search-widget bg-white">
                                    <div class="input-group">
                                        <input type="text" name="search_by_city" id="search_by_city"
                                            class="form-control" placeholder="Search your city"
                                            value="{{ request('search_by_city') }}" />
                                        <span class="v-line"></span>
                                        <input type="text" class="form-control location-input"
                                            name="search_by_location" id="search_by_location"
                                            placeholder="Search location" value="{{ request('search_by_location') }}" />
                                        <button class="btn btn-search btn-secondary" id="search_by" type="submit"><svg
                                                class="me-2" width="24" height="25" viewBox="0 0 24 25"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M21 21.5L16.65 17.15M19 11.5C19 15.9183 15.4183 19.5 11 19.5C6.58172 19.5 3 15.9183 3 11.5C3 7.08172 6.58172 3.5 11 3.5C15.4183 3.5 19 7.08172 19 11.5Z"
                                                    stroke="#1E1E1E" stroke-width="2.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg><span class="d-none d-md-flex">Search</span></button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/Block-->
    </div>
</section>
