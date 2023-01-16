<?php
session_start();  // utilisation des sessions

require 'moteurtemplate.php'; // paramètres TWIG

include "connect.php"; // connexion au SGBD

include "Controllers/jeuxController.php";
include "Controllers/utilisateursController.php";
$jeuController = new JeuController($bdd, $twig);
$userController = new UserController($bdd, $twig);



// ============================== connexion / deconnexion - sessions ==================

if((substr($_SERVER["REQUEST_URI"], -12) === 'Application/') || (substr($_SERVER["REQUEST_URI"], -9) === "index.php") && !isset($_POST)) {
  $jeuController->listeJeux();
}


/*
Ne fonctionne pas à chaque fois, j'utiliserai la solution improvisée juste au-dessus tant que l'application sera en développement, par manque de solution plus propre et fiable pour le moment.
*/

// if(!isset($_GET) && empty($_POST)) {
//   $jeuController->listeJeux();
// }

if(isset($_GET["nom"])) {
  $jeuController->getJeu($_GET["nom"]);
}

if(isset($_GET["user"])) {
  $userController->userInfo($_GET["pseudo"]);
  $jeuController->userList($_GET["pseudo"]);
}

if(strpos($_SERVER["REQUEST_URI"], "connexion") !== false) {
  $userController->loginForms();
}

if(isset($_POST["inscription"])) {
  $userController->userInscription();
}

// click sur le bouton connexion 
if (isset($_POST["connexion"])) {
  $userController->userConnexion($_POST);
  $jeuController->listeJeux();
}

// deconnexion : click sur le bouton deconnexion
if (isset($_POST["deconnexion"])){
  $userController->userDeconnexion($_POST);
  $jeuController->listeJeux();
}

if (strpos($_SERVER["REQUEST_URI"], "proposerJeu") !== false) {
  $jeuController->formProposerJeu();
}

if (isset($_POST["proposition"])) {
  $jeuController->proposerJeu();
  $jeuController->listeJeux();
}

if (isset($_POST["subCom"])) {
  $jeuController->ajouterCommentaire();
}

// suppression d'un itineraire : choix de l'itineraire
    //  https://.../index/php?action=suppr
    if (isset($_GET["action"]) && $_GET["action"] == "suppr") {
      // $itiController->choixSuppItineraire(?? du membre connecté);
    }

    // supression d'un itineraire dans la base
    // --> au clic sur le bouton "valider_supp" du form précédent
    if (isset($_POST["valider_supp"])) {
      $jeuController->suppJeu();
    }

    // modification d'un itineraire : choix de l'itineraire
    //  https://.../index/php?action=modif
    if (isset($_GET["action"]) && $_GET["action"] == "modif") {
      // $itiController->choixModItineraire( ?? du membre connecté );
    }

    // modification d'un itineraire : saisie des nouvelles valeurs
    // --> au clic sur le bouton "saisie modif" du form précédent
    //  ==> version 0 : pas modif de l'iditi ni de l'idmembre
    if (isset($_POST["saisie_modif"])) {
      $jeuController->saisieModJeu();
    }

    //modification d'un itineraire : enregistrement dans la bd
    // --> au clic sur le bouton "valider_modif" du form précédent
    if (isset($_POST["valider_modif"])) {
      $jeuController->modJeu();
    }

    // recherche d'itineraire : saisie des critres de recherche dans un formulaire
    //  https://.../index/php?action=recherc
    if (isset($_GET["action"]) && $_GET["action"] == "recher") {
      $jeuController->formRechercheJeu();
    }

    // recherche des itineraires : construction de la requete SQL en fonction des critères 
    // de recherche et affichage du résultat dans un tableau HTML 
    // --> au clic sur le bouton "valider_recher" du form précédent
    if (isset($_POST["valider_recher"])) {
      $jeuController->rechercheJeu();
    }
