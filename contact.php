
<?php
require_once "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message_text = trim($_POST["message"] ?? "");

    if (
        $name === "" ||
        $email === "" ||
        $subject === "" ||
        $message_text === ""
    ) {

        $message = "Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } else {

        $message = "Thank you, $name. Your message has been received.";
    }
}
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
        content="Contact Macmillan Medical Training College Student Event Registration System."
    >

    <title>Contact | MMTC Event Registration System</title>

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
        .navbar-logo {
    height: 58px;
    width: auto;
    object-fit: contain;
    display: block;
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
            color: white !important;
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

        .contact-card {
            background: white;
            border: none;
            border-radius: 18px;
            padding: 30px;
            height: 100%;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .contact-card h2 {
            color: #123b68;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 25px;
        }

        .contact-icon {
            min-width: 50px;
            height: 50px;
            border-radius: 12px;
            background: #eaf4ff;
            color: #1c79c9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .contact-item h5 {
            color: #123b68;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .contact-item p {
            color: #6c757d;
            margin: 0;
            line-height: 1.6;
        }

        .form-label {
            color: #123b68;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #d8e0e8;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .form-control:focus {
            border-color: #1c79c9;
            box-shadow: 0 0 0 0.2rem rgba(28, 121, 201, 0.15);
        }

        textarea.form-control {
            min-height: 140px;
            resize: vertical;
        }

        .btn-send {
            background: #1c79c9;
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-send:hover {
            background: #123b68;
            color: white;
        }

        .alert {
            border-radius: 10px;
        }

        .map-box {
            background: #eaf4ff;
            border-radius: 18px;
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px;
            margin-top: 30px;
        }

        .map-box h4 {
            color: #123b68;
            font-weight: 700;
        }

        .map-box p {
            color: #6c757d;
            margin-bottom: 0;
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

        }

    </style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a
            class="navbar-brand"
            href="index.php"
        >
        <img src="images/logo Image .png"
         alt="MMTC Logo"
         class="navbar-logo">
         <div class="brand-name">
            <strong>MMTC</strong>
            <span>
                Student Event Registration System
            </span>

        </a>
</div>
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
                        class="nav-link"
                        href="about.php"
                    >
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link active"
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
            Contact Us
        </h1>

        <p>
            Have a question about student events or registration?
            Get in touch with the MMTC Student Event Registration team.
        </p>

    </div>

</section>

<section class="section">

    <div class="container">

        <div class="row g-5">

            <div class="col-lg-5">

                <div class="contact-card">

                    <h2>
                        Get In Touch
                    </h2>

                    <div class="contact-item">

                        <div class="contact-icon">
                            📍
                        </div>

                        <div>

                            <h5>
                                Address
                            </h5>

                            <p>
                                Macmillan Medical Training College<br>
                                Nairobi, Kenya
                            </p>

                        </div>

                    </div>

                    <div class="contact-item">

                        <div class="contact-icon">
                            📞
                        </div>

                        <div>

                            <h5>
                                Phone
                            </h5>

                            <p>
                                +254 700 000 000
                            </p>

                        </div>

                    </div>

                    <div class="contact-item">

                        <div class="contact-icon">
                            📩
                        </div>

                        <div>

                            <h5>
                                Email
                            </h5>

                            <p>
                                info@mmtc.ac.ke
                            </p>

                        </div>

                    </div>

                    <div class="contact-item">

                        <div class="contact-icon">
                            🕐
                        </div>

                        <div>

                            <h5>
                                Office Hours
                            </h5>

                            <p>
                                Monday - Friday<br>
                                8:00 AM - 5:00 PM
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-7">

                <div class="contact-card">

                    <h2>
                        Send Us a Message
                    </h2>

                    <?php if ($message !== ""): ?>

                        <div class="alert alert-info">

                            <?php echo htmlspecialchars($message); ?>

                        </div>

                    <?php endif; ?>

                    <form
                        method="POST"
                        action=""
                        id="contactForm"
                    >

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label
                                    for="name"
                                    class="form-label"
                                >
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Enter your full name"
                                    required
                                >

                            </div>

                            <div class="col-md-6">

                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="Enter your email"
                                    required
                                >

                            </div>

                            <div class="col-md-6">

                                <label
                                    for="phone"
                                    class="form-label"
                                >
                                    Phone Number
                                </label>

                                <input
                                    type="tel"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    placeholder="Enter your phone number"
                                >

                            </div>

                            <div class="col-md-6">

                                <label
                                    for="subject"
                                    class="form-label"
                                >
                                    Subject
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="subject"
                                    name="subject"
                                    placeholder="Enter message subject"
                                    required
                                >

                            </div>

                            <div class="col-12">

                                <label
                                    for="message"
                                    class="form-label"
                                >
                                    Message
                                </label>

                                <textarea
                                    class="form-control"
                                    id="message"
                                    name="message"
                                    placeholder="Write your message here..."
                                    required
                                ></textarea>

                            </div>

                            <div class="col-12">

                                <button
                                    type="submit"
                                    class="btn-send"
                                >
                                    Send Message
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <div class="map-box">

            <div>

                <h4>
                    Macmillan Medical Training College
                </h4>

                <p>
                    Nairobi, Kenya
                </p>

            </div>

        </div>

    </div>

</section>

<section class="cta">

    <div class="container">

        <h2>
            Looking for an Upcoming Event?
        </h2>

        <p>
            View available student events and register online.
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

<script>

    document.getElementById("contactForm").addEventListener("submit", function(event) {

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const subject = document.getElementById("subject").value.trim();
        const message = document.getElementById("message").value.trim();

        if (
            name === "" ||
            email === "" ||
            subject === "" ||
            message === ""
        ) {

            event.preventDefault();

            alert("Please fill in all required fields.");

            return;
        }

        const confirmed = confirm(
            "Are you sure you want to send this message?"
        );

        if (!confirmed) {

            event.preventDefault();

        }

    });

</script>

</body>

</html>

<?php
$conn->close();
?>

