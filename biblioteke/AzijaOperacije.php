<?php
header('Content-Type: application/json');
require_once "../baza.php";

$veza = new Baza();
$conn = $veza->spojiDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $razina_id = $_GET['razina_id'] ?? null;

    $query = "
      SELECT p.pitanje_id, p.tekst_pitanja, p.tip_pitanja, p.url_slike,
             o.odgovor_id, o.tekst_odgovora, o.tocan
      FROM pitanje p
      JOIN odgovor o ON p.pitanje_id = o.pitanje_id
      WHERE p.kategorija_id = 2
    ";

    if ($razina_id !== null) {
        $query .= " AND p.razina_id = " . intval($razina_id);
    }

    $query .= " ORDER BY p.pitanje_id, o.odgovor_id";

    $rezultat = pg_query($conn, $query);

    $pitanja = [];
    while ($red = pg_fetch_assoc($rezultat)) {
        $pid = $red['pitanje_id'];
        if (!isset($pitanja[$pid])) {
            $pitanja[$pid] = [
                'pitanje_id' => $pid,
                'tekst_pitanja' => $red['tekst_pitanja'],
                'tip_pitanja' => $red['tip_pitanja'],
                'url_slike' => $red['url_slike'],
                'odgovori' => []
            ];
        }
        $pitanja[$pid]['odgovori'][] = [
            'odgovor_id' => $red['odgovor_id'],
            'tekst_odgovora' => $red['tekst_odgovora'],
            'tocan' => $red['tocan']
        ];
    }

    echo json_encode(array_values($pitanja));
    $veza->zatvoriDB();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['akcija'])) {
    $akcija = $_POST['akcija'];

    if ($akcija === 'azuriraj') {
        $pitanje_id = $_POST['pitanje_id'];
        $tekst_pitanja = $_POST['tekst_pitanja'] ?? null;
        $url_slike = $_POST['url_slike'] ?? null;
        $tip_pitanja = $_POST['tip_pitanja'] ?? null;
        $odgovori = $_POST['odgovori'] ?? [];
        $tocan_index = intval($_POST['tocan']) - 1;

        if (isset($_FILES['nova_slikica']) && $_FILES['nova_slikica']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['nova_slikica']['tmp_name'];
            $ime = basename($_FILES['nova_slikica']['name']);
            $putanja = "../slike/Azija" . $ime;
            move_uploaded_file($tmp, $putanja);
            $url_slike = "slike/Azija" . $ime;
        }

        $upit = "UPDATE pitanje
                 SET tekst_pitanja = $1, url_slike = $2, tip_pitanja = $3
                 WHERE pitanje_id = $4";
        $res = pg_query_params($conn, $upit, [$tekst_pitanja, $url_slike, $tip_pitanja, $pitanje_id]);

        if (!$res) {
            echo json_encode(['status' => 'error', 'msg' => 'Neuspjelo ažuriranje pitanja']);
            exit;
        }

        pg_query_params($conn, "DELETE FROM odgovor WHERE pitanje_id = $1", [$pitanje_id]);

        foreach ($odgovori as $i => $tekst_odg) {
            $tocan = ($i === $tocan_index) ? 't' : 'f';
            pg_query_params(
                $conn,
                "INSERT INTO odgovor (pitanje_id, tekst_odgovora, tocan)
                 VALUES ($1, $2, $3)",
                [$pitanje_id, $tekst_odg, $tocan]
            );
        }

        echo json_encode(['status' => 'success']);
        $veza->zatvoriDB();
        exit;
    }

    if ($akcija === 'dodaj') {
        $kategorija_id = 2;
        $razina_id = $_POST['razina_id'] ?? 1;
        $tekst_pitanja = $_POST['tekst_pitanja'] ?? null;
        $url_slike = $_POST['url_slike'] ?? null;
        $tip_pitanja = $_POST['tip_pitanja'] ?? 'tekst';
        $odgovori = $_POST['odgovori'] ?? [];
        $tocan_index = intval($_POST['tocan']) - 1;

        if (isset($_FILES['nova_slikica']) && $_FILES['nova_slikica']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['nova_slikica']['tmp_name'];
            $ime = basename($_FILES['nova_slikica']['name']);
            $putanja = "../slike/Azija" . $ime;
            move_uploaded_file($tmp, $putanja);
            $url_slike = "slike/Azija" . $ime;
        }

        $upit = "INSERT INTO pitanje (kategorija_id, razina_id, tip_pitanja, tekst_pitanja, url_slike)
                 VALUES ($1, $2, $3, $4, $5) RETURNING pitanje_id";
        $res = pg_query_params($conn, $upit, [$kategorija_id, $razina_id, $tip_pitanja, $tekst_pitanja, $url_slike]);

        if (!$res) {
            echo json_encode(['status' => 'error', 'msg' => 'Neuspjelo dodavanje pitanja']);
            exit;
        }

        $red = pg_fetch_assoc($res);
        $pitanje_id = $red['pitanje_id'];

        foreach ($odgovori as $i => $tekst_odg) {
            $tocan = ($i === $tocan_index) ? 't' : 'f';
            pg_query_params(
                $conn,
                "INSERT INTO odgovor (pitanje_id, tekst_odgovora, tocan)
                 VALUES ($1, $2, $3)",
                [$pitanje_id, $tekst_odg, $tocan]
            );
        }

        echo json_encode(['status' => 'success']);
        $veza->zatvoriDB();
        exit;
    }

    if ($akcija === 'obrisi') {
        $pitanje_id = $_POST['pitanje_id'] ?? null;

        if (!$pitanje_id) {
            echo json_encode(['status' => 'error', 'msg' => 'Nedostaje ID pitanja']);
            exit;
        }

        $res = pg_query_params($conn, "DELETE FROM pitanje WHERE pitanje_id = $1", [$pitanje_id]);

        if (!$res) {
            echo json_encode(['status' => 'error', 'msg' => 'Neuspjelo brisanje pitanja']);
            exit;
        }

        echo json_encode(['status' => 'success']);
        $veza->zatvoriDB();
        exit;
    }
}
?>