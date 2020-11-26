<?php
require('../../includes/pdo.php');


/*

  Cette page correspond à la page d'accueil. On y déclare dans un tableau data le titre de la page ainsi que la route
  Par la suite, on fait un rendu flight du fichier template : "index.tpl". 

 */
Flight::route('GET /', function(){
  $data = array(
    "titre" => "Index",
    "route" => "http://localhost/TPs/TP4/"
  );
  Flight::render('index.tpl',$data);

});

/*
    Cette route permet d'accéder à la page d'enregistrement par la méthode GET
    On y déclare un tableau, dans lequel on retrouve le titre de la page, sa route complète et les messages d'erreurs qui s'afficheront.
    On fait ensuite un rendu du fichier template : "register.tpl" ; un formulaire nous demandans de rentrer un nom, une adresse mail ainsi qu'un mot de passe.

*/
Flight::route('GET /register', function(){
  $data=array(
    "titre"=>"Register",
    "route"=>"http://localhost/TPs/TP4/register", // Pas obligatoire
    "messages"=>array()
  );
  Flight::render('register.tpl',$data);
});



/**
 * Cette route utilise la méthode POST, 
 * on y déclare une variable $erreur de type Booléen, qui nous servira d'indicateur d'erreur, TRUE s'il y en a une, FALSE sinon. 
 * On implémente des variables $nom ; $mail ; $mdp qui sont les 3 champs rentrés par l'utilisateur.
 * 
 * Dans un premier temps, on vérifier que le champ "nom" envoyé n'est pas vide. 
 * S'il l'est, la variable erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "nom".
 * 
 * On vérifie que le champ "mail" envoyé n'est pas vide.
 * S'il l'est, la variable erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "mail".
 * 
 * Sinon, On vérifie que le champ "mail" envoyé correspond à un mail valide.
 * S'il ne l'est pas, la variable erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "mail". 
 * 
 * On vérifie que le champ "Motdepasse" n'est pas vide.
 * S'il l'est, la variable erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "passe".
 * 
 * Sinon, on vérifie que le champ "Motdepasse" est d'une longueur supérieure à 8 caractères.
 * S'il ne l'est pas, la variable erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "passe".
 * 
 * 
 * Autrement, on fait une requête préparée puisque l'utilisateur injecte des valeurs de champs.
 * Cette requête vérifie que l'Email inséré n'est pas déjà présente dans la base de données.
 * Si elle l'est, la variable erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "mail".
 * 
 * En cas d'erreur, on assigne à une variable flight "messages" le tableau de messages d'erreurs que nous pourrons ré-utiliser dans le fichier template : "register.tpl".
 * On réutilise le tableau $_POST pour ne pas avoir à retaper les champs valides par l'utilisateur.
 * 
 * S'il n'y a pas d'erreur, on peut procéder à l'insertion des champs rentrés par l'utilisateur dans notre base de données.
 * On commence tout d'abord par un chiffrage du mot de passe de manière "salée", on récupère ensuite la base de données.
 * On y insère les champs rentrés par l'utilisateur dans une requête préparée.
 * L'opérateur " bindParam " permet de lier un paramètre un nom de variable spécifique.
 * On exécute ensuite la requête.
 * On redirige l'utilisateur sur la page "Profil" rendue par le fichier template "success.tpl". 
 * 
 */

Flight::route('POST /register', function(){
  $erreur = False;
  $messages=array();

  $nom = $_POST["nom"];
  $mail = $_POST["email"];
  $mdp = $_POST["passe"];

  if (empty($nom)){
    $erreur = True;
    $messages["nom"] = 'Vous devez saisir un Nom.';
  }
  if (empty($mail)){
    $erreur = True;
    $messages["email"] = 'Vous devez saisir une Email.';
  }

  elseif (!filter_var($mail,FILTER_VALIDATE_EMAIL)){
    $erreur = True;
    $messages["email"]='Vous devez saisir une Email VALIDE.';
  }

  if (empty($mdp)){
    $erreur = True;
    $messages["passe"] = 'Vous devez saisir un mot de passe.';
  }

  elseif  (strlen($mdp) < 8 ){
    $erreur = True;
    $messages["passe"] = 'Votre mot de passe doit faire 8 caractères minimum.';
  }

  else {
    $db = Flight::get('db');
    // MAIL 
    $req = $db -> prepare( "SELECT email FROM utilisateur where email = :mail");
    $req -> execute (array(':mail' => "$mail"));

    if ($req -> rowCount() > 0)
    {
      $erreur = True;
      $messages['email'] = 'Email déjà existante.';
    }
  }



  if ($erreur){
    Flight::view()->assign('messages',$messages);

    Flight::render('register.tpl',$_POST);

  }

  else{
    $mdp = password_hash($mdp,PASSWORD_DEFAULT);
    $db = Flight::get('db');
    $req = $db -> prepare("INSERT INTO utilisateur(Nom,Email,Motdepasse) VALUES (:nom,:mail,:passe)");
    $req -> bindParam(':nom',$nom);
    $req -> bindParam(':mail',$mail);
    $req -> bindParam('passe',$mdp);
    $req -> execute();
    Flight::redirect('/success');
    
  }


});



/**
 * 
 * La route success permet d'afficher la page succès pour l'utilisateur une fois qu'il a réussi à s'inscrire. 
 * La page renvoie un message qui confirme l'inscription et qui possède un lien de redirection vers la page d'accueil
 *  où l'on peut s'inscrire ou se connecter.
 * 
 */


Flight::route('/success', function(){
    $data=array(
      "titre"=>"Succes",
      "route"=>"http://localhost/TPs/TP4/succes"
    );
    Flight::render('success.tpl',$data);
});






Flight::route('GET /login', function(){
  
  $data=array(
    "titre"=>"Login",
    "route"=>"http://localhost/TPs/TP4/login"
  );
  Flight::render('login.tpl',$data);

});

/**
 * La méthode POST de la route /login permet de vérifier la correspondance entre les champs rentrés par l'utilisateur
 * et la base de données.
 * 
 * On initialise une variable $erreur à la valeur False. Elle déterminera si une erreur survient durant le traitement TRUE si oui, FALSE sinon.
 * On initialise également un tableau $messages  qui contiendra les messages d'erreurs en fonction des champs problématiques.
 * On initialise des variables $mail, $mdp qui correspondent respectivement à la valeur du champ concerné, rentré par l'utilisateur.
 * 
 * On vérifie que le champ "Email" n'est pas vide.
 * S'il l'est, la variable $erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "email".
 * 
 * On vérifie que le champ "Motdepasse" n'est pas vide.
 * S'il l'est, la variable $erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "passe".
 * 
 * Si les conditions ci-dessus ne sont pas remplies, on procède à une requête préparée.
 * Cette requête est préparée puisque les données traitées sont injectées par l'utilisateur.
 * On associe grâce à la fonction "bindParam" le paramètre email à la variable $mail. 
 * On exécute la requête SQL. 
 * 
 * Si aucune ligne ne ressort de cette requête alors l'identifiant par email n'existe pas dans la table.
 * La variable $erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "email".
 * Sinon, on insère dans une variable $compte le résultat de la requête.
 * 
 * Si cette variable $compte existe, on vérifie la correspondance des mots de passe.
 * Si la correspondance est vérifiée, on insère dans une tableau $_SESSION à la clé "user" la valeur de l'élément d'indice [0] du tableau $compte (le Nom d'utilisateur)
 * 
 * Si le mot de passe n'est pas vérifié
 * La variable $erreur passe à True et on ajoute un message d'erreur dans le tableau à la clé "passe".
 * 
 * 
 * S'il n'y a pas d'erreur dans le traitement, on redirige l'utilisateur sur la page d'index lié à la route "/" 
 * Sinon, on renvoie la page de login avec les champs erronés.
 */


Flight::route('POST /login', function(){
  $erreur = False;
  $messages = array();
  $mail = $_POST["email"];
  $mdp = $_POST["passe"];

  if(empty($mail)){

    $erreur = True; 
    $messages['Email'] = "L'email est invalide, SAISISSEZ UN MAIL VALIDE.";
  }

  if (empty($mdp)){

    $erreur = True;
    $messages['passe'] = "Le mot de passe est invalide, SAISISSEZ UN MOT DE PASSE.";
  }

  else {
    $db = Flight::get('db');
    $req = $db -> prepare ("SELECT * FROM utilisateur WHERE Email=:email");
    $req -> bindParam(':email',$mail);
    $req -> execute();
    if ($req -> rowCount()==0){

      $erreur = True;
      $messages['Email'] = " Les identifiants n'existent pas.";
    }

    else{
      
      $compte= $req -> fetch();
      
    }

    if (isset($compte)){

      if (password_verify($mdp,$compte[2])){

          $_SESSION['user'] = $compte[0];
          $_SESSION['mail'] = $compte[1];
        
        }

      else{ 

          $erreur = True;
          $messages['passe'] = "Mot de passe incorrect";

        }
    }
    
  }

  if ($erreur==False){

    Flight::redirect("/");

  }
  
  else{
    Flight::view()->assign('messages',$messages);
    Flight::render("login.tpl",$_POST);
  }

});





/**
 * 
 * La route profil permet d'afficher la page profile pour l'utilisateur s'il souhaite le consulter en étant connecté.
 * Cette route fait le rendu du fichier template "profile.tpl". 
 */


Flight::route('/profil', function(){
    
  
  Flight::render("profile.tpl",$_SESSION);
});


/**
 * On détruit les variables de session. 
 * 
 * 
 */
Flight::route('GET /logout', function(){ 
  session_destroy();
  Flight::redirect("/");
   

});




Flight::start();
