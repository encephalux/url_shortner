<?php
if (!isset($_SESSION['email'])) {
    header("Location:index.php");
    exit();
}
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['soumettre'])) {
    include('../lib/bd_connexion.php');
/** @var TYPE_NAME $conn */
$nom = trim($_POST['nom']);
$prenom = trim($_POST['prenom']);
$email = trim($_POST['email']);
$role = trim($_POST['role']);

if (empty($nom) || empty($prenom) || empty($email) || empty($role)) {
$champ_vide = "Veuillez remplir tous les champs";
} else {
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$email_erreur = "L'adresse email n'est pas valide.";
} 

else {
    $verifie_duplicate=$conn->prepare('SELECT COUNT(*)FROM utilisateur WHERE email=:email');
        $verifie_duplicate->bindValue(':email', $email);
        $verifie_duplicate->execute();
        if ($verifie_duplicate->fetchColumn() > 0) {
            $erreur_duplicate="Vous avez déjà enrégistré ce email";
        }
else{       

$token = bin2hex(random_bytes(16));
$id_employe = $conn->lastInsertId();

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
$mail->Subject = 'Création de compte';

// URL avec le jeton unique
$url = "http://localhost/url_shortner/pages/first_login.php?token={$token}";

$mail->Body    = "Enregistrement effectué avec succès. Veuillez créer votre compte en cliquant sur ce lien <a href='{$url}'>Créer mon compte maintenant</a>";
$mail->AltBody = "Enregistrement effectué avec succès. Veuillez créer votre compte en suivant ce lien : {$url}";

$mail->send();
$insertion = $conn->prepare('INSERT INTO utilisateur (id_utilisateur, nom, prenom, email, role, token) VALUES(0, :nom, :prenom, :email, :role, :token)');
$insertion->execute(array(
'nom' => $nom,
'prenom' => $prenom,
'email' => $email,
'role' => $role,
'token' => $token
));
$suces = "Insertion réussie et email envoyé";
} catch (Exception $e) {
$echec_envoie="L'email n'a pas pu être envoyé";
}
}
}
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../assets/css/inscrire.css"/>
    <title>Inscrire</title>
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
.deconnexion a{
    text-decoration:none;
    color:white;
}
.deconnexion a:hover{
    color:#0084CA;
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
            <li><a href="utilisateur.php">Utilisateur</li>
        </ul>
    </div>
    <div class="fin">
        <div class="changer"><a href="changer.php">Changer mon mot de passe</a> </div>
        <div class="deconnexion"><button><a href="index.php">Déconnexion</a></button></div>

    </div>
</header>

<div class="formulaire">
    <fieldset>
        <legend>Ajouter un utilisateur</legend>
    <div class="erreur <?php echo (isset($password_erreur)||isset($email_erreur)||isset($email_existe)||isset($echec_envoie)||isset($erreur_duplicate)) ? '' : 'hidden'; ?>">
    <?php
    echo isset($email_existe) ? $email_existe : '';
    echo isset($champ_vide) ? $champ_vide  : '';
    echo isset($email_erreur) ? $email_erreur : '';
    echo isset($erreur_duplicate) ? $erreur_duplicate : '';
    echo isset($echec_envoie) ? $echec_envoie : ''; 
    ?>
    </div>

    <div class="insertion <?php echo (isset($suces)) ? '' : 'hidd' ; ?>">
        <?php
        echo isset($suces) ? $suces  : '';
        ?>
    </div>

    <div class="form">
    <form action="" method="post">
        <div class="email"><input type="text" placeholder="Nom" name="nom" required/> </div>
        <div class="email"><input type="text" placeholder="Prénoms" name="prenom" required/></div>
       <div class="email"><input type="text" placeholder="Email" name="email" required/></div>
        <div class="email">
            <select name="role" id="role">
                <option value="employe">Employé</option>
                <option value="administrateur">Administrateur</option>
            </select>
        </div>
        <div class="bouton"><input type="submit" value="Enrégistrer" name="soumettre"/></div>
    </form>
    </div>
</div>
</body>
</html>