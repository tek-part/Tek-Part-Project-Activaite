<div class="container">
    <div class="row">
        {{-- <div class="col-xl-2 col-sm-6 mb-4 mb-xl-0 single-footer-widget">
            <h4>Top Products</h4>
            <ul>
                <li><a href="#">Managed Website</a></li>
                <li><a href="#">Manage Reputation</a></li>
                <li><a href="#">Power Tools</a></li>
                <li><a href="#">Marketing Service</a></li>
            </ul>
        </div> --}}

        <div class="col-xl-4 col-md-8 mb-4 mb-xl-0 single-footer-widget">
            <div class="company-logo">
                <img src="{{ asset('storage/' . $setting->logo) }}" alt="Company Logo"
                    style="max-width: 100%; height: auto;">

                <p>{{ $setting->description }}</p>
            </div>
        </div>


        <div class="col-xl-3 col-sm-6 mb-4 mb-xl-0 single-footer-widget">
            <h4>{{ __('website.Quick Links') }}</h4>
            <ul>
                <li><a href="{{ route('website.index') }}">{{ __('website.Home') }}</a></li>
                <li><a href="{{ route('website.marketingPackages') }}">{{ __('website.Packages') }}</a></li>
                <li><a href="{{ route('website.projects') }}">{{ __('website.Projects') }}</a></li>
                <li><a href="{{ route('website.blog') }}">{{ __('website.Blogs') }}</a></li>

            </ul>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4 mb-xl-0 single-footer-widget">
            <h4>{{ __('website.Other Links') }}</h4>
            <ul>
                <li><a href="{{ route('website.contact') }}">{{ __('website.Start Project') }}</a></li>
                <li><a href="{{ route('website.contact') }}">{{ __('website.Contact') }}</a></li>
                <li><a href="{{ route('website.terms') }}">{{ __('translations.Terms') }}</a></li>
            </ul>
        </div>
        <div class="col-xl-2 col-sm-6 mb-4 mb-xl-0 single-footer-widget">
            <h4>{{ __('website.Contact Us') }}</h4>
            <ul>
                <li><a href="#">{{ $setting->phone1 }}</a></li>
                <li><a href="#">{{ $setting->phone2 }}</a></li>
                <li><a href="#">{{ $setting->email }}</a></li>
            </ul>
        </div>

    </div>
    <div class="footer-bottom row align-items-center text-center text-lg-left">
        <p class="footer-text m-0 col-lg-8 col-md-12">
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            {{ __('website.copyright') }} <a href="mailto:{{ $setting->email }}"
                target="_blank">{{ $setting->name }}</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
        </p>
        <div class="col-lg-4 col-md-12 text-center text-lg-right footer-social">
            {{-- <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-dribbble"></i></a>
            <a href="#"><i class="fab fa-behance"></i></a> --}}
            @foreach ($socialLinks as $socialLink)
                <a href="{{ $socialLink->link }}"><i class="{{ $socialLink->icon }}"></i></a>
            @endforeach
        </div>
    </div>
</div>
