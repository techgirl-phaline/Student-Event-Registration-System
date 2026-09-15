
<?php

session_start();

require_once "../config/db.php";

if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {

        $error = "Please enter your username and password.";

    } else {

        $sql = "SELECT admin_id, username, password
                FROM admins
                WHERE username = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("s", $username);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows === 1) {

                $admin = $result->fetch_assoc();

                if (password_verify($password, $admin["password"])) {

                    session_regenerate_id(true);

                    $_SESSION["admin_logged_in"] = true;
                    $_SESSION["admin_id"] = $admin["admin_id"];
                    $_SESSION["admin_username"] = $admin["username"];

                    header("Location: dashboard.php");
                    exit();

                } else {

                    $error = "Invalid username or password.";

                }

            } else {

                $error = "Invalid username or password.";

            }

            $stmt->close();

        } else {

            $error = "Unable to process login. Please try again.";

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

    <title>Admin Login | MMTC Events</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(
                135deg,
                #123b68,
                #1c79c9
            );
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.18);
        }

        .login-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #eaf4ff;
            color: #1c79c9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .login-title {
            color: #123b68;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }

        .login-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #263238;
        }

        .form-control {
            padding: 12px 14px;
            border-radius: 10px;
        }

        .form-control:focus {
            border-color: #1c79c9;
            box-shadow: 0 0 0 0.2rem rgba(28, 121, 201, 0.15);
        }

        .login-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: #1c79c9;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .login-btn:hover {
            background: #145f9f;
        }

        .back-link {
            text-align: center;
            margin-top: 22px;
        }

        .back-link a {
            color: #1c79c9;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .admin-badge {
            text-align: center;
            margin-bottom: 20px;
        }

    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <div class="login-icon">
            🔐
        </div>

        <div class="admin-badge">
            <span class="badge text-bg-primary">
                ADMINISTRATOR
            </span>
        </div>

        <h2 class="login-title">
            Admin Login
        </h2>

        <p class="login-subtitle">
            Access the MMTC Event Registration Management System
        </p>

        <?php if ($error !== ""): ?>

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <strong>Login Failed!</strong><br>

                <?php echo htmlspecialchars($error); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="mb-3">

                <label
                    for="username"
                    class="form-label"
                >
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="Enter admin username"
                    required
                >

            </div>

            <div class="mb-4">

                <label
                    for="password"
                    class="form-label"
                >
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter admin password"
                    required
                >

            </div>

            <button
                type="submit"
                class="login-btn"
            >
                Login to Admin Panel
            </button>

        </form>

        <div class="back-link">

            <a href="../index.php">
                ← Back to Website
            </a>

        </div>

    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>

