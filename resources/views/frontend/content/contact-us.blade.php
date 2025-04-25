@extends('frontend.layouts.users')

@section('title', 'Hospital Ease - Contact us')
@section('favicon')
    @include('frontend.includes.favicon')
@endsection
@section('content')
    @if (Auth::check())
        @include('frontend.includes.after-login-header')
    @else
        @include('frontend.includes.inner-header')
    @endif
    <!--content start-->
    <!--MAIN-->
    <main class="inner-page blue-gradient help-page">
        <!--BANNER-->
        <section class="py-5 help-banner">
            <div class="container">
                <!--BLOCK-->
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-5 order-lg-last">
                        <img src="{{ asset('assets/frontend/images/icons/help.svg') }}" alt=""
                            class="img-fluid w-100" />
                    </div>
                    <div class="col-lg-5">
                        <h2>We’re here to help!</h2>
                        <p>Whether you have a question, need assistance, or want to learn more about our services, feel free
                            to get in touch with us using any of the methods below. Our team is ready to assist you during
                            regular business hours.</p>
                    </div>
                </div>
                <!--/BLOCK-->
            </div>
        </section>
        <!--/BANNER-->
        <section class="sepration-bottom">
            <div class="container container-secondary">
                <!--BLOCK-->
                <div class="row gy-4">

                    <div class="col-lg-4">
                        <div class="card help-widget rounded-16 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Start a Conversation</h4>
                                <img src="{{ asset('assets/frontend/images/icons/chat.svg') }}" class="img-fluid"
                                    alt="" />
                            </div>
                            <div class="card-info">
                                <p class="mb-0">We're here to help! Expect a quick response during regular business hours.
                                </p>
                            </div>
                            <div class="card-footer border-0 rounded-0 p-0 bg-white">
                                <a href="#" class="btn btn-info btn-xs font-regular"> Connect on whatsapp</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card help-widget rounded-16 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Give Us a Call</h4>
                                <img src="{{ asset('assets/frontend/images/icons/call.svg') }}" class="img-fluid"
                                    alt="" />
                            </div>
                            <div class="card-info">
                                <p class="mb-0">We're here to help! Expect a quick response during regular business hours.
                                </p>
                            </div>
                            <div class="card-footer border-0 rounded-0 p-0 bg-white">
                                <h4 class="mb-0"><a href="tel:91 9876 54 3210">+91 9876 54 3210</a></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card help-widget rounded-16 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Write an Email</h4>
                                <img src="{{ asset('assets/frontend/images/icons/email.svg') }}" class="img-fluid"
                                    alt="" />
                            </div>
                            <div class="card-info">
                                <p class="mb-0">Send us an email, and we’ll get back to you as soon as possible</p>
                            </div>
                            <div class="card-footer border-0 rounded-0 p-0 bg-white">
                                <h4 class="mb-0"><a href="mailto:info@hospitalease.com">info@hospitalease.com</a></h4>
                            </div>
                        </div>
                    </div>

                </div>
                <!--/BLOCK-->
            </div>
        </section>
    </main>
    <!--/MAIN-->
    <!--content end-->
    @include('frontend.includes.footer')
@endsection
