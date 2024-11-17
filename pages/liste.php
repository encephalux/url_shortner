<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location:index.php");
    exit();
}
$email=$_SESSION['email'];
if ($_SESSION['role'] !== 'employe' && $_SESSION['role'] !== 'administrateur') {
    header("Location:index.php");
    exit();
}
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <link rel="stylesheet" href="../assets/css/liste.css"/>
    <title>Historiques</title>
    <style>
       
    </style>
</head>
<body>
<div class="entete">
    <header>
        <div class="logo">
            <img src="../assets/media/images/logo.jpg"/>
        </div>
        <div class="liens">
            <ul>
                <li><a href="racourcir.php">Raccourcir</a></li>
                <li><a href="liste.php">Liste</a></li>
            </ul>
        </div>
        <div class="fin">
            <div class="changer"><a href="changer.php">Changer mon mot de passe</a></div>
            <form action="" method="POST"><div class="deconnexion"><button type="submit" name="logout">Deconnexion</button></div></form>
        </div>
        
    </header>


    <div class="recherche">
        <div class="champ">
            <input type="text" placeholder="Rechercher" id="recherche_titre"/>
            <img src="../assets/media/images/magnifying-glass-solid.svg"/>
        </div>
        <div class="btn_rechercher"><button id="btn_rechercher">Rechercher</button></div>
    </div>
    <div class="menu_cache">
<i class="fa-solid fa-bars"></i>
</div>

<div class="tableau">
    <?php
    include('../lib/bd_connexion.php');
    /** @var TYPE_NAME $conn */
    $sql = "SELECT id_lien, long_lien, lien_court, nb_clic, date_creation, email FROM lien NATURAL JOIN utilisateur WHERE email=:email ORDER BY id_lien DESC ";
    $result = $conn->prepare($sql);
    $result->execute(array(':email' => $email));

    if ($result->rowCount() > 0) {
    while ($row = $result->fetch()) {
        echo '<div class="bande_bleu">';
        echo '    <div class="ligne_1">';
        echo '        <div class="long_lien">' . htmlspecialchars($row['long_lien']) . '</div>';
        echo '        <div class="fleche"><img src="../assets/media/images/icons8-arrow-50.png"/></div>';
        echo '        <div class="lien_court">' . htmlspecialchars($row['lien_court']) . '</div>';
        echo '</div>';
        echo '    <div class="ligne_2">';
        echo '        <div class="nb_utilisation">' . $row['nb_clic'] . '</div>';
        echo '        <div class="utilisations">utilisations</div>';
        echo '        <div class="date">Date:</div>';
        echo '        <div class="j_m_annee">' . date("d/m/Y", strtotime($row['date_creation'])) . '</div>';
        echo '        <div class="par">Par:</div>';
        echo '        <div class="email">' . htmlspecialchars($row['email']) . '</div>';
        echo '        <div class="write"><a href="modifier.php?id_lien=' . $row['id_lien'] . '"<img src="../assets/media/images/icons8-write-24.png"/></a></div>';
        echo '        <div class="delete"><a href="supprimer_lien.php?id_lien=' . $row['id_lien'] . '"><img src="../assets/media/images/icons8-delete-32.png"/></a></div>';
        echo '    </div>';
        echo '</div>';
    }
}
else{
    echo '';

}
    ?>
</div>
</body>
</html>
<script>
document.getElementById('btn_rechercher').addEventListener('click', function() {
const rechercheTitre = document.getElementById('recherche_titre').value.toLowerCase();
const bandeBleuDivs = document.querySelectorAll('.bande_bleu');
let foundDiv = null;

bandeBleuDivs.forEach(div => {
const lienCourt = div.querySelector('.lien_court').textContent.toLowerCase();

if (lienCourt.includes(rechercheTitre)) {
foundDiv = div;
}
});

if (foundDiv) {

foundDiv.parentNode.insertBefore(foundDiv, foundDiv.parentNode.firstChild);
foundDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
});

</script>