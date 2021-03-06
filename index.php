<?php
require_once("inc/init.inc.php");
require_once("inc/haut.inc.php");
?>

<!-- Colonne du formulaire -->
<div class="col-md-4 form-accueil">

	<form action="" method="post">
		<div class="form-group">
			<label for="categorie_id">Catégorie</label>
			<select name="categorie_id" id="categorie_id" class="form-control">
				<option value="">Toutes les catégories</option>
				<?php
				$reqCategorie = $bdd->query("SELECT id_categorie, titre FROM categorie");
				while($categorie = $reqCategorie->fetch(PDO::FETCH_ASSOC))
				{
					echo '<option value="'. $categorie['id_categorie'] .'">'. $categorie['titre'] .'</option>';
				}
				?>
			</select>
		</div>

		<div class="form-group">
			<label for="ville">Ville</label>
			<select name="ville" class="form-control">
			  <option value="">Toutes les villes</option>
				<?php
				$reqAnnonce = $bdd->query("SELECT DISTINCT ville FROM annonce");
				while($annonce = $reqAnnonce->fetch(PDO::FETCH_ASSOC))
				{
					echo '<option value="'. $annonce['ville'] .'">'. $annonce['ville'] .'</option>';
				}
				?>
			</select>
		</div>

		<div class="form-group">
			<label for="membre_id">Membre</label>
			<select name="membre_id" id="membre_id" class="form-control">
			  <option value="">Tous les membres</option>
				<?php
				$reqMembre = $bdd->query("SELECT id_membre, pseudo FROM membre");
				while($membre = $reqMembre->fetch(PDO::FETCH_ASSOC))
				{
					echo '<option value="'. $membre['id_membre'] .'">'. $membre['pseudo'] .'</option>';
				}
				?>
			</select>
		</div>

		<button type="submit" class="btn btn-info">Rechercher</button>
	</form>

</div>

<!-- Colonne des articles -->
<div class="col-md-8">
<div id="test"></div>
<form action="" method="post">
	<div class="form-group">
		<label for="tri">Trier</label>
		<select name="tri" class="form-control">
			<option value="1">Trier par prix (du moins au plus cher)</option>
			<option value="2">Trier par prix (du plus au moins cher)</option>
		</select>
	</div>
</form>

<?php
	// Requête de sélection des 5 derniers articles publiés
	if($_POST)
	{
		$requete = "SELECT * FROM annonce";
		$first = true;

		if(sizeof($_POST) > 1){
			foreach($_POST as $indice => $valeur)
			{
				if($valeur != "")
				{
					if(!$first){
						$requete .= " AND " . $indice . " = '$valeur'";
					}
					else{
						$requete .= " WHERE " . $indice . " = '$valeur'";
						$first = false;
					}
				}
			}
		}
		else
		{
			foreach($_POST as $indice => $valeur)
			{
				$requete .= " WHERE " . $indice . " = '$valeur'";
				$first = false;
			}
		}
		$requete .= " ORDER BY date_enregistrement DESC LIMIT 0, 5";
		//echo $requete;
	}
	else
	{
		$requete = "SELECT * FROM annonce ORDER BY date_enregistrement DESC LIMIT 0, 5";
	}
	
	$req = $bdd->query($requete);
	//echo $req->rowCount();

	if($req->rowCount() > 0)
	{
		while($annonce = $req->fetch(PDO::FETCH_ASSOC))
		{
		//debug($annonce);
?>
		<div class="bloc_annonce">
			<div class="photo_annonce">
				<img src="<?php echo $annonce['photo']; ?>" alt="photo">
			</div>
			<div class="texte_annonce">
				<h3><?php echo $annonce['titre']; ?></h3>
				<p><?php echo $annonce['description']; ?></p>
			
				<p><span><?php echo $annonce['prix']; ?>€</span></p>

				<?php
				$reqMembre = $bdd->query("SELECT pseudo, moyenne_note FROM membre WHERE id_membre = '$annonce[membre_id]'");
				$membre = $reqMembre->fetch(PDO::FETCH_ASSOC);
				?>
				
				<p>Posté par : <?php echo $membre['pseudo']; ?> - <?php echo $membre['moyenne_note'];  ?> note(s)</p>

				<p><a href="fiche_annonce.php?id_annonce=<?php echo $annonce['id_annonce']; ?>">Consulter cette annonce</a></p>
			</div>
			<div class="clear"></div>
		</div>

<?php
		} // Fin du while ($annonce)
?>
		<p class="voir-plus"><a href="">Voir plus</a></p>
<?php
	} // Fin du if($req->rowCount() > 0)
	else
	{
		echo '<div class="alert alert-danger">Aucun(s) résultat(s). Veuillez effectuer une nouvelle recherche.</div>';
	}
?>
	<div class="clearfix"></div>
</div>


<?php
require_once("inc/bas.inc.php");
?>