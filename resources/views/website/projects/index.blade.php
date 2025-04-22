@extends('website.layouts.main')
@section('title') {{ __('website.Projects') }} @endsection
@section('sub-title') {{ __('website.Projects') }} @endsection
@section('content')
    <!--================ Hero sm Banner start =================-->
    <section class="hero-banner hero-banner--sm mb-30px">
        <div class="container">
            <div class="hero-banner--sm__content">
                <h1>{{ __('website.Our Projects') }}</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('website.index') }}">{{ __('website.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('website.Projects') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <!--================ Hero sm Banner end =================-->


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
                                    <p>{{ \Illuminate\Support\Str::limit($project->description, 100) }}</p>
                                    <a class="button button-blog" href="{{ route('website.projects.detail', $project->id) }}">{{ __('website.View More') }}</a>
                                </span>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!--================ Pricing section end =================-->




@endsection
