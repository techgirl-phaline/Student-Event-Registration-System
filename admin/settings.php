<?php

session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";

$message = "";
$messageType = "";

$adminId = $_SESSION["admin_id"] ?? 0;


/* GET CURRENT ADMIN SETTINGS */

$adminQuery = $conn->prepare(
    "SELECT username, registration_status
     FROM admins
     WHERE admin_id = ?"
);

$adminQuery->bind_param("i", $adminId);
$adminQuery->execute();

$adminResult = $adminQuery->get_result();
$admin = $adminResult->fetch_assoc();

$adminQuery->close();

$currentUsername = $admin["username"] ?? "";
$currentRegistrationStatus = $admin["registration_status"] ?? "Open";


/* UPDATE SETTINGS */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $currentPassword = $_POST["current_password"] ?? "";
    $newPassword = $_POST["new_password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";
    $registrationStatus = $_POST["registration_status"] ?? "Open";


    /* CHECK REGISTRATION STATUS */

    if (!in_array($registrationStatus, ["Open", "Closed"], true)) {
        $registrationStatus = "Open";
    }


    /* VALIDATE USERNAME */

    if ($username === "") {

        $message = "Username cannot be empty.";
        $messageType = "danger";

    } elseif ($currentPassword === "") {

        $message = "Enter your current password.";
        $messageType = "danger";

    } else {

        /* VERIFY CURRENT PASSWORD */

        $passwordQuery = $conn->prepare(
            "SELECT password
             FROM admins
             WHERE admin_id = ?"
        );

        $passwordQuery->bind_param("i", $adminId);
        $passwordQuery->execute();

        $passwordResult = $passwordQuery->get_result();
        $adminData = $passwordResult->fetch_assoc();

        $passwordQuery->close();


        if (
            !$adminData ||
            !password_verify(
                $currentPassword,
                $adminData["password"]
            )
        ) {

            $message = "Current password is incorrect.";
            $messageType = "danger";

        } elseif (
            $newPassword !== "" &&
            strlen($newPassword) < 8
        ) {

            $message = "New password must contain at least 8 characters.";
            $messageType = "danger";

        } elseif (
            $newPassword !== "" &&
            $newPassword !== $confirmPassword
        ) {

            $message = "New password and confirmation password do not match.";
            $messageType = "danger";

        } else {

            /* CHECK USERNAME AVAILABILITY */

            $checkUsername = $conn->prepare(
                "SELECT admin_id
                 FROM admins
                 WHERE username = ?
                 AND admin_id <> ?"
            );

            $checkUsername->bind_param(
                "si",
                $username,
                $adminId
            );

            $checkUsername->execute();

            $usernameResult = $checkUsername->get_result();

            $checkUsername->close();


            if ($usernameResult->num_rows > 0) {

                $message = "That username is already being used.";
                $messageType = "danger";

            } else {

                /* UPDATE WITH NEW PASSWORD */

                if ($newPassword !== "") {

                    $hashedPassword = password_hash(
                        $newPassword,
                        PASSWORD_DEFAULT
                    );

                    $updateQuery = $conn->prepare(
                        "UPDATE admins
                         SET username = ?,
                             password = ?,
                             registration_status = ?
                         WHERE admin_id = ?"
                    );

                    $updateQuery->bind_param(
                        "sssi",
                        $username,
                        $hashedPassword,
                        $registrationStatus,
                        $adminId
                    );


                /* UPDATE WITHOUT CHANGING PASSWORD */

                } else {

                    $updateQuery = $conn->prepare(
                        "UPDATE admins
                         SET username = ?,
                             registration_status = ?
                         WHERE admin_id = ?"
                    );

                    $updateQuery->bind_param(
                        "ssi",
                        $username,
                        $registrationStatus,
                        $adminId
                    );
                }


                /* SAVE SETTINGS */

                if ($updateQuery->execute()) {

                    $_SESSION["admin_username"] = $username;

                    $currentUsername = $username;
                    $currentRegistrationStatus = $registrationStatus;

                    $message = "Settings updated successfully.";
                    $messageType = "success";

                } else {

                    $message = "Unable to update settings.";
                    $messageType = "danger";

                }

                $updateQuery->close();
            }
        }
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

<title>Settings | MMTC Events</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<style>

    body {
        margin: 0;
        background: #f4f7fb;
        font-family: Arial, sans-serif;
        color: #263238;
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        height: 100vh;
        background: #123b68;
        padding: 25px 15px;
        z-index: 1000;
        overflow-y: auto;
    }

    .sidebar-brand {
        color: white;
        text-decoration: none;
        font-size: 22px;
        font-weight: 700;
        display: block;
        padding: 0 15px;
        margin-bottom: 35px;
    }

    .sidebar-brand span {
        display: block;
        font-size: 12px;
        font-weight: 400;
        color: #c8d9ea;
        margin-top: 5px;
    }

    .sidebar-section-title {
        color: #9db5cd;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 15px;
        margin-bottom: 10px;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin-bottom: 6px;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #dce8f3;
        text-decoration: none;
        padding: 12px 15px;
        border-radius: 10px;
        font-size: 15px;
        transition: 0.2s;
    }

    .sidebar-menu a:hover {
        background: rgba(255, 255, 255, 0.10);
        color: white;
    }

    .sidebar-menu a.active {
        background: #1c79c9;
        color: white;
    }

    .sidebar-icon {
        width: 24px;
        text-align: center;
        font-size: 18px;
    }

    .sidebar-divider {
        border: 0;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        margin: 25px 15px;
    }

    .logout-link {
        color: #ffd5d5 !important;
    }

    .logout-link:hover {
        background: rgba(220, 53, 69, 0.18) !important;
        color: #fff !important;
    }

    .main-content {
        margin-left: 250px;
        min-height: 100vh;
    }

    .topbar {
        height: 75px;
        background: white;
        border-bottom: 1px solid #e6eaf0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 35px;
    }

    .page-title {
        color: #123b68;
        font-weight: 700;
        margin: 0;
        font-size: 22px;
    }

    .admin-user {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #495057;
        font-weight: 600;
    }

    .admin-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #eaf4ff;
        color: #1c79c9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .dashboard-section {
        padding: 35px;
    }

    .welcome-title {
        color: #123b68;
        font-weight: 700;
    }

    .welcome-text {
        color: #6c757d;
    }

    .settings-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    }

    .settings-card h4 {
        color: #123b68;
        font-weight: 700;
    }

    .form-label {
        color: #123b68;
        font-weight: 600;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 12px 14px;
        border: 1px solid #d9e1ea;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1c79c9;
        box-shadow: 0 0 0 0.2rem rgba(28, 121, 201, 0.15);
    }

    .update-btn {
        background: #1c79c9;
        border: none;
        border-radius: 10px;
        padding: 11px 22px;
        font-weight: 600;
    }

    .update-btn:hover {
        background: #123b68;
    }

    .info-card {
        border: none;
        border-radius: 18px;
        background: #eaf4ff;
        color: #123b68;
    }

    .info-icon {
        font-size: 35px;
        margin-bottom: 10px;
    }

    .registration-card {
        border: none;
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    }

    .status-box {
        border-radius: 12px;
        padding: 15px;
        background: #f4f7fb;
    }

    .mobile-menu-btn {
        display: none;
        border: none;
        background: #123b68;
        color: white;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 20px;
    }

    .sidebar-overlay {
        display: none;
    }

    .footer {
        margin-top: 40px;
        padding: 25px;
        background: #212529;
        color: white;
        text-align: center;
    }

    @media (max-width: 992px) {

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

        .mobile-menu-btn {
            display: inline-block;
        }

        .sidebar-overlay.show {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 999;
        }

        .topbar {
            padding: 0 20px;
        }

        .dashboard-section {
            padding: 25px 20px;
        }

    }

    @media (max-width: 576px) {

        .page-title {
            font-size: 18px;
        }

        .admin-user span {
            display: none;
        }

        .dashboard-section {
            padding: 20px 12px;
        }

    }

</style>

</head>

<body>

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
    onclick="closeSidebar()"
></div>


<!-- SIDEBAR -->

<aside class="sidebar" id="sidebar">

    <a
        href="dashboard.php"
        class="sidebar-brand"
    >

        MMTC ADMIN

        <span>
            Event Registration System
        </span>

    </a>


    <div class="sidebar-section-title">
        Main Menu
    </div>


    <ul class="sidebar-menu">

        <li>

            <a href="dashboard.php">

                <span class="sidebar-icon">
                    🏠
                </span>

                Dashboard

            </a>

        </li>


        <li>

            <a href="sessions.php">

                <span class="sidebar-icon">
                    📅
                </span>

                Manage Sessions

            </a>

        </li>


        <li>

            <a href="records.php">

                <span class="sidebar-icon">
                    📋
                </span>

                Registration Records

            </a>

        </li>


        <li>

            <a href="alerts.php">

                <span class="sidebar-icon">
                    🔔
                </span>

                Alerts

            </a>

        </li>

    </ul>


    <hr class="sidebar-divider">


    <div class="sidebar-section-title">
        System
    </div>


    <ul class="sidebar-menu">

        <li>

            <a
                href="settings.php"
                class="active"
            >

                <span class="sidebar-icon">
                    ⚙️
                </span>

                Settings

            </a>

        </li>


        <li>

            <a
                href="logout.php"
                class="logout-link"
            >

                <span class="sidebar-icon">
                    🚪
                </span>

                Logout

            </a>

        </li>

    </ul>

</aside>


<!-- MAIN CONTENT -->

<div class="main-content">


    <!-- TOP BAR -->

    <header class="topbar">

        <div class="d-flex align-items-center gap-3">

            <button
                class="mobile-menu-btn"
                onclick="openSidebar()"
            >
                ☰
            </button>

            <h1 class="page-title">
                Settings
            </h1>

        </div>


        <div class="admin-user">

            <div class="admin-avatar">
                👤
            </div>

            <span>

                <?php

                echo htmlspecialchars(
                    $_SESSION["admin_username"] ?? "Administrator"
                );

                ?>

            </span>

        </div>

    </header>


    <!-- SETTINGS -->

    <section class="dashboard-section">

        <div class="container-fluid">


            <div class="mb-4">

                <h2 class="welcome-title">
                    Administrator Settings
                </h2>

                <p class="welcome-text">
                    Manage your administrator account, registration access and security settings.
                </p>

            </div>


            <?php if ($message !== ""): ?>

                <div
                    class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show"
                    role="alert"
                >

                    <?php echo htmlspecialchars($message); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            <?php endif; ?>


            <div class="row g-4">


                <!-- ACCOUNT SETTINGS -->

                <div class="col-lg-8">

                    <div class="card settings-card">

                        <div class="card-body p-4">

                            <h4 class="mb-1">
                                Account & Security
                            </h4>

                            <p class="text-muted mb-4">
                                Update your administrator username or password.
                            </p>


                            <form
                                method="POST"
                                action=""
                            >


                                <div class="mb-3">

                                    <label
                                        for="username"
                                        class="form-label"
                                    >
                                        Admin Username
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="username"
                                        name="username"
                                        value="<?php echo htmlspecialchars($currentUsername); ?>"
                                        maxlength="50"
                                        required
                                    >

                                </div>


                                <hr class="my-4">


                                <h5 class="mb-3">
                                    Change Password
                                </h5>


                                <div class="mb-3">

                                    <label
                                        for="current_password"
                                        class="form-label"
                                    >
                                        Current Password
                                    </label>

                                    <input
                                        type="password"
                                        class="form-control"
                                        id="current_password"
                                        name="current_password"
                                        placeholder="Enter current password"
                                        required
                                    >

                                </div>


                                <div class="mb-3">

                                    <label
                                        for="new_password"
                                        class="form-label"
                                    >
                                        New Password
                                    </label>

                                    <input
                                        type="password"
                                        class="form-control"
                                        id="new_password"
                                        name="new_password"
                                        placeholder="Leave blank to keep current password"
                                        minlength="8"
                                    >

                                    <small class="text-muted">
                                        Password must contain at least 8 characters.
                                    </small>

                                </div>


                                <div class="mb-4">

                                    <label
                                        for="confirm_password"
                                        class="form-label"
                                    >
                                        Confirm New Password
                                    </label>

                                    <input
                                        type="password"
                                        class="form-control"
                                        id="confirm_password"
                                        name="confirm_password"
                                        placeholder="Repeat new password"
                                        minlength="8"
                                    >

                                </div>


                                <hr class="my-4">


                                <!-- REGISTRATION SETTINGS -->

                                <h5 class="mb-2">
                                    Registration Settings
                                </h5>

                                <p class="text-muted mb-3">
                                    Control whether students are allowed to submit new event registrations.
                                </p>


                                <div class="status-box mb-4">

                                    <label
                                        for="registration_status"
                                        class="form-label"
                                    >
                                        Registration Status
                                    </label>

                                    <select
                                        class="form-select"
                                        id="registration_status"
                                        name="registration_status"
                                    >

                                        <option
                                            value="Open"
                                            <?php echo $currentRegistrationStatus === "Open" ? "selected" : ""; ?>
                                        >
                                            Open - Students can register
                                        </option>

                                        <option
                                            value="Closed"
                                            <?php echo $currentRegistrationStatus === "Closed" ? "selected" : ""; ?>
                                        >
                                            Closed - Registration unavailable
                                        </option>

                                    </select>

                                </div>


                                <button
                                    type="submit"
                                    class="btn btn-primary update-btn"
                                >
                                    Save Changes
                                </button>


                            </form>

                        </div>

                    </div>

                </div>


                <!-- RIGHT SIDE -->

                <div class="col-lg-4">


                    <!-- SYSTEM INFORMATION -->

                    <div class="card info-card">

                        <div class="card-body p-4">

                            <div class="info-icon">
                                🏫
                            </div>

                            <h5 class="fw-bold">
                                MMTC Event System
                            </h5>

                            <p class="mb-2">
                                Macmillan Medical Training College
                            </p>

                            <p class="mb-2">
                                Student Event Registration System
                            </p>

                            <hr>

                            <p class="mb-0">

                                <strong>System Status:</strong>

                                <span class="text-success">
                                    Active
                                </span>

                            </p>

                        </div>

                    </div>


                    <!-- SECURITY TIP -->

                    <div class="card settings-card mt-4">

                        <div class="card-body p-4">

                            <h5
                                class="fw-bold"
                                style="color:#123b68;"
                            >
                                Security Tip
                            </h5>

                            <p class="text-muted mb-0">
                                Keep your administrator password private
                                and use a strong password that is difficult
                                for others to guess.
                            </p>

                        </div>

                    </div>


                </div>


            </div>


        </div>

    </section>


    <!-- FOOTER -->

    <footer class="footer">

        <p class="mb-0">

            &copy; <?php echo date("Y"); ?>

            Macmillan Medical Training College -

            Student Event Registration System

        </p>

    </footer>


</div>


<script>

    function openSidebar() {

        document
            .getElementById("sidebar")
            .classList
            .add("show");

        document
            .getElementById("sidebarOverlay")
            .classList
            .add("show");

    }


    function closeSidebar() {

        document
            .getElementById("sidebar")
            .classList
            .remove("show");

        document
            .getElementById("sidebarOverlay")
            .classList
            .remove("show");

    }

</script>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>

<?php

$conn->close();

?>