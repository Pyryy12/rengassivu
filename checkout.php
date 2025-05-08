<?php
session_start();
$thankYouMessage = ""; // Alustetaan kiitosteksti tyhjäksi

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['address'], $_POST['email'])) {
    // Lasketaan tilauksen summa
    $totalSum = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalSum += $item['Hinta'];
        }
    }

    // Tallennetaan tilaus JSON-tiedostoon
    $order = [
        'Tilaaja' => htmlspecialchars($_POST['name']),
        'Osoite' => htmlspecialchars($_POST['address']),
        'Spost' => htmlspecialchars($_POST['email']),
        'Tuotteet' => $_SESSION['cart'],
        'Summa' => $totalSum,
        'Pvm' => date('Y-m-d H:i:s')
    ];

    $file = 'Ostokset.json';
    if (file_exists($file)) {
        $currentData = json_decode(file_get_contents($file), true);
    } else {
        $currentData = [];
    }

    $currentData[] = $order;
    file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT));

    // Näytetään kiitosteksti
    $thankYouMessage = "Kiitos ostoksestasi, " . htmlspecialchars($_POST['name']) . "!";

    // Tyhjennetään ostoskori
    unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="kassa.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="logo_dark.svg" alt="Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Etusivu</a></li>
                    <li><a href="index.php #footer">Yhteystiedot</a></li>
                    <li><a href="index.php #renkaat">Renkaat</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="checkout">
        <?php if (!empty($thankYouMessage)): ?>
            <div class="thank-you-container">
                <p class="thank-you-message"><?php echo $thankYouMessage; ?></p>
            </div>
        <?php else: ?>
            <!-- Vasemman puolen lomake -->
            <div class="form-container">
                <h3>Laskutustiedot</h3>
                <form method="POST" action="">
                    <label for="name">Nimi:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="address">Osoite:</label>
                    <input type="text" id="address" name="address" required>
                    <label for="email">Sähköposti:</label>
                    <input type="email" id="email" name="email" required>
                    <button type="submit">Maksa</button>
                </form>
            </div>

            <!-- Oikean puolen ostoskori -->
            <div class="cart-container">
                <h2>Kassa</h2>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Merkki</th>
                                <th>Malli</th>
                                <th>Tyyppi</th>
                                <th>Koko</th>
                                <th>Hinta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['Merkki']); ?></td>
                                    <td><?php echo htmlspecialchars($item['Malli']); ?></td>
                                    <td><?php echo htmlspecialchars($item['Tyyppi']); ?></td>
                                    <td><?php echo htmlspecialchars($item['Koko']); ?></td>
                                    <td><?php echo htmlspecialchars($item['Hinta']); ?> €</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <form method="POST" action="index.php">
                        <button type="submit" name="clear_cart">Tyhjennä kori</button>
                    </form>
                <?php else: ?>
                    <p>Korisi on tyhjä.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
