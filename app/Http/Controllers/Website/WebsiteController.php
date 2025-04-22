<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ContactUs;
use App\Models\Package;
use App\Models\Project;
use App\Models\Service;
use App\Models\SocialLinks;
use App\Models\Term;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;


class WebsiteController extends Controller
{

    public function index()
    {
        $projects = Project::paginate(3);
        $testimonials = Testimonial::all();
        $services = Service::all();
        $packages = Package::all();
        $socialLinks = SocialLinks::all();
        return view('website.index', compact('projects', 'testimonials', 'services', 'socialLinks'));
    }

    public function features()
    {
        $socialLinks = SocialLinks::all();
        return view('website.features.index', compact('socialLinks'));
    }

    public function marketingPackages()
    {
        $socialLinks = SocialLinks::all();
        $marketingPackages = Package::where('type', 'marketing')->get();
        $testimonials = Testimonial::all();
        return view('website.packages.marketing', compact('marketingPackages', 'testimonials', 'socialLinks'));
    }

    public function serversPackages()
    {
        $socialLinks = SocialLinks::all();
        $testimonials = Testimonial::all();
        $serversPackages = Package::where('type', 'servers')->get();
        return view('website.packages.servers', compact('serversPackages', 'testimonials', 'socialLinks'));
    }

    public function emailsPackages()
    {
        $socialLinks = SocialLinks::all();
        $testimonials = Testimonial::all();
        $emailsPackages = Package::where('type', 'emails')->get();
        return view('website.packages.emails', compact('emailsPackages', 'testimonials', 'socialLinks'));
    }

    public function projects()
    {
        $socialLinks = SocialLinks::all();
        $testimonials = Testimonial::all();
        $projects = Project::all();
        return view('website.projects.index', compact('projects', 'testimonials', 'socialLinks'));
    }

    public function projectsDetail($id)
    {
        $socialLinks = SocialLinks::all();
        $testimonials = Testimonial::all();
        $project = Project::findOrFail($id); // البحث عن المقال بناءً على المعرّف
        return view('website.projects.project_details', compact('project', 'testimonials', 'socialLinks')); // إرسال المقال إلى صفحة التفاصيل
    }

    public function blog()
    {
        $socialLinks = SocialLinks::all();
        $testimonials = Testimonial::all();
        $articles = Article::all();
        return view('website.blog.index', compact('articles', 'testimonials', 'socialLinks'));
    }

    public function blogDetail($id)
    {
        $socialLinks = SocialLinks::all();
        $testimonials = Testimonial::all();
        $article = Article::findOrFail($id); // البحث عن المقال بناءً على المعرّف
        return view('website.blog.blog_details', compact('article', 'testimonials', 'socialLinks')); // إرسال المقال إلى صفحة التفاصيل
    }

    public function contact()
    {
        $socialLinks = SocialLinks::all();

        return view('website.contact.index')->with('socialLinks', $socialLinks);
    }


    public function contactUsForm(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $saveRecord = new ContactUs();



        $saveRecord->name = $request->name;
        $saveRecord->email = $request->email;
        $saveRecord->subject = $request->subject;
        $saveRecord->message = $request->message;


        $saveRecord->save();

        return redirect()->back()->with('success', 'Sent successfully!');
    }



    public function terms()
    {
        $socialLinks = SocialLinks::all();
        $testimonials = Testimonial::all();
        $term = Term::first();
        return view('website.terms.index', compact('term', 'testimonials', 'socialLinks'));
    }










    public function setLocale(Request $request)
    {
        $locale = $request->input('locale');

        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }

        return redirect()->back();
    }
}
