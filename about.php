
<?php
require_once "config/db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Learn about Macmillan Medical Training College and the Student Event Registration System."
    >

    <title>About | MMTC Event Registration System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #263238;
        }

        .navbar {
            background: #123b68;
            padding: 15px 0;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 22px;
        }

        .navbar-brand span {
            display: block;
            font-size: 11px;
            font-weight: 400;
            color: #c8d9ea;
        }
        
        .navbar-brand {
    display: flex !important;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.navbar-logo {
    height: 58px;
    width: auto;
    object-fit: contain;
    display: block;
}

.brand-name {
    display: flex !important;
    flex-direction: column;
    line-height: 1.2;
}

.brand-name strong {
    display: block;
    color: white !important;
    font-size: 22px;
    font-weight: 800;
}
.brand-name span {
    display: block;
    color: #c8d9ea !important;
    font-size: 12px;
    font-weight: 600;
    margin-top: 3px;
}
   
        .navbar-nav .nav-link {
            color: #eaf2f8 !important;
            margin-left: 12px;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #ffffff !important;
        }

        .hero {
            background: linear-gradient(
                135deg,
                #123b68,
                #1c79c9
            );
            color: white;
            padding: 80px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 44px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .hero p {
            max-width: 750px;
            margin: auto;
            font-size: 18px;
            line-height: 1.7;
            color: #eaf4ff;
        }

        .section {
            padding: 70px 20px;
        }

        .section-title {
            color: #123b68;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .section-text {
            color: #5f6b76;
            line-height: 1.8;
            font-size: 16px;
        }

        .about-image {
            width: 100%;
            height: 380px;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.10);
        }

        .about-card {
            background: white;
            border: none;
            border-radius: 18px;
            padding: 30px;
            height: 100%;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: #eaf4ff;
            color: #1c79c9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .about-card h4 {
            color: #123b68;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .about-card p {
            color: #6c757d;
            line-height: 1.7;
        }

        .system-section {
            background: white;
        }

        .system-box {
            background: #f4f7fb;
            border-radius: 18px;
            padding: 35px;
        }

        .system-box ul {
            padding-left: 20px;
            color: #5f6b76;
            line-height: 2;
        }

        .cta {
            background: #123b68;
            color: white;
            padding: 65px 20px;
            text-align: center;
        }

        .cta h2 {
            font-weight: 700;
            margin-bottom: 15px;
        }

        .cta p {
            color: #dce8f3;
            margin-bottom: 25px;
        }

        .btn-register {
            background: #1c79c9;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-register:hover {
            background: white;
            color: #123b68;
        }

        .footer {
            background: #212529;
            color: white;
            text-align: center;
            padding: 25px 15px;
        }

        .footer p {
            margin: 0;
            color: #ced4da;
        }

        @media (max-width: 768px) {

            .hero {
                padding: 60px 20px;
            }

            .hero h1 {
                font-size: 34px;
            }

            .hero p {
                font-size: 16px;
            }

            .section {
                padding: 50px 20px;
            }

            .about-image {
                height: 280px;
            }

        }

    </style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

    <div class="container">

       <a class="navbar-brand" href="index.php">

    <img src="images/logo Image .png"
         alt="MMTC Logo"
         class="navbar-logo">

    <div class="brand-name">
        <strong> Macmillan Medical Training College</strong>
        <span>Student Event Registration System</span>
    </div>

</a>
        <button
            class="navbar-toggler bg-light"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
        >

            <span class="navbar-toggler-icon"></span>

        </button>

        <div
            class="collapse navbar-collapse"
            id="mainNavbar"
        >

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="index.php"
                    >
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="events.php"
                    >
                        Events
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link active"
                        href="about.php"
                    >
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="contact.php"
                    >
                        Contact
                    </a>
                </li>

                <li class="nav-item ms-lg-2">

                    <a
                        class="btn btn-light px-3"
                        href="register.php"
                    >
                        Register Now
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<section class="hero">

    <div class="container">

        <h1>
            About MMTC
        </h1>

        <p>
            Learn more about Macmillan Medical Training College
            and the Student Event Registration System designed
            to make student event registration simple and organized.
        </p>

    </div>

</section>

<section class="section">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <h2 class="section-title">
                    Macmillan Medical Training College
                </h2>

                <p class="section-text">

                    Macmillan Medical Training College (MMTC) is a
                    medical training institution that provides students
                    with opportunities to develop academic, professional
                    and practical skills.

                </p>

                <p class="section-text">

                    Student activities and events form an important part
                    of campus life. These activities provide opportunities
                    for students to learn, interact, participate and
                    develop skills beyond the classroom.

                </p>

            </div>

            <div class="col-lg-6">

                <img
                    src="images/download.jpeg"
                    alt="College students studying together"
                    class="about-image"
                >

            </div>

        </div>

    </div>

</section>

<section class="section system-section">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">
                About the Registration System
            </h2>

            <p class="section-text">
                The Student Event Registration System provides a
                centralized platform for managing student event
                registrations.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="about-card">

                    <div class="icon-box">
                        📅
                    </div>

                    <h4>
                        Event Management
                    </h4>

                    <p>

                        Students can view upcoming events and obtain
                        important information such as the date, time
                        and venue.

                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="about-card">

                    <div class="icon-box">
                        📝
                    </div>

                    <h4>
                        Easy Registration
                    </h4>

                    <p>

                        Students can submit their details and select
                        an available event through an online
                        registration form.

                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="about-card">

                    <div class="icon-box">
                        🔒
                    </div>

                    <h4>
                        Organized Records
                    </h4>

                    <p>

                        Registration information is stored in a
                        MySQL database and can be managed by authorized
                        administrators.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<section class="section">

    <div class="container">

        <div class="system-box">

            <h2 class="section-title">
                How the System Works
            </h2>

            <p class="section-text">

                The system connects students, events and administrators
                through a simple web-based platform.

            </p>

            <ul>

                <li>
                    Students browse available events.
                </li>

                <li>
                    Students select an event and complete the
                    registration form.
                </li>

                <li>
                    Registration information is validated before
                    being stored.
                </li>

                <li>
                    Administrators can view and manage registration
                    records.
                </li>

                <li>
                    Administrators can create, update and manage
                    event sessions.
                </li>

                <li>
                    System alerts can be used to communicate important
                    event information.
                </li>

            </ul>

        </div>

    </div>

</section>

<section class="cta">

    <div class="container">

        <h2>
            Ready to Register for an Event?
        </h2>

        <p>
            View the available events and register for an upcoming
            MMTC student activity.
        </p>

        <a
            href="events.php"
            class="btn-register"
        >
            View Events
        </a>

    </div>

</section>

<footer class="footer">

    <p>

        &copy; <?php echo date("Y"); ?>

        Macmillan Medical Training College -

        Student Event Registration System

    </p>

</footer>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>

<?php
$conn->close();
?>
```
