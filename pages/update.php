<?php
include('../lib/bd_connexion.php');

$id_utilisateur = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;

if ($id_utilisateur) {
    $secret_key = 'votre_cle_secrete';
    $calculated_hash = hash_hmac('sha256', $id_utilisateur, $secret_key);
    /** @var TYPE_NAME $conn */
    $sql = $conn->prepare('SELECT * FROM compte_utilisateur WHERE id_compte=:id_compte AND reset_hash=:hash AND token_expiration > NOW()');
    $sql->execute(array(':id_compte' => $id_utilisateur, ':hash' => $calculated_hash));
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if(isset($_POST['soumettre'])) {
            $mot_de_passe=$_POST['mot_de_passe'];
            $confirmation=$_POST['confirmation'];
            $mot_de_passe_valide = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $mot_de_passe);
            if ($mot_de_passe_valide) {

            if ($mot_de_passe === $confirmation) {
                $password_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);
                $update = $conn->prepare('UPDATE compte_utilisateur SET mot_de_passe =:mot_de_passe WHERE id_compte = :id_compte');
                $update->execute(array('mot_de_passe' => $password_hache, 'id_compte' => $id_utilisateur));
                header('Location:index.php');
            }
            }
            else{
                $message_password = "8 caractères:lettre majuscule,minuscule,un chiffre et un caractère spécial";
            }
                $password_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);

            }

    }
    else {
        echo "Lien invalide ou expiré.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../assets/css/update.css"/>
    <title>Reinitialiser</title>
</head>
<body>
<form action="" method="post">
        <div class="form">
            <div class="logo"><img src="../assets/media/images/logo.jpg" alt=""/> </div>
            <div class="erreur <?php echo (isset($message_password)) ? '' : 'hidden'; ?>">
                <?php echo isset($message_password) ? $message_password : ''; ?>
            </div>
            <div class="mot_de_passe"><input type="password" placeholder="Mot de passe" name="mot_de_passe" required/></div>
            <div class="mot_de_passe"><input type="password" placeholder="Mot de passe de confirmation" name="confirmation" required/></div>
            <div class="bouton"><input type="submit" value="Modifier" name="soumettre"/></div>
        </div>
    </form>
</body>
</html>