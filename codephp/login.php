<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style_css/style_login.css">
    <title>Document</title>
</head>
<body>
<div class="login">
    <div class="erreur"></div>
    <div class="logo"><img src="../images/logo.jpg"/></div>
    <form action="" method="post">
        <div class="email"><input type="text" name="email" placeholder="Email"/></div>
        <div class="mot_de_passe"><input type="password" name="mot_de_passe" placeholder="Mot de passe"/></div>
        <div class="bouton"><input type="submit" value="Se connecter" name="connecter"/></div>
    </form>
</div>
</body>
</html>
<?php
include('bd_connexion.php');
if (isset($_POST['connecter'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    /** @var TYPE_NAME $conn */
    $sql_login = $conn->query('SELECT COUNT(*) as nombre FROM compte_utilisateur WHERE email="' . $email . '" AND mot_de_passe=md5("' . $mot_de_passe . '")');
    $data = $sql_login->fetch();
    if ($data[0] == 1) {
//        $sql_user = $conn->query('select * FROM compte_utilisateur
//	    WHERE email="' . $email . '" AND mot_de_passe="' . $mot_de_passe . '"');
//        $data_user = $sql_user->fetch();
//        SESSION_start();
//        $_SESSION['email'] = $email;
//        $_SESSION['mot_de_passe'] = $mot_de_passe;
        header("Location: to_short.php");
        exit();
    } else {
        $sql_email = $conn->query('SELECT COUNT(*) as nombre FROM compte_utilisateur WHERE email="' . $email . '"');
        $cpte = $sql_email->fetch();

        $sql_password = $conn->query('SELECT COUNT(*) as nombre FROM compte_utilisateur WHERE mot_de_passe=md5("' . $mot_de_passe . '")');
        $cpte_password = $sql_password->fetch();
        if ($cpte[0] == 1 && $cpte_password[0] == 0) {

            echo "Mot de passe incorrect";

        } else {
            SESSION_start();
            $_SESSION['email'] = $email;
            $_SESSION['mot_de_passe'] = $mot_de_passe;
            header("Location: erreur.php");
            exit();
        }
    }

}
?>