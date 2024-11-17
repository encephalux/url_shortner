
<?php
session_start();

// Inclure la connexion à la base de données
$servername="localhost";
$username="root";
$password="";
try{
    $conn=new PDO("mysql:host=$servername;dbname=url_shortner",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e){
    echo "erreur de connexion".$e->getMessage();
}

// Vérifier si un identifiant est passé dans l'URL
if (isset($_GET['identifiant'])) {
    $identifiant = $_GET['identifiant'];

    $sql = $conn->prepare('SELECT long_lien FROM lien WHERE identifiant = :identifiant');
    $sql->execute([':identifiant' => $identifiant]);
    $resultat = $sql->fetch(PDO::FETCH_ASSOC);


    if ($resultat) {
        $update=$conn->prepare('UPDATE lien SET nb_clic=nb_clic+1 WHERE identifiant = :identifiant');
        $update->execute(['identifiant' => $identifiant]);
        $long_lien = $resultat['long_lien'];
        header("Location: $long_lien");
        exit();
    } else {
        header("location:pages/index.php");
    }
}
?>

