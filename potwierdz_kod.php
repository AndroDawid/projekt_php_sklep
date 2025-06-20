<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $code = $_POST['code'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $conn = mysqli_connect('localhost','root','','user_db');

    if ($conn->connect_error) {
        die("Błąd: " . $conn->connect_error);
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
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
</head>
<body>
<header>
    <a href="index.php"><img src="img/logo.png" alt="Logo"></a>
    <a href="index.php"><h1>Primolek</h1></a>
    <div id="clock"></div>
    <nav>
        <ul>
            <li><a href="index.php">Strona Główna</a></li>
            <?php if (!isset($_SESSION['username'])): ?>
                <li><a href="logowanie.php">Logowanie</a></li>
            <?php endif; ?>
            <li><a href="koszyk.php">Koszyk</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="logout.php">Wyloguj się</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
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
<footer>
    <p>&copy; 2025 Dawid Szelągiewicz Wszelkie prawa zastrzeżone.</p>
    <p>Ul.XYZ Miasto xx-xxx +48 000 000 000</p>
</footer>
</body>
</html>
