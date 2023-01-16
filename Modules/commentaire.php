<?php
	/**
 * définition de la classe commentaire
 */
class Commentaire {
	private string $_nom;
	private string $_pseudo;
	private string $_ajout;
	private string $_commentaire;
	private string $_note;
	
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['nom'])) { $this->_nom = $donnees['nom']; }
		if (isset($donnees['pseudo'])) { $this->_pseudo = $donnees['pseudo']; }
		if (isset($donnees['ajout'])) { $this->_ajout = $donnees['ajout']; }
		if (isset($donnees['commentaire'])) { $this->_commentaire = $donnees['commentaire']; }
		if (isset($donnees['note'])) { $this->_note = $donnees['note']; }
	}           
	// GETTERS //
    public function nom() { return $this->_nom;}
	public function pseudo() { return $this->_pseudo;}
	public function ajout() { return $this->_ajout;}
	public function commentaire() { return $this->_commentaire;}
	public function note() { return $this->_note;}
	
	// SETTERS //
	public function setPseudo(string $pseudo) { $this->_pseudo = $pseudo; }
	public function setAjout(string $ajout) { $this->_ajout= $ajout; }
	public function setNom(string $nom) { $this->_nom = $nom; }
	public function setCommentaire(string $commentaire) { $this->_commentaire = $commentaire; }
	public function setNote(string $note) { $this->_note = $note; }	

}

?>