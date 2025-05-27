<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="css/logowanie.css">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <script src="js/script.js"></script>
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
            <h2>Logowanie</h2>
            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="username">Nazwa użytkownika</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Hasło</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Zaloguj</button>
            </form>
            <h2>Zarejestruj</h2>
            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="new_username">Nazwa użytkownika</label>
                    <input type="text" id="new_username" name="new_username" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Hasło</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <button type="submit">Zarejestruj</button>
            </form>
        </section>
    </main>
<footer>
    <p>&copy; 2025 Dawid Szelągiewicz Wszelkie prawa zastrzeżone.</p>
    <p>Ul.XYZ Miasto xx-xxx +48 000 000 000</p>
</footer>
</body>
</html>
