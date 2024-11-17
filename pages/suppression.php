<?php
include('../lib/bd_connexion.php');
session_start();
/** @var TYPE_NAME $conn */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    if (isset($_POST['supprimer'])) {
        $sql = $conn->prepare('DELETE FROM compte_utilisateur WHERE id_compte = :id');
        $sql->execute(array('id' => $id));
        echo '<form id="redirectForm" method="POST" action="utilisateur.php">';
        echo '<input type="hidden" name="message_suppression" value="Compte supprimé avec succès." />';
        echo '</form>';
        echo '<script type="text/javascript">document.getElementById("redirectForm").submit();</script>';
//        header('Location: utilisateur.php');
        exit();

    }
} else {
    if (!isset($_GET['id'])) {
        echo "Aucun ID d'utilisateur fourni.";
        exit();
    }
    $id = htmlspecialchars($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../assets/css/suppression.css"/>
    <title>Suppression</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="../assets/media/images/logo.jpg"/>
    </div>
    <div class="liens">
        <ul>
            <li><a href="racourcir.php">Raccourcir</a></li>
            <li><a href="#">Liste</a></li>
            <li><a href="utilisateur.php">Utilisateur</a></li>
        </ul>
    </div>
    <div class="fin">
        <div class="changer"><a href="#">Changer mon mot de passe</a></div>
        <div class="deconnexion"><button>Deconnexion</button></div>
    </div>
</header>
<div class="global">
    <div class="boite">
        <div class="phrase">Voulez-vous vraiment supprimer ce compte?</div>
        <div class="explication">Une fois que le bouton oui sera cliqué, le compte sera supprimé définitivement.</div>
        <div class="boutons">
            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <input type="submit" name="supprimer" value="Oui"/>
            </form>
            <button><a href="utilisateur.php">Non</a></button>
        </div>
    </div>
</div>

</body>
</html>