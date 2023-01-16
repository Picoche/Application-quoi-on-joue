<?php 

/**
 * définition de la classe categorie
 */

class Categorie {
    private string $_nom_cat;


    // Constructeur

    public function __construct(array $donnees) {
    // initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees["Nom_cat"])) { $this->_nom_cat = $donnees["Nom_cat"];}
    }

    // GETTERS
    public function nom_cat() { return $this->_nom_cat;}

    // SETTERS
    public function setNom_cat(string $nom_cat) { $this->_nom_cat = $nom_cat;}

}