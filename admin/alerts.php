<?php

session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

require_once "../config/db.php";

$notificationQuery = $conn->query(
    "SELECT notification_id,
            event_name,
            message,
            notification_type,
            audience,
            is_read,
            created_at
     FROM notifications
     ORDER BY created_at DESC"
);

$totalNotifications = 0;

$countQuery = $conn->query(
    "SELECT COUNT(*) AS total
     FROM notifications"
);

if ($countQuery) {
    $countRow = $countQuery->fetch_assoc();
    $totalNotifications = $countRow["total"];
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Alerts | MMTC Events</title>

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

    .alert-summary {
        border: none;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    }

    .summary-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        background: #eaf4ff;
        color: #1c79c9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .summary-number {
        font-size: 28px;
        font-weight: 700;
        color: #123b68;
    }

    .summary-label {
        color: #6c757d;
        margin: 0;
    }

    .alerts-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    }

    .alerts-card h4 {
        color: #123b68;
        font-weight: 700;
    }

    .notification-item {
        border: 1px solid #e6eaf0;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 15px;
        background: white;
        transition: 0.2s;
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
    }

    .notification-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        background: #eaf4ff;
        color: #1c79c9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .notification-title {
        color: #123b68;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .notification-message {
        color: #495057;
        margin-bottom: 8px;
        line-height: 1.6;
    }

    .notification-date {
        color: #6c757d;
        font-size: 13px;
    }

    .empty-alerts {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-icon {
        font-size: 50px;
        margin-bottom: 15px;
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

        .notification-item {
            padding: 15px;
        }

    }

</style>
```

</head>

<body>

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
    onclick="closeSidebar()"
></div>

<!-- SIDEBAR -->

<aside class="sidebar" id="sidebar">

```
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

        <a
            href="alerts.php"
            class="active"
        >

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
```

</aside>

<!-- MAIN CONTENT -->

<div class="main-content">

```
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
            Alerts
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


<!-- ALERTS CONTENT -->

<section class="dashboard-section">

    <div class="container-fluid">


        <!-- INTRODUCTION -->

        <div class="mb-4">

            <h2 class="welcome-title">
                System Alerts
            </h2>

            <p class="welcome-text">
                View notifications generated from new events
                and event cancellations.
            </p>

        </div>


        <!-- SUMMARY -->

        <div class="row g-4 mb-4">

            <div class="col-md-4">

                <div class="card alert-summary h-100">

                    <div class="card-body d-flex align-items-center gap-3">

                        <div class="summary-icon">
                            🔔
                        </div>

                        <div>

                            <div class="summary-number">
                                <?php echo $totalNotifications; ?>
                            </div>

                            <p class="summary-label">
                                Total Alerts
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- NOTIFICATIONS -->

        <div class="card alerts-card">

            <div class="card-body p-4">

                <h4 class="mb-1">
                    Recent Alerts
                </h4>

                <p class="text-muted mb-4">
                    Notifications generated by the event management system.
                </p>


                <?php if ($notificationQuery && $notificationQuery->num_rows > 0): ?>

                    <?php while ($notification = $notificationQuery->fetch_assoc()): ?>

                        <div class="notification-item">

                            <div class="d-flex gap-3">

                                <div class="notification-icon">

                                    <?php

                                    if (
                                        $notification["notification_type"]
                                        === "Event Cancellation"
                                    ) {

                                        echo "❌";

                                    } else {

                                        echo "📢";

                                    }

                                    ?>

                                </div>


                                <div class="flex-grow-1">

                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

                                        <div class="notification-title">

                                            <?php

                                            echo htmlspecialchars(
                                                $notification["notification_type"]
                                            );

                                            ?>

                                        </div>


                                        <?php

                                        if (
                                            $notification["notification_type"]
                                            === "Event Cancellation"
                                        ) {

                                            echo '<span class="badge bg-danger">Cancellation</span>';

                                        } else {

                                            echo '<span class="badge bg-primary">New Event</span>';

                                        }

                                        ?>

                                    </div>


                                    <div class="notification-message">

                                        <?php

                                        echo htmlspecialchars(
                                            $notification["message"]
                                        );

                                        ?>

                                    </div>


                                    <div class="d-flex flex-wrap gap-3 notification-date">

                                        <span>
                                            📅 Event:
                                            <?php

                                            echo htmlspecialchars(
                                                $notification["event_name"]
                                            );

                                            ?>
                                        </span>


                                        <span>
                                            👥 Audience:
                                            <?php

                                            echo htmlspecialchars(
                                                $notification["audience"]
                                            );

                                            ?>
                                        </span>


                                        <span>
                                            🕒
                                            <?php

                                            echo date(
                                                "d M Y, h:i A",
                                                strtotime(
                                                    $notification["created_at"]
                                                )
                                            );

                                            ?>
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endwhile; ?>


                <?php else: ?>

                    <div class="empty-alerts">

                        <div class="empty-icon">
                            🔔
                        </div>

                        <h5>
                            No Alerts Yet
                        </h5>

                        <p class="mb-0">
                            Notifications will appear here when
                            new events are created or events are cancelled.
                        </p>

                    </div>

                <?php endif; ?>


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
```

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
