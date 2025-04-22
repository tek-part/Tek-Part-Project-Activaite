@extends('website.layouts.main')
@section('title') {{ __('website.Home') }} @endsection
@section('sub-title') {{ __('website.Home') }} @endsection
@section('content')
    <!--================ Hero sm Banner start =================-->
    <section class="hero-banner mb-30px">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="hero-banner__img">
                        <img class="img-fluid" src="{{ asset('assett/img/banner/hero-banner.png') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-5 pt-5">
                    <div class="hero-banner__content">
                        <h1>{{ __('website.landing title') }}</h1>
                        <p>{{ __('website.landing description') }}</p>
                        <a href="{{ route('website.contact') }}" class="button bg" href="#">{{ __('website.Get Started') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ Hero sm Banner end =================-->

    <!--================ Feature section start =================-->
    <section class="section-margin">
        <div class="container">
            <div class="section-intro pb-85px text-center">
                <h2 class="section-intro__title">{{ __('website.feature_title') }}</h2>
                <p class="section-intro__subtitle">{{ __('website.feature_description') }}</p>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <span class="card-feature__icon">
                                <i class="ti-package"></i>
                            </span>
                            <h3 class="card-feature__title">{{ __('website.feature_name_1') }}</h3>
                            <p class="card-feature__subtitle">{{ __('website.feature_description_1') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <span class="card-feature__icon">
                                <i class="ti-mouse-alt"></i>
                            </span>
                            <h3 class="card-feature__title">{{ __('website.feature_name_2') }}</h3>
                            <p class="card-feature__subtitle">{{ __('website.feature_description_2') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-feature text-center text-lg-left mb-4 mb-lg-0">
                            <span class="card-feature__icon">
                                <i class="ti-headphone-alt"></i>
                            </span>
                            <h3 class="card-feature__title">{{ __('website.feature_name_3') }}</h3>
                            <p class="card-feature__subtitle">{{ __('website.feature_description_3') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ Feature section end =================-->

    <!--================ about section start =================-->
    <section class="section-padding--small bg-magnolia">
        <div class="container">
            <div class="row no-gutters align-items-center">
                <div class="col-md-5 mb-5 mb-md-0">
                    <div class="about__content">
                        <h2>{{ __('website.about_name') }}</h2>
                        <p>{{ __('website.about_description') }}</p>
                        <a class="button button-light" href="#">{{ __('website.Know More') }}</a>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="about__img">
                        <img class="img-fluid" src="{{ asset('assett/img/home/about.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ about section end =================-->

    <!--================ Feature section start =================-->
    <section class="section-margin">
        <div class="container">
            <div class="section-intro pb-85px text-center">
                <h2 class="section-intro__title">{{ __('website.service_title') }}</h2>
                <p class="section-intro__subtitle">{{ __('website.service_description') }}</p>
            </div>

            <div class="container">
                <div class="row">
                    @foreach ($services as $service)
                        <div class="col-lg-4">
                            <div style="margin-top: 30px;"
                                class="card card-feature text-center text-lg-left mt-20 mb-4 mb-lg-0">
                                <span class="card-feature__icon">
                                    <i class="{{ $service->icon }}"></i>
                                </span>
                                <h3 class="card-feature__title">{{ $service->name }}</h3>
                                <p class="card-feature__subtitle">{{ $service->description }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    <!--================ Feature section end =================-->

    <!--================ Solution section start =================-->
    <section class="section-padding--small bg-magnolia">
        <div class="container">
            <div class="row align-items-center pt-xl-3 pb-xl-5">
                <div class="col-lg-6">
                    <div class="solution__img text-center text-lg-left mb-4 mb-lg-0">
                        <img class="img-fluid" src="{{ asset('assett/img/home/solution.png') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="solution__content">
                        <h2>{{ __('website.solution_title') }}</h2>
                        <p>{{ __('website.solution_description') }}</p>
                        <a class="button button-light" href="#">{{ __('website.Know More') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ Solution section end =================-->

    <!--================ Pricing section start =================-->
    <section class="section-margin">
        <div class="container">
            <div class="section-intro pb-85px text-center">
                <h2 class="section-intro__title">{{ __('website.project_title') }}</h2>
                <p class="section-intro__subtitle">{{ __('website.project_description') }}</p>
            </div>

            <div class="row">
                @foreach ($projects as $project)
                    <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                        <div style="margin-bottom: 30px;" class="card text-center card-pricing">
                            <div class="card-pricing__header">
                                <!-- صورة المشروع تشغل عرض div بالكامل -->
                                <img src="{{ asset('storage/' . $project->images->first()->image) }}" alt="Project Image"
                                    class="img-fluid"
                                    style="width: 100%; height: 300px; margin-bottom: 10px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                                <span class="project-title-description">
                                    <h4>{{ \Illuminate\Support\Str::limit($project->name, 25) }}</h4>
                                    <p>{{ \Illuminate\Support\Str::limit($project->description, 90) }}</p>
                                    <a class="button button-blog" href="{{ route('website.projects.detail', $project->id) }}">{{ __('website.View More') }}</a>
                                </span>

                            </div>
                        </div>
                    </div>
                @endforeach

                <a style="margin: auto;" class="button button-light"
                    href="{{ route('website.projects') }}">{{ __('website.Show More') }}</a>

            </div>

        </div>
    </section>
    <!--================ Pricing section end =================-->

    <!--================ Testimonial section start =================-->
    <section class="section-padding bg-magnolia">
        <div class="container">
            <div class="section-intro pb-5 text-center">
                <h2 class="section-intro__title">{{ __('website.client_title') }}</h2>
                <p class="section-intro__subtitle"> {{ __('website.client_description') }} </p>
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
