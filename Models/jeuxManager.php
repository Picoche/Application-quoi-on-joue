<?php
/**
* Définition d'une classe permettant de gérer les jeux 
*   en relation avec la base de données	
*/
class JeuManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}

	/**
	* Recherche dans la BD d'un jeu à partir de son nom
	* @param int $nomJeu 
	* @return Jeu 
	*/
	public function get($nomJeu) {	
		$req = 'SELECT img, Nom, Regles, Materiel, NB_joueurs, Nom_cat, Pseudo FROM Jeu WHERE Nom=?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($nomJeu));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$jeu = new Jeu($stmt->fetch());
		$jeu->setImg("data:image/jpeg;base64,".base64_encode($jeu->img()));
		return $jeu;
	}		
		
	/**
	* retourne l'ensemble des jeux présents dans la BD 
	* @return Jeu[]
	*/
	public function getList() {
		$jeux = array();
		$req = 'SELECT Nom, img, Regles, Nom_cat, NB_joueurs, Temps_moy FROM Jeu WHERE Approuve = 1';
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$jeux[] = new Jeu($donnees);
		}
		foreach ($jeux as $jeu) {
			$jeu->setImg("data:image/jpeg;base64,".base64_encode($jeu->img()));
		}

		return $jeux;
	}

	/**
	* retourne l'ensemble des jeux présents dans la BD pour un utilisateur
	* @param string $donnees
	* @return Jeu[]
	*/
	public function getUserList($pseudo) {
		$jeux = array();
		$req = "SELECT Nom, img, Regles, Nom_cat, NB_joueurs, Temps_moy FROM Jeu WHERE Pseudo = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($pseudo));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch())
		{
			$jeux[] = new Jeu($donnees);
		}
		foreach ($jeux as $jeu) {
			$jeu->setImg("data:image/jpeg;base64,".base64_encode($jeu->img()));
		}
		return $jeux;
	}

	/**
	 * vérifie si le jeu proposé n'existe pas déjà dans la BD
	 * @param aucun
	 * @return rien
	 */
	public function checkDuplicateJeu($jeu) {
		$req = "SELECT Nom FROM Jeu WHERE Nom = ?";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($jeu["Nom"]));
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
	* ajout d'un jeu dans la BD
	* @param jeu à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function proposer(Jeu $jeu) {
		
		// requete d'ajout dans la BD
		$req = "INSERT INTO Jeu (Nom, Temps_moy, Regles, Materiel, NB_joueurs, Pseudo, Approuve, Nom_cat) VALUES (?,?,?,?,?,?,0,(SELECT Nom_cat FROM Categorie WHERE Nom_cat = ?))";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array(
		$jeu->nom(), 
		$jeu->temps_moy(), 
		$jeu->regles(), 
		$jeu->materiel(), 
		$jeu->nb_joueurs(), 
		$_SESSION["conUser"]["pseudo"], 
		$jeu->nom_cat()));
		// Débuguage de la requête
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

		/**
	* Prépare l'upload de l'image du jeu dans la BD
	* @param jeu à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function uploadImg() {
		$targetDir = $_FILES["img"]["tmp_name"];
		$imgData = file_get_contents($_FILES['img']['tmp_name']);
		$fileName = basename($_FILES["img"]["name"]);
		$targetFilePath = $targetDir . $fileName;
		$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

		// N'autorise que certains formats
		$allowTypes = array('jpg','png','jpeg',"webp");
		if(in_array($fileType, $allowTypes)){
			// Ajoute le fichier dans la BD
			$req = "UPDATE Jeu SET img = ? WHERE Nom = ?";
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($imgData,$_POST["Nom"]));		
			// Débuguage de la requête
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {				
				print_r($errorInfo);
			}
			return $res;
		}
	}

        
	/**
	* nombre de jeux dans la base de données
	* @return int le nb de jeux
	*/
	public function count() {
		$stmt = $this->_db->prepare('SELECT COUNT(*) FROM Jeu');
		$stmt->execute();
		return $stmt->fetchColumn();
	}
        
	/**
	* suppression d'un jeu dans la base de données (NON FONCTIONNEL)
	* @param Jeu 
	* @return boolean true si suppression, false sinon
	*/
	public function delete(Jeu $jeu) {
		$req = "DELETE FROM Jeu WHERE Nom = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($jeu->iditi()));
	}
	
	/**
	* méthode de recherche d'itinéraires dans la BD à partir des critères passés en paramètre (NON FONCTIONNEL)
	* @param string $lieudepart
	* @param string $lieudepart
	* @param string $datedepart
	* @return Jeu[]
	*/
	// public function search($lieudepart, $lieuarrivee, $datedepart) {
	// 	$req = "SELECT iditi,lieudepart,lieuarrivee,heuredepart,date_format(datedepart,'%d/%c/%Y')as datedepart,tarif,nbplaces,bagagesautorises,details FROM itineraire";
	// 	$cond = '';

	// 	if ($lieudepart<>"") 
	// 	{ 	$cond = $cond . " lieudepart like '%". $lieudepart ."%'";
	// 	}
	// 	if ($lieuarrivee<>"") 
	// 	{ 	if ($cond<>"") $cond .= " AND ";
	// 		$cond = $cond . " lieuarrivee like '%" . $lieuarrivee ."%'";
	// 	}
	// 	if ($datedepart<>"") 
	// 	{ 	if ($cond<>"") $cond .= " AND ";
	// 		$cond = $cond . " datedepart = '" . dateChgmtFormat($datedepart) . "'";
	// 	}
	// 	if ($cond <>"")
	// 	{ 	$req .= " WHERE " . $cond;
	// 	}
	// 	// execution de la requete				
	// 	$stmt = $this->_db->prepare($req);
	// 	$stmt->execute();
	// 	// pour debuguer les requêtes SQL
	// 	$errorInfo = $stmt->errorInfo();
	// 	if ($errorInfo[0] != 0) {
	// 		print_r($errorInfo);
	// 	}
	// 	$jeux = array();
	// 	while ($donnees = $stmt->fetch())
	// 	{
	// 		$jeux[] = new Jeu($donnees);
	// 	}
	// 	return $jeux;
	// }
	
	/**
	* modification d'un itineraire dans la BD (NON FONCTIONNEL)
	* @param Jeu
	* @return boolean 
	*/
	public function update(Jeu $jeu) {
		$req = "UPDATE itineraire SET lieudepart = :lieudepart, "
					. "lieuarrivee = :lieuarrivee, "
					. "heuredepart = :heuredepart, "
					. "datedepart  = :datedepart, "
					. "tarif = :tarif, "
					. "nbplaces = :nbplaces, "
					. "bagagesautorises= :bagages, "
					. "details = :details" 
					. " WHERE iditi = :iditi";
		//var_dump($iti);

		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":lieudepart" => $iti->lieuDepart(),
								":lieuarrivee" => $iti->lieuArrivee(),
								":heuredepart" => $iti->heureDepart(),
								":datedepart" => dateChgmtFormat($iti->dateDepart()),
								":tarif" => $iti->tarif(),
								":nbplaces" => $iti->nbPlaces(),
								":bagages" => $iti->bagagesAutorises(),
								":details" => $iti->details(),
								":iditi" => $iti->idIti()));
		return $stmt->rowCount();
		
	}
}

// fontion de changement de format d'une date
// tranformation de la date au format j/m/a au format a/m/j
function dateChgmtFormat($date) {
//echo "date:".$date;
		list($j,$m,$a) = explode("/",$date);
		return "$a/$m/$j";
}
