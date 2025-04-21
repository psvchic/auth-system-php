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
            <input type="text" minlength="3" maxlength="100" placeholder="Wpisz swój login lub e-mail" name="login" required>
            <h2>HASŁO</h2>
            <input type="password" placeholder="Wpisz hasło" name="haslo" required><br>
            <input type="submit" id="zaloguj" value="ZALOGUJ">
        </form>
        <p id="blad-logowania">Niepoprawny login lub hasło!</p>
        <a href="./register.php" id="masz-konto">Nie masz konta?</a>
    </section>
    <p id="blad-weryfikacji">Nie jesteś zweryfikowany!</p>
    <p id="weryfikuj">Aby się zweryfikować <a href="./mail-verification.php">kliknij tutaj</a></p>
</body>
</html>
<?php
if(!empty($_POST)){
    $login = $_POST['login'];
    $password = $_POST['haslo'];
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    $pdo = new PDO("mysql:host=localhost;dbname=portal;charset=utf8mb4", "root", "");
    $stmt = $pdo->prepare("SELECT login, haslo, email, zweryfikowany FROM uzytkownicy WHERE login = ? OR email = ?");
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch();

    if($user){
        if(password_verify($password, $user['haslo'])){
            if($user['zweryfikowany'] == 'tak'){
                $_SESSION['login'] = $user['login'];
                header('Location: index.php');
                exit;
            }else{
                echo '<style>#blad-weryfikacji{visibility: visible;}</style>';
                echo '<style>#weryfikuj{visibility: visible;}</style>';
            }
        }else{
            echo '<style>#blad-logowania{visibility: visible;}</style>';
        }
    }
    else{
        echo '<style>#blad-logowania{visibility: visible;}</style>';
    }
}
?>
