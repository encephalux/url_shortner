<?php
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

?>