<?php
//session_start();
//print_r($_SESSION);
//if (!isset($_SESSION['user'])) {
//    header("Location:index.php");
//    exit();
//}
//if ($_SESSION['role'] !== 'administrateur') {
//    header("Location:racourcir.php");
//    exit();
//}
//?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../assets/css/utilisateur.css"/>
    <title>Utilisateur</title>
    <style>
        /* Styles pour la div trouvée */
        .btn_rechercher #btn-rechercher:hover {
    transform: scale(1.05);
       }
        .deconnexion button:hover{
            transform: scale(1.05);
            background-color:white;
            color:#0084CA;
            border:1px solid #0084CA;
     }
        .bande_bleu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px; /* Marge pour séparer des autres éléments */
            padding: 10px;
        }

        .bande_bleu.recherche {
            background-color: #e0e0e0;
            border: 2px solid #007bff;
            z-index: 1;
            position: relative;
            margin-top: 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Ombre pour un effet de mise en avant */
        }

        .bande_bleu .mot_de_passe,
        .bande_bleu .delete {
            margin-left: 10px; /* Assurez-vous que les éléments à droite sont visibles */
        }
        .changer a:hover{
            color:  #0084CA;
        }
        .liens a:hover{
            color:  #0084CA;
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
<div class="entete">
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
            <div class="changer"><a href="changer.php">Changer mon mot de passe</a></div>
            <div class="deconnexion"><button><a href="index.php">Deconnexion</a></button></div>
        </div>
    </header>

    <div class="recherche">
        <div class="plus"><a href="inscrire.php"><img src="../assets/media/images/plus.svg"/></a></div>
        <div class="champ">
            <input type="text" id="recherche-email" placeholder="Rechercher"/>
            <img src="../assets/media/images/magnifying-glass-solid.svg"/>
        </div>

        <div class="btn_rechercher"><button id="btn-rechercher">Rechercher</button></div>
    </div>
</div>

<div class="tableau">
    <?php
    include('../lib/bd_connexion.php');
    /** @var TYPE_NAME $conn */

    $quete = "SELECT email, id_compte FROM compte_utilisateur ORDER BY id_compte DESC";
    $result = $conn->query($quete);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_suppression'])) {
        echo "<div class='message_suppression'>" . htmlspecialchars($_POST['message_suppression']) . "</div>";
    }

    while ($row = $result->fetch()) {
        $email = htmlspecialchars($row['email']);
        $id_compte = htmlspecialchars($row['id_compte']);
        echo "<div class='bande_bleu' data-email='" . $email . "'>";
        echo "<div class='email'>" . $email . "</div>";
        echo "<div class='mot_de_passe'>";
        echo "<form method='POST' action='reinitialiser.php' style='display:inline;'>";
        echo "<input type='hidden' name='id' value='" . $id_compte . "'/>";
        echo "<button type='submit' class='button-as-link'>Réinitialiser le mot de passe</button>";
        echo "</form>";
        echo "</div>";
        echo "<div class='delete'>";
        echo "<form method='POST' action='suppression.php'>";
        echo "<input type='hidden' name='id' value='" . $id_compte . "'/>";
        echo "<input type='image' src='../assets/media/images/icons8-delete-32.png' alt='Supprimer'/>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }
    ?>
</div>

<script>
    document.getElementById('btn-rechercher').addEventListener('click', function() {
        const emailRecherche = document.getElementById('recherche-email').value.toLowerCase();
        const bandeBleuDivs = document.querySelectorAll('.bande_bleu');

        bandeBleuDivs.forEach(div => {
            const emailDiv = div.querySelector('.email').textContent.toLowerCase();

            if (emailDiv.includes(emailRecherche)) {
                div.classList.add('recherche');
                div.style.display = 'flex';
                div.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                div.classList.remove('recherche');
                div.style.display = 'flex';
            }
        });
    });
</script>
</body>
</html>
