<?php
session_start();

$conn = mysqli_connect('localhost','root','','user_db');

if ($conn->connect_error) {
    die("Błąd: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $new_username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: logowanie.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
