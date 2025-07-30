<?php
session_start();
$poruke = isset($_SESSION['poruke']) ? $_SESSION['poruke'] : '';
$korisnicko_ime = isset($_SESSION['korisnicko_ime']) ? $_SESSION['korisnicko_ime'] : '';
$prikaziPoruku = isset($_SESSION['prijava_pokusaj']) ? $_SESSION['prijava_pokusaj'] : false;

unset($_SESSION['prijava_pokusaj']);
unset($_SESSION['poruke']);
?>
<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava - Put oko svijeta</title>
    <link rel="stylesheet" href="css/stylePrijava-Registracija.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="form-box">
            <h2><i class="fa-solid fa-right-to-bracket"></i> Prijava</h2>

            <?php if ($prikaziPoruku && !empty($poruke)): ?>
                <div class="error-message" style="color:red; margin:10px 0;">
                    <?= htmlspecialchars($poruke) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($korisnicko_ime)): ?>
                <div class="success-message" style="color:green; margin:15px 0;">
                    Dobrodošao, <strong><?= htmlspecialchars($korisnicko_ime) ?></strong>!
                    <form action="biblioteke/odjava.php" method="post" style="display:inline;">
                        <button type="submit" class="btn back-btn">
                            <i class="fa-solid fa-sign-out-alt"></i> Odjavi se
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <form action="biblioteke/prijavaProvjera.php" method="post">
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="username" placeholder="Korisničko ime" required>
                    </div>

                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" placeholder="Lozinka" required>
                    </div>

                    <button type="submit" name="prijavaButton" class="btn main-btn">
                        <i class="fa-solid fa-sign-in-alt"></i> Prijavi se
                    </button>

                    <a href="index.php" class="btn back-btn">
                        <i class="fa-solid fa-arrow-left"></i> Povratak na početnu
                    </a>

                    <p>Nemate račun? <a href="registracija.php">Registrirajte se</a></p>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>