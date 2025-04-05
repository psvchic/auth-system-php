<?php
session_start();
if(isset($_SESSION['login'])){
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona</title>
</head>
<body>
    <header><h1>Strona główna</h1></header>
    <main>
        <p>nwm co tu napisać ale strona główna to jest</p>
        <p id="linki-logowanie">
            <a href="./logIn.php">Zaloguj się</a>&nbsp
            <a href="./register.php">Zarejestruj się</a>
        </p>
    </main>
</body>
</html>