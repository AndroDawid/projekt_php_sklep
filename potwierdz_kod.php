<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $code = $_POST['code'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $conn = mysqli_connect('localhost','root','','user_db');

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT reset_code FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($db_code);
    $stmt->fetch();
    $stmt->close();

    if ($db_code === $code) {
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $username);
        $stmt->execute();
        $stmt->close();
        $success = "Hasło zostało zmienione.";
    } else {
        $error = "Nieprawidłowy kod.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Reset hasła – kod</title>
    <link rel="stylesheet" href="css/logowanie.css">
</head>
<body>
<main>
    <section class="login-panel">
        <h2>Reset hasła – krok 2</h2>
        <?php if (!empty($success)): ?>
            <p style="color:green;"><?= htmlspecialchars($success) ?></p>
            <p><a href="logowanie.php">Zaloguj się</a></p>
        <?php else: ?>
            <?php if (!empty($error)) echo "<p style='color:red;'>" . htmlspecialchars($error) . "</p>"; ?>
            <form method="post">
                <label>Nazwa użytkownika:</label>
                <input type="text" name="username" required>
                <label>Kod resetujący:</label>
                <input type="text" name="code" required>
                <label>Nowe hasło:</label>
                <input type="password" name="new_password" required>
                <button type="submit">Zmień hasło</button>
            </form>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
