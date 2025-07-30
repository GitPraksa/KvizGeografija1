<?php
session_start();
include '../baza.php';

$veza = new Baza();
$db = $veza->spojiDB();

if (!isset($_SESSION['korisnik_id'])) {
    $_SESSION['poruke_podaci'] = "<span style='color:red;'>Greška: korisnik nije prijavljen.</span>";
    header("Location: ../postavke.php");
    exit;
}

$korisnik_id = (int) $_SESSION['korisnik_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['obrisi_racun']) && $_POST['obrisi_racun'] === 'da') {
        $upit_delete = "DELETE FROM korisnik WHERE korisnik_id = $1";
        $rezultat_delete = pg_query_params($db, $upit_delete, array($korisnik_id));

        if ($rezultat_delete) {
            session_destroy();
            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['poruke_podaci'] = "<span style='color:red;'>Greška pri brisanju korisnika: " . pg_last_error($db) . "</span>";
            header("Location: ../postavke.php");
            exit;
        }
    }

    if (isset($_POST['ime'], $_POST['prezime'], $_POST['korisnicko_ime']) && !isset($_POST['promijeni_lozinku']) && !isset($_POST['obrisi_racun'])) {
        $ime = trim($_POST['ime']);
        $prezime = trim($_POST['prezime']);
        $korisnicko_ime = trim($_POST['korisnicko_ime']);

        if (!preg_match("/^[A-ZČĆŽŠĐ][a-zčćžšđ]+$/u", $ime)) {
            $_SESSION['poruke_podaci'] = "<span style='color:red;'>Ime mora početi velikim slovom i sadržavati samo slova!</span>";
            header("Location: ../postavke.php");
            exit;
        }

        if (!preg_match("/^[A-ZČĆŽŠĐ][a-zčćžšđ]+$/u", $prezime)) {
            $_SESSION['poruke_podaci'] = "<span style='color:red;'>Prezime mora početi velikim slovom i sadržavati samo slova!</span>";
            header("Location: ../postavke.php");
            exit;
        }

        if (strlen($korisnicko_ime) < 5) {
            $_SESSION['poruke_podaci'] = "<span style='color:red;'>Korisničko ime mora imati barem 5 znakova!</span>";
            header("Location: ../postavke.php");
            exit;
        }

        $upit = "SELECT korisnik_id FROM korisnik WHERE korisnicko_ime = $1 AND korisnik_id != $2";
        $rezultat = pg_query_params($db, $upit, array($korisnicko_ime, $korisnik_id));

        if (pg_num_rows($rezultat) > 0) {
            $_SESSION['poruke_podaci'] = "<span style='color:red;'>Korisničko ime je zauzeto!</span>";
            header("Location: ../postavke.php");
            exit;
        }

        $upit_update = "UPDATE korisnik SET ime = $1, prezime = $2, korisnicko_ime = $3 WHERE korisnik_id = $4";
        $rezultat_update = pg_query_params($db, $upit_update, array($ime, $prezime, $korisnicko_ime, $korisnik_id));

        if ($rezultat_update) {
            $_SESSION['poruke_podaci'] = "<span style='color:green;'>Podaci su uspješno ažurirani.</span>";
        } else {
            $_SESSION['poruke_podaci'] = "<span style='color:red;'>Greška pri ažuriranju podataka: " . pg_last_error($db) . "</span>";
        }
        header("Location: ../postavke.php");
        exit;
    }

    if (isset($_POST['promijeni_lozinku'])) {
        $stara_lozinka = $_POST['stara_lozinka'] ?? '';
        $nova_lozinka = $_POST['nova_lozinka'] ?? '';
        $ponovljena_lozinka = $_POST['ponovljena_lozinka'] ?? '';

        if (empty($stara_lozinka) || empty($nova_lozinka) || empty($ponovljena_lozinka)) {
            $_SESSION['poruke_lozinka'] = "<span style='color:red;'>Sva polja su obavezna!</span>";
            header("Location: ../postavke.php");
            exit;
        }

        if ($nova_lozinka !== $ponovljena_lozinka) {
            $_SESSION['poruke_lozinka'] = "<span style='color:red;'>Nove lozinke se ne podudaraju!</span>";
            header("Location: ../postavke.php");
            exit;
        }

        $upit_lozinka = "SELECT lozinka FROM korisnik WHERE korisnik_id = $1";
        $rezultat_lozinka = pg_query_params($db, $upit_lozinka, array($korisnik_id));

        if ($rezultat_lozinka && pg_num_rows($rezultat_lozinka) === 1) {
            $red = pg_fetch_assoc($rezultat_lozinka);
            $hash = $red['lozinka'];

            if (!password_verify($stara_lozinka, $hash)) {
                $_SESSION['poruke_lozinka'] = "<span style='color:red;'>Stara lozinka nije točna!</span>";
                header("Location: ../postavke.php");
                exit;
            }

            $nova_lozinka_hash = password_hash($nova_lozinka, PASSWORD_DEFAULT);

            $upit_update_lozinka = "UPDATE korisnik SET lozinka = $1 WHERE korisnik_id = $2";
            $rezultat_update_lozinka = pg_query_params($db, $upit_update_lozinka, array($nova_lozinka_hash, $korisnik_id));

            if ($rezultat_update_lozinka) {
                $_SESSION['poruke_lozinka'] = "<span style='color:green;'>Lozinka je uspješno promijenjena.</span>";
            } else {
                $_SESSION['poruke_lozinka'] = "<span style='color:red;'>Greška pri promjeni lozinke: " . pg_last_error($db) . "</span>";
            }
            header("Location: ../postavke.php");
            exit;
        } else {
            $_SESSION['poruke_lozinka'] = "<span style='color:red;'>Korisnik nije pronađen.</span>";
            header("Location: ../postavke.php");
            exit;
        }
    }
}

$veza->zatvoriDB();
?>