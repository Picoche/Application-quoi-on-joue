<?php
/**
* Définition d'une classe permettant de gérer les jeux 
*   en relation avec la base de données	
*/
class CatManager {

    private $_db; // Instance de PDO - objet de connexion au SGBD

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db) {
        $this->_db = $db;
    }

    /**
     * Récupération dans la BD de toutes les catégories
     * @param aucun
     * @return Categorie[]
     */
    public function getCategories() {
        $categories = array();
        $req = "SELECT Nom_cat FROM Categorie";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();
        // Débuguage de la requête
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
        // Récupération des données
        while ($donnees = $stmt->fetch()) {
            $categories[] = new Categorie($donnees);
        }
        return $categories;
    }
}