
<?php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";

$message = "";
$messageType = "";

/* CREATE SESSION */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_session"])) {

    $title = trim($_POST["session_title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $date = $_POST["session_date"] ?? "";
    $time = $_POST["session_time"] ?? "";
    $venue = trim($_POST["venue"] ?? "");

    if ($title === "" || $description === "" || $date === "" || $time === "" || $venue === "") {

        $message = "Please fill in all fields.";
        $messageType = "danger";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO sessions 
            (session_title, description, session_date, session_time, venue, status)
            VALUES (?, ?, ?, ?, ?, 'Upcoming')"
        );

        $stmt->bind_param("sssss", $title, $description, $date, $time, $venue);

       if ($stmt->execute()) {

    $notificationMessage =
        "A new event, " . $title .
        ", has been added. The event will take place on " .
        date("d M Y", strtotime($date)) .
        " at " . date("h:i A", strtotime($time)) .
        " at " . $venue .
        ". Register now to participate.";

    $notify = $conn->prepare(
        "INSERT INTO notifications
        (registration_id, event_name, message, notification_type, audience)
        VALUES (NULL, ?, ?, 'New Event', 'All')"
    );

    $notify->bind_param(
        "ss",
        $title,
        $notificationMessage
    );

    $notify->execute();

    $notify->close();

           $message =
            "Session created successfully. All students have been notified.";

        $messageType = "success";

        } else {

            $message = "Failed to create session.";
            $messageType = "danger";
        }

              $stmt->close();
    }
}


/* UPDATE SESSION */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_session"])) {

    $session_id = intval($_POST["session_id"] ?? 0);
    $title = trim($_POST["session_title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $date = $_POST["session_date"] ?? "";
    $time = $_POST["session_time"] ?? "";
    $venue = trim($_POST["venue"] ?? "");

    if ($session_id <= 0 || $title === "" || $description === "" || $date === "" || $time === "" || $venue === "") {

        $message = "Please provide all session details.";
        $messageType = "danger";

    } else {

        /*
         If students are already registered, keep the original
         event title so existing registrations remain connected.
        */
        $check = $conn->prepare(
            "SELECT COUNT(*) AS total
             FROM registrations
             WHERE event_name = (
                 SELECT session_title FROM sessions WHERE session_id = ?
             )"
        );

        $check->bind_param("i", $session_id);
        $check->execute();

        $result = $check->get_result();
        $registrationData = $result->fetch_assoc();
        $registeredCount = intval($registrationData["total"]);

        $check->close();

        if ($registeredCount > 0) {

            $stmt = $conn->prepare(
                "UPDATE sessions
                 SET description = ?, session_date = ?, session_time = ?, venue = ?
                 WHERE session_id = ?"
            );

            $stmt->bind_param(
                "ssssi",
                $description,
                $date,
                $time,
                $venue,
                $session_id
            );

        } else {

            $stmt = $conn->prepare(
                "UPDATE sessions
                 SET session_title = ?, description = ?, session_date = ?, session_time = ?, venue = ?
                 WHERE session_id = ?"
            );

            $stmt->bind_param(
                "sssssi",
                $title,
                $description,
                $date,
                $time,
                $venue,
                $session_id
            );
        }

        if ($stmt->execute()) {

            if ($registeredCount > 0) {
                $message = "Session updated. The event title was kept unchanged because students are already registered.";
            } else {
                $message = "Session updated successfully.";
            }

            $messageType = "success";

        } else {

            $message = "Failed to update session.";
            $messageType = "danger";
        }

        $stmt->close();
    }
}


/* CANCEL SESSION */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cancel_session"])) {

    $session_id = intval($_POST["session_id"] ?? 0);

    if ($session_id <= 0) {

        $message = "Invalid session.";
        $messageType = "danger";

    } else {

        $stmt = $conn->prepare(
            "SELECT session_title, session_date
             FROM sessions
             WHERE session_id = ?"
        );

        $stmt->bind_param("i", $session_id);
        $stmt->execute();

        $sessionResult = $stmt->get_result();
        $session = $sessionResult->fetch_assoc();

        $stmt->close();

        if (!$session) {

            $message = "Session not found.";
            $messageType = "danger";

        } else {

            $eventName = $session["session_title"];
            $eventDate = date("d M Y", strtotime($session["session_date"]));

            /* Find all registered students */
            $stmt = $conn->prepare(
                "SELECT registration_id
                 FROM registrations
                 WHERE event_name = ?"
            );

            $stmt->bind_param("s", $eventName);
            $stmt->execute();

            $registeredStudents = $stmt->get_result();

            $studentsNotified = 0;

            while ($student = $registeredStudents->fetch_assoc()) {

                $registration_id = intval($student["registration_id"]);

                $notificationMessage =
                    "Event Cancelled — " . $eventName .
                    " scheduled for " . $eventDate .
                    " has been cancelled. Please check the events page for future events.";

                $notify = $conn->prepare(
                    "INSERT INTO notifications
                    (registration_id, event_name, message, notification_type)
                    VALUES (?, ?, ?, 'Event Cancellation')"
                );

                $notify->bind_param(
                    "iss",
                    $registration_id,
                    $eventName,
                    $notificationMessage
                );

                if ($notify->execute()) {
                    $studentsNotified++;
                }

                $notify->close();
            }

            $stmt->close();

            /* Change event status to Cancelled */
            $stmt = $conn->prepare(
                "UPDATE sessions
                 SET status = 'Cancelled'
                 WHERE session_id = ?"
            );

            $stmt->bind_param("i", $session_id);

            if ($stmt->execute()) {

                $message =
                    "Event cancelled successfully. " .
                    $studentsNotified .
                    " registered student(s) were notified.";

                $messageType = "success";

            } else {

                $message = "Failed to cancel event.";
                $messageType = "danger";
            }

            $stmt->close();
        }
    }
}


/* DELETE SESSION */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_session"])) {

    $session_id = intval($_POST["session_id"] ?? 0);

    if ($session_id <= 0) {

        $message = "Invalid session.";
        $messageType = "danger";

    } else {

        $stmt = $conn->prepare(
            "SELECT session_title
             FROM sessions
             WHERE session_id = ?"
        );

        $stmt->bind_param("i", $session_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $session = $result->fetch_assoc();

        $stmt->close();

        if (!$session) {

            $message = "Session not found.";
            $messageType = "danger";

        } else {

            $eventName = $session["session_title"];

            $stmt = $conn->prepare(
                "SELECT COUNT(*) AS total
                 FROM registrations
                 WHERE event_name = ?"
            );

            $stmt->bind_param("s", $eventName);
            $stmt->execute();

            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            $registeredCount = intval($data["total"]);

            $stmt->close();

            if ($registeredCount > 0) {

                $message =
                    "This event cannot be deleted because students are already registered.";

                $messageType = "warning";

            } else {

                $stmt = $conn->prepare(
                    "DELETE FROM sessions
                     WHERE session_id = ?"
                );

                $stmt->bind_param("i", $session_id);

                if ($stmt->execute()) {
                    $message = "Session deleted successfully.";
                    $messageType = "success";
                } else {
                    $message = "Failed to delete session.";
                    $messageType = "danger";
                }

                $stmt->close();
            }
        }
    }
}


/* GET SESSIONS */
$sessions = $conn->query(
    "SELECT 
        s.session_id,
        s.session_title,
        s.description,
        s.session_date,
        s.session_time,
        s.venue,
        s.status,
        COUNT(r.registration_id) AS registrations
     FROM sessions s
     LEFT JOIN registrations r
        ON r.event_name = s.session_title
     GROUP BY
        s.session_id,
        s.session_title,
        s.description,
        s.session_date,
        s.session_time,
        s.venue,
        s.status
     ORDER BY s.session_date ASC, s.session_time ASC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Sessions | MMTC</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #123b68;
            color: white;
            padding: 25px 15px;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 35px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 15px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.15);
        }

        .main {
            margin-left: 250px;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 18px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.06);
        }

        .table th {
            background: #123b68;
            color: white;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-action {
            margin: 2px;
        }

        .status-badge {
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-upcoming {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .status-active {
            background: #e7f7ed;
            color: #198754;
        }

        .status-completed {
            background: #eeeeee;
            color: #555;
        }

        .status-cancelled {
            background: #ffe7e7;
            color: #dc3545;
        }

        @media (max-width: 900px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 15px;
            }

        }

    </style>

</head>

<body>

<div class="sidebar">

    <h3>MMTC Admin</h3>

    <a href="dashboard.php">Dashboard</a>

    <a href="sessions.php" class="active">
        Manage Sessions
    </a>

    <a href="records.php">
        Registration Records
    </a>

    <a href="alerts.php">
        Alerts
    </a>

    <a href="settings.php">
        Settings
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<div class="main">

    <div class="topbar">

        <h4 class="mb-1">
            Manage Sessions
        </h4>

        <p class="text-muted mb-0">
            Create, edit, cancel and manage student events.
        </p>

    </div>


    <?php if ($message !== ""): ?>

        <div class="alert alert-<?= htmlspecialchars($messageType) ?> alert-dismissible fade show">

            <?= htmlspecialchars($message) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


    <!-- CREATE SESSION -->

    <div class="card mb-4">

        <div class="card-body p-4">

            <h5 class="mb-4">
                Create New Session
            </h5>

            <form method="POST">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Session Title
                        </label>

                        <input
                            type="text"
                            name="session_title"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Date
                        </label>

                        <input
                            type="date"
                            name="session_date"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Time
                        </label>

                        <input
                            type="time"
                            name="session_time"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Venue
                        </label>

                        <input
                            type="text"
                            name="venue"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="2"
                            required
                        ></textarea>

                    </div>

                </div>


                <button
                    type="submit"
                    name="create_session"
                    class="btn btn-primary mt-4"
                >
                    Create Session
                </button>

            </form>

        </div>

    </div>


    <!-- SESSIONS TABLE -->

    <div class="card">

        <div class="card-body p-4">

            <h5 class="mb-4">
                All Sessions
            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Session</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Venue</th>
                            <th>Registrations</th>
                            <th>Status</th>
                            <th>Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if ($sessions && $sessions->num_rows > 0): ?>

                        <?php while ($row = $sessions->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?= $row["session_id"] ?>
                                </td>

                                <td>

                                    <strong>
                                        <?= htmlspecialchars($row["session_title"]) ?>
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        <?= htmlspecialchars($row["description"]) ?>
                                    </small>

                                </td>

                                <td>
                                    <?= date("d M Y", strtotime($row["session_date"])) ?>
                                </td>

                                <td>
                                    <?= date("h:i A", strtotime($row["session_time"])) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row["venue"]) ?>
                                </td>

                                <td>
                                    <?= $row["registrations"] ?>
                                </td>

                                <td>

                                    <?php
                                    $statusClass = "status-upcoming";

                                    if ($row["status"] === "Active") {
                                        $statusClass = "status-active";
                                    } elseif ($row["status"] === "Completed") {
                                        $statusClass = "status-completed";
                                    } elseif ($row["status"] === "Cancelled") {
                                        $statusClass = "status-cancelled";
                                    }
                                    ?>

                                    <span class="status-badge <?= $statusClass ?>">
                                        <?= htmlspecialchars($row["status"]) ?>
                                    </span>

                                </td>

                                <td>

                                    <button
                                        class="btn btn-sm btn-outline-primary btn-action"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        onclick='editSession(
                                            <?= json_encode($row["session_id"]) ?>,
                                            <?= json_encode($row["session_title"]) ?>,
                                            <?= json_encode($row["description"]) ?>,
                                            <?= json_encode($row["session_date"]) ?>,
                                            <?= json_encode($row["session_time"]) ?>,
                                            <?= json_encode($row["venue"]) ?>
                                        )'
                                    >
                                        Edit
                                    </button>


                                    <?php if ($row["status"] !== "Cancelled" && $row["status"] !== "Completed"): ?>

                                        <button
                                            class="btn btn-sm btn-outline-warning btn-action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cancelModal"
                                            onclick="setCancelSession(<?= $row['session_id'] ?>, '<?= htmlspecialchars(addslashes($row['session_title'])) ?>')"
                                        >
                                            Cancel
                                        </button>

                                    <?php endif; ?>


                                    <button
                                        class="btn btn-sm btn-outline-danger btn-action"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        onclick="setDeleteSession(<?= $row['session_id'] ?>, '<?= htmlspecialchars(addslashes($row['session_title'])) ?>')"
                                    >
                                        Delete
                                    </button>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="8" class="text-center text-muted py-4">
                                No sessions have been created yet.
                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- EDIT MODAL -->

<div class="modal fade" id="editModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Session
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <input
                        type="hidden"
                        name="session_id"
                        id="edit_session_id"
                    >


                    <div class="mb-3">

                        <label class="form-label">
                            Session Title
                        </label>

                        <input
                            type="text"
                            name="session_title"
                            id="edit_session_title"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="edit_description"
                            class="form-control"
                            rows="3"
                            required
                        ></textarea>

                    </div>


                    <div class="row">

                        <div class="col-md-4">

                            <label class="form-label">
                                Date
                            </label>

                            <input
                                type="date"
                                name="session_date"
                                id="edit_session_date"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Time
                            </label>

                            <input
                                type="time"
                                name="session_time"
                                id="edit_session_time"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Venue
                            </label>

                            <input
                                type="text"
                                name="venue"
                                id="edit_venue"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>

                    <button
                        type="submit"
                        name="update_session"
                        class="btn btn-primary">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- CANCEL MODAL -->

<div class="modal fade" id="cancelModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST">

                <div class="modal-header">

                    <h5 class="modal-title text-warning">
                        Cancel Event
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <input
                        type="hidden"
                        name="session_id"
                        id="cancel_session_id"
                    >

                    <p>
                        Are you sure you want to cancel:
                    </p>

                    <strong id="cancel_event_name"></strong>

                    <p class="mt-3 text-muted">
                        All students registered for this event will receive
                        a cancellation notification.
                    </p>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        No, Keep Event
                    </button>

                    <button
                        type="submit"
                        name="cancel_session"
                        class="btn btn-warning">
                        Yes, Cancel Event
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- DELETE MODAL -->

<div class="modal fade" id="deleteModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST">

                <div class="modal-header">

                    <h5 class="modal-title text-danger">
                        Delete Session
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <input
                        type="hidden"
                        name="session_id"
                        id="delete_session_id"
                    >

                    <p>
                        Are you sure you want to delete:
                    </p>

                    <strong id="delete_event_name"></strong>

                    <p class="mt-3 text-danger">
                        This action cannot be undone.
                    </p>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        name="delete_session"
                        class="btn btn-danger">
                        Delete
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

function editSession(id, title, description, date, time, venue) {

    document.getElementById("edit_session_id").value = id;

    document.getElementById("edit_session_title").value = title;

    document.getElementById("edit_description").value = description;

    document.getElementById("edit_session_date").value = date;

    document.getElementById("edit_session_time").value = time;

    document.getElementById("edit_venue").value = venue;
}


function setCancelSession(id, name) {

    document.getElementById("cancel_session_id").value = id;

    document.getElementById("cancel_event_name").textContent = name;
}


function setDeleteSession(id, name) {

    document.getElementById("delete_session_id").value = id;

    document.getElementById("delete_event_name").textContent = name;
}

</script>

</body>
</html>
