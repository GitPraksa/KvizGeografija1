let offset = 0;
let brojTocnih = 0;
let timerInterval;
let zivoti = 3;
const maxZivota = 3;
let prvoPitanje = null;

function dohvatiKategorijaID(naziv) {
    const mapa = {
        Europa: 1,
        Azija: 2,
        SjevernaAmerika: 3,
        JužnaAmerika: 4,
        Afrika: 5,
        AustralijaAntarktika: 6

    };
    return mapa[naziv] || 1;
}

function oznaciRazinuOtkljucanom(kontinent, level) {
    let progress = JSON.parse(localStorage.getItem("progress")) || {};
    if (!progress[kontinent]) {
        progress[kontinent] = { lako: false, srednje: false, tesko: false };
    }
    progress[kontinent][level] = true;
    localStorage.setItem("progress", JSON.stringify(progress));
}

document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const kategorija = urlParams.get("kategorija");
    if (kategorija) {
        const naslov = document.querySelector('.naslovKviza');
        if (naslov) {
            naslov.textContent = `Kviz ${kategorija}`;
        }
    }

    const pitanjeDiv = document.getElementById('pitanje-sadrzaj');
    const status = document.getElementById('status_pitanja');
    const gumb = document.getElementById('sljedece-pitanje');
    const izlazBtn = document.getElementById('izlaz-btn');
    const brojac = document.getElementById('brojac');
    const srcaElement = document.getElementById('srca');

    const pokreniKvizBtn = document.getElementById('pokreniKviz');
    const kvizDiv = document.querySelector('.kvizAzija');
    const pravilaDiv = document.getElementById('pravila-kviza');

    kvizDiv.style.display = "none";

    pripremiPrvoPitanje();

    pokreniKvizBtn.addEventListener('click', () => {
        pravilaDiv.style.display = 'none';
        kvizDiv.style.display = 'block';
        ucitajPitanje();
    });

    izlazBtn.addEventListener('click', () => {
        window.location.href = "index.php";
    });

    function azurirajSrca() {
        let srca = '';
        for (let i = 0; i < maxZivota; i++) {
            srca += `<span class="srce ${i < zivoti ? '' : 'izgubljeno'}">❤️</span>`;
        }
        srcaElement.innerHTML = srca;
    }

    function spremiRezultat(bodovi, callback) {
        const urlParams = new URLSearchParams(window.location.search);
        const kategorija = urlParams.get("kategorija");
        const tezina = urlParams.get("tezina");

        fetch("biblioteke/spremiRezultat.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                kategorija,
                tezina,
                bodovi
            }),
        })
        .then(res => res.json())
        .then(data => {
            if (callback) callback(data.noviRekord);
        })
        .catch(err => {
            console.error("Greška pri spremanju rezultata:", err);
            if (callback) callback(false);
        });
    }

    function krajPoraz() {
        spremiRezultat(brojTocnih, noviRekord => {
            let poruka = `
                <h2>Izgubili ste! 😢</h2>
                <p>Iskoristili ste sve živote.</p>
            `;
            if (noviRekord) {
                poruka += `<p><strong>Novi rekord na zadnjem pitanju! 🏆</strong></p>`;
            }

            pitanjeDiv.innerHTML = poruka;
            status.style.display = 'none';
            document.querySelector('.kvizBrojac').style.display = 'none';

            gumb.textContent = "Ispočetka";
            gumb.onclick = () => location.reload();
            izlazBtn.style.display = 'inline-block';
        });
    }

    function pokreniTimer(callback, data) {
        let vrijeme = 25;
        brojac.textContent = `${vrijeme}s`;

        timerInterval = setInterval(() => {
            vrijeme--;
            brojac.textContent = `${vrijeme}s`;

            if (vrijeme <= 0) {
                clearInterval(timerInterval);
                if (!data.odgovorKliknut) {
                    zivoti--;
                    azurirajSrca();
                    prikaziOdgovore(data);
                    if (zivoti <= 0) {
                        krajPoraz();
                    }
                }
            }
        }, 1000);
    }

    function prikaziOdgovore(data) {
        const opcije = document.querySelectorAll('.odgovori-opcije');
        opcije.forEach((o, i) => {
            o.classList.remove('odgovori-opcijeTočno', 'odgovori-opcijeNetočno');
            const span = document.createElement('span');
            span.classList.add('material-symbols-outlined');
            if (data.odgovori[i].tocan) {
                o.classList.add('odgovori-opcijeTočno');
                span.textContent = 'check_circle';
                o.appendChild(span);
            }
        });
        opcije.forEach(o => o.style.pointerEvents = 'none');
    }

    function pripremiPrvoPitanje() {
        const urlParams = new URLSearchParams(window.location.search);
        const kategorija = urlParams.get("kategorija");
        const tezina = urlParams.get("tezina");

        let razina_id = 1;
        if (tezina === "srednje") razina_id = 2;
        else if (tezina === "tesko") razina_id = 3;

        const kategorija_id = dohvatiKategorijaID(kategorija);

        fetch(`php/get_pitanje.php?kategorija_id=${kategorija_id}&razina_id=${razina_id}&offset=0`)
            .then(res => res.json())
            .then(data => {
                prvoPitanje = data;
            });
    }

    function ucitajPitanje() {
        clearInterval(timerInterval);

        const urlParams = new URLSearchParams(window.location.search);
        const kategorija = urlParams.get("kategorija");
        const tezina = urlParams.get("tezina");

        let razina_id = 1;
        if (tezina === "srednje") razina_id = 2;
        else if (tezina === "tesko") razina_id = 3;

        const kategorija_id = dohvatiKategorijaID(kategorija);

        if (prvoPitanje && offset === 0) {
            prikaziPitanje(prvoPitanje);
            prvoPitanje = null;
        } else {
            fetch(`php/get_pitanje.php?kategorija_id=${kategorija_id}&razina_id=${razina_id}&offset=${offset}`)
                .then(response => response.json())
                .then(data => {
                    prikaziPitanje(data);
                });
        }
    }

    function prikaziPitanje(data) {
        if (data.kraj) {
            const urlParams = new URLSearchParams(window.location.search);
            spremiRezultat(brojTocnih, noviRekord => {
                let poruka = `
                    <h2>Kraj kviza! 🎉</h2>
                    <p>Točno ste odgovorili na <b>${brojTocnih}</b> od <b>${offset}</b> pitanja.</p>
                `;
                if (noviRekord) {
                    poruka += `<p><strong>Novi rekord na zadnjem pitanju! 🏆</strong></p>`;
                }

                pitanjeDiv.innerHTML = poruka;
                status.style.display = 'none';
                document.querySelector('.kvizBrojac').style.display = 'none';

                if (zivoti > 0) {
                    oznaciRazinuOtkljucanom(urlParams.get("kategorija"), urlParams.get("tezina"));
                }

                gumb.textContent = "Ispočetka";
                gumb.onclick = () => location.reload();
                izlazBtn.style.display = 'inline-block';
            });
            return;
        }

        let html = '';
        if (data.pitanje.tip_pitanja === 'tekst') {
            html += `<h1 class="kvizAzija-pitanje">${data.pitanje.tekst_pitanja}</h1>`;
        } else {
            html += `
                <div style="display: flex; justify-content: center; align-items: center;">
                    <img src="${data.pitanje.url_slike}" style="max-width: 360px; height: auto;">
                </div>`;
        }

        html += '<ul class="odgovori">';
        data.odgovori.forEach((odg, index) => {
            html += `
                <li class="odgovori-opcije" data-index="${index}" data-correct="${odg.tocan}">
                    <p>${odg.tekst}</p>
                </li>`;
        });
        html += '</ul>';

        pitanjeDiv.innerHTML = html;
        status.innerHTML = `<b>${data.broj}</b> od <b>20</b>`;
        azurirajSrca();

        const opcije = document.querySelectorAll('.odgovori-opcije');
        data.odgovorKliknut = false;

        opcije.forEach((opcija, index) => {
            opcija.addEventListener('click', () => {
                if (data.odgovorKliknut) return;
                data.odgovorKliknut = true;
                clearInterval(timerInterval);

                if (data.odgovori[index].tocan) {
                    brojTocnih++;
                } else {
                    zivoti--;
                    azurirajSrca();
                    if (zivoti <= 0) {
                        prikaziOdgovore(data);
                        krajPoraz();
                        return;
                    }
                }

                opcije.forEach((o, i) => {
                    o.classList.remove('odgovori-opcijeTočno', 'odgovori-opcijeNetočno');
                    const span = document.createElement('span');
                    span.classList.add('material-symbols-outlined');
                    if (data.odgovori[i].tocan) {
                        o.classList.add('odgovori-opcijeTočno');
                        span.textContent = 'check_circle';
                        o.appendChild(span);
                    } else if (i === index) {
                        o.classList.add('odgovori-opcijeNetočno');
                        span.textContent = 'cancel';
                        o.appendChild(span);
                    }
                });

                opcije.forEach(o => o.style.pointerEvents = 'none');
            });
        });

        pokreniTimer(() => {}, data);
    }

    gumb.addEventListener('click', () => {
        const opcije = document.querySelectorAll('.odgovori-opcije');
        const answered = Array.from(opcije).some(o => o.style.pointerEvents === 'none');

        if (!answered) {
            const potvrda = confirm("Niste odabrali odgovor! Sigurno želite prijeći na sljedeće pitanje? Ovo će se računati kao netočan odgovor.");
            if (!potvrda) return;

            zivoti--;
            azurirajSrca();

            opcije.forEach(o => {
                if (o.dataset.correct === "true") {
                    const span = document.createElement('span');
                    span.classList.add('material-symbols-outlined');
                    span.textContent = 'check_circle';
                    o.appendChild(span);
                    o.classList.add('odgovori-opcijeTočno');
                }
                o.style.pointerEvents = 'none';
            });

            if (zivoti <= 0) {
                krajPoraz();
                return;
            }
        }

        offset++;
        ucitajPitanje();
    });
});
