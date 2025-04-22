@extends('website.layouts.main')
@section('title') {{ __('website.Blogs') }} @endsection
@section('sub-title') {{ __('website.Blogs') }} @endsection
@section('content')
    <!--================ Hero sm Banner start =================-->
    <section class="hero-banner hero-banner--sm mb-30px">
        <div class="container">
            <div class="hero-banner--sm__content">
                <h1>{{ __('website.Our Blogs') }}</h1>
                <nav aria-label="breadcrumb" class="banner-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('website.index') }}">{{ __('website.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('website.Blogs') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <!--================ Hero sm Banner end =================-->


    <!--================Blog Categorie Area =================-->
    <section class="blog_categorie_area">

    </section>
    <!--================Blog Categorie Area =================-->

    <!--================Blog Area =================-->
    <section class="blog_area">
        <div class="container">
            <div class="row">
                @foreach ($articles as $article)
                <div class="col-lg-6">
                    <div class="blog_left_sidebar">
                            <article class="row blog_item">
                                <div class="col-md-3">
                                    <div class="blog_info text-right">

                                        <ul class="blog_meta list">
                                            <li>
                                                <a href="#">
                                                    <i class="lnr lnr-user"></i>
                                                    <span>{{ $user->name }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="lnr lnr-calendar-full"></i>
                                                    <span>{{ $article->created_at->format('d M Y') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a id="views-counter" href="#">
                                                    <i class="lnr lnr-eye"></i>
                                                    <span>1 {{ __('website.Views') }}</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="blog_post">
                                        <img src="{{ asset('storage/'. $article->image) }}" alt="">
                                        <div class="blog_details">
                                            <a href="single-blog.html">
                                                <h2>{{ $article->name }}</h2>
                                            </a>
                                            <p>{{ \Illuminate\Support\Str::limit($article->description, 100) }}</p>
                                            <a class="button button-blog" href="{{ route('website.blog.detail', $article->id) }}">{{ __('website.View More') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    @endforeach
            </div>
        </div>
    </section>
    <!--================Blog Area =================-->



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const counterElement = document.getElementById("views-counter");
            let currentViews = 1; // القيمة الابتدائية لعدد المشاهدات
            const increment = Math.floor(Math.random() * 10) + 1; // قيمة عشوائية للزيادة

            function updateViews() {
                currentViews += increment;
                counterElement.innerText = currentViews.toLocaleString() + " Views";
            }

            // تحديث الرقم كل 3 ثوانٍ
            setInterval(updateViews, 10000);
        });
    </script>
@endsection
