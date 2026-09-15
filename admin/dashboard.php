<?php

session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";

$countQuery = $conn->query(
    "SELECT COUNT(*) AS total FROM registrations"
);

$countRow = $countQuery->fetch_assoc();

$totalRegistrations = $countRow["total"];

$eventQuery = $conn->query(
    "SELECT COUNT(DISTINCT event_name) AS total
     FROM registrations"
);

$eventRow = $eventQuery->fetch_assoc();

$totalEvents = $eventRow["total"];

$recentQuery = $conn->query(
    "SELECT student_name, course, event_name, registration_date
     FROM registrations
     ORDER BY registration_date DESC
     LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard | MMTC Events</title>

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

        .stat-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: #eaf4ff;
            color: #1c79c9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: 700;
            color: #123b68;
        }

        .stat-label {
            color: #6c757d;
            margin-bottom: 0;
        }

        .action-card,
        .recent-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .action-card h4,
        .recent-card h4 {
            color: #123b68;
            font-weight: 700;
        }

        .action-btn {
            border-radius: 10px;
            padding: 11px 18px;
            font-weight: 600;
        }

        .table thead th {
            background: #123b68;
            color: white;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
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

            <a
                href="dashboard.php"
                class="active"
            >

                <span class="sidebar-icon">
                    🏠
                </span>

                Dashboard

            </a>

        </li>


        <!-- MANAGE SESSIONS -->

        <li>

            <a href="sessions.php">

                <span class="sidebar-icon">
                    📅
                </span>

                Manage Sessions

            </a>

        </li>


        <!-- REGISTRATION RECORDS -->

        <li>

            <a href="records.php">

                <span class="sidebar-icon">
                    📋
                </span>

                Registration Records

            </a>

        </li>


        <!-- ALERTS -->

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

            <a href="settings.php">

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
                Admin Dashboard
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


    <!-- DASHBOARD -->

    <section class="dashboard-section">

        <div class="container-fluid">


            <!-- WELCOME -->

            <div class="mb-4">

                <h2 class="welcome-title">
                    Welcome, Administrator
                </h2>

                <p class="welcome-text">
                    Manage student registrations and college sessions
                    from the admin panel.
                </p>

            </div>


            <!-- STAT CARDS -->

            <div class="row g-4 mb-4">


                <div class="col-md-4">

                    <div class="card stat-card h-100">

                        <div class="card-body">

                            <div class="stat-icon">
                                👥
                            </div>

                            <div class="stat-number">
                                <?php echo $totalRegistrations; ?>
                            </div>

                            <p class="stat-label">
                                Total Registrations
                            </p>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card stat-card h-100">

                        <div class="card-body">

                            <div class="stat-icon">
                                📅
                            </div>

                            <div class="stat-number">
                                <?php echo $totalEvents; ?>
                            </div>

                            <p class="stat-label">
                                Sessions With Registrations
                            </p>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card stat-card h-100">

                        <div class="card-body">

                            <div class="stat-icon">
                                🔐
                            </div>

                            <div class="stat-number">
                                Active
                            </div>

                            <p class="stat-label">
                                Admin System Status
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- SESSION MANAGEMENT -->

            <div class="card action-card mb-4">

                <div class="card-body p-4">

                    <h4>
                        Session Management
                    </h4>

                    <p class="text-muted">
                        Create, update and manage college sessions
                        and events available for student registration.
                    </p>

                    <a
                        href="sessions.php"
                        class="btn btn-primary action-btn"
                    >
                        Manage Sessions
                    </a>

                    <a
                        href="records.php"
                        class="btn btn-outline-primary action-btn ms-2"
                    >
                        View Registrations
                    </a>

                </div>

            </div>


            <!-- RECENT REGISTRATIONS -->

            <div class="card recent-card">

                <div class="card-body p-4">

                    <h4 class="mb-1">
                        Recent Registrations
                    </h4>

                    <p class="text-muted mb-4">
                        The latest students who registered
                        for college sessions.
                    </p>


                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th>
                                        Student Name
                                    </th>

                                    <th>
                                        Course
                                    </th>

                                    <th>
                                        Session
                                    </th>

                                    <th>
                                        Registration Date
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php if ($recentQuery && $recentQuery->num_rows > 0): ?>

                                <?php while ($row = $recentQuery->fetch_assoc()): ?>

                                    <tr>

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
                                                $row["course"]
                                            );
                                            ?>
                                        </td>

                                        <td>
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

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>

                                    <td
                                        colspan="4"
                                        class="text-center text-muted py-4"
                                    >
                                        No registrations available yet.
                                    </td>

                                </tr>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>


                    <a
                        href="records.php"
                        class="btn btn-outline-primary action-btn"
                    >
                        View All Registration Records
                    </a>

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