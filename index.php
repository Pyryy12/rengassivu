<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "renkaat";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchTerm = $conn->real_escape_string($_POST['search']);
    $sql = "SELECT * FROM renkaat WHERE Merkki LIKE '%$searchTerm%' OR Malli LIKE '%$searchTerm%' OR Tyyppi LIKE '%$searchTerm%' OR Koko LIKE '%$searchTerm%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Renkaat</title>
    <link rel="stylesheet" href="sivu.css">
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
                    <li><a href="#footer">Yhteystiedot</a></li>
                    <li><a href="#renkaat">Renkaat</a></li>
                </ul>
            </nav>
        </div>
    </header>

    

    <div class="tausta">
        <div class="component">
            <p class="laadukkaat-kes-talvi">
                <br />Laadukkaat kesä-, talvi- ja ympärivuotiset renkaat kilpailukykyiseen hintaan – nopeasti ja asiantuntevasti.
            </p>
            <div class="katso-tarjoukset">
                <div class="overlap-group">
                    <div class="text-wrapper">KATSO TARJOUKSET</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mainos1">
        <div class="component">
            <p class="mainos-text">
                <br />Kevään parhaat rengastarjoukset!
            </p>
        </div>
    </div>

    <div class="mainos2">
        <div class="component">
            <p class="mainos-text">
                <br />Talven parhaat rengastarjoukset!
            </p>
        </div>
    </div>
        
    <div class="search">
        <h2 id="renkaat">Hae Renkaat</h2>
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Hae merkkiä, mallia, tyyppiä tai kokoa">
            <button type="submit">Hae</button>
        </form>
    </div>

    <div class="results">
        <h2>Hakutulokset</h2>
        <?php if (!empty($searchResults)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Merkki</th>
                        <th>Malli</th>
                        <th>Tyyppi</th>
                        <th>Koko</th>
                        <th>Hinta</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Merkki']); ?></td>
                            <td><?php echo htmlspecialchars($row['Malli']); ?></td>
                            <td><?php echo htmlspecialchars($row['Tyyppi']); ?></td>
                            <td><?php echo htmlspecialchars($row['Koko']); ?></td>
                            <td><?php echo htmlspecialchars($row['Hinta']); ?> €</td>
                            <td><?php echo htmlspecialchars($row['Saldo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Ei hakutuloksia.</p>
        <?php endif; ?>
    </div>

    <div class="video">
        <h2>Video</h2>
        <iframe src="https://www.youtube.com/embed/d_LD4LAy81w?si=HnT93L42nF0HOtNc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>

    <footer id="footer">
        <div class="footer-container"> 
            Mustapään Auto Oy<br>
            Mustat Renkaat<br>
            Kosteenkatu 1, 86300 Oulainen<br>
            Puh. 040-7128158<br>
            email. myyntimies@mustatrenkaat.net<br>
        </div>

        <div class="footer-kartta"> 
            
        </div>
    </footer>
</body>
</html>