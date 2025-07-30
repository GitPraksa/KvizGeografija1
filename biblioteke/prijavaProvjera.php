<?php
session_start();
include '../baza.php';

$veza = new Baza();
$veza->spojiDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prijavaButton'])) {
    $kor_ime = trim($_POST['username']);
    $lozinka = trim($_POST['password']);

    if (empty($kor_ime) || empty($lozinka)) {
        $_SESSION['poruke'] = "Molimo unesite korisničko ime i lozinku!";
        header("Location: ../prijava.php");
        exit;
    }

    $upit = "SELECT korisnik_id, korisnicko_ime, hash_lozinka, uloga_id 
             FROM korisnik 
             WHERE korisnicko_ime = $1";

    $rezultat = pg_query_params($veza->spojiDB(), $upit, array($kor_ime));

    $_SESSION['prijava_pokusaj'] = true;

    if ($rezultat && pg_num_rows($rezultat) > 0) {
        $korisnik = pg_fetch_assoc($rezultat);
        $hash = trim($korisnik['hash_lozinka']);

        if (password_verify($lozinka, $hash)) {
            $_SESSION['korisnik_id'] = $korisnik['korisnik_id'];
            $_SESSION['korisnicko_ime'] = $korisnik['korisnicko_ime'];
            $_SESSION['uloga_id'] = $korisnik['uloga_id'];
            $_SESSION['ulogiran'] = true;

            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['poruke'] = "Unijeli ste pogrešnu lozinku!";
            header("Location: ../prijava.php");
            exit;
        }
    } else {
        $_SESSION['poruke'] = "Korisničko ime ne postoji!";
        header("Location: ../prijava.php");
        exit;
    }
}

$veza->zatvoriDB();

