<?php
session_start();
if(!isset($_SESSION["email"])){
    header("location:index.php");
    exit();
}

if(isset($_POST['modifier'])) {
    $ancien = $_POST['ancien'];
    $nouveau = $_POST['nouveau'];
    $email = $_SESSION['email'];
        $confirmation=$_POST["confirmation"];
    $options = [
        'cost' => 12,
    ];
    if($nouveau==$confirmation){

    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    include('../lib/bd_connexion.php');
    /** @var TYPE_NAME $conn */
    $sql_password = $conn->prepare("SELECT mot_de_passe FROM compte_utilisateur WHERE email = :email");
    $sql_password->execute(array(':email' => $email));
    $result = $sql_password->fetch(PDO::FETCH_ASSOC);
        $password = $result['mot_de_passe'];
    if(!preg_match($pattern, $nouveau)) {
        $password_erreur = "Le mot de passe doit contenir au moins 8 caractères...";
    }
    else {
        if (password_verify($ancien, $password)) {
            $nouveau_hash = password_hash($nouveau, PASSWORD_BCRYPT, $options);
            $sql_update = $conn->prepare("UPDATE compte_utilisateur SET mot_de_passe = :nouveau WHERE email = :email");
            $sql_update->execute(array(':nouveau' => $nouveau_hash, ':email' => $email));
            $correct = "Mot de passe mis à jour avec succès.";
        } else {
            $incorrect = "Le mot de passe est incorrect.";
        }
    }
    }
    else{
        $non_conforme="Le mot de passe et le mot de passe de confirmation doivent  conforment";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../assets/css/changer.css"/>
    <title>changer de mot de passe</title>
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
        </ul>
    </div>
    <div class="fin">
        <div class="changer"><a href="changer.php">Changer mon mot de passe</a> </div>
        <div class="deconnexion"><button><a href="index.php">Deconnexion</a></button></div>
    </div>
</header>
<div class="container">
    <div class="chang_password">Changer votre mot de passe</div>
    <div class="erreur <?php echo (isset($incorrect)||isset($password_erreur)||isset($non_conforme)) ? '' : 'hidden'; ?>">
          <?php
    echo isset($incorrect) ? $incorrect : '';
    echo isset($password_erreur) ? $password_erreur : '';
    echo isset($non_conforme) ? $non_conforme : '';
    ?>
    </div>
    <div class="succes <?php echo (isset($correct)) ? '' : 'hid'; ?>">
        <?php
        echo isset($correct) ? $correct : '';
        ?>
    </div>

    <form action="" method="post">
    <div class="input_ancien"><input type="password" placeholder="Ancien mot de passe" name="ancien" required/></div>
    <div class="input_nouveau"><input type="password" placeholder="Nouveau mot de passe" name="nouveau" required/></div>
        <div class="input_nouveau"><input type="password" placeholder="Mot de passe de confirmation" name="confirmation" required/></div>
    <div class="bouton"><input type="submit" value="Modifier" name="modifier"/></div>
    </form>
</div>
</body>
</html>