<?php
header('Content-Type: application/json');
require_once "../baza.php";

$veza = new Baza();
$conn = $veza->spojiDB();

try {
    $sql = "SELECT korisnicko_ime, ukupno_bodova FROM rang_lista LIMIT 10";
    $rezultat = pg_query($conn, $sql);

    if (!$rezultat) {
        echo json_encode(['status' => 'error', 'msg' => 'Greška kod dohvaćanja rang liste']);
        $veza->zatvoriDB();
        exit;
    }

    $rangLista = [];
    while ($red = pg_fetch_assoc($rezultat)) {
        $rangLista[] = [
            'korisnicko_ime' => $red['korisnicko_ime'],
            'rezultat' => $red['ukupno_bodova']
        ];
    }

    echo json_encode($rangLista);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'msg' => 'Neočekivana greška']);
}

$veza->zatvoriDB();
exit;
?>