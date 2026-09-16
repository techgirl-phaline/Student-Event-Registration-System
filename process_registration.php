
<?php

require_once "config/db.php";
/* Check whether student registration is open */

$registrationSettings = $conn->query(
    "SELECT registration_status
     FROM admins
     LIMIT 1"
);

if ($registrationSettings && $registrationSettings->num_rows === 1) {

    $registrationData = $registrationSettings->fetch_assoc();

    if ($registrationData["registration_status"] === "Closed") {
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>

            <meta charset="UTF-8">

            <meta
                name="viewport"
                content="width=device-width, initial-scale=1.0"
            >

            <title>Registration Closed | MMTC</title>

            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet"
            >

            <style>

                body {
                    background: #f5f7fb;
                    font-family: Arial, sans-serif;
                }

                .message-card {
                    max-width: 600px;
                    margin: 80px auto;
                    border: none;
                    border-radius: 18px;
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
                }

                .closed-icon {
                    font-size: 55px;
                }

                .page-title {
                    font-weight: 700;
                }

            </style>

        </head>

        <body>

        <div class="container">

            <div class="card message-card">

                <div class="card-body p-5 text-center">

                    <div class="closed-icon mb-3">
                        🔒
                    </div>

                    <h2 class="page-title text-danger mb-3">
                        Registration Currently Closed
                    </h2>

                    <p class="text-muted mb-4">
                        Student event registration is currently unavailable.
                        Please check again later or contact the administration
                        for more information.
                    </p>

                    <a
                        href="events.php"
                        class="btn btn-primary"
                    >
                        View Events
                    </a>

                    <a
                        href="index.php"
                        class="btn btn-outline-secondary ms-2"
                    >
                        Back to Home
                    </a>

                </div>

            </div>

        </div>

        </body>

        </html>

        <?php
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit();
}

$student_name = trim($_POST["student_name"] ?? "");
$admission_number = trim($_POST["admission_number"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$course = trim($_POST["course"] ?? "");
$event_name = trim($_POST["event_name"] ?? "");

$errors = [];


/* Validation */

if ($student_name === "") {
    $errors[] = "Student name is required.";
} elseif (strlen($student_name) < 3) {
    $errors[] = "Student name must be at least 3 characters.";
}

if ($admission_number === "") {
    $errors[] = "Admission number is required.";
}

if ($email === "") {
    $errors[] = "Email address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if ($phone === "") {
    $errors[] = "Phone number is required.";
} elseif (!preg_match("/^[0-9+\-\s]{10,20}$/", $phone)) {
    $errors[] = "Please enter a valid phone number.";
}

if ($course === "") {
    $errors[] = "Please select your course.";
}

if ($event_name === "") {
    $errors[] = "Please select an event.";
}


/* Check that selected event exists and is still available */

if ($event_name !== "") {

    $eventCheck = $conn->prepare(
        "SELECT session_id
         FROM sessions
         WHERE session_title = ?
         AND session_date >= CURDATE()
         AND status IN ('Upcoming', 'Active')
         LIMIT 1"
    );

    $eventCheck->bind_param("s", $event_name);
    $eventCheck->execute();

    $eventResult = $eventCheck->get_result();

    if ($eventResult->num_rows === 0) {
        $errors[] = "The selected event is no longer available for registration.";
    }

    $eventCheck->close();
}


/* Display validation errors */

if (!empty($errors)) {
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registration Error | MMTC</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .message-card {
            max-width: 600px;
            margin: 80px auto;
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .error-icon {
            font-size: 55px;
        }

        .page-title {
            font-weight: 700;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card message-card">

        <div class="card-body p-5 text-center">

            <div class="error-icon mb-3">
                ⚠️
            </div>

            <h2 class="page-title text-danger mb-3">
                Registration Failed
            </h2>

            <p class="text-muted">
                Please correct the following errors:
            </p>

            <div class="alert alert-danger text-start">

                <ul class="mb-0">

                    <?php foreach ($errors as $error) { ?>

                        <li>
                            <?php echo htmlspecialchars($error); ?>
                        </li>

                    <?php } ?>

                </ul>

            </div>

            <a
                href="register.php"
                class="btn btn-primary"
            >
                Return to Registration
            </a>

        </div>

    </div>

</div>

</body>

</html>

<?php

    exit();
}


/* Insert registration into database */

$sql = "INSERT INTO registrations
        (student_name, admission_number, email, phone, course, event_name)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registration Error | MMTC</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .message-card {
            max-width: 600px;
            margin: 80px auto;
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card message-card">

        <div class="card-body p-5 text-center">

            <div class="fs-1 mb-3">
                ❌
            </div>

            <h2 class="text-danger">
                Registration Failed
            </h2>

            <div class="alert alert-danger mt-3">

                Unable to prepare the registration request.

            </div>

            <a
                href="register.php"
                class="btn btn-primary"
            >
                Return to Registration
            </a>

        </div>

    </div>

</div>

</body>

</html>

<?php
    exit();
}


$stmt->bind_param(
    "ssssss",
    $student_name,
    $admission_number,
    $email,
    $phone,
    $course,
    $event_name
);


if ($stmt->execute()) {

    $registration_id = $stmt->insert_id;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registration Successful | MMTC</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .success-card {
            max-width: 650px;
            margin: 70px auto;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #d1e7dd;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
        }

        .success-title {
            font-weight: 700;
            color: #198754;
        }

        .details-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
        }

        .details-box p {
            margin-bottom: 10px;
        }

        .details-box strong {
            color: #212529;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card success-card">

        <div class="card-body p-5 text-center">

            <div class="success-icon">
                ✓
            </div>

            <h2 class="success-title mb-3">
                Registration Successful!
            </h2>

            <p class="text-muted mb-4">
                Your event registration has been successfully
                submitted and saved in the system.
            </p>

            <div class="details-box mb-4">

                <p>
                    <strong>Registration ID:</strong>
                    #<?php echo htmlspecialchars($registration_id); ?>
                </p>

                <p>
                    <strong>Student Name:</strong>
                    <?php echo htmlspecialchars($student_name); ?>
                </p>

                <p>
                    <strong>Admission Number:</strong>
                    <?php echo htmlspecialchars($admission_number); ?>
                </p>

                <p>
                    <strong>Course:</strong>
                    <?php echo htmlspecialchars($course); ?>
                </p>

                <p>
                    <strong>Event:</strong>
                    <?php echo htmlspecialchars($event_name); ?>
                </p>

            </div>

            <div class="alert alert-success">

                Your registration has been recorded successfully.

            </div>

            <div class="d-flex justify-content-center gap-2 flex-wrap">

                <a
                    href="events.php"
                    class="btn btn-outline-primary"
                >
                    View Events
                </a>

                <a
                    href="register.php"
                    class="btn btn-primary"
                >
                    Register Another Student
                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>

<?php

} else {

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registration Error | MMTC</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .message-card {
            max-width: 600px;
            margin: 80px auto;
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card message-card">

        <div class="card-body p-5 text-center">

            <div class="fs-1 mb-3">
                ❌
            </div>

            <h2 class="text-danger">
                Registration Failed
            </h2>

            <div class="alert alert-danger mt-3">

                We could not save your registration.
                Please try again.

            </div>

            <a
                href="register.php"
                class="btn btn-primary"
            >
                Try Again
            </a>

        </div>

    </div>

</div>

</body>

</html>

<?php

}

$stmt->close();
$conn->close();

?>

