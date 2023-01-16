<?php
include "Modules/utilisateur.php";
include "Models/utilisateursManager.php";
/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 *   en relation avec la base de données	
 */
class UserController
{

	//private $_db; // Instance de PDO - objet de connexion au SGBD
	private $userManager; // instance du manager

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		//$this->_db=$db;
		$this->userManager = new UserManager($db);
		$this->twig = $twig;
	}

	/**
	 * liste les infos d'un utilisateur
	 * @param aucun
	 * @return rien
	 */
	function userInfo($donnees) {
		$user = $this->userManager->getUserInfo($donnees);
		echo $this->twig->render("user/utilisateur.html.twig", ["user" => $user]);
	}

	/**
	 * affiche les formulaires d'inscription / connexion
	 * @param aucun
	 * @return rien
	 */
	function loginForms() {
		echo $this->twig->render("user/loginForms.html.twig");
	}

	/**
	 * ajoute l'utilisateur à la BD Utilisateur
	 * @param aucun
	 * @return rien
	 */
	function userInscription() 
	{
		foreach ($_POST as $input) {
			if ($input === $_POST["emailInsc"] || $input === $_POST["emailVerif"]) {
				filter_input(INPUT_POST, $input, FILTER_SANITIZE_EMAIL);
			} else {
				filter_input(INPUT_POST, $input, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
		$check = $this->userManager->checkDuplicateUser($_POST);
		if($check) {
			$user = new User($_POST);
			$ok = $this->userManager->ajoutUtilisateur($user);
		}
		unset($_POST);
	}

	/**
	 * connexion
	 * @param aucun
	 * @return message string -> message de retour vers l'utilisateur
	 *    qu'il est bien connecté ou pas
	 */
	function userConnexion($data)
	{
		foreach ($data as $input) {
			if ($input === $data["emailCon"]) {
				filter_input(INPUT_POST, $input, FILTER_SANITIZE_EMAIL);
			} else {
				filter_input(INPUT_POST, $input, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
		$ok = $this->userManager->verif_identification($data["emailCon"], $data["mdpCon"]);
		if ($ok) {
			echo "connexion réussie";
			$_SESSION["access"] = "oui";
			unset($_SESSION["conUser"]);
			$_SESSION["conUser"] = $ok->getAll();
		} else {
			echo "probleme lors de la connexion";
			$_SESSION["access"] = "non";
			unset($_SESSION["conUser"]);
		}
		unset($_POST);
	}

	/**
	 * deconnexion
	 * @param aucun
	 * @return message string -> message de retour vers l'utilisateur
	 */
	function userDeconnexion()
	{
		unset($_SESSION["conUser"]);
		$_SESSION["access"] = "non";
		$message = "vous êtes déconnecté";
		return $message;
	}
}


	