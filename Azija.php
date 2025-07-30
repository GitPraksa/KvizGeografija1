<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Azija-Kviz</title>
  <link rel="stylesheet" href="css/style3.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <header class="top-bar">
    <div class="logo">
      <a href="index.php" class="logo-link"><img src="slike/logo.png" alt="Logo" class="logo-img" /></a>
      <span class="site-title">Put oko svijeta</span>
    </div>
    <div class="right-header">
      <!--<a href="prijava.php"> <button class="header-btn"> <i class="fa-solid fa-right-to-bracket"> </i> Prijavi se </button> </a>-->
      <a href="postavke.php"> <button class="header-btn"> <i class="fa-solid fa-gear"> </i> Postavke </button> </a>
      <a href="korisnici.php"> <button class="header-btn"> <i class="fa-solid fa-users"> </i> Korisnici </button> </a>
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

  <main class="card-list">
    <div class="horizontal-card">
      <div class="card-left">
        <div class="image-text-wrapper">
          <img src="slike/azija.png" alt="Afrika" />
          <div class="card-text-block">
            <div class="card-title">
              <i class="fa-solid fa-star"></i> OSNOVNA RAZINA
            </div>
            <p class="card-subtitle">Države koje većina ljudi lako prepoznaje</p>
          </div>
        </div>
      </div>
      <div class="card-buttons">
        <button class="btn btn-primary" id="dodajPitanjeLako" data-razina="1">Dodaj pitanje</button>
        <button class="btn btn-secondary pregledaj-btn" data-razina="1">Pregledaj pitanja</button>
        <button>Igraj</button>
      </div>
    </div>
    <div class="horizontal-card">
      <div class="card-left">
        <div class="image-text-wrapper">
          <img src="slike/azija.png" alt="Afrika" />
          <div class="card-text-block">
            <div class="card-title">
              <i class="fa-solid fa-star"></i> SREDNJA RAZINA
            </div>
            <p class="card-subtitle">Države koje možda ne zna baš svatko, ali ih prepoznaju oni s solidnim znanjem</p>
          </div>
        </div>
      </div>
      <div class="card-buttons">
        <button class="btn btn-primary" id="dodajPitanjeSrednje" data-razina="2">Dodaj pitanje</button>
        <button class="btn btn-secondary pregledaj-btn" data-razina="2">Pregledaj pitanja</button>
        <button>Igraj</button>
      </div>
    </div>
    <div class="horizontal-card">
      <div class="card-left">
        <div class="image-text-wrapper">
          <img src="slike/azija.png" alt="Afrika" />
          <div class="card-text-block">
            <div class="card-title">
              <i class="fa-solid fa-star"></i> NAPREDNA RAZINA
            </div>
            <p class="card-subtitle">Manje poznate države koje znaju samo pravi poznavatelji geografije</p>
          </div>
        </div>
      </div>
      <div class="card-buttons">
        <button class="btn btn-primary" id="dodajPitanjeTeško" data-razina="3">Dodaj pitanje</button>
        <button class="btn btn-secondary pregledaj-btn" data-razina="3">Pregledaj pitanja</button>
        <button>Igraj</button>
      </div>
    </div>
  </main>

  <div class="modal fade" id="dodajModal" tabindex="-1" aria-labelledby="dodajModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="dodajForm" class="modal-content" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="dodajModalLabel">Dodaj novo pitanje</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="kategorija_id" value="1">
          <input type="hidden" name="razina_id" value="1">

          <div class="mb-3">
            <label for="tip_pitanja_novo" class="form-label">Tip pitanja</label>
            <select class="form-select" name="tip_pitanja" id="tip_pitanja_novo" required>
              <option value="tekst">Tekstualno</option>
              <option value="slika">Slikovno</option>
            </select>
          </div>

          <div class="mb-3 novo-tekst-polje">
            <label for="tekst_pitanja_novo" class="form-label">Tekst pitanja</label>
            <input type="text" class="form-control" name="tekst_pitanja" id="tekst_pitanja_novo">
          </div>

          <div class="mb-3 novo-slika-polje">
            <label for="novaSlika_novo" class="form-label">Nova slika (opcionalno)</label>
            <input type="file" class="form-control" name="nova_slikica" id="novaSlika_novo" accept="image/*">
          </div>

          <div class="mb-3">
            <label class="form-label">Odgovori</label>
            <div id="odgovoriWrapperNovo">
              <input type="text" name="odgovori[]" class="form-control mb-1" placeholder="Odgovor 1" required>
              <input type="text" name="odgovori[]" class="form-control mb-1" placeholder="Odgovor 2" required>
              <input type="text" name="odgovori[]" class="form-control mb-1" placeholder="Odgovor 3" required>
              <input type="text" name="odgovori[]" class="form-control mb-1" placeholder="Odgovor 4" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="tocanSelectNovo" class="form-label">Točan odgovor</label>
            <select name="tocan" id="tocanSelectNovo" class="form-select" required>
              <option value="1">Odgovor 1</option>
              <option value="2">Odgovor 2</option>
              <option value="3">Odgovor 3</option>
              <option value="4">Odgovor 4</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Dodaj</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        </div>
      </form>
    </div>
  </div>


  <div class="modal fade" id="pregledajPitanjaModal" tabindex="-1" aria-labelledby="pregledajPitanjaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pregledajPitanjaLabel">Pregledaj pitanja</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
        </div>
        <div class="modal-body">
          <h5 id="tekstPitanja"></h5>
          <ul id="listaOdgovora" class="list-group mb-3"></ul>
          <div class="d-flex justify-content-between">
            <button id="prevPitanje" class="btn btn-outline-secondary">Prethodno</button>
            <button id="nextPitanje" class="btn btn-outline-primary">Sljedeće</button>
            <button id="obrisiPitanje" class="btn btn-danger">Obriši</button>
            <button id="azurirajPitanje" class="btn btn-warning">Ažuriraj</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="azurirajModal" tabindex="-1" aria-labelledby="azurirajModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="azurirajForm" class="modal-content" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="azurirajModalLabel">Ažuriraj pitanje</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="pitanje_id" id="azurirajPitanjeId">

          <div class="mb-3">
            <label for="tip_pitanja" class="form-label">Tip pitanja</label>
            <select class="form-select" name="tip_pitanja" id="tip_pitanja" required>
              <option value="tekst">Tekstualno</option>
              <option value="slika">Slikovno</option>
            </select>
          </div>

          <div class="mb-3 tekst-polje">
            <label for="tekst_pitanja" class="form-label">Tekst pitanja</label>
            <input type="text" class="form-control" name="tekst_pitanja" id="tekst_pitanja">
          </div>

          <div class="mb-3 slika-polje">
            <label for="url_slike" class="form-label">URL slike (opcionalno)</label>
            <input type="text" class="form-control" name="url_slike" id="url_slike">
          </div>
          <div class="mb-3 slika-polje">
            <label class="form-label">Trenutna slika</label><br>
            <img id="trenutnaSlika" src="" alt="Slika" class="img-fluid mb-2" style="display:none; max-height:150px;">
          </div>
          <div class="mb-3 slika-polje">
            <label for="novaSlika" class="form-label">Nova slika (opcionalno)</label>
            <input type="file" class="form-control" name="nova_slikica" id="novaSlika" accept="image/*">
          </div>

          <div class="mb-3">
            <label class="form-label">Odgovori</label>
            <div id="odgovoriWrapper"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Točan odgovor</label>
            <select name="tocan" class="form-select" id="tocanSelect" required></select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Spremi</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function () {
      let svaPitanja = [];
      let trenutno = 0;
      let trenutnaRazina = 1;

      function prikaziPitanje() {
        if (svaPitanja.length === 0) return;

        const pitanje = svaPitanja[trenutno];

        if (pitanje.tip_pitanja === 'tekst') {
          $('#tekstPitanja').html(pitanje.tekst_pitanja);
        } else if (pitanje.tip_pitanja === 'slika') {
          $('#tekstPitanja').html(`<div style="display: flex; justify-content: center; align-items: center;">
        <img src="${pitanje.url_slike}" alt="slika pitanja" style="max-width: 360px; height: auto;" /> </div>`);

        }

        $('#listaOdgovora').empty();
        pitanje.odgovori.forEach((odg, index) => {
          const klasa = odg.tocan === 't' || odg.tocan === true ? 'list-group-item-success' : '';
          $('#listaOdgovora').append(`<li class="list-group-item ${klasa}">${String.fromCharCode(65 + index)}. ${odg.tekst_odgovora}</li>`);
        });
      }

      function prikaziPoljaZaTip(tip, prefix = '') {
        if (tip === 'tekst') {
          $(`.${prefix}tekst-polje, .${prefix}tekst-polje-novo`).show();
          $(`.${prefix}slika-polje, .${prefix}slika-polje-novo`).hide();
        } else {
          $(`.${prefix}tekst-polje, .${prefix}tekst-polje-novo`).hide();
          $(`.${prefix}slika-polje, .${prefix}slika-polje-novo`).show();
        }
      }

      $('#tip_pitanja').on('change', function () {
        prikaziPoljaZaTip($(this).val());
      });

      $('#tip_pitanja_novo').on('change', function () {
        prikaziPoljaZaTip($(this).val(), 'novo-');
      });

      $('[id^=dodajPitanje]').on('click', function () {
        trenutnaRazina = $(this).data('razina');
        $('#dodajForm')[0].reset();
        $('#dodajForm input[name="razina_id"]').val(trenutnaRazina);
        prikaziPoljaZaTip($('#tip_pitanja_novo').val(), 'novo-');
        $('#dodajModal').modal('show');
      });

      $('#dodajForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('akcija', 'dodaj');

        $.ajax({
          url: 'biblioteke/AzijaOperacije.php',
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function () {
            alert('Novo pitanje dodano!');
            $('#dodajModal').modal('hide');
          },
          error: function () {
            alert('Greška prilikom dodavanja.');
          }
        });
      });

      $('.pregledaj-btn').on('click', function () {
        const razina = $(this).data('razina');
        $.ajax({
          url: 'biblioteke/AzijaOperacije.php',
          method: 'GET',
          data: { razina_id: razina },
          dataType: 'json',
          success: function (data) {
            svaPitanja = data;
            trenutno = 0;
            prikaziPitanje();
            $('#pregledajPitanjaModal').modal('show');
          },
          error: function () {
            alert('Greška pri dohvaćanju podataka.');
          }
        });
      });

      $('#prevPitanje').on('click', function () {
        if (trenutno > 0) {
          trenutno--;
          prikaziPitanje();
        }
      });

      $('#nextPitanje').on('click', function () {
        if (trenutno < svaPitanja.length - 1) {
          trenutno++;
          prikaziPitanje();
        }
      });

      $('#azurirajPitanje').on('click', function () {
        const pitanje = svaPitanja[trenutno];
        $('#azurirajPitanjeId').val(pitanje.pitanje_id);
        $('#tekst_pitanja').val(pitanje.tekst_pitanja || '');
        $('#url_slike').val(pitanje.url_slike || '');
        $('#tip_pitanja').val(pitanje.tip_pitanja || 'tekst');
        prikaziPoljaZaTip(pitanje.tip_pitanja || 'tekst');

        if (pitanje.url_slike) {
          $('#trenutnaSlika').attr('src', pitanje.url_slike).show();
        } else {
          $('#trenutnaSlika').hide();
        }

        $('#odgovoriWrapper').empty();
        $('#tocanSelect').empty();

        pitanje.odgovori.forEach((odg, index) => {
          $('#odgovoriWrapper').append(`<input type="text" class="form-control mb-1" name="odgovori[]" value="${odg.tekst_odgovora}" required>`);
          $('#tocanSelect').append(`<option value="${index + 1}" ${odg.tocan === 't' || odg.tocan === true ? 'selected' : ''}>Odgovor ${index + 1}</option>`);
        });

        $('#azurirajModal').modal('show');
      });

      $('#azurirajForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('akcija', 'azuriraj');

        $.ajax({
          url: 'biblioteke/AzijaOperacije.php',
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function () {
            alert('Pitanje ažurirano!');
            $('#azurirajModal').modal('hide');
            location.reload();
          },
          error: function () {
            alert('Greška prilikom ažuriranja.');
          }
        });
      });

      $('#obrisiPitanje').on('click', function () {
        if (svaPitanja.length === 0) return;

        const pitanje = svaPitanja[trenutno];
        const potvrda = confirm(`Jesi li siguran da želiš obrisati pitanje:\n\n"${pitanje.tekst_pitanja || 'SLIKOVNO PITANJE'}"?`);

        if (!potvrda) return;

        $.ajax({
          url: 'biblioteke/AzijaOperacije.php',
          method: 'POST',
          dataType: 'json',
          data: {
            akcija: 'obrisi',
            pitanje_id: pitanje.pitanje_id
          },
          success: function (res) {
            if (res.status === 'success') {
              alert('Pitanje uspješno obrisano.');
              svaPitanja.splice(trenutno, 1);
              if (trenutno >= svaPitanja.length) trenutno = svaPitanja.length - 1;
              if (svaPitanja.length > 0) {
                prikaziPitanje();
              } else {
                $('#tekstPitanja').html('Nema više pitanja.');
                $('#listaOdgovora').empty();
              }
            } else {
              alert('Greška: ' + res.msg);
            }
          },
          error: function () {
            alert('Greška u komunikaciji sa serverom.');
          }
        });
      });

    });
  </script>