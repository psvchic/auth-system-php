<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona główna</title>
</head>
<body>
    <header>
        <h1>Witamy, <?php echo htmlspecialchars($_SESSION['login']); ?>!</h1>
    </header>
    <section>
        <a href="./logout.php">Wyloguj się</a>
    </section>
</body>
</html>