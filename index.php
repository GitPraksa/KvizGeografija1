<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kviz kontinenata</title>
  <link rel="stylesheet" href="css/style2.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

  <header class="top-bar">
    <div class="logo">
      <img src="slike/logo.png" alt="Logo" class="logo-img">
      <span class="site-title">Put oko svijeta</span>
    </div>
    <div class="right-header">
      <?php if (isset($_SESSION['korisnicko_ime'])): ?>
        <a href="postavke.php"> <button class="header-btn"><i class="fa-solid fa-gear"></i> Postavke</button></a>
      <?php endif; ?>
      <?php if (isset($_SESSION['uloga_id']) && $_SESSION['uloga_id'] === '1'): ?>
        <a href="korisnici.php"> <button class="header-btn"><i class="fa-solid fa-users"></i> Korisnici</button></a>
      <?php endif; ?>

      <?php if (isset($_SESSION['korisnicko_ime'])): ?>
        <a href="biblioteke/odjava.php"><button class="header-btn"><i class="fa-solid fa-right-from-bracket"></i> Odjavi
            se</button></a>
      <?php else: ?>
        <a href="prijava.php"><button class="header-btn"><i class="fa-solid fa-right-to-bracket"></i> Prijavi
            se</button></a>
      <?php endif; ?>
    </div>
  </header>

  <nav class="nav-bar">
    <a href="europa.php"><strong>Europa</strong></a>
    <a href="azija.php"><strong>Azija</strong></a>
    <a href="SjevernaAmerika.php"><strong>Sjeverna Amerika</strong></a>
    <a href="JužnaAmerika.php"><strong>Južna Amerika</strong></a>
    <a href="afrika.php"><strong>Afrika</strong></a>
    <a href="AustralijaAntarktika.php"><strong>Australija/Antarktika</strong></a>
  </nav>

  <main class="main-content">
    <section class="continent-grid">
      <div class="continent-card">
        <div class="card-top">
          <a href="Europa.php"> <img src="slike/europa.png" alt="Europa"></a>
          <a href="Europa.php" class="continent-name"><strong>Europa</strong></a>
        </div>
        <div class="orange-line"></div>
        <div class="card-body">
          <p>Odaberi razinu:</p>
          <div class="razine-container">
            <a href="kviz.php?kategorija=Europa&tezina=lako" class="razina-btn" data-kontinent="Europa"
              data-level="lako">Lako</a>
            <a href="kviz.php?kategorija=Europa&tezina=srednje" class="razina-btn" data-kontinent="Europa"
              data-level="srednje">Srednje</a>
            <a href="kviz.php?kategorija=Europa&tezina=tesko" class="razina-btn" data-kontinent="Europa"
              data-level="tesko">Teško</a>
          </div>
          <div class="buttonStatistika">

            <a href="Europa.php">
              <button class="stats-btn"><i class="fa-solid fa-chart-line"></i> Uredi pitanja</button>
            </a>
          </div>
        </div>
      </div>
      </div>

      <div class="continent-card">
        <div class="card-top">
          <img src="slike/Azija.png" alt="Azija" />
          <span class="continent-name">Azija</span>
        </div>
        <div class="orange-line"></div>
        <div class="card-body">
          <p>Odaberi razinu:</p>
          <div class="razine-container">
            <a href="kviz.php?kategorija=Azija&tezina=lako" class="razina-btn" data-kontinent="Azija"
              data-level="lako">Lako</a>
            <a href="kviz.php?kategorija=Azija&tezina=srednje" class="razina-btn" data-kontinent="Azija"
              data-level="srednje">Srednje</a>
            <a href="kviz.php?kategorija=Azija&tezina=tesko" class="razina-btn" data-kontinent="Azija"
              data-level="tesko">Teško</a>
          </div>
          <div class="buttonStatistika">
            <a href="Azija.php">
              <button class="stats-btn"><i class="fa-solid fa-chart-line"></i> Uredi pitanja</button>
            </a>

          </div>
        </div>
      </div>

      <div class="continent-card">
        <div class="card-top">
          <img src="slike/Sjeverna Amerika.png" alt="Sjeverna Amerika" />
          <span class="continent-name">Sjeverna Amerika</span>
        </div>
        <div class="orange-line"></div>
        <div class="card-body">
          <p>Odaberi razinu</p>
          <div class="razine-container">
            <a href="kviz.php?kategorija=SjevernaAmerika&tezina=lako" class="razina-btn"
              data-kontinent="SjevernaAmerika" data-level="lako">Lako</a>
            <a href="kviz.php?kategorija=SjevernaAmerika&tezina=srednje" class="razina-btn"
              data-kontinent="SjevernaAmerika" data-level="srednje">Srednje</a>
            <a href="kviz.php?kategorija=SjevernaAmerika&tezina=tesko" class="razina-btn"
              data-kontinent="SjevernaAmerika" data-level="tesko">Teško</a>
          </div>
          <?php if (isset($_SESSION['uloga_id']) && $_SESSION['uloga_id'] === '1'): ?>
            <div class="buttonStatistika">
              <a href="SjevernaAmerika.php">
                <button class="stats-btn"><i class="fa-solid fa-chart-line"></i> Uredi pitanja</button>
              </a>
            </div>
          <?php endif; ?>

        </div>
      </div>

      <div class="continent-card">
        <div class="card-top">
          <img src="slike/Južna Amerika.png" alt="Južna Amerika" />
          <span class="continent-name">Južna Amerika</span>
        </div>
        <div class="orange-line"></div>
        <div class="card-body">
          <p>Odaberi razinu</p>
          <div class="razine-container">
            <a href="kviz.php?kategorija=JužnaAmerika&tezina=lako" class="razina-btn" data-kontinent="JužnaAmerika"
              data-level="lako">Lako</a>
            <a href="kviz.php?kategorija=JužnaAmerika&tezina=srednje" class="razina-btn" data-kontinent="JužnaAmerika"
              data-level="srednje">Srednje</a>
            <a href="kviz.php?kategorija=JužnaAmerika&tezina=tesko" class="razina-btn" data-kontinent="JužnaAmerika"
              data-level="tesko">Teško</a>
          </div>
          <div class="buttonStatistika">
              <a href="JužnaAmerika.php">
                <button class="stats-btn"><i class="fa-solid fa-chart-line"></i> Uredi pitanja</button>
              </a>
            </div>
        </div>
      </div>

      <div class="continent-card">
        <div class="card-top">
          <a href="Afrika.php"> <img src="slike/afrika.png" alt="Afrika"></a>
          <a href="afrika.php" class="continent-name"><strong>Afrika</strong></a>
        </div>
        <div class="orange-line"></div>
        <div class="card-body">
          <p>Odaberi razinu</p>
          <div class="razine-container">
            <a href="kviz.php?kategorija=Afrika&tezina=lako" class="razina-btn" data-kontinent="Afrika"
              data-level="lako">Lako</a>
            <a href="kviz.php?kategorija=Afrika&tezina=srednje" class="razina-btn" data-kontinent="Afrika"
              data-level="srednje">Srednje</a>
            <a href="kviz.php?kategorija=Afrika&tezina=tesko" class="razina-btn" data-kontinent="Afrika"
              data-level="tesko">Teško</a>
          </div>
          <div class="buttonStatistika">
            <a href="Afrika.php">
                <button class="stats-btn"><i class="fa-solid fa-chart-line"></i> Uredi pitanja</button>
            </a>
          </div>
        </div>
      </div>

      <div class="continent-card">
        <div class="card-top">
          <a href="AustralijaAntarktika.php"> <img src="slike/Australija-Antarktika.png" alt="Australija-Antarktika"></a>
          <a href="AustralijaAntarktika.php" class="continent-name"><strong>Australija i Oceanija/Antarktika</strong></a>
        </div>
        <div class="orange-line"></div>
        <div class="card-body">
          <p>Odaberi razinu</p>
          <div class="razine-container">
            <a href="kviz.php?kategorija=AustralijaAntarktika&tezina=lako" class="razina-btn" data-kontinent="Australija"
              data-level="lako">Lako</a>
            <a href="kviz.php?kategorija=AustralijaAntarktika&tezina=srednje" class="razina-btn" data-kontinent="Australija"
              data-level="srednje">Srednje</a>
            <a href="kviz.php?kategorija=AustralijaAntarktika&tezina=tesko" class="razina-btn" data-kontinent="Australija"
              data-level="tesko">Teško</a>
          </div>
          <div class="buttonStatistika">
            <a href="AustralijaAntarktika.php">
                <button class="stats-btn"><i class="fa-solid fa-chart-line"></i> Uredi pitanja</button>
            </a>
          </div>
        </div>
      </div>
    </section>

    <aside class="ranking">
      <h3>Rang lista</h3>
    </aside>
  </main>


  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const progress = JSON.parse(localStorage.getItem("progress")) || {};

      document.querySelectorAll(".razina-btn").forEach(btn => {
        const kontinent = btn.dataset.kontinent;
        const level = btn.dataset.level;

        if (!progress[kontinent]) {
          progress[kontinent] = { lako: false, srednje: false, tesko: false };
        }

        if (level === "lako") {
          return; 
        } else if (level === "srednje" && !progress[kontinent].lako) {
          btn.classList.add("disabled");
          btn.href = "#";
        } else if (level === "tesko" && !progress[kontinent].srednje) {
          btn.classList.add("disabled");
          btn.href = "#";
        }
      });
    });
  </script>

  <script>
    $(document).ready(function () {
      function ucitajRangListu() {
        $.ajax({
          url: 'biblioteke/dohvatiRangListu.php',
          method: 'GET',
          dataType: 'json',
          success: function (data) {
            let html = '<ul>';
            if (data.length > 0) {
              data.forEach((item, index) => {
                html += `<li data-rank="${index + 1}">${item.korisnicko_ime} <strong>${item.rezultat} bodova</strong></li>`;
              });
            } else {
              html += '<li>Nema dostupnih rezultata.</li>';
            }
            html += '</ul>';
            $('.ranking').html('<h3>Rang lista</h3>' + html);
          },
          error: function () {
            $('.ranking').html('<h3>Rang lista</h3><p>Greška pri dohvaćanju rang liste.</p>');
          }
        });
      }
      ucitajRangListu();

    });
  </script>
</body>

</html>