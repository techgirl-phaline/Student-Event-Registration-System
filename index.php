<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="MMTC Student Event Registration System - Discover, explore and register for exciting student events at Macmillan Medical Training College.">

    <title>MMTC Events | Student Event Registration System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap"
          rel="stylesheet">


    <style>

        :root {
            --primary: #174a5b;
            --primary-dark: #0e3440;
            --accent: #e4a72c;
            --accent-dark: #c88d16;
            --light: #f6f9fa;
            --text: #1c2930;
            --muted: #68777e;
            --white: #ffffff;
            --border: #e5ebee;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Inter", sans-serif;
            color: var(--text);
            background: #ffffff;
            line-height: 1.7;
        }

        a {
            text-decoration: none;
        }

        /* NAVBAR */

        .navbar {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            padding: 14px 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            color: var(--primary);
            font-size: 1.25rem;
        }
        .navbar-logo {
            height: 58px;
            width: auto;
            object-fit: contain;
            display: block;
        }    

            .brand-name small {
                display: block;
                font-size: 0.68rem;
                font-weight: 600;
                color: var(--muted);
                margin-top: 3px;
        }        
     
        .brand-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .navbar-nav .nav-link {
            color: #42545b;
            font-weight: 600;
            margin: 0 7px;
            transition: 0.3s;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary);
        }

        .nav-register {
            background: var(--primary);
            color: white !important;
            padding: 10px 19px !important;
            border-radius: 10px;
        }

        .nav-register:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }


        /* HERO */

        .hero {
            min-height: 690px;
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
            background:
                linear-gradient(
                    90deg,
                    rgba(8, 31, 39, 0.91) 0%,
                    rgba(8, 31, 39, 0.78) 43%,
                    rgba(8, 31, 39, 0.25) 100%
                ),
                url("images/hero.jpg")
                center/cover no-repeat;
        }

        .hero-content {
            color: white;
            max-width: 720px;
            padding: 80px 0;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.13);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-family: "Playfair Display", serif;
            font-size: clamp(3rem, 6vw, 5.5rem);
            line-height: 1.04;
            font-weight: 800;
            margin-bottom: 25px;
        }

        .hero h1 span {
            color: var(--accent);
        }

        .hero p {
            font-size: 1.12rem;
            color: rgba(255,255,255,0.86);
            max-width: 620px;
            margin-bottom: 32px;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 13px;
        }

        .btn-main {
            background: var(--accent);
            color: #182027;
            padding: 13px 23px;
            border-radius: 10px;
            font-weight: 800;
            border: none;
            transition: 0.3s;
        }

        .btn-main:hover {
            background: #f0bd50;
            color: #182027;
            transform: translateY(-2px);
        }

        .btn-outline-light-custom {
            color: white;
            border: 1px solid rgba(255,255,255,0.5);
            padding: 13px 23px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-outline-light-custom:hover {
            background: white;
            color: var(--primary);
        }


        /* QUICK STATS */

        .stats-wrapper {
            margin-top: -55px;
            position: relative;
            z-index: 5;
        }

        .stats-card {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 15px 45px rgba(19, 47, 57, 0.12);
        }

        .stat {
            text-align: center;
            padding: 10px 15px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.88rem;
            font-weight: 600;
        }


        /* GENERAL */

        .section {
            padding: 100px 0;
        }

        .section-light {
            background: var(--light);
        }

        .section-heading {
            max-width: 700px;
            margin: 0 auto 50px;
            text-align: center;
        }

        .section-tag {
            display: inline-block;
            color: var(--primary);
            font-weight: 800;
            font-size: 0.82rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .section-heading h2 {
            font-family: "Playfair Display", serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--primary-dark);
            margin-bottom: 15px;
        }

        .section-heading p {
            color: var(--muted);
        }


        /* SEARCH */

        .search-box {
            max-width: 650px;
            margin: 0 auto 45px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 16px 55px 16px 22px;
            outline: none;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        }

        .search-box i {
            position: absolute;
            right: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
        }


        /* EVENT CARDS */

        .event-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--border);
            height: 100%;
            transition: 0.35s;
        }

        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 45px rgba(15, 48, 60, 0.13);
        }

        .event-image {
            height: 225px;
            position: relative;
            overflow: hidden;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
        }

        .event-card:hover .event-image img {
            transform: scale(1.06);
        }

        .event-date {
            position: absolute;
            left: 15px;
            top: 15px;
            background: white;
            border-radius: 10px;
            padding: 7px 12px;
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--primary);
            box-shadow: 0 5px 15px rgba(0,0,0,0.12);
        }

        .event-body {
            padding: 23px;
        }

        .event-category {
            color: var(--accent-dark);
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .event-body h4 {
            margin: 7px 0 10px;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .event-body p {
            color: var(--muted);
            font-size: 0.91rem;
        }

        .event-info {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            color: #63747b;
            font-size: 0.82rem;
            margin: 15px 0;
        }

        .event-info i {
            color: var(--primary);
        }

        .event-btn {
            display: inline-block;
            color: var(--primary);
            font-weight: 800;
            font-size: 0.88rem;
        }


        /* CATEGORIES */

        .category-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px 20px;
            text-align: center;
            height: 100%;
            transition: 0.3s;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }

        .category-icon {
            width: 60px;
            height: 60px;
            margin: auto auto 16px;
            border-radius: 16px;
            background: #edf4f6;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
        }

        .category-card h5 {
            font-weight: 800;
            margin-bottom: 6px;
        }

        .category-card p {
            font-size: 0.82rem;
            color: var(--muted);
            margin: 0;
        }


        /* ABOUT */

        .about-image {
            border-radius: 22px;
            overflow: hidden;
            height: 500px;
        }

        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-content {
            padding-left: 35px;
        }

        .about-content h2 {
            font-family: "Playfair Display", serif;
            font-size: 2.8rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
        }

        .about-content p {
            color: var(--muted);
        }

        .check-item {
            display: flex;
            gap: 12px;
            margin: 15px 0;
            color: #42545b;
            font-weight: 600;
        }

        .check-item i {
            color: #198754;
            font-size: 1.15rem;
        }


        /* HOW IT WORKS */

        .step {
            text-align: center;
            position: relative;
            padding: 10px 20px;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto auto 20px;
            border-radius: 50%;
            font-weight: 800;
            font-size: 1.2rem;
        }

        .step h5 {
            font-weight: 800;
            color: var(--primary-dark);
        }

        .step p {
            color: var(--muted);
            font-size: 0.9rem;
        }


        /* FEATURE BANNER */

        .feature-banner {
            border-radius: 25px;
            padding: 70px;
            color: white;
            background:
                linear-gradient(
                    100deg,
                    rgba(14, 52, 64, 0.97),
                    rgba(23, 74, 91, 0.87)
                ),
                 url("images/feature-banner.jpeg");
        }

        .feature-banner h2 {
            font-family: "Playfair Display", serif;
            font-size: 2.8rem;
        }

        .feature-banner p {
            color: rgba(255,255,255,0.78);
            max-width: 650px;
        }


        /* FAQ */

        .accordion-item {
            border: 1px solid var(--border);
            margin-bottom: 12px;
            border-radius: 12px !important;
            overflow: hidden;
        }

        .accordion-button {
            font-weight: 700;
            color: var(--primary-dark);
            padding: 20px;
        }

        .accordion-button:not(.collapsed) {
            background: #eef5f7;
            color: var(--primary);
            box-shadow: none;
        }


        /* CTA */

        .cta {
            background: var(--light);
            text-align: center;
            padding: 90px 20px;
        }

        .cta h2 {
            font-family: "Playfair Display", serif;
            font-size: 3rem;
            color: var(--primary-dark);
        }

        .cta p {
            color: var(--muted);
            max-width: 650px;
            margin: 15px auto 28px;
        }


        /* FOOTER */

        footer {
            background: #0d2933;
            color: white;
            padding: 65px 0 25px;
        }

        footer h5 {
            font-weight: 800;
            margin-bottom: 18px;
        }

        footer p,
        footer a {
            color: rgba(255,255,255,0.67);
            font-size: 0.9rem;
        }

        footer a:hover {
            color: white;
        }

        .footer-brand {
            font-size: 1.35rem;
            font-weight: 800;
            color: white;
        }

        .social-icon {
            display: inline-flex;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.09);
            color: white;
            margin-right: 6px;
            transition: 0.3s;
        }

        .social-icon:hover {
            background: var(--accent);
            color: #172229;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 45px;
            padding-top: 20px;
        }


        /* SCROLL TOP */

        #scrollTop {
            position: fixed;
            right: 22px;
            bottom: 22px;
            width: 46px;
            height: 46px;
            border: none;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: none;
            z-index: 1000;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }


        /* RESPONSIVE */

        @media (max-width: 991px) {

            .hero {
                min-height: 620px;
            }

            .about-content {
                padding-left: 0;
                padding-top: 30px;
            }

            .feature-banner {
                padding: 45px 30px;
            }

        }

        @media (max-width: 767px) {

            .hero {
                min-height: 650px;
            }

            .hero h1 {
                font-size: 3rem;
            }

            .stats-wrapper {
                margin-top: -35px;
            }

            .section {
                padding: 70px 0;
            }

            .about-image {
                height: 350px;
            }

            .feature-banner h2 {
                font-size: 2.1rem;
            }

            .cta h2 {
                font-size: 2.3rem;
            }

        }

    </style>

</head>


<body>


<!-- ================= NAVBAR ================= -->

<nav class="navbar navbar-expand-lg sticky-top">

    <div class="container">

        <a class="navbar-brand" href="index.php">

    <img src="images/logo Image .png"
         alt="MMTC Logo"
         class="navbar-logo">
         <span class="brand-name">
        Macmillan Medical Training College <br>
        <small>Student Event Registration System</small>
    </span>

</a>
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="events.php">
                        Events
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="about.php">
                        About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">
                        Contact
                    </a>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">

                    <a class="nav-link nav-register"
                       href="register.php">

                        Register for an Event
                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- ================= HERO ================= -->

<section class="hero">

    <div class="container">

        <div class="hero-content">

            <div class="hero-badge">

                <i class="bi bi-stars"></i>

                Macmillan Medical Training College • Nairobi

            </div>


            <h1>
                Discover.
                <span>Connect.</span>
                Participate.
            </h1>


            <p>
                Discover exciting academic, medical, technology,
                sports, cultural and community events happening
                at MMTC. Find your event and register in a few clicks.
            </p>


            <div class="hero-buttons">

                <a href="events.php" class="btn btn-main">

                    Explore Events
                    <i class="bi bi-arrow-right ms-2"></i>

                </a>


                <a href="register.php"
                   class="btn btn-outline-light-custom">

                    Register Now

                </a>

            </div>

        </div>

    </div>

</section>



<!-- ================= STATS ================= -->

<div class="container stats-wrapper">

    <div class="stats-card">

        <div class="row g-3">

            <div class="col-6 col-md-3">

                <div class="stat">

                    <div class="stat-number">20+</div>

                    <div class="stat-label">
                        Events
                    </div>

                </div>

            </div>


            <div class="col-6 col-md-3">

                <div class="stat">

                    <div class="stat-number">500+</div>

                    <div class="stat-label">
                        Students
                    </div>

                </div>

            </div>


            <div class="col-6 col-md-3">

                <div class="stat">

                    <div class="stat-number">8+</div>

                    <div class="stat-label">
                        Event Categories
                    </div>

                </div>

            </div>


            <div class="col-6 col-md-3">

                <div class="stat">

                    <div class="stat-number">100%</div>

                    <div class="stat-label">
                        Online Registration
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- ================= UPCOMING EVENTS ================= -->

<section class="section" id="events">

    <div class="container">

        <div class="section-heading">

            <span class="section-tag">
                What's happening
            </span>

            <h2>
                Explore Upcoming Events
            </h2>

            <p>
                From medical training and technology to athletics,
                community service and student talent, there is
                something for everyone at MMTC.
            </p>

        </div>


        <!-- SEARCH -->

        <div class="search-box">

            <input
                type="text"
                id="eventSearch"
                placeholder="Search events e.g. medical, sports, technology..."
            >

            <i class="bi bi-search"></i>

        </div>


        <div class="row g-4" id="eventContainer">


            <!-- TECHNOLOGY -->

            <div class="col-lg-4 col-md-6 event-item">

                <div class="event-card">

                    <div class="event-image">

                        <img src="images/development.jpg" alt="Software Development Challenge">

                        <span class="event-date">
                            OCT 18
                        </span>

                    </div>


                    <div class="event-body">

                        <span class="event-category">
                            Technology
                        </span>

                        <h4>
                            Software Development Challenge
                        </h4>

                        <p>
                            A practical technology challenge where
                            students collaborate, innovate and build
                            creative digital solutions.
                        </p>

                        <div class="event-info">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                Oct 18, 2026
                            </span>

                            <span>
                                <i class="bi bi-geo-alt"></i>
                                Computer Lab
                            </span>

                        </div>

                        <a href="register.php?event=Software%20Development%20Challenge"
                           class="event-btn">

                            Register
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </div>

            </div>



            <!-- MEDICAL -->

            <div class="col-lg-4 col-md-6 event-item">

                <div class="event-card">

                    <div class="event-image">

                        <img src="images/Health & Clinical Skills Day.webp" alt="Health & Clinical Skills Day">
                        <span class="event-date">
                            OCT 24
                        </span>

                    </div>


                    <div class="event-body">

                        <span class="event-category">
                            Health & Medical
                        </span>

                        <h4>
                            Health & Clinical Skills Day
                        </h4>

                        <p>
                            A practical learning experience focused on
                            clinical skills, teamwork and healthcare
                            knowledge.
                        </p>

                        <div class="event-info">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                Oct 24, 2026
                            </span>

                            <span>
                                <i class="bi bi-geo-alt"></i>
                                Skills Lab
                            </span>

                        </div>

                        <a href="register.php?event=Health%20%26%20Clinical%20Skills%20Day"
                           class="event-btn">

                            Register
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </div>

            </div>



            <!-- ATHLETICS -->

            <div class="col-lg-4 col-md-6 event-item">

                <div class="event-card">

                    <div class="event-image">

                        <img src="images/Sports & Athletics.avif" alt="Sports & Athletics">

                        <span class="event-date">
                            NOV 02
                        </span>

                    </div>


                    <div class="event-body">

                        <span class="event-category">
                            Sports & Athletics
                        </span>

                        <h4>
                            MMTC Athletics Day
                        </h4>

                        <p>
                            Bring your energy and team spirit for a
                            fun-filled day of races, games and
                            friendly competition.
                        </p>

                        <div class="event-info">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                Nov 02, 2026
                            </span>

                            <span>
                                <i class="bi bi-geo-alt"></i>
                                College Grounds
                            </span>

                        </div>

                        <a href="register.php?event=MMTC%20Athletics%20Day"
                           class="event-btn">

                            Register
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </div>

            </div>



            <!-- COMMUNITY -->

            <div class="col-lg-4 col-md-6 event-item">

                <div class="event-card">

                    <div class="event-image">

                        <img
                            src="images/children .images.jpeg"
                            alt="Students participating in community outreach">

                        <span class="event-date">
                            NOV 10
                        </span>

                    </div>


                    <div class="event-body">

                        <span class="event-category">
                            Community
                        </span>

                        <h4>
                            Children's Home Outreach
                        </h4>

                        <p>
                            Join fellow students in giving back through
                            community service, mentorship and meaningful
                            engagement.
                        </p>

                        <div class="event-info">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                Nov 10, 2026
                            </span>

                            <span>
                                <i class="bi bi-geo-alt"></i>
                                Nairobi
                            </span>

                        </div>

                        <a href="register.php?event=Children%27s%20Home%20Outreach"
                           class="event-btn">

                            Register
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </div>

            </div>



            <!-- CULTURE -->

            <div class="col-lg-4 col-md-6 event-item">

                <div class="event-card">

                    <div class="event-image">

                        <img
                            src="images/cultural images.jpeg"
                            alt="Students at a cultural event">

                        <span class="event-date">
                            NOV 21
                        </span>

                    </div>


                    <div class="event-body">

                        <span class="event-category">
                            Culture & Talent
                        </span>

                        <h4>
                            Student Talent & Culture Day
                        </h4>

                        <p>
                            Celebrate creativity, music, dance, fashion,
                            poetry and the diverse talents of MMTC students.
                        </p>

                        <div class="event-info">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                Nov 21, 2026
                            </span>

                            <span>
                                <i class="bi bi-geo-alt"></i>
                                Main Hall
                            </span>

                        </div>

                        <a href="register.php?event=Student%20Talent%20%26%20Culture%20Day"
                           class="event-btn">

                            Register
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </div>

            </div>



            <!-- WELLNESS -->

            <div class="col-lg-4 col-md-6 event-item">

                <div class="event-card">

                    <div class="event-image">

                        <img
                            src="images/wellness images.jpeg"
                            alt="Student wellness activity">

                        <span class="event-date">
                            DEC 05
                        </span>

                    </div>


                    <div class="event-body">

                        <span class="event-category">
                            Student Life
                        </span>

                        <h4>
                            Student Wellness Day
                        </h4>

                        <p>
                            Take time to recharge with wellness activities,
                            fitness sessions and conversations around
                            healthy student life.
                        </p>

                        <div class="event-info">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                Dec 05, 2026
                            </span>

                            <span>
                                <i class="bi bi-geo-alt"></i>
                                College Campus
                            </span>

                        </div>

                        <a href="register.php?event=Student%20Wellness%20Day"
                           class="event-btn">

                            Register
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </div>

            </div>


        </div>


        <div id="noResults"
             class="text-center mt-5"
             style="display:none;">

            <i class="bi bi-search"
               style="font-size:2rem; color:#174a5b;"></i>

            <h5 class="mt-3">
                No events found
            </h5>

            <p class="text-muted">
                Try searching for another event or category.
            </p>

        </div>


        <div class="text-center mt-5">

            <a href="events.php"
               class="btn btn-outline-dark px-4 py-2">

                View All Events
                <i class="bi bi-arrow-right ms-2"></i>

            </a>

        </div>

    </div>

</section>



<!-- ================= CATEGORIES ================= -->

<section class="section section-light">

    <div class="container">

        <div class="section-heading">

            <span class="section-tag">
                Something for everyone
            </span>

            <h2>
                Explore Event Categories
            </h2>

            <p>
                Whether you're passionate about healthcare,
                technology, sports, creativity or community service,
                MMTC has something for you.
            </p>

        </div>


        <div class="row g-4">


            <div class="col-6 col-md-4 col-lg-3">

                <div class="category-card">

                    <div class="category-icon">
                        👩‍⚕️
                    </div>

                    <h5>
                        Health & Medical
                    </h5>

                    <p>
                        Clinical and healthcare activities
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="category-card">

                    <div class="category-icon">
                        💻
                    </div>

                    <h5>
                        Technology
                    </h5>

                    <p>
                        Digital innovation and coding
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="category-card">
                    <div class="category-icon">
                        🤾‍♂️
                    </div>
                    <h5>
                        Sports
                    </h5>

                    <p>
                        Athletics and team activities
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="category-card">

                    <div class="category-icon">
                        🧑‍🤝‍🧑
                    </div>

                    <h5>
                        Community
                    </h5>

                    <p>
                        Outreach and social activities
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="category-card">

                    <div class="category-icon">
                        📚
                    </div>

                    <h5>
                        Academic
                    </h5>

                    <p>
                        Learning and academic events
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="category-card">

                    <div class="category-icon">
                        🎵
                    </div>

                    <h5>
                        Culture & Talent
                    </h5>

                    <p>
                        Music, dance and creativity
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="category-card">

                    <div class="category-icon">
                        🌳
                    </div>

                    <h5>
                        Environment
                    </h5>

                    <p>
                        Green and sustainability events
                    </p>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg-3">

                <div class="category-card">

                    <div class="category-icon">
                       🛟
                    </div>

                    <h5>
                        Student Life
                    </h5>

                    <p>
                        Wellness and student experiences
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- ================= ABOUT ================= -->

<section class="section" id="about">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <div class="about-image">

    <img
        src="images/students.jpg" alt="MMTC students on campus">

</div>

            </div>


            <div class="col-lg-6">

                <div class="about-content">

                    <span class="section-tag">
                        About the platform
                    </span>

                    <h2>
                        More than events.
                        It's student life.
                    </h2>

                    <p>
                        The MMTC Student Event Registration System makes
                        it easier for students to discover what's happening
                        around the college and participate in activities
                        beyond the classroom.
                    </p>

                    <p>
                        From academic opportunities to sports, technology,
                        community service, wellness and culture, the platform
                        brings student activities together in one convenient
                        place.
                    </p>


                    <div class="check-item">

                        <i class="bi bi-check-circle-fill"></i>

                        Discover events happening at MMTC

                    </div>


                    <div class="check-item">

                        <i class="bi bi-check-circle-fill"></i>

                        Register online quickly and conveniently

                    </div>


                    <div class="check-item">

                        <i class="bi bi-check-circle-fill"></i>

                        Participate in activities beyond the classroom

                    </div>


                    <div class="mt-4">

                        <a href="about.php"
                           class="btn btn-main">

                            Learn More
                            <i class="bi bi-arrow-right ms-2"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= HOW IT WORKS ================= -->

<section class="section section-light" id="how-it-works">

    <div class="container">

        <div class="section-heading">

            <span class="section-tag">
                Simple & convenient
            </span>

            <h2>
                How Event Registration Works
            </h2>

            <p>
                Register for your favourite MMTC events in just a
                few simple steps.
            </p>

        </div>


        <div class="row g-4">


            <div class="col-md-3">

                <div class="step">

                    <div class="step-number">
                        1
                    </div>

                    <h5>
                        Explore
                    </h5>

                    <p>
                        Browse upcoming events and discover activities
                        that interest you.
                    </p>

                </div>

            </div>


            <div class="col-md-3">

                <div class="step">

                    <div class="step-number">
                        2
                    </div>

                    <h5>
                        Select
                    </h5>

                    <p>
                        Choose the event you would like to attend.
                    </p>

                </div>

            </div>


            <div class="col-md-3">

                <div class="step">

                    <div class="step-number">
                        3
                    </div>

                    <h5>
                        Register
                    </h5>

                    <p>
                        Complete the registration form with your
                        student details.
                    </p>

                </div>

            </div>


            <div class="col-md-3">

                <div class="step">

                    <div class="step-number">
                        4
                    </div>

                    <h5>
                        Participate
                    </h5>

                    <p>
                        Show up, connect with others and enjoy the event.
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- ================= FEATURE BANNER ================= -->

<section class="section">

    <div class="container">

        <div class="feature-banner">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <span class="section-tag text-warning">
                        Your campus. Your experience.
                    </span>

                    <h2>
                        Don't just study at MMTC.
                        Be part of it.
                    </h2>

                    <p>
                        Events create opportunities to meet people,
                        develop skills, discover talents and make
                        unforgettable college memories.
                    </p>

                </div>


                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <a href="register.php"
                       class="btn btn-main">

                        Register for an Event
                        <i class="bi bi-arrow-right ms-2"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= FAQ ================= -->

<section class="section section-light">

    <div class="container">

        <div class="section-heading">

            <span class="section-tag">
                Need help?
            </span>

            <h2>
                Frequently Asked Questions
            </h2>

        </div>


        <div class="row justify-content-center">

            <div class="col-lg-9">

                <div class="accordion" id="faqAccordion">


                    <div class="accordion-item">

                        <h2 class="accordion-header">

                            <button class="accordion-button"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq1">

                                Who can register for events?

                            </button>

                        </h2>

                        <div id="faq1"
                             class="accordion-collapse collapse show"
                             data-bs-parent="#faqAccordion">

                            <div class="accordion-body">

                                MMTC students can register for events
                                available on the platform. Some events
                                may have specific participation requirements.

                            </div>

                        </div>

                    </div>


                    <div class="accordion-item">

                        <h2 class="accordion-header">

                            <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq2">

                                How do I register for an event?

                            </button>

                        </h2>

                        <div id="faq2"
                             class="accordion-collapse collapse"
                             data-bs-parent="#faqAccordion">

                            <div class="accordion-body">

                                Select an event, click the register button
                                and complete the registration form with
                                your student details.

                            </div>

                        </div>

                    </div>


                    <div class="accordion-item">

                        <h2 class="accordion-header">

                            <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq3">

                                Can I register for more than one event?

                            </button>

                        </h2>

                        <div id="faq3"
                             class="accordion-collapse collapse"
                             data-bs-parent="#faqAccordion">

                            <div class="accordion-body">

                                Yes. You can register for multiple events
                                as long as each event allows student
                                registration.

                            </div>

                        </div>

                    </div>


                    <div class="accordion-item">

                        <h2 class="accordion-header">

                            <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq4">

                                Where can I see available events?

                            </button>

                        </h2>

                        <div id="faq4"
                             class="accordion-collapse collapse"
                             data-bs-parent="#faqAccordion">

                            <div class="accordion-body">

                                You can view available events on the home
                                page or visit the dedicated Events page.

                            </div>

                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= CTA ================= -->

<section class="cta">

    <div class="container">

        <span class="section-tag">
            Ready?
        </span>

        <h2>
            Find something you love.
        </h2>

        <p>
            Explore what's happening at MMTC and register for your
            next unforgettable student experience.
        </p>

        <a href="events.php"
           class="btn btn-main">

            Explore Events
            <i class="bi bi-arrow-right ms-2"></i>

        </a>

    </div>

</section>



<!-- ================= FOOTER ================= -->

<footer id="contact">

    <div class="container">

        <div class="row g-5">


            <div class="col-lg-5">

                <a href="index.php"
                   class="footer-brand">

                    <i class="bi bi-mortarboard-fill me-2"></i>

                    MMTC Events

                </a>

                <p class="mt-3">

                    Student Event Registration System for
                    Macmillan Medical Training College,
                    Nairobi.

                </p>


                <div class="mt-4">

                    <a href="#" class="social-icon">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="#" class="social-icon">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="#" class="social-icon">
                        <i class="bi bi-twitter-x"></i>
                    </a>

                    <a href="#" class="social-icon">
                        <i class="bi bi-linkedin"></i>
                    </a>

                </div>

            </div>


            <div class="col-6 col-lg-2">

                <h5>
                    Explore
                </h5>

                <p>
                    <a href="index.php">
                        Home
                    </a>
                </p>

                <p>
                    <a href="events.php">
                        Events
                    </a>
                </p>

                <p>
                    <a href="about.php">
                        About
                    </a>
                </p>

                <p>
                    <a href="register.php">
                        Register
                    </a>
                </p>

            </div>


            <div class="col-6 col-lg-2">

                <h5>
                    Categories
                </h5>

                <p>
                    <a href="#">
                        Medical
                    </a>
                </p>

                <p>
                    <a href="#">
                        Technology
                    </a>
                </p>

                <p>
                    <a href="#">
                        Sports
                    </a>
                </p>

                <p>
                    <a href="#">
                        Culture
                    </a>
                </p>

            </div>


            <div class="col-lg-3">

                <h5>
                    Contact
                </h5>

                <p>
                    <i class="bi bi-geo-alt me-2"></i>
                    Nairobi, Kenya
                </p>

                <p>
                    <i class="bi bi-envelope me-2"></i>
                    events@mmtc.ac.ke
                </p>

                <p>
                    <i class="bi bi-telephone me-2"></i>
                    +254 700 000 000
                </p>

            </div>


        </div>


        <div class="footer-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <p class="mb-0">

                        © 2026 MMTC Student Event Registration System.

                        All rights reserved.

                    </p>

                </div>


                <div class="col-md-6 text-md-end mt-2 mt-md-0">

                    <a href="admin/login.php">

                        <i class="bi bi-shield-lock me-1"></i>

                        Admin Login

                    </a>

                </div>

            </div>

        </div>

    </div>

</footer>



<!-- SCROLL TO TOP -->

<button id="scrollTop"
        onclick="window.scrollTo({top: 0, behavior: 'smooth'})">

    <i class="bi bi-arrow-up"></i>

</button>



<!-- Bootstrap JavaScript -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

    /* EVENT SEARCH */

    const searchInput = document.getElementById("eventSearch");

    const eventItems = document.querySelectorAll(".event-item");

    const noResults = document.getElementById("noResults");


    searchInput.addEventListener("keyup", function () {

        const searchValue = this.value.toLowerCase().trim();

        let visibleEvents = 0;


        eventItems.forEach(function (item) {

            const eventText = item.innerText.toLowerCase();


            if (eventText.includes(searchValue)) {

                item.style.display = "";

                visibleEvents++;

            } else {

                item.style.display = "none";

            }

        });


        if (visibleEvents === 0) {

            noResults.style.display = "block";

        } else {

            noResults.style.display = "none";

        }

    });



    /* SCROLL TOP BUTTON */

    const scrollTopButton = document.getElementById("scrollTop");


    window.addEventListener("scroll", function () {

        if (window.scrollY > 500) {

            scrollTopButton.style.display = "block";

        } else {

            scrollTopButton.style.display = "none";

        }

    });


</script>


</body>
</html>