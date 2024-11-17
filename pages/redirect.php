<?php

include('../lib/bd_connexion.php');

/** @var TYPE_NAME $conn */

if (isset($_GET['u'])) {
    $identifiant = $_GET['u'];
    $stmt = $conn->prepare("SELECT long_lien FROM lien WHERE identifiant = :identifiant");
    $stmt->execute(array(':identifiant' => $identifiant));

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $long_lien = $row['long_lien'];
        header("Location: " . $long_lien);
        exit();
    } else {
        echo "Identifiant invalide.";
    }
} else {
    echo "Aucun identifiant fourni.";
}
?>