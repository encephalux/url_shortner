<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('../lib/bd_connexion.php');
session_start();

$id_utilisateur = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : null;

if ($id_utilisateur) {
    /** @var TYPE_NAME $conn */
    $sql = $conn->prepare('SELECT email FROM compte_utilisateur WHERE id_compte=:id_compte');
    $sql->execute(array(':id_compte' => $id_utilisateur));
    $resultat = $sql->fetchAll(PDO::FETCH_ASSOC);
    $email = $resultat[0]['email'];

    // Génération du hachage sécurisé
    $secret_key = 'votre_cle_secrete'; // Clé secrète à conserver en sécurité
    $hash = hash_hmac('sha256', $id_utilisateur, $secret_key);

    // Stocker le hachage dans la base de données
    $sql_hash = $conn->prepare('UPDATE compte_utilisateur SET reset_hash=:hash, token_expiration=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id_compte=:id_compte');
    $sql_hash->execute(array(':hash' => $hash, ':id_compte' => $id_utilisateur));

    if (isset($_POST['update'])) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';

            $mail->Username = 'lv.eklou2@gmail.com';
            $mail->Password = 'bgjj zmrt jxxw rgsi';

            $mail->setFrom('lv.eklou2@gmail.com', 'SHORT URL');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Subject = 'Réinitialisation de mot de passe';

            // Utiliser le hachage dans l'URL
            $url = "http://localhost/url_shortner/pages/update.php?id={$id_utilisateur}";

            $mail->Body = "Réinitialiser votre mot de passe via ce lien <a href='{$url}'>Réinitialiser mon compte maintenant</a>";
            $mail->AltBody = "Réinitialiser votre mot de passe en suivant ce lien : {$url}";

            $mail->send();
            header('location:utilisateur.php');
        } catch (Exception $e) {
            echo "L'email n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../assets/css/reinitialiser.css"/>
    <title>Reinitialiser</title>
    <style>
        .deconnexion button:hover{
            transform: scale(1.05);
            background-color:white;
            color:#0084CA;
            border:1px solid #0084CA;
            }
        .changer a:hover{
            color:  #0084CA;
        }
        .liens a:hover{
            color:  #0084CA;
        }
        .bouton input[type="submit"]:hover{
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<header>
    <div class="logo">
        <img src="../assets/media/images/logo.jpg"/>
    </div>
    <div class="liens">
        <ul>
            <li><a href="racourcir.php">Raccourcir</a></li>
            <li><a href="liste.php">Liste</a></li>
            <li><a href="utilisateur.php">Utilisateur</a></li>
        </ul>
    </div>
    <div class="fin">
        <div class="changer"><a href="changer.php">Changer mon mot de passe</a> </div>
        <div class="deconnexion"><button>Deconnexion</button></div>
    </div>
</header>
<div class="container">
    <div class="titre">Reinitialiser le mot de passe</div>
    <div class="avec_bordure">
        <div class="phrase">
            <div class="instruction">Reinitialiser le mot de passe du compte:</div>
            <div class="email <?php echo isset($email) ? '' : 'hidden'; ?>">
                <?php
                echo isset($email) ? $email : '';
                ?>
            </div>
        </div>
        <div class="form">
            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_utilisateur); ?>"/>
                <div class="champ">
                    <input type="text" name="email" placeholder="Email" required/>
                </div>
                <div class="bouton"><input type="submit" name="update" value="Modifier"/></div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
