<?php
include "Modules/jeu.php";
include "Modules/commentaire.php";
include "Modules/categorie.php";
include "Models/jeuxManager.php";
include "Models/comManager.php";
include "Models/catManager.php";
/**
 * Définition d'une classe permettant de gérer les jeux 
 *   en relation avec la base de données	
 */
class JeuController
{

	private $jeuManager; // instance du manager jeu
	private $comManager; // instance du manager commentaire
	private $catManager; // instance du manager categorie

	/**
	 * Constructeur = initialisation de twig et de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		// $this->_db=$db;
		$this->jeuManager = new JeuManager($db);
		$this->comManager = new ComManager($db);
		$this->catManager = new CatManager($db);
		$this->twig = $twig;
	}

	/**
	 * liste tous les jeux
	 * @param aucun
	 * @return rien
	 */
	public function listeJeux()
	{
		$jeux = $this->jeuManager->getList();
		echo $this->twig->render("jeux/publicUnfilteredList.html.twig", ["jeux" => $jeux, "session" => $_SESSION]);
	}

	/**
	 * liste les informations d'un jeu en particulier
	 * @param string $nomJeu
	 * @return rien
	 */
	public function getJeu($nomJeu) {
		$jeu = $this->jeuManager->get($nomJeu);
		$coms = $this->comManager->getCom($nomJeu);
		echo $this->twig->render("jeux/publicDetailsGame.html.twig", ["jeu" => $jeu, "coms" => $coms]);
	}

	/**
	 * liste les informations d'un utilisateur
	 * @param string $donnees
	 * @return rien
	 */
	public function userList($pseudo) {
		$jeux = $this->jeuManager->getUserList($pseudo);
		$user = $_GET["pseudo"];
		echo $this->twig->render("jeux/userList.html.twig", ["user" => $user,"jeux" => $jeux]);
	}

	/**
	 * Affiche le formulaire d'ajout d'un jeu
	 * @param aucun
	 * @return rien
	 */
	public function formProposerJeu() {
		$categories = $this->catManager->getCategories();
		echo $this->twig->render("jeux/formProposerJeu.html.twig", ["categories" => $categories]);
	}

	/**
	 * ajout dans la BD d'un jeu à partir du form
	 * @param aucun
	 * @return rien
	 */
	public function proposerJeu()
	{
		$check = $this->jeuManager->checkDuplicateJeu($_POST);
		if($check) {
			$jeu = new Jeu($_POST);
			$ok = $this->jeuManager->proposer($jeu);
			if($ok) $this->jeuManager->uploadImg();
		}
		unset($_POST);
	}

	public function ajouterCommentaire() 
	{
		$com = new Commentaire($_POST);
		$isSent = $this->comManager->newCom($com);
		unset($_POST);
	}

	/**
	 * liste les jeux d'un utilisateur sur sa page profil (NON FONCTIONNEL)
	 * @param string $pseudo
	 * @return rien
	 */
	public function listeMesJeux($pseudo)
	{
		$jeux = $this->jeuManager->getListUtilisateur($pseudo);
		echo '<table class="table table-hover table-condensed"><thead>';
		echo '<tr><th>Id</th><th>Lieu de départ</th><th>Lieu d\'arrivee</th><th>Date départ</th><th>Heure départ</th></tr>';
		echo '</thead><tbody>';
		foreach ($jeux as $jeu) {
			echo '<tr><td>' . $jeu->pseudo() . '</td><td>' . $jeu->nom() . '</td><td>' . $jeu->logo() . '</td><td>' . $jeu->regles() . '</td><td>' . $jeu->nom_cat();
		}
		echo '</tbody></table>';
	}
	/**
	 * form de choix du jeu à supprimer (NON FONCTIONNEL)
	 * @param string $pseudo
	 * @return rien
	 */
	public function choixSuppJeu($pseudo)
	{
		echo '<form method="post" action="index.php" class="well">';
		echo '<fieldset><legend>Choix de l\'itineraire à supprimer</legend>';
		$jeux = $this->jeuManager->getListUtilisateur($pseudo);
		echo '<select name="iditi" class="form-control form-control-sm">';
		foreach ($jeux as $jeu) {
			echo '<option value="' . $jeu->idIti() . '">' . $jeu->lieuDepart() . ' -> ' . $jeu->lieuArrivee() . ' (' . $jeu->dateDepart() . ') ' . '</option>';
		}
		echo '</select>';
		echo '<input type="submit"  name="valider_supp" value="valider" />';
		echo '</fieldset></form>';
	}
	/**
	 * suppression dans la BD d'un jeu à partir de l'id choisi dans le form précédent (NON FONCTIONNEL)
	 * @param aucun
	 * @return rien
	 */
	public function suppJeu()
	{
		$jeu = new Jeu($_POST);
		$ok = $this->jeuManager->delete($jeu);
		if ($ok) echo "itineraire supprimé";
		else echo "probleme lors de la supression";
	}
	/**
	 * form de choix du jeu à modifier (NON FONCTIONNEL)
	 * @param aucun
	 * @return rien
	 */
	public function choixModJeu($pseudo)
	{
		echo '<form method="post" action="index.php" class="well">';
		echo '<fieldset><legend>Choix de l\'itineraire à modifier</legend>';
		$jeux = $this->jeuManager->getListUtilisateur($pseudo);
		echo '<select name="iditi" class="form-control form-control-sm">';
		foreach ($jeux as $jeu) {
			echo '<option value="' . $jeu->idIti() . '">' . $jeu->lieuDepart() . ' -> ' . $jeu->lieuArrivee() . ' (' . $jeu->dateDepart() . ') ' . '</option>';
		}
		echo '</select>';
		echo '<input type="submit"  name="saisie_modif" value="valider" />';
		echo '</fieldset></form>';
	}
	/**
	 * form de saisi des nouvelles valeurs du jeu à modifier (NON FONCTIONNEL)
	 * @param aucun
	 * @return rien
	 */
	public function saisieModJeu()
	{
		$jeu =  $this->jeuManager->get($_POST["iditi"]);
	?>
		<form method="post" action="index.php" class="well">
			<fieldset class="form-group">
				<legend>Modification itinéraire</legend>
				<div class="form-group row">
					<label for="lieudepart" class="col-sm-2 col-form-label col-form-label-sm">Départ</label>
					<div class="col-sm-10">
						<input type="text" id="lieudepart" class="form-control form-control-sm" name="lieudepart" required value="<?php echo $iti->lieuDepart(); ?>" placeholder="lieu de départ" />
					</div>
				</div>
				<div class="form-group row">
					<label for="lieuarrivee" class="col-sm-2 col-form-label col-form-label-sm">Arrivée</label>
					<div class="col-sm-10">
						<input type="text" id="lieuarrivee" class="form-control form-control-sm" name="lieuarrivee" required value="<?php echo $iti->lieuArrivee(); ?>" placeholder="lieu d'arrivée" />
					</div>
				</div>
				<div class="form-group row">
					<label for="datedepart" class="col-sm-2 col-form-label col-form-label-sm">Date</label>
					<div class="col-sm-10">
						<input type="text" id="datedepart" class="form-control form-control-sm" name="datedepart" required value="<?php echo $iti->dateDepart(); ?>" placeholder="date de départ jj/mm/aaaa" />
					</div>
				</div>
				<div class="form-group row">
					<label for="heuredepart" class="col-sm-2 col-form-label col-form-label-sm">Heure</label>
					<div class="col-sm-10">
						<input type="time" id="heuredepart" class="form-control form-control-sm" name="heuredepart" required value="<?php echo $iti->heureDepart(); ?>" placeholder="heure de départ hh:mm" />
					</div>
				</div>
				<div class="form-group row">
					<label for="tarif" class="col-sm-2 col-form-label col-form-label-sm">Tarif</label>
					<div class="col-sm-10">
						<input type="number" min="0" id="tarif" class="form-control form-control-sm" name="tarif" required value="<?php echo $iti->tarif(); ?>" placeholder="tarif" />
					</div>
				</div>
				<div class="form-group row">
					<label for="nbplaces" class="col-sm-2 col-form-label col-form-label-sm">Nb de places</label>
					<div class="col-sm-10">
						<input type="number" min="0" id="nbplaces" class="form-control form-control-sm" name="nbplaces" required value="<?php echo $iti->nbPlaces(); ?>" placeholder="nb de places" />
					</div>
				</div>
				<div class="form-group row">
					<label for="bagagesautorises" class="col-sm-2 col-form-label col-form-label-sm">Bagages autorisés</label>
					<div class="col-sm-10">
						<select id="bagagesautorises" class="form-control form-control-sm" name="bagagesautorises" />
						<option value="1" <?php if ($iti->bagagesAutorises() == 1) echo 'selected="selected"'; ?>>Oui</option>
						<option value="0" <?php if ($iti->bagagesAutorises() == 0) echo 'selected="selected"'; ?>>Non</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="details" class="col-sm-2 col-form-label col-form-label-sm">Détails</label>
					<div class="col-sm-10">
						<textarea id="details" class="form-control form-control-sm" name="details" placeholder="détails de l'itinéraire" /><?php echo $iti->details(); ?></textarea>
					</div>
				</div>
				<input type="hidden" id="idmembre" name="idmembre" value="<?php echo $iti->idMembre(); ?>" />
				<input type="hidden" id="iditi" name="iditi" value="<?php echo $iti->idIti(); ?>" />
				<input type="submit" class="btn btn-primary" name="valider_modif" value="valider" />
			</fieldset>
		</form>
	<?php
	}

	/**
	 * modification dans la BD d'un jeu à partir des données du form précédent (NON FONCTIONNEL)
	 * @param aucun
	 * @return rien
	 */
	public function modJeu()
	{
		$jeu =  new Jeu($_POST);
		$ok = $this->jeuManager->update($jeu);

		if ($ok) echo "itineraire modifié";
		else echo "probleme lors de la modification";
	}
	/**
	 * form de saisie des criteres (NON FONCTIONNEL)
	 * @param aucun
	 * @return rien
	 */
	public function formRechercheJeu()
	{
	?>
		<form id="recherche" method="post" action="index.php" class="well">
			<fieldset class="form-group">
				<legend>Recherche d'jeux</legend>
				<div class="form-group row">
					<label for="lieudepart" class="col-sm-2 col-form-label col-form-label-sm">Départ</label>
					<div class="col-sm-10">
						<input type="text" id="lieudepart" name="lieudepart" class="form-control form-control-sm" placeholder="lieu de départ" />
					</div>
				</div>
				<div class="form-group row">
					<label for="lieuarrivee" class="col-sm-2 col-form-label col-form-label-sm">Arrivée</label>
					<div class="col-sm-10">
						<input type="text" id="lieuarrivee" name="lieuarrivee" class="form-control form-control-sm" placeholder="lieu d'arrivee" />
					</div>
				</div>
				<div class="form-group row">
					<label for="dateDepart" class="col-sm-2 col-form-label col-form-label-sm">Date</label>
					<div class="col-sm-10">
						<input type="text" id="datedepart" name="datedepart" class="form-control form-control-sm" placeholder="date de départ jj/mm/aaaa" />
					</div>
				</div>
				<input type="submit" name="valider_recher" value="valider" />
			</fieldset>
		</form>
<?php
	}
	/**
	 * recherche dans la BD jeu à partir des données du form précédent (NON FONCTIONNEL)
	 * @param aucun
	 * @return rien
	 */
	public function rechercheJeu()
	{
		$jeux = $this->jeuManager->search($_POST["lieudepart"], $_POST["lieuarrivee"], $_POST["datedepart"]);
		echo '<table class="table"><thead>';
		echo '<tr><th>Id</th><th>Lieu de départ</th><th>Lieu d\'arrivee</th><th>Date départ</th><th>Heure départ</th></tr>';
		echo '</thead><tbody>';
		foreach ($jeux as $jeu) {
			echo '<tr><td>' . $jeu->idIti() . '</td><td>' . $jeu->lieuDepart() . '</td><td>' . $jeu->lieuArrivee() . '</td><td>' . $jeu->dateDepart() . '</td><td>' . $jeu->heureDepart() . '</td></tr>';
		}
		echo '</tbody></table>';
	}
}
