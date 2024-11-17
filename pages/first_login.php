<?php
require '../vendor/autoload.php';
include('../lib/bd_connexion.php');

/** @var TYPE_NAME $conn */

$token = $_GET['token'] ?? '';
$formulaire_visible = true;


if ($token) {
$query = $conn->prepare('SELECT * FROM utilisateur WHERE token = :token');
$query->execute(array('token' => $token));
$employe = $query->fetch();

if ($employe) {
if ($employe['etat_connexion']) {
$formulaire_visible = false;
echo "Ce lien a déjà été utilisé.";
} else {
if (isset($_POST['soumettre'])) {
$mot_de_passe = $_POST['mot_de_passe'];
$confirmation = $_POST['confirmation'];

$mot_de_passe_valide = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $mot_de_passe);

if ($mot_de_passe_valide) {

if ($mot_de_passe === $confirmation) {

$password_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);

$insert = $conn->prepare('INSERT INTO compte_utilisateur (email, mot_de_passe, role) VALUES (:email, :mot_de_passe, :role)');
$insert->execute(array(
'email' => $employe['email'],
'mot_de_passe' => $password_hache,
'role' => $employe['role']
));

$update = $conn->prepare('UPDATE utilisateur SET etat_connexion = TRUE WHERE token = :token');
$update->execute(array('token' => $token));
header('Location:index.php');
} else {
$message_password = "Les mots de passe ne correspondent pas.";
}
} else {
$message_password = "8 caractères:lettre majuscule,minuscule,un chiffre et un caractère spécial";


}
} else {
//echo "Lien valide. Vous pouvez créer votre compte.";
}
}
} else {
echo "Lien invalide.";
$formulaire_visible = false;
}
} else {
echo "Lien invalide.";
$formulaire_visible = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../assets/css/first_login.css"/>
    <title>First_connection</title>
</head>
<body>
<?php if ($formulaire_visible): ?>
    <form action="" method="post">
        <div class="form">
            <div class="logo"><img src="../assets/media/images/logo.jpg" alt=""/> </div>
            <div class="erreur <?php echo (isset($message_password)) ? '' : 'hidden'; ?>">
                <?php echo isset($message_password) ? $message_password : ''; ?>
            </div>
            <div class="mot_de_passe"><input type="password" placeholder="Mot de passe" name="mot_de_passe" required/></div>
            <div class="mot_de_passe"><input type="password" placeholder="Mot de passe de confirmation" name="confirmation" required/></div>
            <div class="bouton"><input type="submit" value="Enregistrer" name="soumettre"/></div>
        </div>
    </form>
<?php endif; ?>
</body>
</html>
