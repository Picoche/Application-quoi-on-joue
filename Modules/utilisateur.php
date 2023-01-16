<?php
	/**
 * définition de la classe user
 */
class User {
	private string $_pseudo;
	private string $_login;
	private string $_nom;
	private string $_prenom;
	private string $_date_insc;
	private string $_mdp;
	private int $_admin;
	
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
		if (isset($donnees['Pseudo'])) { $this->_pseudo = $donnees['Pseudo']; }
		if (isset($donnees['Login'])) { $this->_login = $donnees['Login']; }
		if (isset($donnees['Nom'])) { $this->_nom = $donnees['Nom']; }
		if (isset($donnees['Prenom'])) { $this->_prenom = $donnees['Prenom']; }
		if (isset($donnees['Date_insc'])) { $this->_date_insc = $donnees['Date_insc']; }
		if (isset($donnees['MDP'])) { $this->_mdp = $donnees['MDP']; }
		if (isset($donnees['Admin'])) { $this->_admin = $donnees['Admin']; }
	}           

	// Instantiation de la méthode __serialize()
	public function __serialize() { 
		return [
			"Pseudo" => $this->_pseudo,
			"Login" => $this->_login,
			"Nom" => $this->_nom,
			"Prenom" => $this->_prenom,
			"Date_insc" => $this->_date_insc,
			"MDP" => $this->_mdp,
			"Admin" => $this->_admin
		];
	}

	public function __unserialize(array $donnees): void 
	{
		$this->_pseudo = $donnees['Pseudo'];
		$this->_login = $donnees['Login']; 
		$this->_nom = $donnees['Nom']; 
		$this->_prenom = $donnees['Prenom'];
		$this->_date_insc = $donnees['Date_insc']; 
		$this->_mdp = $donnees['MDP']; 
		$this->_admin = $donnees['Admin']; 
		var_dump($donnees);
	}

	// GETTERS //
	public function pseudo() { return $this->_pseudo;}
	public function login() { return $this->_login;}
	public function nom() { return $this->_nom;}
	public function prenom() { return $this->_prenom;}
	public function date_insc() { return $this->_date_insc;}
	public function mdp() { return $this->_mdp;}
	public function admin() { return $this->_admin;}
	public function getAll() {
		return array("pseudo" => $this->_pseudo, "login" => $this->_login, "nom" => $this->_nom, "prenom" => $this->_prenom, "date_insc" => $this->_date_insc, "mdp" => $this->_mdp, "admin" => $this->_admin);
	}
	
	// SETTERS //
	public function setPseudo(string $pseudo) { $this->_pseudo = $pseudo; }
	public function setLogin(string $login) { $this->_login= $login; }
	public function setNom(string $nom) { $this->_nom = $nom; }
	public function setPrenom(string $prenom) { $this->_prenom = $prenom; }
	public function setDate_insc(string $date_insc) { $this->_date_insc = $date_insc; }
	public function setMdp(string $mdp) { $this->_mdp = $mdp; }	
	public function setAdmin(int $admin) { $this->_admin = $admin; }		

}

?>