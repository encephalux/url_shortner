# url_shortner
## CREATION DE LA PAGE DE CONNEXION
* création de deux champs de formulaire
* création d'un bouton pour créer un compte
* création d'un bouton pour se connecter
* utilisation de la biobliotheque 
``` js
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 ```
de javascript pour afficher les messages erreurs lorsque l'utilisateur tente d'utiliser un email ou un mot de passe qui n'existe pas dans notre base de données.
* On propose á l'utilisateur de créer un nouveau compte .
* Lors de la création de compte ,on vérifie si l'email existe déjá.si il existe déjá on envoie un message "ce email existe déjá".
* On vérifie également si le mot de passe contient 8 caractères 
* Une fois le nouveau compte crée l'utilisateur  est dirigé vers la page to_short.php qui est pour le moment vide.