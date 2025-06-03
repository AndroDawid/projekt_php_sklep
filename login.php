<?php
session_start();

$conn = mysqli_connect('localhost','root','','user_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            echo "Nieprawidłowe hasło.";
            echo "<br>";
            echo '<a href="logowanie.php">Powrót do strony logowania</a>';
}
    } else {
        echo "Nie znaleziono użytkownika.";
        echo "<br>";
        echo '<a href="logowanie.php">Powrót do strony logowania</a>';
}

    $stmt->close();
}

$conn->close();
?>