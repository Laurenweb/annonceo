<?php
// Inclusion du fichier d'initialisation
include 'include/init.php';

// Pour activer la barre de recherche de la barre Nav 
$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';

// Pour activer la section de tri de la page d'accueil             
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : '';

$ville = isset($_GET['ville']) ? $_GET['ville'] : '';

$membre = isset($_GET['membre']) ? $_GET['membre'] : '';

$prix = isset($_GET['prix']) ? $_GET['prix'] : '';

// Requête pour récupérer le prénom du membre et rattacher l'annonce correspondante 
$query = 'SELECT * from annonce a JOIN membre m on a.membre_id=m.id_membre WHERE true';

if (!empty($recherche)) {
    $query .= ' AND titre LIKE ' . $pdo->quote("%$recherche%");
} 

if (!empty($categorie)) {
    $query .= ' AND categorie_id = ' . (int)$categorie;
}

if (!empty($ville)) {
    $query .= ' AND ville = ' . $pdo->quote($ville);
}

if (!empty($membre)) {
    $query .= ' AND membre_id = ' . (int)$membre;
}

if (!empty($prix)){
    $query .= ' AND prix ' . $prix;
}
$stmt = $pdo->query($query);
$annonces = $stmt->fetchAll();


// Requête pour la sélection des catégories
$query1 = 'SELECT * FROM categorie ORDER BY titre';
$stmt = $pdo->query($query1);
$categorie = $stmt->fetchAll();

// Requête pour la sélection des villes
$query2 = 'SELECT distinct(ville) FROM annonce ORDER BY ville';
$stmt = $pdo->query($query2);
$ville = $stmt->fetchAll();

// Requête pour la sélection des membres par leur pseudo
$query3 = 'SELECT * FROM membre ORDER BY pseudo';
$stmt = $pdo->query($query3);
$membre = $stmt->fetchAll();

// Requête de tri général
$query4 = 'SELECT * from annonce ORDER by date_enregistrement DESC';
$stmt = $pdo -> query($query4);
$tri = $stmt->fetchAll();

// Insertion du fichier haut de page
include 'layout/top.php';

displayFlashMessage();
?>

<!-- Titre de la page -->
<h2 class="text-center my_titles my_font">Bienvenue sur Annonceo</h2>
<hr style="width: 300px">


<!-- Section de tri page accueil -->
<div class="well col-sm-3">
  

<!-- Début formulaire de tri -->
    <form class="">
       
    <!--  --------------- Tri par catégorie --------------- -->
        <div class="form-group">
            <label>Catégories</label>
            <select class="form-control" name="categorie">
                <option value="">Toutes les annonces</option>
                <?php
                foreach($categorie as $element):
                $selected = ($element['id_categorie'] == $categorie)
                    ? 'selected'
                    : '';
                ?>
                <option value="<?= $element['id_categorie'];?>"<?= $selected; ?>><?= $element['titre']; ?></option>
                <?php
                endforeach;
                ?>				
            </select>
        </div>

    <!--  --------------- Tri par ville --------------- --> 
        <div class="form-group">
            <label>Villes</label>
            <select class="form-control" name="ville">
                <option value="">Choisissez</option>
                <?php
                foreach($ville as $element):
                $selected = ($element['ville'] == $ville)
                    ? 'selected'
                    : '';
                ?>
                <option value="<?= $element['ville'];?>"<?= $selected; ?>><?= $element['ville']; ?></option>
                <?php
                endforeach;
                ?>				
            </select>
        </div>
     
    <!--  --------------- Tri de contenu --------------- -->  
        <div class="form-group">
            <label>Trier par</label>
            <select class="form-control" name="tri">
                <option value="">Sélectionnez</option>
                <option value="date_enregistrement DESC">Annonces les plus récentes</option>
                <option value="date_enregistrement">Annonces les plus anciennes</option>
                <option value="prix ASC">Prix croissant</option>
                <option value="prix DESC">Prix décroissant</option> 
            </select>
        </div>
        
    <!--  --------------- Tri par prix --------------- -->
        <div class="form-group">
            <label>Prix</label>
            <select class="form-control" name="prix">
                <option value="">Sélectionnez</option>
                <option value="<=50"<?php if ($prix == 50) { echo 'selected' ;}?>>Moins de 50€</option>
                <option value="<=100"<?php if ($prix == 100) { echo 'selected' ;}?>>Moins de 100€</option>
                <option value="<=250"<?php if ($prix == 250) { echo 'selected' ;}?>>Moins de 250€</option>
                <option value="<=500"<?php if ($prix == 500) { echo 'selected' ;}?>>Moisn de 500€</option>
                <option value="<=1000"<?php if ($prix == 1000) { echo 'selected' ;}?>>Moins de 1000€</option>
                <option value=">=1000"<?php if ($prix == 1000) { echo 'selected' ;}?>>Plus de 1000€</option>
            </select>
        </div>
        
    <!--  ----------- Bouton "Rechercher" du formulaire  ----------- --> 
        <p class="text-center">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </p>
    </form>  <!--  Fin formulaire --> 
    
</div> <!--  Fin de section de tri page accueil --> 


<!--  ----------- Boucle d'affichage des annonces postées  ----------- --> 
        
    <div class="col-md-9">
        <?php foreach ($annonces as $annonce) : ?>
        <div class="row cadre">

            <div class="col-md-3 text-center">
                <img src="<?= PHOTO_WEB . $annonce['photo']; ?>" style="max-height: 130px; max-width: 165px;">
            </div>          

            <div class="col-md-9">

                <div class="row">
                    <div class="col-md-9">            
                        <h4><?= $annonce['titre']; ?></h4>
                        <p><?= $annonce['description_courte']; ?></p>
                    </div>
                </div>

                <div class="row in">
                    <div class="col-md-9">
                        <p class="pacifico">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <?= $annonce['pseudo']; ?>
                        </p>               
                        <p class="btn btn-default marge_cadre"><a href="<?= RACINE_WEB ?>detail_annonce.php?id_annonce=<?= $annonce['id_annonce']; ?>">Voir le détail</a></p>
                    </div>
                    <div class="col-md-3">       
                        <p style="font-size: 20px;"><?= number_format($annonce['prix'], 2, ',', ' ') . '€'; ?></p>
                    </div>    
                </div>

            </div>

        </div>
        <?php endforeach; ?>
    </div>
<hr style="width: 300px">  
  

<?php
// Insertion du fichier bas de page
include 'layout/bottom.php';
?>
    