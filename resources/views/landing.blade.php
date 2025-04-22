<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ __('landing.title') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6e8efb 0%, #4a6cf7 100%);
            --success-gradient: linear-gradient(135deg, #28c76f 0%, #19a05e 100%);
            --warning-gradient: linear-gradient(135deg, #ffc107 0%, #f7b500 100%);
            --info-gradient: linear-gradient(135deg, #00cfe8 0%, #0097a7 100%);
            --danger-gradient: linear-gradient(135deg, #ea5455 0%, #d92550 100%);
            --purple-gradient: linear-gradient(135deg, #8a62e3 0%, #6a38ca 100%);
            --primary-color: #4a6cf7;
            --success-color: #19a05e;
            --warning-color: #f7b500;
            --info-color: #0097a7;
            --purple-color: #6a38ca;
            --light-bg: #f9fafc;
            --dark-text: #2c3e50;
            --card-radius: 16px;
            --section-padding: 80px 0;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        body.ltr {
            font-family: 'Poppins', sans-serif;
            direction: ltr;
            text-align: left;
        }

        .btn-lang {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(74, 108, 247, 0.3);
            transition: all 0.3s;
        }

        .btn-lang:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(74, 108, 247, 0.4);
        }

        .ltr .btn-lang {
            left: auto;
            right: 20px;
        }

        header {
            background: var(--primary-gradient);
            color: white;
            padding: 130px 0 100px;
            position: relative;
            overflow: hidden;
            /* إزالة شكل المنحنى */
            clip-path: none;
            margin-bottom: 0;
        }

        header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 15s ease-in-out infinite;
        }

        header::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s ease-in-out 2s infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-20px) translateX(10px); }
            100% { transform: translateY(0px) translateX(0px); }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
        }

        .hero-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            right: 0;
            width: 40%;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 2px;
        }

        .ltr .hero-title::after {
            right: auto;
            left: 0;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            max-width: 550px;
        }

        .btn-primary {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-trial {
            background: var(--warning-gradient);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(247, 181, 0, 0.3);
            transition: all 0.3s;
            margin-right: 15px;
        }

        .btn-trial:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(247, 181, 0, 0.4);
            color: white;
        }

        .ltr .btn-trial {
            margin-right: 0;
            margin-left: 15px;
        }

        .feature-card {
            background: white;
            border-radius: var(--card-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 30px;
            height: 100%;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            z-index: -1;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .feature-card.primary::before {
            background: var(--primary-gradient);
        }

        .feature-card.success::before {
            background: var(--success-gradient);
        }

        .feature-card.warning::before {
            background: var(--warning-gradient);
        }

        .feature-card.info::before {
            background: var(--info-gradient);
        }

        .feature-card.purple::before {
            background: var(--purple-gradient);
        }

        .feature-card:hover::before {
            width: 100%;
            opacity: 0.05;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }

        .feature-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.8;
            z-index: -1;
        }

        .feature-icon.primary::before {
            background: var(--primary-gradient);
        }

        .feature-icon.success::before {
            background: var(--success-gradient);
        }

        .feature-icon.warning::before {
            background: var(--warning-gradient);
        }

        .feature-icon.info::before {
            background: var(--info-gradient);
        }

        .feature-icon.purple::before {
            background: var(--purple-gradient);
        }

        .feature-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .feature-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            right: 0;
            width: 30px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .ltr .feature-title::after {
            right: auto;
            left: 0;
        }

        .feature-card:hover .feature-title::after {
            width: 50px;
        }

        .feature-text {
            opacity: 0.8;
            font-size: 1rem;
            line-height: 1.6;
        }

        .section-title {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50%;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 5px;
        }

        .ltr .section-title::after {
            left: 0;
            right: auto;
        }

        .section-subtitle {
            font-size: 1.1rem;
            margin-bottom: 3rem;
            opacity: 0.8;
            max-width: 700px;
        }

        .screenshot {
            border-radius: var(--card-radius);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            max-width: 100%;
            height: auto;
            border: 5px solid white;
        }

        .screenshot:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 40px rgba(0, 0, 0, 0.15);
        }

        .statistic-item {
            text-align: center;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .statistic-item:hover {
            transform: translateY(-5px);
        }

        .statistic-value {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .statistic-label {
            font-size: 1.1rem;
            opacity: 0.8;
            font-weight: 500;
        }

        .cta-section {
            background: var(--primary-gradient);
            padding: var(--section-padding);
            color: white;
            position: relative;
            overflow: hidden;
            /* إزالة شكل المنحنى */
            clip-path: none;
            margin-top: 0;
        }

        .cta-section::before, .cta-section::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 15s ease-in-out infinite alternate;
        }

        .cta-section::before {
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
        }

        .cta-section::after {
            bottom: -80px;
            left: -80px;
            width: 250px;
            height: 250px;
            animation-delay: 3s;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cta-subtitle {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        /* System Description Section Styles */
        .system-feature {
            transition: all 0.3s ease;
            padding: 20px;
            border-radius: var(--card-radius);
        }

        .system-feature:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-5px);
        }

        .feature-item {
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateX(5px);
        }

        .ltr .feature-item:hover {
            transform: translateX(-5px);
        }

        .feature-item .icon-wrapper {
            transition: all 0.3s ease;
        }

        .feature-item:hover .icon-wrapper {
            transform: scale(1.1);
        }

        .highlight-box {
            border-radius: var(--card-radius);
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.03);
            background-color: white;
            position: relative;
            overflow: hidden;
        }

        .highlight-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
        }

        .highlight-box.success::before {
            background: var(--success-gradient);
        }

        .highlight-box.warning::before {
            background: var(--warning-gradient);
        }

        .highlight-box.info::before {
            background: var(--info-gradient);
        }

        .highlight-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        footer {
            background: #2c3e50;
            color: white;
            padding: 50px 0 20px;
            /* إزالة شكل المنحنى */
            clip-path: none;
            padding-top: 50px;
        }

        .footer-title {
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.3rem;
            position: relative;
            display: inline-block;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            right: 0;
            width: 30px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .ltr .footer-title::after {
            right: auto;
            left: 0;
        }

        .footer-list {
            list-style: none;
            padding: 0;
        }

        .footer-list li {
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .footer-list li:hover {
            transform: translateX(5px);
        }

        .ltr .footer-list li:hover {
            transform: translateX(-5px);
        }

        .footer-list a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }

        .footer-list a:hover {
            color: white;
            padding-right: 5px;
        }

        .ltr .footer-list a:hover {
            padding-right: 0;
            padding-left: 5px;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .ltr .social-icons a {
            margin-right: 0;
            margin-left: 10px;
        }

        .social-icons a:hover {
            background: white;
            color: #2c3e50;
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .social-icons a:hover i {
            animation: pulse 0.6s;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 30px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .bg-pattern-light {
            background-color: #f8f9fc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234a6cf7' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .bg-pattern-dark {
            background-color: #f8f9fc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .counter-animation {
            animation: countUp 2.5s ease-out forwards;
        }

        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .animate-on-scroll.show {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            .section-title {
                font-size: 2rem;
            }
            header {
                padding: 100px 0 70px;
            }
            .btn-trial, .btn-primary {
                display: block;
                width: 100%;
                margin-bottom: 10px;
                text-align: center;
            }
            .btn-trial {
                margin-right: 0;
            }
            .feature-card {
                margin-bottom: 20px;
            }
            .statistic-item {
                margin-bottom: 30px;
            }
            .cta-title {
                font-size: 2rem;
            }
            .cta-subtitle {
                font-size: 1rem;
            }
            .footer-title {
                margin-top: 20px;
            }
            .whatsapp-float {
                width: 50px;
                height: 50px;
                font-size: 25px;
            }
        }

        @media (max-width: 576px) {
            body {
                font-size: 14px;
            }
            .container {
                padding-left: 12px;
                padding-right: 12px;
            }
            .hero-title {
                font-size: 1.5rem;
                margin-bottom: 10px;
            }
            .hero-subtitle {
                font-size: 0.95rem;
                margin-bottom: 1.5rem;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .section-subtitle {
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            }
            h3 {
                font-size: 1.4rem;
            }
            header {
                padding: 60px 0 40px;
            }
            .screenshot {
                margin-top: 15px;
                border-width: 3px;
            }
            .highlight-box {
                padding: 15px;
                margin-bottom: 15px;
            }
            .highlight-box p {
                font-size: 0.9rem;
            }
            .highlight-box ul li, .system-feature, .feature-item {
                font-size: 0.85rem;
            }
            .feature-card {
                padding: 20px;
            }
            .feature-icon {
                width: 50px;
                height: 50px;
                margin-bottom: 15px;
            }
            .feature-title {
                font-size: 1.2rem;
                margin-bottom: 10px;
            }
            .feature-text {
                font-size: 0.9rem;
                line-height: 1.5;
            }
            section {
                padding-top: 25px !important;
                padding-bottom: 25px !important;
            }
            .py-md-5 {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }
            .py-3, .container.py-3 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
            .cta-section {
                padding-top: 35px !important;
                padding-bottom: 35px !important;
            }
            .btn-trial, .btn-primary {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
            .cta-title {
                font-size: 1.5rem;
            }
            .cta-subtitle {
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            }
            .mb-4 {
                margin-bottom: 1rem !important;
            }
            .mb-5 {
                margin-bottom: 1.5rem !important;
            }
            .g-4 {
                --bs-gutter-y: 0.75rem;
            }
            .g-3 {
                --bs-gutter-y: 0.5rem;
            }
            footer {
                padding-top: 30px;
                padding-bottom: 15px;
            }

            /* Hide decorative elements on mobile */
            .position-absolute.rounded-circle {
                display: none !important;
            }

            /* Form optimization */
            .form-floating input, .form-floating select {
                height: 45px !important;
            }
            .form-floating textarea {
                min-height: 80px !important;
            }

            /* Reduce rounded corners for a sleeker mobile look */
            .btn, .card, .feature-card, .highlight-box, .form-control, .form-select {
                border-radius: 12px !important;
            }

            /* Make feature items more compact on mobile */
            li.mb-3 {
                margin-bottom: 0.5rem !important;
            }
            .feature-card .feature-text {
                margin-bottom: 0;
            }

            /* Fix row gutters for tighter layout */
            .row {
                --bs-gutter-x: 1rem;
            }

            /* تحسين مظهر الأزرار على الموبايل */
            .btn {
                padding: 8px 12px !important;
                font-size: 0.85rem !important;
            }
            .btn-trial, .btn-primary {
                min-width: 120px;
            }
            .me-2 {
                margin-right: 0.35rem !important;
            }
            .gap-2 {
                gap: 0.35rem !important;
            }

            /* إضافة أنماط أخرى لجعل الموقع أكثر استجابة على الهواتف */
        }

        @media (max-width: 576px) {
            /* تحسين صورة الهيدر على الموبايل */
            header .screenshot {
                max-width: 85%;
                margin: 0 auto;
                display: block;
                margin-top: 1.5rem;
                margin-bottom: 0.5rem;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            }

            /* تقليل المساحة في الهيدر على الموبايل نظرًا لإخفاء الأزرار */
            header {
                padding: 75px 0 30px !important;
            }

            .hero-title {
                text-align: center;
                margin-top: 15px;
            }

            .hero-subtitle {
                text-align: center;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>

    <!-- CSS لـ Navbar الذي يظهر عند التمرير -->
    <style>
        .sticky-navbar {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            z-index: 1020;
            background: var(--primary-gradient);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            transition: all 0.3s ease-in-out;
        }

        .sticky-navbar.show {
            top: 0;
        }

        .sticky-navbar .navbar-brand {
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .sticky-navbar .nav-link {
            color: white;
            opacity: 0.9;
            margin: 0 10px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .sticky-navbar .nav-link:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        .sticky-navbar .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }

        .sticky-navbar .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.7)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 576px) {
            .sticky-navbar {
                padding: 10px 0;
            }

            .sticky-navbar .navbar-brand {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>


    <!-- Login Button -->
    <a href="{{ route('login') }}" class="btn-login" style="position: fixed; top: 20px; right: 20px; z-index: 1000; background: var(--info-gradient); color: white; border: none; padding: 8px 15px; border-radius: 50px; font-size: 14px; box-shadow: 0 4px 15px rgba(0, 151, 167, 0.3); transition: all 0.3s; text-decoration: none;">
        {{ __('landing.login') }}
    </a>

    <!-- Sticky Navbar on Scroll -->
    <nav class="sticky-navbar navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                {{ __('landing.brand_name') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">{{ __('landing.navbar.features') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{ __('landing.navbar.services') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#trial-request">{{ __('landing.navbar.trial_request') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{ __('landing.navbar.contact_us') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/964XXXXXXXXX" class="whatsapp-float" target="_blank" style="position: fixed; bottom: 20px; right: 20px; width: 55px; height: 55px; background-color: #25d366; color: white; border-radius: 50px; text-align: center; font-size: 28px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); z-index: 1000; display: flex; justify-content: center; align-items: center; text-decoration: none;">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title" data-ar="اركيد لإدارة شركات التنظيف" data-en="Arkeed Cleaning Management">اركيد لإدارة شركات التنظيف في العراق</h1>
                    <p class="hero-subtitle" data-ar="الحل الأمثل لإدارة شركات النظافة في العراق بكفاءة وسهولة" data-en="The optimal solution for managing cleaning companies in Iraq efficiently and easily">الحل الأمثل لإدارة شركات النظافة في العراق بكفاءة وسهولة</p>
                    <div class="d-flex flex-wrap gap-2 d-none d-md-flex">
                        <a href="#trial-request" class="btn btn-trial me-2" data-ar="طلب نسخة تجريبية" data-en="Request Trial Version">طلب نسخة تجريبية</a>
                        <a href="#features" class="btn btn-primary" data-ar="اكتشف المزايا" data-en="Discover Features">اكتشف المزايا</a>
                    </div>
                </div>
                <div class="col-lg-6 d-block">
                    <img src="{{ asset('assets/landing/1.png') }}" alt="Arkeed Dashboard" class="img-fluid screenshot">
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container py-4 py-md-5">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="section-title" data-ar="مميزات نظام اركيد" data-en="Arkeed System Features">مميزات نظام اركيد</h2>
                <p class="section-subtitle mx-auto" data-ar="نظام متكامل يوفر جميع الأدوات اللازمة لإدارة شركات التنظيف بكفاءة عالية" data-en="An integrated system that provides all the necessary tools to manage cleaning companies with high efficiency">نظام متكامل يوفر جميع الأدوات اللازمة لإدارة شركات التنظيف بكفاءة عالية</p>
            </div>

            <div class="row g-3 g-md-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card primary h-100">
                        <div class="feature-icon primary">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                        <h3 class="feature-title" data-ar="إدارة العملاء" data-en="Customer Management">إدارة العملاء</h3>
                        <p class="feature-text" data-ar="تتبع جميع بيانات العملاء وإدارة سجلاتهم بسهولة ويسر، مع إمكانية حفظ كافة التفاصيل المهمة" data-en="Track all customer data and manage their records easily, with the ability to save all important details">تتبع جميع بيانات العملاء وإدارة سجلاتهم بسهولة ويسر، مع إمكانية حفظ كافة التفاصيل المهمة</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card success h-100">
                        <div class="feature-icon success">
                            <i class="fas fa-user-check fa-2x text-white"></i>
                        </div>
                        <h3 class="feature-title" data-ar="إدارة عمال النظافة" data-en="Cleaners Management">إدارة عمال النظافة</h3>
                        <p class="feature-text" data-ar="إدارة شاملة لعمال النظافة مع جدولة المهام وتتبع الأداء وتنظيم سير العمل بكفاءة" data-en="Comprehensive management of cleaners with task scheduling, performance tracking, and efficient workflow organization">إدارة شاملة لعمال النظافة مع جدولة المهام وتتبع الأداء وتنظيم سير العمل بكفاءة</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card warning h-100">
                        <div class="feature-icon warning">
                            <i class="fas fa-calendar-day fa-2x text-white"></i>
                        </div>
                        <h3 class="feature-title" data-ar="جدولة الزيارات" data-en="Visit Scheduling">جدولة الزيارات</h3>
                        <p class="feature-text" data-ar="تنظيم الزيارات ومواعيد العمل بطريقة سهلة مع إمكانية التحكم الكامل بجدول المواعيد اليومي والأسبوعي والشهري" data-en="Organize visits and work schedules easily with full control over daily, weekly, and monthly schedules">تنظيم الزيارات ومواعيد العمل بطريقة سهلة مع إمكانية التحكم الكامل بجدول المواعيد اليومي والأسبوعي والشهري</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card info h-100">
                        <div class="feature-icon info">
                            <i class="fas fa-chart-line fa-2x text-white"></i>
                        </div>
                        <h3 class="feature-title" data-ar="تقارير وإحصائيات" data-en="Reports & Statistics">تقارير وإحصائيات</h3>
                        <p class="feature-text" data-ar="رسوم بيانية وتقارير تفصيلية توفر نظرة شاملة عن أداء الشركة ومعدلات الزيارات وإحصائيات العملاء" data-en="Charts and detailed reports providing a comprehensive view of company performance, visit rates, and customer statistics">رسوم بيانية وتقارير تفصيلية توفر نظرة شاملة عن أداء الشركة ومعدلات الزيارات وإحصائيات العملاء</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card purple h-100">
                        <div class="feature-icon purple">
                            <i class="fas fa-mobile-alt fa-2x text-white"></i>
                        </div>
                        <h3 class="feature-title" data-ar="واجهة سهلة الاستخدام" data-en="User-Friendly Interface">واجهة سهلة الاستخدام</h3>
                        <p class="feature-text" data-ar="تصميم عصري وسهل الاستخدام يتيح للمستخدمين التعامل مع النظام بسلاسة وسرعة من أي جهاز" data-en="Modern and easy-to-use design that allows users to interact with the system smoothly and quickly from any device">تصميم عصري وسهل الاستخدام يتيح للمستخدمين التعامل مع النظام بسلاسة وسرعة من أي جهاز</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="feature-card primary h-100">
                        <div class="feature-icon primary">
                            <i class="fas fa-shield-alt fa-2x text-white"></i>
                        </div>
                        <h3 class="feature-title" data-ar="نظام صلاحيات متكامل" data-en="Integrated Permissions System">نظام صلاحيات متكامل</h3>
                        <p class="feature-text" data-ar="نظام إدارة صلاحيات قوي يتيح التحكم في وصول المستخدمين وفقاً لأدوارهم في الشركة" data-en="Powerful permissions management system that controls user access according to their roles in the company">نظام إدارة صلاحيات قوي يتيح التحكم في وصول المستخدمين وفقاً لأدوارهم في الشركة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- System Description Section -->
    <section class="py-4 py-md-5 bg-pattern-light">
        <div class="container py-3 py-md-5">
            <div class="row justify-content-center text-center mb-4 mb-md-5 animate-on-scroll">
                <div class="col-md-11 col-lg-9">
                    <h2 class="section-title">نظام متكامل لإدارة شركات التنظيف</h2>
                    <p class="section-subtitle mx-auto" data-ar="نظام اركيد هو الحل الأمثل لإدارة شركات التنظيف في العراق، يقدم مجموعة شاملة من الأدوات لتحسين كفاءة العمل وزيادة الإنتاجية" data-en="Arkeed system is the optimal solution for managing cleaning companies in Iraq, providing a comprehensive set of tools to improve work efficiency and increase productivity">نظام اركيد هو الحل الأمثل لإدارة شركات التنظيف في العراق، يقدم مجموعة شاملة من الأدوات لتحسين كفاءة العمل وزيادة الإنتاجية</p>
                </div>
            </div>

            <!-- System Overview -->
            <div class="row align-items-center mb-4 mb-md-5 pb-4 pb-md-5 border-bottom border-2 border-light animate-on-scroll">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="position-relative">
                        <img src="{{ asset('assets/landing/1.png') }}" alt="Arkeed System Overview" class="img-fluid screenshot">
                        <div class="position-absolute rounded-circle bg-warning" style="width: 120px; height: 120px; top: -30px; right: -20px; opacity: 0.1; z-index: -1;"></div>
                        <div class="position-absolute rounded-circle bg-primary" style="width: 80px; height: 80px; bottom: -20px; left: -10px; opacity: 0.1; z-index: -1;"></div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="highlight-box mb-4">
                        <h3 class="h3 h2-md mb-3 mb-md-4 fw-bold text-primary" >نظام متكامل بواجهة سهلة الاستخدام</h3>
                        <p class="mb-3 mb-md-4 fs-6 fs-md-5" >تم تصميم نظام اركيد بواجهة مستخدم سهلة وبديهية، حيث يمكن للمستخدمين الوصول بسرعة إلى جميع الوظائف الأساسية والإحصائيات اللازمة لإدارة أعمالهم اليومية.</p>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex feature-item">
                            <div class="me-3 text-primary icon-wrapper"><i class="fas fa-check-circle fa-lg"></i></div>
                            <div><strong>متوافق مع جميع الأجهزة</strong> - <span>يعمل على أجهزة الكمبيوتر والهواتف الذكية والأجهزة اللوحية</span></div>
                        </li>
                        <li class="mb-3 d-flex feature-item">
                            <div class="me-3 text-primary icon-wrapper"><i class="fas fa-check-circle fa-lg"></i></div>
                            <div><strong>تصميم عصري</strong> - <span>واجهة مستخدم جذابة وعصرية تسهل التنقل والاستخدام</span></div>
                        </li>
                        <li class="mb-3 d-flex feature-item">
                            <div class="me-3 text-primary icon-wrapper"><i class="fas fa-check-circle fa-lg"></i></div>
                            <div><strong>نظام الإشعارات</strong> - <span>إشعارات فورية للمهام والمواعيد والتذكيرات المهمة</span></div>
                        </li>
                        <li class="d-flex feature-item">
                            <div class="me-3 text-primary icon-wrapper"><i class="fas fa-check-circle fa-lg"></i></div>
                            <div><strong>لوحة تحكم مخصصة</strong> - <span>يمكن تخصيص لوحة التحكم حسب احتياجات كل مستخدم ودوره</span></div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Advanced Management Features -->
            <div class="row align-items-center my-5 pb-5 border-bottom border-2 border-light animate-on-scroll">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="{{ asset('assets/landing/2.png') }}" alt="Advanced Management Features" class="img-fluid screenshot">
                        <div class="position-absolute rounded-circle bg-success" style="width: 100px; height: 100px; top: -20px; left: -30px; opacity: 0.1; z-index: -1;"></div>
                        <div class="position-absolute rounded-circle bg-info" style="width: 70px; height: 70px; bottom: -10px; right: -20px; opacity: 0.1; z-index: -1;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="highlight-box success mb-4">
                        <h3 class="h2 mb-4 fw-bold text-success">إدارة متطورة للعمليات</h3>
                        <p class="mb-4 fs-5">يوفر نظام اركيد مجموعة متكاملة من الأدوات لإدارة العمليات اليومية لشركة التنظيف بكفاءة عالية، مما يتيح لك التركيز على تنمية أعمالك.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex feature-item">
                                    <div class="me-3 text-success icon-wrapper"><i class="fas fa-check-circle fa-lg"></i></div>
                                    <div>إدارة جدول الزيارات</div>
                                </li>

                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-success"><i class="fas fa-check-circle fa-lg"></i></div>
                                    <div>تتبع الكادر ميدانياً</div>
                                </li>
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-success"><i class="fas fa-check-circle fa-lg"></i></div>
                                    <div>إدارة المخزون والمعدات</div>
                                </li>
                                <li class="d-flex">
                                    <div class="me-3 text-success"><i class="fas fa-check-circle fa-lg"></i></div>
                                    <div>توزيع المهام تلقائياً</div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-success"><i class="fas fa-check-circle fa-lg"></i></div>
                                    <div>إدارة عقود العملاء</div>
                                </li>
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-success"><i class="fas fa-check-circle fa-lg"></i></div>
                                    <div>نظام تقييم جودة الخدمة</div>
                                </li>
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-success"><i class="fas fa-check-circle fa-lg"></i></div>
                                    <div>نماذج تقارير الزيارات</div>
                                </li>
                                <li class="d-flex">
                                    <div class="me-3 text-success"><i class="fas fa-check-circle fa-lg"></i></div>
                                    <div>إدارة الشكاوى والطلبات</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Management -->
            <div class="row align-items-center my-5 pb-5 border-bottom border-2 border-light">
                <div class="col-lg-6 order-lg-2">
                    <img src="{{ asset('assets/landing/4.png') }}" alt="Financial Management" class="img-fluid screenshot">
                </div>
                <div class="col-lg-6 order-lg-1">
                    <h3 class="h2 mb-4 fw-bold text-warning" data-ar="إدارة مالية شاملة" data-en="Comprehensive Financial Management">إدارة مالية شاملة</h3>
                    <p class="mb-4 fs-5" data-ar="يوفر نظام اركيد أدوات متقدمة لإدارة الشؤون المالية لشركتك، مما يساعدك على تتبع الإيرادات والنفقات وإدارة الفواتير والمدفوعات بكفاءة." data-en="Arkeed system provides advanced tools to manage your company's financial affairs, helping you track revenues and expenses and manage invoices and payments efficiently.">يوفر نظام اركيد أدوات متقدمة لإدارة الشؤون المالية لشركتك، مما يساعدك على تتبع الإيرادات والنفقات وإدارة الفواتير والمدفوعات بكفاءة.</p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex">
                            <div class="me-3 text-warning"><i class="fas fa-check-circle fa-lg"></i></div>
                            <div><strong>إصدار الفواتير تلقائياً</strong> - <span>إنشاء وإرسال الفواتير للعملاء تلقائياً حسب جدول العقود</span></div>
                        </li>
                        <li class="mb-3 d-flex">
                            <div class="me-3 text-warning"><i class="fas fa-check-circle fa-lg"></i></div>
                            <div><strong>تقارير الربحية</strong> - <span>تحليل تفصيلي لربحية كل عميل ومشروع وخدمة</span></div>
                        </li>
                        <li class="mb-3 d-flex">
                            <div class="me-3 text-warning"><i class="fas fa-check-circle fa-lg"></i></div>
                            <div><strong>إدارة الرواتب</strong> - <span>حساب رواتب الموظفين والحوافز والخصومات تلقائياً</span></div>
                        </li>
                        <li class="d-flex">
                            <div class="me-3 text-warning"><i class="fas fa-check-circle fa-lg"></i></div>
                            <div><strong>التذكير بالمدفوعات</strong> - <span>نظام تذكير آلي للفواتير المستحقة والمتأخرة</span></div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Analytical Reports -->
            <div class="row align-items-center my-5">
                <div class="col-lg-6">
                    <img src="{{ asset('assets/landing/7.png') }}" alt="Analytical Reports" class="img-fluid screenshot">
                </div>
                <div class="col-lg-6">
                    <h3 class="h2 mb-4 fw-bold text-info" data-ar="تقارير تحليلية متقدمة" data-en="Advanced Analytical Reports">تقارير تحليلية متقدمة</h3>
                    <p class="mb-4 fs-5" data-ar="يقدم نظام اركيد مجموعة شاملة من التقارير التحليلية التي تساعدك على فهم أداء شركتك واتخاذ قرارات مستنيرة لتحسين الكفاءة وزيادة الربحية." data-en="Arkeed system provides a comprehensive set of analytical reports that help you understand your company's performance and make informed decisions to improve efficiency and increase profitability.">يقدم نظام اركيد مجموعة شاملة من التقارير التحليلية التي تساعدك على فهم أداء شركتك واتخاذ قرارات مستنيرة لتحسين الكفاءة وزيادة الربحية.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-info"><i class="fas fa-chart-line fa-lg"></i></div>
                                    <div>تحليل أداء الموظفين</div>
                                </li>
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-info"><i class="fas fa-chart-pie fa-lg"></i></div>
                                        <div>تقارير رضا العملاء</div>
                                </li>
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-info"><i class="fas fa-chart-bar fa-lg"></i></div>
                                    <div>تحليل الإيرادات الشهرية</div>
                                </li>
                                <li class="d-flex">
                                    <div class="me-3 text-info"><i class="fas fa-chart-area fa-lg"></i></div>
                                    <div>مؤشرات الأداء الرئيسية</div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-info"><i class="fas fa-chart-line fa-lg"></i></div>
                                    <div>تحليل تكاليف المشاريع</div>
                                </li>
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-info"><i class="fas fa-chart-pie fa-lg"></i></div>
                                    <div>توزيع المبيعات حسب الخدمة</div>
                                </li>
                                <li class="mb-3 d-flex">
                                    <div class="me-3 text-info"><i class="fas fa-chart-bar fa-lg"></i></div>
                                    <div>معدلات نمو الأعمال</div>
                                </li>
                                <li class="d-flex">
                                    <div class="me-3 text-info"><i class="fas fa-chart-area fa-lg"></i></div>
                                    <div>تنبؤات مستقبلية للأعمال</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Preview Section -->
    <section class="py-4 py-md-5 bg-light">
        <div class="container py-3 py-md-5">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="section-title">لوحة تحكم متطورة</h2>
                <p class="section-subtitle mx-auto">لوحة تحكم احترافية تمنحك رؤية شاملة لأعمال شركتك بطريقة مرئية وسهلة الفهم</p>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <div class="px-2 px-md-4 py-2">
                        <img src="{{ asset('assets/landing/1.png') }}" alt="Arkeed Dashboard" class="screenshot img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-8">
                    <h2 class="cta-title">ابدأ استخدام نظام اركيد اليوم</h2>
                    <p class="cta-subtitle">ارفع كفاءة شركة التنظيف الخاصة بك واحصل على تحكم كامل بعمليات الإدارة</p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="#trial-request" class="btn btn-trial me-2">طلب نسخة تجريبية</a>
                        <a href="#" class="btn btn-primary">تواصل معنا</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trial Request Section -->
    <section id="trial-request" class="py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-10 text-center mb-5">
                    <h2 class="section-title">طلب نسخة تجريبية لمدة 10 أيام</h2>
                    <p class="section-subtitle mx-auto">احصل على تجربة كاملة لنظام اركيد لمدة 10 أيام مجاناً</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0" style="border-radius: 24px; box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1); overflow: hidden; background-image: linear-gradient(120deg, #fdfbfb 0%, #f9f7fa 100%);">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block" style="background: var(--primary-gradient), url('https://via.placeholder.com/600x800'); background-size: cover; background-position: center; position: relative;">
                                <div class="p-5 text-white h-100 d-flex flex-column justify-content-center">
                                    <div style="position: relative; z-index: 2;">
                                        <h3 class="h2 fw-bold mb-4">لماذا تختار نظام اركيد؟</h3>
                                        <ul class="list-unstyled">
                                            <li class="mb-4 d-flex align-items-center">
                                                <div class="me-3 bg-white rounded-circle text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <span>إدارة متكاملة لشركات التنظيف</span>
                                            </li>
                                            <li class="mb-4 d-flex align-items-center">
                                                <div class="me-3 bg-white rounded-circle text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <span>واجهة سهلة الاستخدام</span>
                                            </li>
                                            <li class="mb-4 d-flex align-items-center">
                                                <div class="me-3 bg-white rounded-circle text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <span>تقارير تفصيلية لمراقبة الأداء</span>
                                            </li>
                                            <li class="mb-4 d-flex align-items-center">
                                                <div class="me-3 bg-white rounded-circle text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <span>دعم فني على مدار الساعة</span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <div class="me-3 bg-white rounded-circle text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <span>تحديثات مستمرة للنظام</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0;">
                                        <div class="position-absolute" style="width: 300px; height: 300px; border-radius: 50%; background: rgba(255, 255, 255, 0.1); top: -100px; right: -100px;"></div>
                                        <div class="position-absolute" style="width: 200px; height: 200px; border-radius: 50%; background: rgba(255, 255, 255, 0.1); bottom: -50px; left: -50px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-body p-4 p-lg-5">
                                    <h3 class="fw-bold mb-4 text-center text-lg-start">أدخل بياناتك للحصول على النسخة التجريبية</h3>
                                    <form id="trialRequestForm">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="companyName" placeholder=" " required style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.75rem 1rem 0.75rem 2.5rem; height: auto;">
                                            <label for="companyName" class="ms-4">اسم الشركة</label>
                                            <i class="fas fa-building position-absolute" style="top: 50%; transform: translateY(-50%); left: 1rem; color: #6e8efb;"></i>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="fullName" placeholder=" " required style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.75rem 1rem 0.75rem 2.5rem; height: auto;">
                                                <label for="fullName" class="ms-4">الاسم الكامل</label>
                                            <i class="fas fa-user position-absolute" style="top: 50%; transform: translateY(-50%); left: 1rem; color: #6e8efb;"></i>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="tel" class="form-control" id="phoneNumber" placeholder=" " required style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.75rem 1rem 0.75rem 2.5rem; height: auto;">
                                            <label for="phoneNumber" class="ms-4">رقم الهاتف</label>
                                            <i class="fas fa-phone-alt position-absolute" style="top: 50%; transform: translateY(-50%); left: 1rem; color: #6e8efb;"></i>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="email" placeholder=" " required style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.75rem 1rem 0.75rem 2.5rem; height: auto;">
                                            <label for="email" class="ms-4">البريد الإلكتروني</label>
                                            <i class="fas fa-envelope position-absolute" style="top: 50%; transform: translateY(-50%); left: 1rem; color: #6e8efb;"></i>
                                        </div>
                                        <div class="form-floating mb-4">
                                            <select class="form-select" id="employeesCount" required style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.75rem 1rem 0.75rem 2.5rem; height: auto;">
                                                <option value="" selected disabled></option>
                                                <option value="1-5">1-5</option>
                                                <option value="6-20">6-20</option>
                                                <option value="21-50">21-50</option>
                                                <option value="50+">أكثر من 50</option>
                                            </select>
                                            <label for="employeesCount" class="ms-4">عدد الموظفين</label>
                                            <i class="fas fa-users position-absolute" style="top: 50%; transform: translateY(-50%); left: 1rem; color: #6e8efb;"></i>
                                        </div>
                                        <div class="form-floating mb-4">
                                            <textarea class="form-control" id="message" placeholder=" " rows="3" style="border-radius: 10px; border: 1px solid #e2e8f0; padding: 1.5rem 1rem 0.75rem 2.5rem; min-height: 100px;"></textarea>
                                            <label for="message" class="ms-4">ملاحظات إضافية</label>
                                            <i class="fas fa-comment-alt position-absolute" style="top: 25px; left: 1rem; color: #6e8efb;"></i>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-lg position-relative overflow-hidden" style="background: var(--primary-gradient); border: none; color: white; font-weight: 600; border-radius: 50px; padding: 12px 20px; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 20px rgba(74, 108, 247, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(74, 108, 247, 0.2)';">
                                                <span>إرسال الطلب</span>
                                                <i class="fas fa-paper-plane ms-2"></i>
                                            </button>
                                        </div>
                                        <div id="trialRequestSuccess" class="alert alert-success mt-4 animate__animated animate__fadeIn" style="display: none; border-radius: 10px;" data-ar="شكراً لك! تم إرسال طلب النسخة التجريبية بنجاح. سنتواصل معك خلال 24 ساعة." data-en="Thank you! Your trial request has been sent successfully. We will contact you within 24 hours.">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <span>شكراً لك! تم إرسال طلب النسخة التجريبية بنجاح. سنتواصل معك خلال 24 ساعة.</span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">عن اركيد</h4>
                    <p>نظام اركيد هو الحل الأمثل لإدارة شركات التنظيف في العراق، يوفر مجموعة متكاملة من الأدوات لتحسين كفاءة العمل وزيادة الإنتاجية.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">روابط سريعة</h4>
                    <ul class="footer-list">
                        <li><a href="#">الصفحة الرئيسية</a></li>
                        <li><a href="#features">المميزات</a></li>
                        <li><a href="#">خطط الأسعار</a></li>
                        <li><a href="#">الدعم الفني</a></li>
                        <li><a href="#">اتصل بنا</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">معلومات الاتصال</h4>
                    <ul class="footer-list">
                        <li>العراق - بغداد</li>
                        <li>البريد الإلكتروني: info@arkeed-system.com</li>
                        <li>هاتف: +964 7XX XXX XXXX</li>
                    </ul>
                </div>
            </div>
            <div class="text-center copyright">
                <p>© 2023 اركيد لإدارة شركات التنظيف. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;


            // Animate elements when they come into view
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.2
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-on-scroll').forEach(element => {
                observer.observe(element);
            });

            // Animate stats counter
            const animateCounters = () => {
                document.querySelectorAll('.statistic-value').forEach(counter => {
                    counter.classList.add('counter-animation');

                    const updateCount = () => {
                        const target = parseInt(counter.innerText.replace(/\D/g, ''));
                        const speed = 1000;
                        const inc = target / speed;

                        let count = 0;
                        const timer = setInterval(() => {
                            count = count + inc;
                            if (count >= target) {
                                clearInterval(timer);
                                counter.innerText = '+' + target;
                            } else {
                                counter.innerText = '+' + Math.floor(count);
                            }
                        }, 1);
                    };

                    // Add observer for stats section
                    const statsObserver = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            updateCount();
                            statsObserver.unobserve(counter);
                        }
                    }, { threshold: 0.5 });

                    statsObserver.observe(counter);
                });
            };

            // Run counter animation
            animateCounters();

            // Trial Request Form Handler
            const trialRequestForm = document.getElementById('trialRequestForm');
            if (trialRequestForm) {
                // Add input animation effects
                const formInputs = trialRequestForm.querySelectorAll('input, select, textarea');
                formInputs.forEach(input => {
                    // Add focus effect
                    input.addEventListener('focus', function() {
                        this.parentElement.style.transform = 'translateY(-3px)';
                        this.parentElement.style.transition = 'transform 0.3s';
                    });

                    // Remove focus effect
                    input.addEventListener('blur', function() {
                        this.parentElement.style.transform = 'translateY(0)';
                    });
                });

                // Form submission
                trialRequestForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Collect form data
                    const formData = {
                        companyName: document.getElementById('companyName').value,
                        fullName: document.getElementById('fullName').value,
                        phoneNumber: document.getElementById('phoneNumber').value,
                        email: document.getElementById('email').value,
                        employeesCount: document.getElementById('employeesCount').value,
                        message: document.getElementById('message').value
                    };

                    // Here you would typically send this data to your server
                    console.log('Form data submitted:', formData);

                    // Show success message
                    const successMessage = document.getElementById('trialRequestSuccess');

                    // Hide form fields with animation
                    Array.from(trialRequestForm.elements).forEach((element, index) => {
                        setTimeout(() => {
                            if (element.type !== 'submit') {
                                element.closest('.form-floating')?.classList.add('animate__animated', 'animate__fadeOutUp');
                            } else {
                                element.closest('.d-grid')?.classList.add('animate__animated', 'animate__fadeOut');
                            }
                        }, index * 100); // Staggered animation
                    });

                    // Show success message after animations complete
                    setTimeout(() => {
                        // Hide all form elements
                        Array.from(trialRequestForm.elements).forEach(element => {
                            if (element.type !== 'submit') {
                                element.closest('.form-floating')?.classList.add('d-none');
                            } else {
                                element.closest('.d-grid')?.classList.add('d-none');
                            }
                        });

                        // Show success message with animation
                        successMessage.style.display = 'block';
                        successMessage.classList.add('animate__animated', 'animate__fadeIn');

                        // Reset form (hidden elements)
                        trialRequestForm.reset();
                    }, 800);
                });
            }

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    if (this.getAttribute('href') !== '#') {
                        e.preventDefault();

                        const targetId = this.getAttribute('href');
                        const targetElement = document.querySelector(targetId);

                        if (targetElement) {
                            window.scrollTo({
                                top: targetElement.offsetTop - 70, // Offset for header
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });

            // Floating animation for placeholders in form background
            const animateBgElements = () => {
                const formBgElements = document.querySelectorAll('.position-absolute[style*="border-radius: 50%"]');
                formBgElements.forEach(element => {
                    // Random animation
                    const duration = 15 + Math.random() * 10;
                    const delay = Math.random() * 5;

                    element.style.animation = `float ${duration}s ease-in-out ${delay}s infinite`;
                });
            };

            // Add float animation to CSS
            const style = document.createElement('style');
            style.textContent = `
                @keyframes float {
                    0% { transform: translateY(0px) translateX(0px); }
                    50% { transform: translateY(-20px) translateX(10px); }
                    100% { transform: translateY(0px) translateX(0px); }
                }
            `;
            document.head.appendChild(style);

            // Animate background elements
            animateBgElements();

            // تفعيل الـ Navbar عند التمرير
            const stickyNavbar = document.querySelector('.sticky-navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 200) {
                    stickyNavbar.classList.add('show');
                } else {
                    stickyNavbar.classList.remove('show');
                }
            });
        });
    </script>

    <!-- Mobile-specific style fixes -->
    <style>
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem 1.25rem !important;
            }
            .form-floating {
                margin-bottom: 0.8rem !important;
            }
            .form-floating input, .form-floating select, .form-floating textarea {
                font-size: 14px !important;
                padding-top: 0.65rem !important;
                padding-bottom: 0.65rem !important;
            }
            .form-floating label {
                font-size: 13px !important;
                padding-top: 0.5rem !important;
            }
            .btn-lang, .btn-login {
                font-size: 12px !important;
                padding: 6px 10px !important;
            }
            .whatsapp-float {
                bottom: 15px !important;
                right: 15px !important;
                width: 45px !important;
                height: 45px !important;
                font-size: 22px !important;
            }
            .footer-title {
                font-size: 1.1rem;
            }
            .footer-list {
                font-size: 0.9rem;
            }
            .copyright {
                font-size: 0.8rem;
            }
        }

        /* Optimize trial form for mobile */
        @media (max-width: 576px) {
            .card {
                border-radius: 16px !important;
                margin: 0 5px;
            }
            #trialRequestForm .form-floating {
                margin-bottom: 0.6rem !important;
            }
            #trialRequestForm input, #trialRequestForm select, #trialRequestForm textarea {
                padding-left: 2.2rem !important;
                font-size: 13px !important;
            }
            #trialRequestForm .form-floating label {
                font-size: 12px !important;
            }
            #trialRequestForm button[type="submit"] {
                padding: 10px 15px !important;
                font-size: 14px !important;
            }
            #trialRequestForm i.position-absolute {
                font-size: 0.9rem;
            }
            .social-icons a {
                width: 35px;
                height: 35px;
                margin-right: 8px;
            }
        }
    </style>
</body>
</html>
