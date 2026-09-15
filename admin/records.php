<?php

session_start();

require_once "../config/db.php";

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit();
}

$message = "";
$messageType = "";


/* =========================
   DELETE REGISTRATION
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_registration"])) {

    $registration_id = intval($_POST["registration_id"]);

    if ($registration_id > 0) {

        $stmt = $conn->prepare(
            "DELETE FROM registrations WHERE registration_id = ?"
        );

        $stmt->bind_param("i", $registration_id);

        if ($stmt->execute()) {

            $message = "Registration deleted successfully.";
            $messageType = "success";

        } else {

            $message = "Unable to delete the registration.";
            $messageType = "danger";
        }

        $stmt->close();
    }
}


/* =========================
   UPDATE REGISTRATION
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_registration"])) {

    $registration_id = intval($_POST["registration_id"]);
    $student_name = trim($_POST["student_name"] ?? "");
    $admission_number = trim($_POST["admission_number"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $event_name = trim($_POST["event_name"] ?? "");

    if (
        $student_name === "" ||
        $admission_number === "" ||
        $email === "" ||
        $phone === "" ||
        $course === "" ||
        $event_name === ""
    ) {

        $message = "Please fill in all registration fields.";
        $messageType = "danger";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "danger";

    } else {

        $stmt = $conn->prepare(
            "UPDATE registrations
             SET student_name = ?,
                 admission_number = ?,
                 email = ?,
                 phone = ?,
                 course = ?,
                 event_name = ?
             WHERE registration_id = ?"
        );

        $stmt->bind_param(
            "ssssssi",
            $student_name,
            $admission_number,
            $email,
            $phone,
            $course,
            $event_name,
            $registration_id
        );

        if ($stmt->execute()) {

            $message = "Registration updated successfully.";
            $messageType = "success";

        } else {

            $message = "Unable to update the registration.";
            $messageType = "danger";
        }

        $stmt->close();
    }
}


/* =========================
   SEARCH AND FILTER
========================= */

$search = trim($_GET["search"] ?? "");
$eventFilter = trim($_GET["event"] ?? "");


/* =========================
   GET EVENTS FOR FILTER
========================= */

$eventQuery = $conn->query(
    "SELECT DISTINCT event_name
     FROM registrations
     ORDER BY event_name ASC"
);

$events = [];

if ($eventQuery) {

    while ($eventRow = $eventQuery->fetch_assoc()) {

        $events[] = $eventRow["event_name"];
    }
}


/* =========================
   GET TOTAL REGISTRATIONS
========================= */

$countQuery = $conn->query(
    "SELECT COUNT(*) AS total
     FROM registrations"
);

$countRow = $countQuery->fetch_assoc();

$totalRegistrations = $countRow["total"];


/* =========================
   GET REGISTRATIONS
========================= */

$sql = "SELECT
            registration_id,
            student_name,
            admission_number,
            email,
            phone,
            course,
            event_name,
            registration_date
        FROM registrations
        WHERE 1=1";

$params = [];
$types = "";


/* Search */

if ($search !== "") {

    $sql .= " AND (
        student_name LIKE ?
        OR admission_number LIKE ?
        OR email LIKE ?
        OR phone LIKE ?
        OR course LIKE ?
    )";

    $searchTerm = "%" . $search . "%";

    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;

    $types .= "sssss";
}


/* Event Filter */

if ($eventFilter !== "") {

    $sql .= " AND event_name = ?";

    $params[] = $eventFilter;

    $types .= "s";
}


$sql .= " ORDER BY registration_date DESC";


$stmt = $conn->prepare($sql);

if (!empty($params)) {

    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

$result = $stmt->get_result();


/* =========================
   VIEW REGISTRATION
========================= */

$viewRegistration = null;

if (isset($_GET["view"])) {

    $viewId = intval($_GET["view"]);

    if ($viewId > 0) {

        $viewStmt = $conn->prepare(
            "SELECT
                registration_id,
                student_name,
                admission_number,
                email,
                phone,
                course,
                event_name,
                registration_date
             FROM registrations
             WHERE registration_id = ?"
        );

        $viewStmt->bind_param("i", $viewId);

        $viewStmt->execute();

        $viewResult = $viewStmt->get_result();

        if ($viewResult->num_rows === 1) {

            $viewRegistration = $viewResult->fetch_assoc();
        }

        $viewStmt->close();
    }
}


/* =========================
   EDIT REGISTRATION
========================= */

$editRegistration = null;

if (isset($_GET["edit"])) {

    $editId = intval($_GET["edit"]);

    if ($editId > 0) {

        $editStmt = $conn->prepare(
            "SELECT
                registration_id,
                student_name,
                admission_number,
                email,
                phone,
                course,
                event_name,
                registration_date
             FROM registrations
             WHERE registration_id = ?"
        );

        $editStmt->bind_param("i", $editId);

        $editStmt->execute();

        $editResult = $editStmt->get_result();

        if ($editResult->num_rows === 1) {

            $editRegistration = $editResult->fetch_assoc();
        }

        $editStmt->close();
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

    <title>Registration Records | MMTC Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            background: #f5f7fb;
            font-family: Arial, sans-serif;
            color: #263238;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #212529;
            padding: 25px 15px;
            z-index: 1000;
        }

        .sidebar-brand {
            color: white;
            font-size: 23px;
            font-weight: 700;
            text-decoration: none;
            display: block;
            padding: 10px 15px 30px;
        }

        .sidebar-link {
            display: block;
            color: #ced4da;
            text-decoration: none;
            padding: 13px 15px;
            border-radius: 8px;
            margin-bottom: 6px;
        }

        .sidebar-link:hover {
            background: #343a40;
            color: white;
        }

        .sidebar-link.active {
            background: #0d6efd;
            color: white;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            padding: 18px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h5 {
            margin: 0;
            font-weight: 700;
        }

        .page-content {
            padding: 30px;
        }

        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #0d6efd;
        }

        .records-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #0d6efd;
            color: white;
            white-space: nowrap;
            padding: 14px 12px;
        }

        .table tbody td {
            padding: 13px 12px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f4f8ff;
        }

        .registration-id {
            color: #0d6efd;
            font-weight: 700;
        }

        .event-name {
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .page-title {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .filter-card {
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
        }

        .modal-header {
            background: #0d6efd;
            color: white;
        }

        .detail-label {
            font-weight: 700;
            color: #6c757d;
            font-size: 14px;
        }

        .detail-value {
            font-size: 16px;
            margin-bottom: 18px;
        }

        .mobile-menu {
            display: none;
        }

        @media (max-width: 900px) {

            .sidebar {
                transform: translateX(-100%);
                transition: 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu {
                display: block;
            }

            .page-content {
                padding: 20px 15px;
            }

            .topbar {
                padding: 15px;
            }
        }

    </style>

</head>

<body>


<!-- =========================
     SIDEBAR
========================= -->

<aside class="sidebar" id="sidebar">

    <a href="dashboard.php" class="sidebar-brand">
        MMTC Admin
    </a>

    <a href="dashboard.php" class="sidebar-link">
        🏠 Dashboard
    </a>

    <a href="sessions.php" class="sidebar-link">
        📅 Manage Sessions
    </a>

    <a href="records.php" class="sidebar-link active">
        📋 Registration Records
    </a>

    <a href="alerts.php" class="sidebar-link">
        🔔 Alerts
    </a>

    <a href="settings.php" class="sidebar-link">
        ⚙️ Settings
    </a>

    <a href="logout.php" class="sidebar-link">
        🚪 Logout
    </a>

</aside>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <!-- Topbar -->

    <div class="topbar">

        <div>

            <button
                class="btn btn-dark mobile-menu"
                onclick="toggleSidebar()"
            >
                ☰
            </button>

            <span class="ms-2">

                <h5 class="d-inline">
                    Registration Records
                </h5>

            </span>

        </div>


        <div>

            <span class="text-muted">

                Admin:

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $_SESSION["admin_username"] ?? "Administrator"
                    );
                    ?>
                </strong>

            </span>

        </div>

    </div>


    <!-- Page -->

    <div class="page-content">


        <h2 class="page-title">
            Registration Records
        </h2>

        <p class="text-muted mb-4">
            View and manage all student event registrations.
        </p>


        <!-- Message -->

        <?php if ($message !== "") { ?>

            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">

                <?php echo htmlspecialchars($message); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        <?php } ?>


        <!-- Statistics -->

        <div class="row mb-4">

            <div class="col-md-4">

                <div class="card stat-card">

                    <div class="card-body">

                        <p class="text-muted mb-1">
                            Total Registrations
                        </p>

                        <div class="stat-number">
                            <?php echo $totalRegistrations; ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Records Card -->

        <div class="card records-card">

            <div class="card-body p-4">


                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h4 class="fw-bold mb-1">
                            Student Registrations
                        </h4>

                        <p class="text-muted mb-0">
                            Search, filter and manage registrations.
                        </p>

                    </div>

                </div>


                <!-- Search and Filter -->

                <div class="filter-card mb-4">

                    <form method="GET" action="records.php">

                        <div class="row g-3">


                            <div class="col-lg-6">

                                <label class="form-label fw-bold">
                                    Search
                                </label>

                                <input
                                    type="search"
                                    name="search"
                                    class="form-control"
                                    placeholder="Student name, admission number, email, phone or course"
                                    value="<?php echo htmlspecialchars($search); ?>"
                                >

                            </div>


                            <div class="col-lg-4">

                                <label class="form-label fw-bold">
                                    Filter by Event
                                </label>

                                <select
                                    name="event"
                                    class="form-select"
                                >

                                    <option value="">
                                        All Events
                                    </option>

                                    <?php foreach ($events as $event) { ?>

                                        <option
                                            value="<?php echo htmlspecialchars($event); ?>"
                                            <?php
                                            if ($eventFilter === $event) {
                                                echo "selected";
                                            }
                                            ?>
                                        >

                                            <?php
                                            echo htmlspecialchars($event);
                                            ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                            <div class="col-lg-2 d-flex align-items-end">

                                <div class="w-100">

                                    <button
                                        type="submit"
                                        class="btn btn-primary w-100 mb-2"
                                    >
                                        Search
                                    </button>

                                    <a
                                        href="records.php"
                                        class="btn btn-outline-secondary w-100"
                                    >
                                        Clear
                                    </a>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>


                <!-- Table -->

                <div class="table-responsive table-container">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Student Name</th>

                                <th>Admission Number</th>

                                <th>Email</th>

                                <th>Phone</th>

                                <th>Course</th>

                                <th>Event</th>

                                <th>Registration Date</th>

                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php if ($result && $result->num_rows > 0) { ?>

                            <?php while ($row = $result->fetch_assoc()) { ?>

                                <tr>

                                    <td class="registration-id">

                                        #<?php
                                        echo htmlspecialchars(
                                            $row["registration_id"]
                                        );
                                        ?>

                                    </td>


                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $row["student_name"]
                                        );
                                        ?>

                                    </td>


                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $row["admission_number"]
                                        );
                                        ?>

                                    </td>


                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $row["email"]
                                        );
                                        ?>

                                    </td>


                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $row["phone"]
                                        );
                                        ?>

                                    </td>


                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $row["course"]
                                        );
                                        ?>

                                    </td>


                                    <td class="event-name">

                                        <?php
                                        echo htmlspecialchars(
                                            $row["event_name"]
                                        );
                                        ?>

                                    </td>


                                    <td>

                                        <?php
                                        echo date(
                                            "d M Y, h:i A",
                                            strtotime(
                                                $row["registration_date"]
                                            )
                                        );
                                        ?>

                                    </td>


                                    <td>

                                        <div class="action-buttons">


                                            <!-- View -->

                                            <a
                                                href="records.php?view=<?php echo $row["registration_id"]; ?>"
                                                class="btn btn-sm btn-info text-white"
                                            >
                                                View
                                            </a>


                                            <!-- Edit -->

                                            <a
                                                href="records.php?edit=<?php echo $row["registration_id"]; ?>"
                                                class="btn btn-sm btn-warning"
                                            >
                                                Edit
                                            </a>


                                            <!-- Delete -->

                                            <form
                                                method="POST"
                                                action="records.php"
                                                onsubmit="return confirm('Are you sure you want to delete this registration? This action cannot be undone.');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="registration_id"
                                                    value="<?php echo $row["registration_id"]; ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    name="delete_registration"
                                                    class="btn btn-sm btn-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-4"
                                >

                                    <div class="alert alert-info mb-0">

                                        <?php if ($search !== "" || $eventFilter !== "") { ?>

                                            No registration records match your search or filter.

                                        <?php } else { ?>

                                            No registration records available yet.

                                        <?php } ?>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================
     VIEW MODAL
========================= -->

<?php if ($viewRegistration !== null) { ?>

<div
    class="modal fade"
    id="viewModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Registration Details
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="row">


                    <div class="col-md-6">

                        <div class="detail-label">
                            Registration ID
                        </div>

                        <div class="detail-value">
                            #<?php
                            echo htmlspecialchars(
                                $viewRegistration["registration_id"]
                            );
                            ?>
                        </div>


                        <div class="detail-label">
                            Student Name
                        </div>

                        <div class="detail-value">
                            <?php
                            echo htmlspecialchars(
                                $viewRegistration["student_name"]
                            );
                            ?>
                        </div>


                        <div class="detail-label">
                            Admission Number
                        </div>

                        <div class="detail-value">
                            <?php
                            echo htmlspecialchars(
                                $viewRegistration["admission_number"]
                            );
                            ?>
                        </div>


                        <div class="detail-label">
                            Email
                        </div>

                        <div class="detail-value">
                            <?php
                            echo htmlspecialchars(
                                $viewRegistration["email"]
                            );
                            ?>
                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="detail-label">
                            Phone
                        </div>

                        <div class="detail-value">
                            <?php
                            echo htmlspecialchars(
                                $viewRegistration["phone"]
                            );
                            ?>
                        </div>


                        <div class="detail-label">
                            Course
                        </div>

                        <div class="detail-value">
                            <?php
                            echo htmlspecialchars(
                                $viewRegistration["course"]
                            );
                            ?>
                        </div>


                        <div class="detail-label">
                            Event
                        </div>

                        <div class="detail-value">
                            <?php
                            echo htmlspecialchars(
                                $viewRegistration["event_name"]
                            );
                            ?>
                        </div>


                        <div class="detail-label">
                            Registration Date
                        </div>

                        <div class="detail-value">

                            <?php
                            echo date(
                                "d M Y, h:i A",
                                strtotime(
                                    $viewRegistration["registration_date"]
                                )
                            );
                            ?>

                        </div>

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Close
                </button>

            </div>

        </div>

    </div>

</div>

<?php } ?>


<!-- =========================
     EDIT MODAL
========================= -->

<?php if ($editRegistration !== null) { ?>

<div
    class="modal fade"
    id="editModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Registration
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form method="POST" action="records.php">

                <div class="modal-body">


                    <input
                        type="hidden"
                        name="registration_id"
                        value="<?php echo $editRegistration["registration_id"]; ?>"
                    >


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label fw-bold">
                                Student Name
                            </label>

                            <input
                                type="text"
                                name="student_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($editRegistration["student_name"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-bold">
                                Admission Number
                            </label>

                            <input
                                type="text"
                                name="admission_number"
                                class="form-control"
                                value="<?php echo htmlspecialchars($editRegistration["admission_number"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-bold">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?php echo htmlspecialchars($editRegistration["email"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-bold">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="<?php echo htmlspecialchars($editRegistration["phone"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-bold">
                                Course
                            </label>

                            <input
                                type="text"
                                name="course"
                                class="form-control"
                                value="<?php echo htmlspecialchars($editRegistration["course"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-bold">
                                Event
                            </label>

                            <input
                                type="text"
                                name="event_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($editRegistration["event_name"]); ?>"
                                required
                            >

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        name="update_registration"
                        class="btn btn-primary"
                    >
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php } ?>


<!-- Bootstrap -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<script>

function toggleSidebar() {

    document
        .getElementById("sidebar")
        .classList
        .toggle("show");
}


/* Automatically open View modal */

<?php if ($viewRegistration !== null) { ?>

    document.addEventListener("DOMContentLoaded", function () {

        const modal = new bootstrap.Modal(
            document.getElementById("viewModal")
        );

        modal.show();

    });

<?php } ?>


/* Automatically open Edit modal */

<?php if ($editRegistration !== null) { ?>

    document.addEventListener("DOMContentLoaded", function () {

        const modal = new bootstrap.Modal(
            document.getElementById("editModal")
        );

        modal.show();

    });

<?php } ?>

</script>

</body>

</html>

<?php

$stmt->close();

$conn->close();

?>