<?php
include('../lib/bd_connexion.php');
session_start();
/** @var TYPE_NAME $conn */
if (isset($_GET['id_lien'])) {
    $id = $_GET['id_lien'];
    if (isset($_POST['supprimer'])) {
        $sql = $conn->prepare('DELETE FROM lien WHERE id_lien = :id');
        $sql->execute(array('id' => $id));
        header('Location: liste.php');
        exit();

    }
} else {
    if (!isset($_GET['id_lien'])) {
        echo "Aucun ID d'utilisateur fourni.";
        exit();
    }
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
<div class="global">
    <div class="boite">
        <div class="phrase">Voulez-vous vraiment supprimer ce compte?</div>
        <div class="explication">Une fois que le bouton oui sera cliqué, le compte sera supprimé définitivement.</div>
        <div class="boutons">
            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <input type="submit" name="supprimer" value="Oui"/>
            </form>
            <button><a href="liste.php">Non</a></button>
        </div>
    </div>
</div>

</body>
</html>