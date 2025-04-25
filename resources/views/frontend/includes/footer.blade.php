   <!--FOOTER-->
   <footer>
       <div class="container">
           <!--block-->
           <div class="row gy-5 justify-content-between">


               <div class="col-6 col-md-3 col-lg-2">
                   <h4>Quick Links</h4>
                   <ul>
                       <li><a href="/">Home</a></li>
                       <li><a href="#">About Us</a></li>
                       <li><a href="#">Press</a></li>
                       <li><a href="#">Careers</a></li>
                       <li><a href="#">Contact us</a></li>
                       <li><a href="#">Help</a></li>
                   </ul>
               </div>
               @if (!empty($headerSubCategory))
                   @foreach (collect($headerSubCategory)->chunk(6) as $chunk)
                       <div class="col-6 col-md-3 col-lg-2">
                           <h4>Specialization</h4>
                           <ul>
                               @foreach ($chunk as $option)
                                   <li>
                                       <a
                                           href="{{ route('hospital.list', ['medical_system' => strtolower($option)]) }}">
                                           {{ $option }}
                                       </a>
                                   </li>
                               @endforeach
                           </ul>
                       </div>
                   @endforeach
               @endif
               @if (!empty($headerBusinessCategory))
                   @foreach (collect($headerBusinessCategory)->chunk(8) as $chunk)
                       <div class="col-6 col-md-3 col-lg-2">
                           <h4>Services</h4>
                           <ul>
                               @foreach ($chunk as $id => $name)
                                   <li>
                                       <a href="{{ route('business_listing') . '/?search_by_category=' . $id }}">
                                           {{ $name }}
                                       </a>
                                   </li>
                               @endforeach
                           </ul>
                       </div>
                   @endforeach
               @endif
           </div>
           <!--/block-->


       </div>
       <div class="footer-last py-3 mt-5">
           <div class="container">
               <div class="row align-items-center gy-2">
                   <div class="col-lg-4">
                       <a href="#" class="me-3">Terms & Conditions</a> <a href="#"
                           class="ms-3">Privacy</a>
                   </div>
                   <div class="col-lg-4 text-center">
                       © 2024 HospitalEase | All Right Reserved.
                   </div>
                   <div class="col-lg-4">
                       <ul class="social-widget">
                           <li><a href="#"><img src="{{ asset('assets/frontend/images/icons/facebook.svg') }}"
                                       alt="" /></a></li>
                           <li><a href="#"><img src="{{ asset('assets/frontend/images/icons/instagram.svg') }}"
                                       alt="" /></a></li>
                           <li><a href="#"><img src="{{ asset('assets/frontend/images/icons/whats-app.svg') }}"
                                       alt="" /></a></li>
                           <li><a href="#"><img src="{{ asset('assets/frontend/images/icons/youtube.svg') }}"
                                       alt="" /></a></li>
                       </ul>
                   </div>
               </div>
           </div>
       </div>
   </footer>
   <!--/FOOTER-->

   <!--Start: Scroll button to top -->
   <button type="button" id="scrollTop"><i class="fa fa-angle-up"></i></button>
   <!--End: Scroll button to top -->

   <!-- bootstrap  JS -->
   <script src="{{ asset('assets/frontend/js/jquery.min.js') }}"></script>
   <script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('assets/frontend/js/bootstrap-select.js') }}"></script>

   <!-- Slider JS -->
   <script src="{{ asset('assets/frontend/js/slick.min.js') }}"></script>

   <!-- Custom JS -->
   <script src="{{ asset('assets/frontend/js/custom.js') }}"></script>
