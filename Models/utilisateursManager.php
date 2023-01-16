<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 * en relation avec la base de données
 *
 */

class UserManager
{
	private $_db; // Instance de PDO - objet de connexion au SGBD

	/** 
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}

	/**
	 * liste les informations d'un utilisateur
	 * @param aucun
	 * @return rien
	 */
	public function getUserInfo($donnees) {
		$req = "SELECT * FROM Utilisateur WHERE Pseudo = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($donnees));
		// Débuguage de la requête
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$user = $stmt->fetch();
		return $user;
	}

	/**
	 * verification de l'identité d'un utilisateur (Login/password)
	 * @param string $login
	 * @param string $password
	 * @return utilisateur si authentification ok, false sinon
	 */
	public function verif_identification($login, $password)
	{
		$req = "SELECT Pseudo, Login, Nom, Prenom, MDP, Admin, Date_insc FROM Utilisateur WHERE Login = ? AND MDP = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($login, $password));
		// Débuguage de la requête
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		if ($donnees = $stmt->fetch()) {
			$user = new User($donnees);
			return $user;
		}
		else return false;
	}

	/**
	 * vérifie si les identifiants indiqués pour l'inscription ne sont pas déjà utilisés
	 * @param aucun
	 * @return rien
	 */
	public function checkDuplicateUser($user) {
		$req = "SELECT Pseudo, Login, Nom, Prenom, MDP FROM Utilisateur WHERE Pseudo = ? OR Login = ? OR (Prenom = ? AND Nom = ?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($user["Pseudo"], $user["Login"], $user["Prenom"], $user["Nom"]));
		// Débuguage de la requête
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		if ($donnees = $stmt->fetch()) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * ajoute un utilisateur à la BD Utilisateur
	 * @param aucun
	 * @return rien
	 */
	public function ajoutUtilisateur($user) {
		$req = "INSERT INTO Utilisateur (Pseudo, Login, Nom, Prenom, Date_insc, MDP, Admin) VALUES (?,?,?,?,?,?,0)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($user->pseudo(), $user->login(), $user->nom(), $user->prenom(), date("j-m-y"), $user->mdp()));
		// Débuguage de la requête
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$newUser = new User($user);
		return $newUser;
	}
}
