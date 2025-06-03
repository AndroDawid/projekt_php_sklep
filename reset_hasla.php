<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $reset_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    $conn = mysqli_connect('localhost','root','','user_db');

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE users SET reset_code = ? WHERE username = ?");
    $stmt->bind_param("ss", $reset_code, $username);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    $generated = true;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Reset hasła</title>
    <link rel="stylesheet" href="css/logowanie.css">
</head>
<body>
<main>
    <section class="login-panel">
        <h2>Reset hasła – krok 1</h2>
        <?php if (!empty($generated)): ?>
            <p>Twój kod resetujący to: <strong><?= htmlspecialchars($reset_code) ?></strong></p>
            <p><a href="potwierdz_kod.php">Kliknij tutaj, aby przejść do zmiany hasła</a></p>
        <?php else: ?>
            <form method="post">
                <label for="username">Podaj nazwę użytkownika:</label>
                <input type="text" name="username" required>
                <button type="submit">Generuj kod</button>
            </form>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
