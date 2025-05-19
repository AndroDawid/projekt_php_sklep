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

// Dodawanie produktu do koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && !isset($_POST['remove']) && !isset($_POST['buy'])) {
    $product_id = $_POST['product_id'];

    $result = $conn->query("SELECT id_produktu, nazwa, cena, zdjecie FROM produkty WHERE id_produktu = $product_id");
    $product = $result->fetch_assoc();

    if ($product) {
        $_SESSION['koszyk'][] = $product;
    }
}

// Usuwanie produktu z koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];

    foreach ($_SESSION['koszyk'] as $key => $item) {
        if ($item['id_produktu'] == $product_id) {
            unset($_SESSION['koszyk'][$key]);
            break;
        }
    }

    // Resetowanie kluczy tablicy
    $_SESSION['koszyk'] = array_values($_SESSION['koszyk']);
}

// Opróżnianie koszyka po kliknięciu "Kupuję"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {
    $_SESSION['koszyk'] = [];
}
?>

    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Koszyk</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    </head>
    <body>
    <header>
        <img src="img/logo.png" alt="Logo">
        <h1>Nazwa</h1>
        <nav>
            <ul>
                <li><a href="index.php">Strona Główna</a></li>
                <li><a href="logowanie.html">Logowanie</a></li>
                <li><a href="koszyk.php">Kosz</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Koszyk</h2>
        <?php
        if (!empty($_SESSION['koszyk'])) {
            foreach ($_SESSION['koszyk'] as $item) {
                echo '<div class="cart-item">';
                echo '<img src="' . $item['zdjecie'] . '" alt="' . $item['nazwa'] . '" style="max-width: 100px; height: auto;">';
                echo '<h3>' . $item['nazwa'] . '</h3>';
                echo '<p>Cena: ' . $item['cena'] . ' zł</p>';
                echo '<form method="POST">';
                echo '<input type="hidden" name="product_id" value="' . $item['id_produktu'] . '">';
                echo '<button type="submit" name="remove" value="1">Usuń</button>';
                echo '</form>';
                echo '</div>';
            }
            echo '<form method="POST">';
            echo '<button type="submit" name="buy" value="1">Kupuję</button>';
            echo '</form>';
        } else {
            echo '<p>Koszyk jest pusty.</p>';
        }
        ?>
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