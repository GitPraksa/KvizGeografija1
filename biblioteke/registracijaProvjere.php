<?php
session_start();
include '../baza.php';

$veza = new Baza();
$veza->spojiDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $korisnicko_ime = trim($_POST['username']);
    $lozinka = trim($_POST['password']);
    $ponovljena_lozinka = trim($_POST['repeat-password']);

    if (!preg_match("/^[A-ZČĆŽŠĐ][a-zčćžšđ]+$/u", $ime)) {
        $_SESSION['poruke'] = "Ime mora početi velikim slovom i sadržavati samo slova!";
        header("Location: ../registracija.php");
        exit;
    }
    if (!preg_match("/^[A-ZČĆŽŠĐ][a-zčćžšđ]+$/u", $prezime)) {
        $_SESSION['poruke'] = "Prezime mora početi velikim slovom i sadržavati samo slova!";
        header("Location: ../registracija.php");
        exit;
    }

    if (strlen($korisnicko_ime) < 5) {
        $_SESSION['poruke'] = "Korisničko ime mora imati barem 5 znakova!";
        header("Location: ../registracija.php");
        exit;
    }

    $upit = "SELECT korisnik_id FROM korisnik WHERE korisnicko_ime = $1";
    $rezultat = pg_query_params($veza->spojiDB(), $upit, array($korisnicko_ime));

    if (!$rezultat) {
        $_SESSION['poruke'] = "Greška pri provjeri korisničkog imena: " . pg_last_error($veza->spojiDB());
        header("Location: ../registracija.php");
        exit;
    }

    if (pg_num_rows($rezultat) > 0) {
        $_SESSION['poruke'] = "Korisničko ime već postoji, odaberite drugo!";
        header("Location: ../registracija.php");
        exit;
    }

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{7,}$/", $lozinka)) {
        $_SESSION['poruke'] = "Lozinka mora imati najmanje 7 znakova, jedno veliko slovo, jedno malo slovo i broj!";
        header("Location: ../registracija.php");
        exit;
    }

    if ($lozinka !== $ponovljena_lozinka) {
        $_SESSION['poruke'] = "Lozinke se ne podudaraju!";
        header("Location: ../registracija.php");
        exit;
    }

    $hash_lozinka = password_hash($lozinka, PASSWORD_BCRYPT);

    $upit_insert = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, hash_lozinka, uloga_id) 
                    VALUES ($1, $2, $3, $4, $5)";
    $rezultat_insert = pg_query_params($veza->spojiDB(), $upit_insert, array($ime, $prezime, $korisnicko_ime, $hash_lozinka, 2));

    if ($rezultat_insert) {
        $_SESSION['poruke'] = "<span style='color:green;'>Registracija uspješna! Možete se prijaviti.</span>";
        header("Location: ../prijava.php");
        exit;
    } else {
        $_SESSION['poruke'] = "Došlo je do greške prilikom registracije: " . pg_last_error($veza->spojiDB());
        header("Location: ../registracija.php");
        exit;
    }
}

$veza->zatvoriDB();
?>
