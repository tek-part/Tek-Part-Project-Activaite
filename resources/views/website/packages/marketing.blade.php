@extends('website.layouts.main')
@section('title') {{ __('website.Marketing Packages') }} @endsection
@section('sub-title') {{ __('website.Marketing Packages') }} @endsection
@section('content')
    <!--================ Hero sm Banner start =================-->
    <section class="hero-banner hero-banner--sm mb-30px">
        <div class="container">
            <div class="hero-banner--sm__content">
                <h1>{{ __('website.Marketing Packages') }}</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('website.index') }}">{{ __('website.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('website.Marketing Packages') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <!--================ Hero sm Banner end =================-->


    <!--================ Offer section start =================-->
    <section class="section-margin">
        <div class="container">
            <div class="section-intro pb-85px text-center">
                <h2 class="section-intro__title">{{ __('website.Packages We Offer for Marketing') }}</h2>
                <p class="section-intro__subtitle">{{ __('website.marketing_packages_description') }}</p>
            </div>

            <div class="row">
                <div class="col-lg-6">

                    <div class="row offer-single-wrapper">
                        <div class="col-lg-6 offer-single">
                            <div class="card offer-single__content text-center">
                                <span class="offer-single__icon">
                                    <i class="ti-pencil-alt"></i>
                                </span>
                                <h4>{{ __('website.packages_feature_name_1') }}</h4>
                                <p>{{ __('website.packages_feature_description_1') }}</p>
                            </div>
                        </div>

                        <div class="col-lg-6 offer-single">
                            <div class="card offer-single__content text-center">
                                <span class="offer-single__icon">
                                    <i class="ti-ruler-pencil"></i>
                                </span>
                                <h4>{{ __('website.packages_feature_name_2') }}</h4>
                                <p>{{ __('website.packages_feature_description_2') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row offer-single-wrapper">
                        <div class="col-lg-6 offer-single">
                            <div class="card offer-single__content text-center">
                                <span class="offer-single__icon">
                                    <i class="ti-cut"></i>
                                </span>
                                <h4>{{ __('website.packages_feature_name_3') }}</h4>
                                <p>{{ __('website.packages_feature_description_3') }}</p>
                            </div>
                        </div>

                        <div class="col-lg-6 offer-single">
                            <div class="card offer-single__content text-center">
                                <span class="offer-single__icon">
                                    <i class="ti-light-bulb"></i>
                                </span>
                                <h4>{{ __('website.packages_feature_name_4') }}</h4>
                                <p>{{ __('website.packages_feature_description_4') }}</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="offer-single__img">
                        <img class="img-fluid feature-img" src="{{ asset('assett/img/home/offer.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ Offer section end =================-->

    <!--================ Pricing section start =================-->
    <section class="section-margin">
        <div class="container">
            <div class="section-intro pb-85px text-center">
                <h2 class="section-intro__title">{{ __('website.marketing_packages_title') }}</h2>
                <p class="section-intro__subtitle">{{ __('website.marketing_packages_description') }}</p>
            </div>

            <div class="row">
                @foreach ($marketingPackages as $package)
                    <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                        <div style="margin-bottom: 30px" class="card text-center card-pricing">
                            <div class="card-pricing__header">
                                <h4 class="packege-name">{{ $package->name }}</h4>
                                <p class="packege-description">{{ __('website.packages-description') }}</p>
                                <h1 class="card-pricing__price"><span>$</span>{{ $package->price }}</h1>
                            </div>
                            <ul class="card-pricing__list">
                                @foreach ($package->features as $feature)
                                    <li><i class="ti-check"></i>{{ $feature->feature }}</li>
                                @endforeach
                            </ul>
                            <div class="card-pricing__footer">
                                <button class="button button-light">{{ __('website.Buy Now') }}</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--================ Pricing section end =================-->


    <!--================ Testimonial section start =================-->
    <section class="section-padding bg-magnolia">
        <div class="container">
            <div class="section-intro pb-5 text-center">
                <h2 class="section-intro__title">Client Says Me</h2>
                <p class="section-intro__subtitle">Vel aliquam quis, nulla pede mi commodo tristique nam hac.
                    Luctus torquent velit felis commodo pellentesque nulla cras. Tincidunt hacvel alivquam </p>
            </div>

            <div class="owl-carousel owl-theme testimonial">
                @foreach ($testimonials as $testimonial)
                    <div class="testimonial__item text-center">
                        <div class="testimonial__img">
                            <img src="{{ asset('storage/' . $testimonial->image) }}" alt="">
                        </div>
                        <div class="testimonial__content">
                            <h3>{{ $testimonial->name }}</h3>
                            <p>Executive, ACI Group</p>
                            <p class="testimonial__i">{{ $testimonial->description }}</p>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>
    <!--================ Testimonial section end =================-->


@endsection
