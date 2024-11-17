<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location:index.php");
    exit();
}
if ($_SESSION['role'] !== 'employe' && $_SESSION['role'] !== 'administrateur') {
    header("Location:index.php");
    exit();
}

$lien_court = '';

if (isset($_POST['racourcir'])) {
    $email = $_SESSION['email'];
    include('../lib/bd_connexion.php');
    /** @var TYPE_NAME $conn */
    $sql = $conn->prepare('SELECT id_utilisateur FROM utilisateur WHERE email=:email');
    $sql->execute([':email' => $email]);
    $resultat = $sql->fetch(PDO::FETCH_ASSOC);
    $id = $resultat['id_utilisateur'];

    $long_lien = $_POST['long_lien'];
    $titre = $_POST['titre'] ?? '';
    $date = date('Y-m-d');

    if (filter_var($long_lien, FILTER_VALIDATE_URL)) {
        $verifie_duplicate=$conn->prepare('SELECT COUNT(*)FROM lien WHERE long_lien=:long_lien');
        $verifie_duplicate->bindValue(':long_lien', $long_lien);
        $verifie_duplicate->execute();
        if ($verifie_duplicate->fetchColumn() > 0) {
            $erreur_duplicate="URL déjà raccourcie. Recherche la dans vos historiques";
        }
        else{
        if (!empty($titre)) {
            $identifiant = str_replace(' ', '_', $titre);
        } else {
            $identifiant = chr(rand(97, 122)) . chr(rand(97, 122)) . chr(rand(97, 122)) . rand(0, 9);
        }
        $verifie=$conn->prepare('SELECT COUNT(*)FROM lien WHERE identifiant=:identifiant');
        $verifie->bindValue(':identifiant', $identifiant);
        $verifie->execute();
        if ($verifie->fetchColumn() > 0) {
            $erreur="Vous avez déjà donné ce nom à un lien changez de nom";
        }
        else{
        $lien_court = "http://localhost/url_shortner/$identifiant";
        $insertion = $conn->prepare('INSERT INTO lien (id_utilisateur, identifiant, long_lien, lien_court, date_creation)
        VALUES (:id_utilisateur, :identifiant, :long_lien, :lien_court, :date_creation)');
        $insertion->execute([
            'id_utilisateur' => $id,
            'identifiant' => $identifiant,
            'long_lien' => $long_lien,
            'lien_court' => $lien_court,
            'date_creation' => $date
        ]);
    }
}
    }else {
        echo "Le lien soumis n'est pas une URL valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../assets/css/racourcir.css"/>

    <title>Raccourcir</title>
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
        .erreur_duplicate{
    margin-left: 5%;
    color: red;
    font-size: 0.8em;
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
            <li><a href="Liste.php">Liste</a></li>
        </ul>
    </div>
    <div class="fin">
        <div class="changer"><a href="changer.php">Changer mon mot de passe</a></div>
        <div class="deconnexion"><button><a href="index.php">Deconnexion</a></button></div>
    </div>
</header>
<div class="container">
    <div class="form">
        <form action="" method="post">
            <div class="creer">Créez une nouvelle url</div>
            <div class="lien_original"><input type="text" name="long_lien" placeholder="Lien original" required/></div>
            <div class="erreur_duplicate<?php echo (isset($erreur_duplicate)) ?'':'hidden';?>">
                <?php echo isset($erreur_duplicate) ? $erreur_duplicate : ''; ?></div>
            <div class="lien_original"><input type="text" name="titre" placeholder="Titre(optionel)"/></div>
            <div class="erreur_identifiant<?php echo (isset($erreur)) ?'':'hidden';?>">
                <?php echo isset($erreur) ? $erreur : ''; ?></div>
            <div class="lien_short">
                <div class="label"><label></label></div>
                <div class="input_short"><input type="text" id="lienCourt" name="lien_court" placeholder="URL raccourcie" value="<?php echo htmlspecialchars($lien_court); ?>" readonly/>
                   <a href="javascript:void(0);"><img src="../assets/media/images/icons8-copy-24.png" onclick="copyToClipboard()"/></a> </div>
            </div>
            <div class="btn_raccourcir"><input type="submit" value="Raccourcir" name="racourcir"/></div>
        </form>
    </div>
</div>
</body>
</html>
<script>
    function copyToClipboard() {
        var lienCourtInput = document.getElementById("lienCourt");
        lienCourtInput.select();
        lienCourtInput.setSelectionRange(0, 99999);
        try {
            document.execCommand("copy");
        } catch (err) {
            console.error('Échec de la copie du texte', err);
        }
    }
</script>