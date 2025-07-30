<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija - Put oko svijeta</title>
    <link rel="stylesheet" href="css/stylePrijava-Registracija.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="form-box">
            <h2><i class="fa-solid fa-user-plus"></i> Registracija</h2>

            <?php
            session_start();
            if (isset($_SESSION['poruke'])) {
                echo "<p style='color: red; font-weight: bold; text-align: center;'>" . $_SESSION['poruke'] . "</p>";
                unset($_SESSION['poruke']);
            }
            ?>

            <form action="biblioteke/registracijaProvjere.php" method="post">
                <div class="input-group">
                    <i class="fa-solid fa-id-card"></i>
                    <input type="text" name="ime" placeholder="Ime" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-id-card"></i>
                    <input type="text" name="prezime" placeholder="Prezime" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="username" placeholder="Korisničko ime" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Lozinka" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="repeat-password" placeholder="Ponovite lozinku" required>
                </div>

                <button type="submit" class="btn main-btn"><i class="fa-solid fa-user-plus"></i> Registriraj se</button>

                <a href="index.php" class="btn back-btn"><i class="fa-solid fa-arrow-left"></i> Povratak na početnu</a>

                <p>Već imate račun? <a href="prijava.php">Prijavite se</a></p>
            </form>
        </div>
    </div>
</body>

</html>