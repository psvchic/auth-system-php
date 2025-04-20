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
    <link rel="stylesheet" href="./styles/verificationStyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <title>Weryfikacja mailowa</title>
</head>
<body>
    <section id="strona-weryfikacji">
        <section id="weryfikacja-email">
            <h2>Wpisz swój e-mail</h2>
            <form action="./mail-verification.php" method="post">
                <input type="mail" placeholder="Wpisz swój e-mail" name="mail">
                <input type="submit" value="Wyślij" name="wyslij-email">
            </form>
        </section>
        <section id="weryfikacja-kod">
            <h2>Wpisz otrzymany kod</h2>
            <form action="./mail-verification.php" method="post">
                <input type="text" name="kod" minlength="6" maxlength="6" placeholder="Wpisz kod">
                <input type="submit" value="Zweryfikuj" name="weryfikacja">
            </form>
            <p id="wynik-wyslania-maila"></p>
            <p id="wynik-weryfikacji"><br><span id="przekierowywanie"></span></p>
        </section>
    </section>
</body>
</html>
<?php
if(!empty($_POST['wyslij-email'])){
    $email = $_POST['mail'];
    $link = mysqli_connect("localhost", "root", "", "portal");
    $sqlCheckEmail = "SELECT `kod` FROM `uzytkownicy` WHERE `email` = '$email'";
    $sqlCheckIfVerified = "SELECT `zweryfikowany` FROM `uzytkownicy` WHERE `email` = '$email'";
    $queryCheckVerified = mysqli_query($link, $sqlCheckIfVerified);
    $queryCheckEmail = mysqli_query($link, $sqlCheckEmail);
    $checkEmail = mysqli_num_rows($queryCheckEmail);
    $code = mysqli_fetch_assoc($queryCheckEmail);
    $verified = mysqli_fetch_assoc($queryCheckVerified);
    
    if($checkEmail == 1){
        if($verified['zweryfikowany'] == 'tak'){
            echo '<style>#wynik-wyslania-maila::before{content: "Mail już został zweryfikowany!"; visibility: visible;}</style>';
        }else{
            $_SESSION['temp-mail'] = $email;
            // SCRIPT FOR SENDING MAIL HERE (e.g. using PHPMailer)
            echo '<style>#wynik-wyslania-maila::before{content: "Wysłano kod na edres e-mail!"; visibility: visible; color: green; margin-bottom: 100px;} #weryfikacja-kod{visibility: visible;} #weryfikacja-email{visibility: hidden} </style>';   
        }
    }else{
        echo '<style>#wynik-wyslania-maila::before{content: "Błędny adres e-mail! Spróbuj ponownie"; visibility: visible;}</style>';
    }
}
if(!empty($_POST['weryfikacja'])){
    $codeFromInput = $_POST['kod'];
    $email = $_SESSION['temp-mail'];
    $link = mysqli_connect("localhost", "root", "", "portal");
    $sqlCheckCode = "SELECT `kod` FROM `uzytkownicy` WHERE `kod` = '$codeFromInput'";
    $sqlUpdateVerified = "UPDATE `uzytkownicy` SET `zweryfikowany`= 'tak' WHERE `email` = '$email'";
    $queryCheckCode = mysqli_query($link, $sqlCheckCode);
    $codeFromDatabase = mysqli_num_rows($queryCheckCode);
    if($codeFromDatabase == 1){
        mysqli_query($link, $sqlUpdateVerified);
        session_destroy();
        echo '<style>#wynik-weryfikacji::before{content: "Zweryfikowano pomyślnie!"; color: green; visibility: visible;} #przekierowywanie::after{visibility: visible;} #weryfikacja-kod{visibility: visible;} #weryfikacja-email{visibility: hidden}</style>';
        header("refresh:2;url=logIn.php");
    }else{
        echo "<style>#wynik-weryfikacji::before{content: 'Zły kod! Spróbuj ponownie'; color: red; visibility: visible;} #weryfikacja-kod{visibility: visible;} #weryfikacja-email{visibility: hidden}</style>";

    }
}
?>