<?php
session_start();
if(isset($_POST['envoyer'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    include('../lib/bd_connexion.php');
    /** @var TYPE_NAME $conn */

    $sql_email = $conn->prepare("SELECT * FROM compte_utilisateur WHERE email=:email");
    $sql_email->execute(array(':email' => $email));
    $user = $sql_email->fetch(PDO::FETCH_ASSOC);
    if ($user !== false) {
        if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] =="administrateur") {
               $_SESSION['user'] = $email;
                header("location:utilisateur.php");
                exit();
            }
            else {
                $_SESSION['email'] = $email;
                header("Location:racourcir.php");
                exit();
            }
        }
        else {
            $message_password = "Email ou Mot de passe incorrect";
        }
    }
    else {
        $email_erreur = "Email ou mot de passe incorrect";
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../assets/css/login.css"/>
    <title>Login</title>
</head>
<body>
<div class="container">
<div class="formulaire_container">
    <div class="logo_form">
    <div class="logo">
        <img src="../assets/media/images/logo.jpg"/>
    </div>
        <div class="text_logo">
            Veuillez vous connecter et accéder au tableau de bord
        </div>
        <div class="erreur <?php echo (isset($message_password)||isset($email_erreur)) ? '' : 'hidden'; ?>">
            <?php
            echo isset($message_password) ? $message_password  : '';
            echo isset($email_erreur) ? $email_erreur : '';
            ?>
        </div>
        <div class="form">
            <form action="" method="post">
            <div class="email"><input type="text" placeholder="Email" name="email" required/></div>
            <div class="mot_de_passe"><input type="password" placeholder="Mot de passe" name="mot_de_passe" required/></div>
            <div class="bouton"><input type="submit" value="Envoyer" name="envoyer"/></div>
            </form>
        </div>
    </div>
</div>

    <div class="instruction">
    <div class="gras">Transformez vos URL en un CLIC !</div>
        <div class="description">
            <p>Short URL est un outil de raccourcissement et de gestion des ULR.</p>
            <p>Il vise  à offrir aux entreprises partenaires de TIDD un espace
            privé de raccourcissement des URL.</p>
        </div>
    </div>
</div>
</body>
</html>