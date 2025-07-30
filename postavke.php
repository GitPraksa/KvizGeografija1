<?php
session_start();
include 'baza.php';

$ime = "";
$prezime = "";
$korisnicko_ime = "";

if (isset($_SESSION['korisnik_id'])) {
    $veza = new Baza();
    $db = $veza->spojiDB();

    $upit = "SELECT ime, prezime, korisnicko_ime FROM korisnik WHERE korisnik_id = $1";
    $rezultat = pg_query_params($db, $upit, array($_SESSION['korisnik_id']));

    if ($rezultat && pg_num_rows($rezultat) === 1) {
        $podaci = pg_fetch_assoc($rezultat);
        $ime = $podaci['ime'];
        $prezime = $podaci['prezime'];
        $korisnicko_ime = $podaci['korisnicko_ime'];
    }
    $ukupni_bodovi = 0;
    $broj_igara = 0;



    $korisnik_id = (int) $_SESSION['korisnik_id'];

    $upit2 = "SELECT COALESCE(SUM(bodovi), 0) AS ukupno_bodova, COUNT(*) AS broj_igara
             FROM kviz_rezultat
             WHERE korisnik_id = $1";
    $rezultat2 = pg_query_params($db, $upit2, array($korisnik_id));

    if ($rezultat && pg_num_rows($rezultat2) === 1) {
        $red = pg_fetch_assoc($rezultat2);
        $ukupni_bodovi = $red['ukupno_bodova'];
        $broj_igara = $red['broj_igara'];
    }


    $veza->zatvoriDB();
}
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Postavke korisnika</title>
    <link rel="stylesheet" href="css/stylePostavke.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>

    <div class="container">
        <header class="header">
            <h1><i class="fa-solid fa-gear"></i> Postavke računa</h1>
        </header>

        <div class="settings-card">
            <h2 class="card-title"><i class="fa-solid fa-user-pen"></i> Uredi korisničke podatke</h2>
            <?php
            if (!empty($_SESSION['poruke_podaci'])) {
                echo "<div class='poruka'>" . $_SESSION['poruke_podaci'] . "</div>";
                unset($_SESSION['poruke_podaci']);
            }
            ?>
            <form action="biblioteke/svojstvaProvjera.php" method="post">
                <input type="text" name="ime" placeholder="Ime" value="<?php echo htmlspecialchars($ime); ?>"
                    required />
                <input type="text" name="prezime" placeholder="Prezime"
                    value="<?php echo htmlspecialchars($prezime); ?>" required />
                <input type="text" name="korisnicko_ime" placeholder="Korisničko ime"
                    value="<?php echo htmlspecialchars($korisnicko_ime); ?>" required />
                <button type="submit" class="btn main-btn"><i class="fa-solid fa-save"></i> Spremi promjene</button>
            </form>
        </div>

        <div class="settings-card">
            <h2 class="card-title"><i class="fa-solid fa-lock"></i> Promjena lozinke</h2>
            <?php
            if (!empty($_SESSION['poruke_lozinka'])) {
                echo "<div class='poruka'>" . $_SESSION['poruke_lozinka'] . "</div>";
                unset($_SESSION['poruke_lozinka']);
            }
            ?>
            <form action="biblioteke/svojstvaProvjera.php" method="post">
                <input type="password" name="stara_lozinka" placeholder="Stara lozinka" required />
                <input type="password" name="nova_lozinka" placeholder="Nova lozinka" required />
                <input type="password" name="ponovljena_lozinka" placeholder="Ponovite novu lozinku" required />
                <button type="submit" name="promijeni_lozinku" class="btn main-btn"><i class="fa-solid fa-key"></i>
                    Promijeni lozinku</button>
            </form>
        </div>

        <div class="settings-card">
            <h2 class="card-title"><i class="fa-solid fa-chart-line"></i> Statistika i povijest igre</h2>
            <ul class="stats">
                <li><i class="fa-solid fa-trophy"></i> Najbolji rezultat: <strong><?php echo $ukupni_bodovi; ?>
                        bodova</strong></li>
                <li><i class="fa-solid fa-gamepad"></i> Odigrano igara: <strong><?php echo $broj_igara; ?></strong></li>
            </ul>

        </div>

        <div class="settings-card danger-zone">
            <h2 class="card-title"><i class="fa-solid fa-triangle-exclamation"></i> Brisanje računa</h2>
            <p>Ova akcija je <strong>nepovratna</strong>. Ako obrišete račun, svi vaši podaci će biti trajno izbrisani.
            </p>
            <form action="biblioteke/svojstvaProvjera.php" method="post"
                onsubmit="return confirm('Jeste li sigurni da želite izbrisati račun? Ova radnja je nepovratna.');">
                <input type="hidden" name="obrisi_racun" value="da" />
                <button type="submit" class="btn delete-btn">
                    <i class="fa-solid fa-user-slash"></i> Obriši račun
                </button>
            </form>
        </div>

        <div class="back-section">
            <a href="index.php" class="back-home"><i class="fa-solid fa-arrow-left"></i> Povratak na početnu
                stranicu</a>
        </div>
    </div>

</body>

</html>