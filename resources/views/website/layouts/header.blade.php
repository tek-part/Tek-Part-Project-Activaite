<div class="main_menu">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container box_1620">
            <!-- Brand and toggle get grouped for better mobile display -->
            <a class="navbar-brand logo_h" href="index.html"><img style="width: 80px;" src="{{ asset('storage/' . $setting->logo) }}"
                    alt=""></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                <ul class="nav navbar-nav menu_nav justify-content-end">
                    <li class="nav-item active"><a class="nav-link" href="{{ route('website.index') }}">{{ __('website.Home') }}</a></li>
                    <li class="nav-item submenu dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="featuresDropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ __('website.Packages') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="featuresDropdown">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('website.marketingPackages') }}">{{ __('website.Marketing') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('website.serversPackages') }}">{{ __('website.Servers') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('website.emailsPackages') }}">{{ __('website.Emails') }}</a>
                            </li>
                            <!-- Add more features as needed -->
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="{{ route('website.projects') }}">{{ __('website.Projects') }}</a>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ route('website.price') }}">{{ __('website.Price') }}</a> --}}
                    <li class="nav-item"><a class="nav-link" href="{{ route('website.blog') }}">{{ __('website.Blogs') }}</a>
                    <li class="nav-item"><a class="nav-link" href="{{ route('website.contact') }}">{{ __('website.Contact') }}</a></li>
                    <li class="nav-item submenu dropdown">
                        <form action="{{ route('setLocale') }}" method="POST" >
                            @csrf
                            <a href="#" class="nav-link dropdown-toggle" id="languageDropdown"
                                data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                {{ app()->getLocale() === 'ar' ? __('website.Arabic') : __('website.English') }}
                            </a>
                            <ul class="dropdown-menu language-ul" aria-labelledby="languageDropdown">
                                <!-- Arabic Option -->
                                <li class="nav-item">
                                    <button  class="nav-link" type="submit" name="locale" value="ar">
                                        {{ __('website.Arabic') }}
                                    </button>
                                </li>

                                <!-- English Option -->
                                <li class="nav-item">
                                    <button  class="nav-link" type="submit" name="locale" value="en">
                                        {{ __('website.English') }}
                                    </button>
                                </li>
                            </ul>
                        </form>
                    </li>



                </ul>

                <ul class="navbar-right">
                    <li class="nav-item">
                        <a href="{{ route('website.contact') }}" class="button button-header bg">{{ __('website.Sign Up') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
