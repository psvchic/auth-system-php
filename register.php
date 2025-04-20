<?php
session_start();
if(isset($_SESSION['login'])){
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/registerStyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <title>Zarejestruj się</title>
</head>
<body>
    <section id="strona-rejestracji">
        <form action="./register.php" method="post">
            <h2>LOGIN</h2>
            <input type="text" minlength="3" maxlength="60" placeholder="Wpisz swój login" class="inputy" name="login" required>
            <h2>EMAIL</h2>
            <input type="email" placeholder="Wpisz swój email" name="email" class="inputy" maxlength="100" required>
            <h2>HASŁO</h2>
            <input type="password" placeholder="Wpisz hasło" name="haslo"  class="inputy"  minlength="3" maxlength="60" required><br>
            <input type="submit" id="zarejestruj" value="ZAREJESTRUJ" name="rejestracja">
        </form>
        <p id="wynik-rejestracji"><br><span id="przekierowywanie"></span></p>
        <a href="./logIn.php" id="masz-konto">Masz już konto?</a>
    </section>

    <section id="weryfikacja">
        <p>Przysłaliśmy Ci kod na adres e-mail który podałeś/aś. Wpisz kod w pole poniżej</p>
        <form action="./register.php" method="post">
            <input type="text" class="inputy" name="kod" minlength="6" maxlength="6">
            <input type="submit" value="Zweryfikuj" name="weryfikacja">
        </form>
        <p id="bledny-kod"></p>
    </section>

    <script>
        document.querySelectorAll(".inputy").forEach(input => {
            input.addEventListener("input", function() {
            this.value = this.value.replace(" ", "");
        });
  });
    </script>

</body>
</html>
<?php

if(!empty($_POST['rejestracja'])){
    $login = $_POST['login'];
    $password = $_POST['haslo'];
    $email = $_POST['email'];
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
    $verificationCode = '';
    for($i = 0; $i < 6; $i++){
        $verificationCode .= rand(0, 9);
    }

    $pdo = new PDO("mysql:host=localhost;dbname=portal;charset=utf8mb4", "root", "");
    $stmt = $pdo->prepare("INSERT INTO `uzytkownicy`(`login`, `email`, `haslo`, `kod`, `zweryfikowany`) VALUES (?, ?, ?, ?, 'nie')");
    $stmtCheckIfExists = $pdo->prepare("SELECT `login` FROM `uzytkownicy` WHERE login = ? OR email = ?");
    $stmtCheckIfExists->execute([$login, $email]);
    $checkIfExists = $stmtCheckIfExists->fetch();

    if(($checkIfExists)){
        echo '<style>#wynik-rejestracji::before{content: "Użytkownik o podanym loginie/emailu już istnieje!"; color: red; visibility: visible;}</style>';
    }else{
        $register = $stmt->execute([$login, $email, $hashed_pass, $verificationCode]);
        if($register){
            $_SESSION['temp-login'] = $login;
            // SCRIPT FOR SENDING MAIL HERE (e.g. using PHPMailer)
            echo '<style>#weryfikacja{visibility: visible;}</style>';
        }else{
            echo "<style>#wynik-rejestracji::before{content: 'Błąd rejestracji!'; color: red; visibility: visible;}</style>";
        }
    }
}
if(!empty($_POST['weryfikacja'])){
    $codeFromInput = $_POST['kod'];
    $login = $_SESSION['temp-login'];
    
    $pdo = new PDO("mysql:host=localhost;dbname=portal;charset=utf8mb4", "root", "");
    $stmtCheckCode = $pdo->prepare("SELECT `kod` FROM `uzytkownicy` WHERE `kod` = ? AND `login` = ?");
    $stmtCheckCode->execute([$codeFromInput, $login]);
    $checkCode = $stmtCheckCode->fetch();
    $stmtUpdateVerified = $pdo->prepare("UPDATE `uzytkownicy` SET `zweryfikowany`= 'tak' WHERE `login` = ?");

    if($checkCode){
        $stmtUpdateVerified->execute([$login]);
        session_destroy();
        echo '<style>#wynik-rejestracji::before{content: "Zarejestrowano pomyślnie"; color: green; visibility: visible;} #przekierowywanie::after{visibility: visible;}</style>';
        header("refresh:2;url=logIn.php");
    }else{
        echo "<style>#bledny-kod::before{content: 'Zły kod! Spróbuj ponownie'; color: red; visibility: visible;}</style>";
        echo '<style>#weryfikacja{visibility: visible;}</style>';

    }
}
?>