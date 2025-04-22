@extends('website.layouts.main')
@section('title')
    {{ __('website.Terms') }}
@endsection
@section('sub-title')
    {{ __('website.Terms') }}
@endsection
@section('content')
    <!--================ Hero sm Banner start =================-->
    <section class="hero-banner hero-banner--sm mb-30px">
        <div class="container">
            <div class="hero-banner--sm__content">
                <h1>{{ __('translations.Terms') }}</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('website.index') }}">{{ __('website.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translations.Terms') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <!--================ Hero sm Banner end =================-->

    <!--================ Feature section start =================-->
    <section class="section-margin">
        <div class="container">
            <div class="section-intro pb-85px text-center">
                <h2 class="section-intro__title">{{ __('website.terms_title') }}</h2>
                <p class="section-intro__subtitle">{{ __('website.terms_description') }}</p>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term1 }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term2 }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term3 }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term4 }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term5 }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term6 }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term7 }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term8 }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term9 }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="terms-description card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <p class="card-feature__subtitle">{{ $term->term10 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    {{-- <section class="hero-banner hero-banner--sm mb-30px">
        <div class="container">
            <div class="row">

                <div class="col-lg-6">
                    <div class="blog_left_sidebar">
                        <article class="row blog_item">
                            <div class="col-md-12">
                                <div class="blog_info text-right">

                                    <p>{{ $term->term1 }}</p>
                                    <p>{{ $term->term2 }}</p>
                                    <p>{{ $term->term3 }}</p>
                                    <p>{{ $term->term4 }}</p>
                                    <p>{{ $term->term5 }}</p>
                                    <p>{{ $term->term6 }}</p>
                                    <p>{{ $term->term7 }}</p>
                                    <p>{{ $term->term8 }}</p>
                                    <p>{{ $term->term9 }}</p>
                                    <p>{{ $term->term10 }}</p>
                                </div>
                            </div>

                        </article>
                    </div>
                </div>

            </div>
        </div>
    </section> --}}
    <!--================Blog Area =================-->
    {{-- <!--================ Terms and Conditions Area =================-->
    <section class="terms_and_conditions_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="terms_content">
                        <h3>{{ __('website.Terms and Conditions') }}</h3>

                        <!-- List of Terms -->
                        <p>{{ $term->term1 }}</p>
                        <p>{{ $term->term2 }}</p>
                        <p>{{ $term->term3 }}</p>
                        <p>{{ $term->term4 }}</p>
                        <p>{{ $term->term5 }}</p>
                        <p>{{ $term->term6 }}</p>
                        <p>{{ $term->term7 }}</p>
                        <p>{{ $term->term8 }}</p>
                        <p>{{ $term->term9 }}</p>
                        <p>{{ $term->term10 }}</p>

                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!--================ Terms and Conditions Area end =================-->
@endsection
