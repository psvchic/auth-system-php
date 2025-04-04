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
        <form method="post" action="./register.php">
            <h2>LOGIN</h2>
            <input type="text" minlength="3" maxlength="60" placeholder="Wpisz swój login" class="inputy" name="login" required>
            <h2>EMAIL</h2>
            <input type="email" placeholder="Wpisz swój email" name="email" class="inputy" maxlength="100" required>
            <h2>HASŁO</h2>
            <input type="password" placeholder="Wpisz hasło" name="haslo"  class="inputy"  minlength="3" maxlength="60" required><br>
            <input type="submit" id="zaloguj" value="ZAREJESTRUJ">
        </form>
        <p id="zarejestrowano">Zarejestrowano pomyślnie!<br><span id="przekierowywanie"></span></p>
        <a href="./logIn.php" id="masz-konto">Masz już konto?</a>
    </section>
    <script>
        document.querySelectorAll(".inputy").forEach(input => {
            input.addEventListener("input", function() {
            this.value = this.value.replace(/\s/g, "");
        });
  });
    </script>
</body>
</html>
<?php
if(!empty($_POST)){
    $login = $_POST['login'];
    $password = $_POST['haslo'];
    $email = $_POST['email'];
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    $link = mysqli_connect("localhost", "root", "", "portal");
    $sql = "INSERT INTO `uzytkownicy`(`login`, `email`, `haslo`) VALUES($login, $email, $password)";
    $query = mysqli_query($link, $sql);
    if($query){
        echo "<style>#zarejestrowano{visibility: visible;}</style>";
        header("refresh:2;url=logIn.php");
    }
}
?>