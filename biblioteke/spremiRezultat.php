<?php
header('Content-Type: application/json');
require_once "../baza.php";
session_start();

if (!isset($_SESSION['korisnik_id'])) {
    echo json_encode(['status' => 'error', 'msg' => 'Niste prijavljeni.']);
    exit;
}

$korisnik_id = $_SESSION['korisnik_id'];

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['kategorija'], $input['tezina'], $input['bodovi'])) {
    echo json_encode(['status' => 'error', 'msg' => 'Neispravan unos.']);
    exit;
}

$kategorija = $input['kategorija'];
$tezina = $input['tezina'];
$bodovi = intval($input['bodovi']);

$mapa_razina = [
    "lako" => 1,
    "srednje" => 2,
    "tesko" => 3
];
$razina_id = $mapa_razina[$tezina] ?? 1;

$veza = new Baza();
$conn = $veza->spojiDB();

try {
    $sqlKategorija = "SELECT kategorija_id FROM kategorija WHERE naziv_kategorije = $1";
    $resKat = pg_query_params($conn, $sqlKategorija, [$kategorija]);
    if (pg_num_rows($resKat) === 0) {
        echo json_encode(['status' => 'error', 'msg' => 'Nepoznata kategorija.']);
        exit;
    }
    $rowKat = pg_fetch_assoc($resKat);
    $kategorija_id = intval($rowKat['kategorija_id']);

    $sqlProvjera = "SELECT bodovi FROM kviz_rezultat 
                    WHERE korisnik_id = $1 AND kategorija_id = $2 AND razina_id = $3";
    $res = pg_query_params($conn, $sqlProvjera, [$korisnik_id, $kategorija_id, $razina_id]);

    $rekord = false;

    if (pg_num_rows($res) > 0) {
        $row = pg_fetch_assoc($res);
        $stari_bodovi = intval($row['bodovi']);

        if ($bodovi > $stari_bodovi) {
            $sqlUpdate = "UPDATE kviz_rezultat 
                          SET bodovi = $1, datum_igranja = CURRENT_TIMESTAMP 
                          WHERE korisnik_id = $2 AND kategorija_id = $3 AND razina_id = $4";
            pg_query_params($conn, $sqlUpdate, [$bodovi, $korisnik_id, $kategorija_id, $razina_id]);
            $rekord = true;
        }
    } else {
        $sqlInsert = "INSERT INTO kviz_rezultat (korisnik_id, kategorija_id, razina_id, bodovi) 
                      VALUES ($1, $2, $3, $4)";
        pg_query_params($conn, $sqlInsert, [$korisnik_id, $kategorija_id, $razina_id, $bodovi]);
        $rekord = true;
    }

    echo json_encode(['status' => 'OK', 'noviRekord' => $rekord]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}

$veza->zatvoriDB();
?>