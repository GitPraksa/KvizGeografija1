<!DOCTYPE html>
<html lang="hr">

<head>
    <title>Kviz Europa</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/kviz.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>

    <div id="pravila-kviza" class="pravila-kviza">
        <h2>Pravila kviza</h2>
        <div class="pravilo">1. Za odgovor na svako pitanje predviđeno je 25 sekundi</div>
        <div class="pravilo">2. Za svaki točan odgovor dobivate 1 bod</div>
        <div class="pravilo">3. Nije moguće vraćanje na prethodna pitanja</div>
        <div class="pravilo">4. Jedanput odabrani odgovor ne može se poništiti</div>
        <div class="pravilo">5. Da biste uspješno riješili kviz morate točno odgovoriti na barem 18 od 20 pitanja</div>

        <div class="pravila-gumbi">
            <button id="pokreniKviz" class="pokreniKviz">Pokreni kviz</button>
            <button onclick="window.location.href='index.php'" class="izlaz-pravila">Izađi</button>
        </div>
    </div>

    <div class="kvizAzija" style="display: none;">
        <header class="kvizAzija-header">
            <h2 class="naslovKviza">Kviz Europa</h2>
            <div class="kvizStatus" style="display: flex; align-items: center; gap: 12px;">
                <div id="srca"></div>
                <div class="kvizBrojac">
                    <span id="brojac">30s</span>
                </div>
            </div>
        </header>

        <div id="pitanje-sadrzaj" class="kvizAzija-sadržaj"></div>

        <div class="kviz-footer">
            <p id="status_pitanja" class="status_pitanja"><b>1</b> od <b>20</b></p>
            <div class="footer-buttons">
                <button id="sljedece-pitanje" class="sljedećePitanje-gumb">Sljedeće pitanje</button>
                <button id="izlaz-btn" class="izađi-gumb">Izađi</button>
            </div>
        </div>
    </div>

</body>

</html>