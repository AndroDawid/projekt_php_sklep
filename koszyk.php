<?php
session_start();

$host = 'localhost';
$db = 'user_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id']) && !isset($_POST['remove'])) {
        $product_id = intval($_POST['product_id']);

        $stmt = $conn->prepare("SELECT id_produktu, nazwa, cena, zdjecie FROM produkty WHERE id_produktu = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $_SESSION['koszyk'][] = $product;
            header('Location: index.php');
            exit;
        }
        $stmt->close();
    }

    if (isset($_POST['remove']) && isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);

        foreach ($_SESSION['koszyk'] as $key => $item) {
            if ($item['id_produktu'] == $product_id) {
                unset($_SESSION['koszyk'][$key]);
                break;
            }
        }

        $_SESSION['koszyk'] = array_values($_SESSION['koszyk']);
    }

    if (isset($_POST['buy'])) {
        $_SESSION['koszyk'] = [];
    }
}
?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Koszyk</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
        <script src="js/script.js"></script>
    </head>
    <body>
    <header>
        <img src="img/logo.png" alt="Logo">
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
        <h2>Koszyk</h2>
        <?php if (!empty($_SESSION['koszyk'])): ?>
            <?php foreach ($_SESSION['koszyk'] as $item): ?>
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($item['zdjecie']) ?>" alt="<?= htmlspecialchars($item['nazwa']) ?>" style="max-width: 100px; height: auto;">
                    <h3><?= htmlspecialchars($item['nazwa']) ?></h3>
                    <p>Cena: <?= htmlspecialchars($item['cena']) ?> zł</p>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['id_produktu']) ?>">
                        <button type="submit" name="remove" value="1">Usuń</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <form method="POST">
                <button type="submit" name="buy" value="1">Kupuję</button>
            </form>
        <?php else: ?>
            <p>Koszyk jest pusty.</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2025 Dawid Szelągiewicz Wszelkie prawa zastrzeżone.</p>
        <p>Ul.XYZ Miasto xx-xxx +48 000 000 000</p>
    </footer>
    </body>
    </html>
<?php
$conn->close();
?>