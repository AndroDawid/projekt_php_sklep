<?php
$host = 'localhost';
$db = 'user_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

$zapytanie = mysqli_query($conn, "SELECT id_produktu, nazwa, cena, ilosc, zdjecie FROM `produkty`;");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Strona Główna - Nazwa</title>
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
                <li><a href="koszyk.php">Koszyk</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Produkty</h2>
        <?php
        if (mysqli_num_rows($zapytanie) > 0) {
            while ($wiersz = mysqli_fetch_assoc($zapytanie)) {
                echo '<div class="product">';
                echo '<img src="' . $wiersz['zdjecie'] . '" alt="' . $wiersz['nazwa'] . '">';
                echo '<h3>' . $wiersz['nazwa'] . '</h3>';
                echo '<p>Cena: ' . $wiersz['cena'] . ' zł</p>';
                echo '<p>Ilość: ' . $wiersz['ilosc'] . '</p>';
                echo '<form method="POST" action="koszyk.php">';
                echo '<input type="hidden" name="product_id" value="' . $wiersz['id_produktu'] . '">';
                echo '<button type="submit">Dodaj do koszyka</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo '<p>Brak produktów w bazie danych.</p>';
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
mysqli_close($conn);
?>