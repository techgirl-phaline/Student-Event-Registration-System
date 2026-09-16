
<?php

require_once "config/db.php";

$selected_event = $_GET['event'] ?? '';

$defaultEvents = [

    "New Students Orientation",
    "Academic & Study Skills Seminar",
    "Clinical Skills Competition",
    "Blood Donation Drive",
    "Sports & Athletics Day",
    "ICT & Innovation Day",
    "Mental Health & Wellness Day",
    "Cultural & Talent Day",
    "Community Health Outreach",
    "Career & Professional Development Day",
    "Science & Research Exhibition",
    "Student Awards & Closing Ceremony"

];

$events = [];

foreach ($defaultEvents as $eventName) {

    $events[] = [

        "session_title" => $eventName

    ];

}

$sql = "SELECT session_title, session_date, status
        FROM sessions
        WHERE session_date >= CURDATE()
        AND status IN ('Upcoming', 'Active')
        ORDER BY session_date ASC";

$result = $conn->query($sql);

if ($result) {

    while ($row = $result->fetch_assoc()) {

        $exists = false;

        foreach ($events as $event) {

            if ($event["session_title"] === $row["session_title"]) {

                $exists = true;

                break;

            }

        }

        if (!$exists) {

            $events[] = $row;

        }

    }

}

if ($selected_event !== '') {

    $valid_event = false;

    foreach ($events as $event) {

        if ($selected_event === $event["session_title"]) {

            $valid_event = true;

            break;

        }

    }

    if (!$valid_event) {

        $selected_event = '';

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

    <title>Register for Event | MMTC</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {

            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1f2937;

        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar {

            background: #123b68;
            padding: 15px 0;

        }

        .navbar-brand {

            color: white !important;
            font-weight: 700;
            font-size: 22px;
            text-decoration: none;

        }

        .brand-wrapper {

            display: flex !important;
            align-items: center;
            gap: 12px;

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
            justify-content: center;
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

        /* =========================
           REGISTRATION SECTION
        ========================= */

        .registration-section {

            min-height: 100vh;
            padding: 70px 20px;

        }

        .registration-card {

            max-width: 850px;
            margin: auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);

        }

        .registration-header {

            background: #123b68;
            color: white;
            padding: 35px;
            text-align: center;

        }

        .registration-header h1 {

            margin-bottom: 10px;
            font-weight: 700;

        }

        .registration-header p {

            margin: 0;
            opacity: 0.9;

        }

        .registration-body {

            padding: 40px;

        }

        .form-label {

            font-weight: 600;
            color: #26374a;

        }

        .form-control,
        .form-select {

            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #d5dce5;

        }

        .form-control:focus,
        .form-select:focus {

            border-color: #1c79c9;
            box-shadow: 0 0 0 0.2rem rgba(28, 121, 201, 0.15);

        }

        .register-btn {

            width: 100%;
            border: none;
            background: #1c79c9;
            color: white;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            transition: 0.3s;

        }

        .register-btn:hover {

            background: #145f9f;

        }

        .form-note {

            background: #eef6ff;
            border-left: 4px solid #1c79c9;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 25px;
            color: #31516e;

        }

        #messageBox {

            margin-bottom: 20px;

        }

        .alert {

            border-radius: 10px;

        }

        /* =========================
           MODAL
        ========================= */

        .modal-header {

            background: #123b68;
            color: white;

        }

        .modal-header .btn-close {

            filter: brightness(0) invert(1);

        }

        .confirmation-details {

            background: #f5f8fc;
            padding: 18px;
            border-radius: 10px;
            line-height: 1.9;

        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 576px) {

            .registration-section {

                padding: 30px 12px;

            }

            .registration-body {

                padding: 25px 18px;

            }

            .registration-header {

                padding: 28px 20px;

            }

            .navbar-logo {

                height: 50px;

            }

            .brand-name strong {

                font-size: 19px;

            }

            .brand-name span {

                font-size: 10px;

            }

        }

    </style>

</head>

<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a
            class="navbar-brand brand-wrapper"
            href="index.php"
        >

            <img
                src="images/logo Image .png"
                alt="MMTC Logo"
                class="navbar-logo"
            >

            <div class="brand-name">

                <strong>MMTC</strong>

                <span>
                    Student Event Registration System
                </span>

            </div>

        </a>


        <button
            class="navbar-toggler bg-light"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
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


<!-- =========================
     REGISTRATION SECTION
========================= -->

<section class="registration-section">

    <div class="registration-card">


        <div class="registration-header">

            <h1>
                Event Registration
            </h1>

            <p>
                Macmillan Medical Training College - MMTC
            </p>

        </div>


        <div class="registration-body">

            <div id="messageBox"></div>


            <div class="form-note">

                <strong>Important:</strong>

                Please provide accurate information when registering
                for a college event.

            </div>


            <form
                id="registrationForm"
                method="POST"
                action="process_registration.php"
            >

                <div class="row g-4">


                    <div class="col-md-6">

                        <label
                            for="student_name"
                            class="form-label"
                        >
                            Student Name
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="student_name"
                            name="student_name"
                            maxlength="100"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label
                            for="admission_number"
                            class="form-label"
                        >
                            Admission Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="admission_number"
                            name="admission_number"
                            maxlength="50"
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
                            maxlength="100"
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
                            maxlength="20"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label
                            for="course"
                            class="form-label"
                        >
                            Course
                        </label>

                        <select
                            class="form-select"
                            id="course"
                            name="course"
                            required
                        >

                            <option value="">
                                Select your course
                            </option>

                            <option value="Diploma in Nursing">
                                Diploma in Nursing
                            </option>

                            <option value="Diploma in Clinical Medicine">
                                Diploma in Clinical Medicine
                            </option>

                            <option value="Diploma in Medical Laboratory Sciences">
                                Diploma in Medical Laboratory Sciences
                            </option>

                            <option value="Diploma in Pharmacy">
                                Diploma in Pharmacy
                            </option>

                            <option value="Diploma in Health Records">
                                Diploma in Health Records
                            </option>

                            <option value="Diploma in ICT">
                                Diploma in ICT
                            </option>

                            <option value="Certificate in Nursing">
                                Certificate in Nursing
                            </option>

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label
                            for="event_name"
                            class="form-label"
                        >
                            Select Event
                        </label>

                        <select
                            class="form-select"
                            id="event_name"
                            name="event_name"
                            required
                        >

                            <option value="">
                                Select an event
                            </option>

                            <?php foreach ($events as $event): ?>

                                <option
                                    value="<?php echo htmlspecialchars($event['session_title']); ?>"
                                    <?php

                                    if ($selected_event === $event['session_title']) {

                                        echo "selected";

                                    }

                                    ?>
                                >

                                    <?php echo htmlspecialchars($event['session_title']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="col-12">

                        <button
                            type="button"
                            class="register-btn"
                            id="registerButton"
                        >
                            Register for Event
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</section>


<!-- =========================
     CONFIRMATION MODAL
========================= -->

<div
    class="modal fade"
    id="confirmationModal"
    tabindex="-1"
    aria-labelledby="confirmationModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="confirmationModalLabel"
                >
                    Confirm Registration
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <p>
                    Please confirm that the information below is correct:
                </p>


                <div class="confirmation-details">

                    <strong>Student:</strong>

                    <span id="confirmStudent"></span>

                    <br>

                    <strong>Admission No:</strong>

                    <span id="confirmAdmission"></span>

                    <br>

                    <strong>Event:</strong>

                    <span id="confirmEvent"></span>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Go Back
                </button>


                <button
                    type="button"
                    class="btn btn-primary"
                    id="confirmSubmit"
                >
                    Yes, Submit Registration
                </button>

            </div>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const form =
        document.getElementById("registrationForm");

    const registerButton =
        document.getElementById("registerButton");

    const confirmSubmit =
        document.getElementById("confirmSubmit");

    const messageBox =
        document.getElementById("messageBox");


    function showError(title, message) {

        messageBox.innerHTML = `

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <strong>${title}</strong><br>

                ${message}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        `;

        window.scrollTo({

            top: 0,
            behavior: "smooth"

        });

    }


    const fields = [

        "student_name",
        "admission_number",
        "email",
        "phone",
        "course",
        "event_name"

    ];


    fields.forEach(function (fieldId) {

        const field =
            document.getElementById(fieldId);


        field.addEventListener("input", function () {

            this.classList.remove("is-invalid");

        });


        field.addEventListener("change", function () {

            this.classList.remove("is-invalid");

        });

    });


    registerButton.addEventListener("click", function () {

        const studentName =
            document.getElementById("student_name").value.trim();

        const admissionNumber =
            document.getElementById("admission_number").value.trim();

        const email =
            document.getElementById("email").value.trim();

        const phone =
            document.getElementById("phone").value.trim();

        const course =
            document.getElementById("course").value;

        const eventName =
            document.getElementById("event_name").value;


        messageBox.innerHTML = "";


        fields.forEach(function (fieldId) {

            document
                .getElementById(fieldId)
                .classList.remove("is-invalid");

        });


        if (studentName === "") {

            showError(
                "Registration Error!",
                "Please enter your student name."
            );

            document
                .getElementById("student_name")
                .classList.add("is-invalid");

            document
                .getElementById("student_name")
                .focus();

            return;

        }


        if (admissionNumber === "") {

            showError(
                "Registration Error!",
                "Please enter your admission number."
            );

            document
                .getElementById("admission_number")
                .classList.add("is-invalid");

            document
                .getElementById("admission_number")
                .focus();

            return;

        }


        if (email === "") {

            showError(
                "Registration Error!",
                "Please enter your email address."
            );

            document
                .getElementById("email")
                .classList.add("is-invalid");

            document
                .getElementById("email")
                .focus();

            return;

        }


        if (phone === "") {

            showError(
                "Registration Error!",
                "Please enter your phone number."
            );

            document
                .getElementById("phone")
                .classList.add("is-invalid");

            document
                .getElementById("phone")
                .focus();

            return;

        }


        if (course === "") {

            showError(
                "Registration Error!",
                "Please select your course."
            );

            document
                .getElementById("course")
                .classList.add("is-invalid");

            document
                .getElementById("course")
                .focus();

            return;

        }


        if (eventName === "") {

            showError(
                "Registration Error!",
                "Please select an event."
            );

            document
                .getElementById("event_name")
                .classList.add("is-invalid");

            document
                .getElementById("event_name")
                .focus();

            return;

        }


        if (studentName.length < 3) {

            showError(
                "Invalid Student Name!",
                "Student name must contain at least 3 characters."
            );

            document
                .getElementById("student_name")
                .classList.add("is-invalid");

            document
                .getElementById("student_name")
                .focus();

            return;

        }


        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


        if (!emailPattern.test(email)) {

            showError(
                "Invalid Email!",
                "Please enter a valid email address."
            );

            document
                .getElementById("email")
                .classList.add("is-invalid");

            document
                .getElementById("email")
                .focus();

            return;

        }


        const phonePattern =
            /^[0-9+\-\s]{10,20}$/;


        if (!phonePattern.test(phone)) {

            showError(
                "Invalid Phone Number!",
                "Please enter a valid phone number."
            );

            document
                .getElementById("phone")
                .classList.add("is-invalid");

            document
                .getElementById("phone")
                .focus();

            return;

        }


        document.getElementById("confirmStudent").textContent =
            studentName;

        document.getElementById("confirmAdmission").textContent =
            admissionNumber;

        document.getElementById("confirmEvent").textContent =
            eventName;


        const modalElement =
            document.getElementById("confirmationModal");


        const confirmationModal =
            bootstrap.Modal.getOrCreateInstance(modalElement);


        confirmationModal.show();

    });


    confirmSubmit.addEventListener("click", function () {

        form.submit();

    });

});

</script>


</body>

</html>

