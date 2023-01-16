<?php
/**
 * Définition d'une classe permettant de gérer les commentaires et les notes en relation avec la base de données
 */
class ComManager {
    private $_db; // Instance de POO - initialisation de la connexion au SGBD

    /**
     * Constructeur - initialisation de la connexion vers le SGBD
     */
    public function __construct($db) {
        $this->_db=$db;
    }

    /**
     * Retourne l'ensemble des commentaires et des notes pour un jeu
     * @return Com[]
     */
    public function getCom($nomJeu) {
        $coms = array();
        $req = "SELECT nom, pseudo, ajout, commentaire, note FROM Noter_Commenter WHERE nom = ?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($nomJeu));
        // Pour débuguer la requête
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // Récupération des données
        while($donnees = $stmt->fetch())
        {
            $coms[] = new Commentaire($donnees);
        }
        return $coms;
    }

    public function newCom(Commentaire $com) {
        $req = "INSERT INTO Noter_Commenter (Nom, Pseudo, Ajout, Commentaire, Note) VALUES ((SELECT Nom FROM Jeu WHERE Nom = ?),?,?,?,?)";
        $stmt = $this->_db->prepare($req);
        $res = $stmt->execute(array($com->nom(), $_SESSION["conUser"]["pseudo"], date("Y-m-d H:i:s"), $com->commentaire(), $com->note()));
        // Débuguage de la requête
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
    }
}