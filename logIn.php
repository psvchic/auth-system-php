<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/loginStyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <title>Strona Logowania</title>
</head>
<body>
    <section id="strona-logowania">
        <form method="post" action="./logIn.php">
            <h2>LOGIN</h2>
            <input type="text" minlength="3" maxlength="100" placeholder="Wpisz swój login" name="login" required>
            <h2>HASŁO</h2>
            <input type="password" placeholder="Wpisz hasło" name="haslo" required><br>
            <input type="submit" id="zaloguj" value="ZALOGUJ">
        </form>
        <p id="blad-logowania">Niepoprawny login lub hasło!</p>
        <a href="./register.php" id="masz-konto">Nie masz konta?</a>
    </section>
</body>
</html>
<?php
if(!empty($_POST)){
    $login = $_POST['login'];
    $password = $_POST['haslo'];
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
    
    $link = mysqli_connect("localhost", "root", "", "portal");
    $sql = "SELECT `login`, `haslo` FROM uzytkownicy WHERE login = '$login' AND haslo = '$hashed_pass'";
    $query = mysqli_query($link, $sql);
    if(mysqli_num_rows($query) == 1){
        header("url=index.php");
    }
    else{
        echo "<style>#blad-logowania{visibility: visible;}</style>";
    }
}
?>