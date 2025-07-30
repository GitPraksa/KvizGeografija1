<?php
session_start();
include_once '../baza.php';
$baza = new Baza();
$conn = $baza->spojiDB();

$pitanje_offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$kategorija_id = isset($_GET['kategorija_id']) ? (int) $_GET['kategorija_id'] : 1;
$razina_id = isset($_GET['razina_id']) ? (int) $_GET['razina_id'] : 1;

$kljuc_sesije = "pitanja_k{$kategorija_id}_r{$razina_id}";

if (!isset($_SESSION[$kljuc_sesije])) {
    $upit = "
        SELECT pitanje_id
        FROM pitanje
        WHERE kategorija_id = $1
        AND razina_id = $2
        ORDER BY RANDOM()
        LIMIT 20
    ";
    $rez = pg_query_params($conn, $upit, [$kategorija_id, $razina_id]);

    $svi_id = [];
    while ($red = pg_fetch_assoc($rez)) {
        $svi_id[] = $red['pitanje_id'];
    }

    $_SESSION[$kljuc_sesije] = $svi_id;
}

if ($pitanje_offset >= count($_SESSION[$kljuc_sesije])) {
    echo json_encode(["kraj" => true]);
    exit;
}

$pitanje_id = $_SESSION[$kljuc_sesije][$pitanje_offset];

$upit = "
    SELECT pitanje_id, tekst_pitanja, url_slike, tip_pitanja
    FROM pitanje
    WHERE pitanje_id = $1
";
$rezultat = pg_query_params($conn, $upit, [$pitanje_id]);
$pitanje = pg_fetch_assoc($rezultat);

if (!$pitanje) {
    echo json_encode(["kraj" => true]);
    exit;
}

$odgovori_upit = "
    SELECT tekst_odgovora, tocan
    FROM odgovor
    WHERE pitanje_id = $1
";
$rez_odgovori = pg_query_params($conn, $odgovori_upit, [$pitanje_id]);

$odgovori = [];
while ($odgovor = pg_fetch_assoc($rez_odgovori)) {
    $odgovori[] = [
        "tekst" => $odgovor['tekst_odgovora'],
        "tocan" => ($odgovor['tocan'] === 't')
    ];
}

shuffle($odgovori);

echo json_encode([
    "pitanje" => $pitanje,
    "odgovori" => $odgovori,
    "broj" => $pitanje_offset + 1
]);
