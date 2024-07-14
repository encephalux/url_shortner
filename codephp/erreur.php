
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style_css/style_erreur.css">
    <title>Document</title>
</head>
<body>
   <div class="erreur">

       <div class="logo"><img src="../images/logo.jpg"/></div>
       <div class="phrase1">Il n'y a pas de compte avec ces parametre</div>
       <div class="phrase2">Voulez vous creer un compte</div>
       <div class="boutons">
           <form action="" method="post">
            <div class="btn_bleu"><input type="submit" value="Oui" name="oui"/></div>
           </form>
          <div class="non"> <button><a href="login.php">Non</a> </button> </div>

       </div>

   </div>
</body>
</html>
<?php
session_start();
if (isset($_SESSION['email']) && isset($_SESSION['mot_de_passe'])) {
    $email = $_SESSION['email'];
    $mot_de_passe = $_SESSION['mot_de_passe'];
}
if(isset($_POST['oui'])) {
    include('bd_connexion.php');
    /** @var TYPE_NAME $conn */

    $insertion = $conn->prepare('INSERT INTO compte_utilisateur VALUES(0,:email,md5(:mot_de_passe))');
    $insertion->execute(array(
        ':email' => $email,
        ':mot_de_passe' => $mot_de_passe
    ));
    echo "Insertion avec succes";
}
?>