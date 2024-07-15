<?php
session_start();
include('../lib/bd_connexion.php');

$password_error = !1;
$unregistered = !1;

if (isset($_POST['submit'])) {
    if($_POST["submit"] === "Se connecter") {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        $sql_login = $conn->query('SELECT COUNT(*) as nombre FROM compte_utilisateur WHERE email="' . $email . '" AND mot_de_passe=md5("' . $mot_de_passe . '")');
        $data = $sql_login->fetch();

        $sql_email = $conn->query('SELECT COUNT(*) as nombre FROM compte_utilisateur WHERE email="' . $email . '"');
        $cpte = $sql_email->fetch()[0];

        if ($cpte) {
            $sql_password = $conn->query('SELECT COUNT(*) as nombre FROM compte_utilisateur WHERE mot_de_passe=md5("' . $mot_de_passe . '")');
            $cpte_password = $sql_password->fetch()[0];

            if ($cpte_password > 0) {
                $_SESSION["user"] = $email;
                header("Location: pages/dashboard.php");
            } else {
                $password_error = !0;
            }
        } else {
            $unregistered = !0;
        }
    } elseif($_POST["submit"] === "Créer un compte") {
        echo "compte créé";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="../assets/css/login.css"/>
        <title>Url Shortner</title>
    </head>
    <body>
        <div class="login">
            <div class="logo"><img src="../assets/media/images/logo.jpg" alt=""/></div>
            <?php if($password_error) {
                ?>
                <div class="erreur">Mot de passe incorrect</div>
                <?php
            } ?>
            <?php if($unregistered) {
                ?>
                <div class="unregistered">
                    <p>Aucun compte n'existe pour ces paramètres.</p>
                    <p>Vous pouvez en créer en cliquant sur le bouton "créer un compte"</p>
                </div>
                <?php
            } ?>
            <form action="" method="post">
                <div class="email"><input type="text" name="email" placeholder="Email" value="<?php echo $unregistered === !0 ? $_POST["email"] : "" ?>"/></div>
                <div class="mot_de_passe"><input type="password" name="mot_de_passe" placeholder="Mot de passe" value="<?php echo $unregistered === !0 ? $_POST["mot_de_passe"] : "" ?>"/></div>
                <div class="bouton"><input type="submit" value="<?php echo $unregistered === !0 ? "Créer un compte" : "Se connecter" ?>" name="submit"/></div>
            </form>
            <?php if($unregistered) {
                ?>
                <a href="/pages/login.php" class="login">Login</a>
                <?php
            } ?>
        </div>
    </body>
</html>
