<?php

/** 
* définition de la classe jeu
*/
class Jeu {
	private string $_nom;
	private string $_temps_moy;
	private string $_regles;
	private string $_materiel;
	private int $_nb_joueurs;
	private string $_img;
	private string $_nom_cat;
	private string $_pseudo;
	
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
		if (isset($donnees['Nom'])) { $this->_nom = $donnees['Nom']; }
		if (isset($donnees['Temps_moy'])) { $this->_temps_moy = $donnees['Temps_moy']; }
		if (isset($donnees['Regles'])) { $this->_regles = $donnees['Regles']; }
		if (isset($donnees['Materiel'])) { $this->_materiel = $donnees['Materiel']; }
		if (isset($donnees['NB_joueurs'])) { $this->_nb_joueurs = $donnees['NB_joueurs']; }
		if (isset($donnees['img'])) { $this->_img = $donnees['img']; }
		if (isset($donnees['Nom_cat'])) { $this->_nom_cat = $donnees['Nom_cat']; }
		if (isset($donnees['Pseudo'])) { $this->_pseudo = $donnees['Pseudo']; }
	}           
	// GETTERS //
	public function nom() { return $this->_nom;}
	public function temps_moy() { return $this->_temps_moy;}
	public function regles() { return $this->_regles;}
	public function materiel() { return $this->_materiel;}
	public function nb_joueurs() { return $this->_nb_joueurs;}
	public function img() { return $this->_img;}
	public function nom_cat() { return $this->_nom_cat;}
	public function pseudo() { return $this->_pseudo;}
	
	// SETTERS //
	public function setNom(string $nom) { $this->_nom= $nom; }
	public function setTemps_moy(string $temps_moy) { $this->_temps_moy = $temps_moy; }
	public function setRegles(string $regles) { $this->_regles = $regles; }
	public function setMateriel(string $materiel) { $this->_materiel = $materiel; }
	public function setNb_joueurs(int $nb_joueurs) { $this->_nb_joueurs = $nb_joueurs; }
	public function setImg(string $img) { $this->_img = $img; }
	public function setNom_cat(string $nom_cat) { $this->_nom_cat = $nom_cat; }		
	public function setPseudo(string $pseudo) { $this->_pseudo = $pseudo; }		

}

