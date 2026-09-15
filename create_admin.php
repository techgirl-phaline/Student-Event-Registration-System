
<?php

require_once "config/db.php";

$username = "admin";
$password = "MMTC@2026";

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, password) VALUES (?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo "Admin account created successfully.";
} else {
    echo "Error creating admin account: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>

