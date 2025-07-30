<?php
session_start();
include 'baza.php';

$veza = new Baza();
$veza->spojiDB();

if (isset($_GET['obrisi'])) {
    $id = intval($_GET['obrisi']);

    $upit_delete = "DELETE FROM korisnik WHERE korisnik_id = $1";
    $rezultat_delete = pg_query_params($veza->spojiDB(), $upit_delete, array($id));

    if ($rezultat_delete) {
        $_SESSION['poruka'] = "Korisnik je uspješno obrisan.";
    } else {
        $_SESSION['poruka'] = "Greška prilikom brisanja korisnika.";
    }
    header("Location: korisnici.php");
    exit;
}

$upit = "
    SELECT k.korisnik_id, k.ime, k.prezime, k.korisnicko_ime, COALESCE(SUM(r.bodovi), 0) AS ukupno_bodova
    FROM korisnik k
    LEFT JOIN kviz_rezultat r ON k.korisnik_id = r.korisnik_id
    GROUP BY k.korisnik_id, k.ime, k.prezime, k.korisnicko_ime
    ORDER BY ukupno_bodova DESC
";

$rezultat = pg_query($veza->spojiDB(), $upit);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Korisnici i bodovi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/korisnici.css">
</head>
<body>
    <h2><i class="fa-solid fa-users"></i> Rang lista korisnika</h2>

    <?php
    if (isset($_SESSION['poruka'])) {
        echo "<p class='alert'>" . htmlspecialchars($_SESSION['poruka']) . "</p>";
        unset($_SESSION['poruka']);
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>Pozicija</th>
                <th>Korisničko ime</th>
                <th>Ime i Prezime</th>
                <th>Ukupno bodova</th>
                <th>Akcija</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $pozicija = 1;
            while ($red = pg_fetch_assoc($rezultat)) {
                echo "<tr>";
                echo "<td>" . $pozicija;
                if ($pozicija == 1) echo " <i class='fa-solid fa-medal'></i>";
                echo "</td>";
                echo "<td>" . htmlspecialchars($red['korisnicko_ime']) . "</td>";
                echo "<td>" . htmlspecialchars($red['ime']) . " " . htmlspecialchars($red['prezime']) . "</td>";
                echo "<td>" . $red['ukupno_bodova'] . "</td>";
                echo "<td><a href='?obrisi=" . $red['korisnik_id'] . "' class='delete-btn' onclick='return confirm(\"Jeste li sigurni da želite obrisati ovog korisnika?\")'><i class='fa-solid fa-trash'></i> Obriši</a></td>";
                echo "</tr>";
                $pozicija++;
            }
            ?>
        </tbody>
    </table>

    <div class="center">
        <a href="index.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Povratak</a>
    </div>
</body>
</html>
<?php
$veza->zatvoriDB();
?>
