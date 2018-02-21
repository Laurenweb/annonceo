<?php
// Inclusion du fichier d'initialisation
include 'include/init.php';

// Pour modifier l'annonce
$errors = [];
$titre = $description_courte = $description_longue = $prix = $categorie = $adresse = $cp = $ville = $pays = $membre = $nomPhoto1 = $photoActuelle1 = '';


// Photos téléchargées par l'utilisateur
$photo3 = $photo2 = $photo1 = $photo4 = $photo5 = '';

if (isset($_GET['id_annonce'])) { 

    $query = 'SELECT * FROM annonce WHERE id_annonce = ' . $_GET['id_annonce'];
    $stmt = $pdo->query($query);
    $annonce_modif = $stmt->fetch();


    $query2 = 'SELECT * FROM photo WHERE photo1 = "' . $annonce_modif['photo'] . '"';
    $stmt = $pdo->query($query2);
    $photoAutres = $stmt->fetch();

    if (empty($annonce_modif)) {

        header('Location: profil.php');
        die;
    }

    $titre = $annonce_modif['titre'];
    $description_courte = $annonce_modif['description_courte'];
    $description_longue = $annonce_modif['description_longue'];
    $prix = $annonce_modif['prix'];
    $categorie = $annonce_modif['categorie_id'];
    $adresse = $annonce_modif['adresse'];
    $cp = $annonce_modif['cp'];
    $ville = $annonce_modif['ville'];
    $pays = $annonce_modif['pays'];
    $photoActuelle1 = $annonce_modif['photo'];
    $photoActuelle2 = $photoAutres['photo2'];
    $photoActuelle3 = $photoAutres['photo3'];
    $photoActuelle4 = $photoAutres['photo4'];
    $photoActuelle5 = $photoAutres['photo5'];
    
    echo $photoActuelle4;echo $titre;echo $photo1;
}


if (!empty($_POST)) {
    
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';
    
    sanitizePost();
    extract($_POST);

    $prix = str_replace(',', '.', $prix);

    if (empty($titre)) {
        $errors['titre'] = 'Un titre est obligatoire';
    }

    if (empty($description_courte)) {
        $errors['description_courte'] = 'La description courte est obligatoire';
    }

    if (empty($description_longue)) {
        $errors['description_longue'] = 'La description longue est obligatoire';
    }

    if (empty($prix)) {
        $errors['prix'] = 'Le prix est obligatoire';
    } elseif (!is_numeric($prix)) {
        $errors['prix'] = 'Le prix doit être une valeur numérique';
    }

    if (empty($categorie)) {
        $errors['categorie'] = 'La catégorie est obligatoire';
    }
    
    if (empty($adresse)) {
        $errors['adresse'] = 'Une adresse est obligatoire';
    }
    
    if (empty($cp)) {
        $errors['cp'] = 'Un code postal est obligatoire';
    } elseif (strlen($_POST['cp']) != 5 || !ctype_digit($_POST['cp'])) {
        $errors['cp'] = 'Le cp est invalide';
    }

    if (empty($ville)) {
        $errors['ville'] = 'Une ville est obligatoire';
    }

    if (empty($pays)) {
        $errors['pays'] = 'Un pays est obligatoire';
    }

    if (empty($membre)) {
        $errors['membre'] = 'Un membre est obligatoire';
    }

    // Message d'erreur (Photo principale obligatoire)
    if (empty($_FILES['photo1']['tmp_name'])) {
        $errors['photo1'] = 'La photo est obligatoire';
    }


    // Messages d'erreur (Taille des images)
    
    // Si le formulaire est correctement rempli -> Insert dans dans la BDD


    // Photos supplémentaires 
        
        
        
    // Insertion des photos dans la BDD (Table photo)
        
    /*   $query = 'SELECT photo_id FROM annonce WHERE id_annonce = ' . $_GET['id_annonce'];
        $stmt = $pdo->query($query);
        $id_photo_annonce = $stmt->fetchcolumn();
        
        $query = 'UPDATE photo SET'
            . 'photo1=:photo1, '
            . 'photo2=:photo2, '
            . 'photo3=:photo3, '
            . 'photo4=:photo4, '
            . 'photo5=:photo5 '
            . ' WHERE id_photo= '. $id_photo_annonce;
            
    echo 'ici $nomPhoto1 '. $nomPhoto2;
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':photo1', $nomPhoto1, PDO::PARAM_STR);
        $stmt->bindParam(':photo2', $nomPhoto2, PDO::PARAM_STR);
        $stmt->bindParam(':photo3', $nomPhoto3, PDO::PARAM_STR);
        $stmt->bindParam(':photo4', $nomPhoto4, PDO::PARAM_STR);
        $stmt->bindParam(':photo5', $nomPhoto5, PDO::PARAM_STR);

        $stmt->execute();*/
    
    if (empty($errors)){
        if (isset($_GET['id_annonce'])) {
            $query = 'UPDATE annonce SET'
                . ' titre=:titre, '
                . ' description_courte=:description_courte, '
                . ' description_longue=:description_longue, '
                . ' prix=:prix, '
                //. ' photo=:photo, '
                . ' adresse=:adresse,'
                . ' cp=:cp,'
                . ' ville=:ville, '
                . ' pays=:pays, '
                . ' categorie_id=:categorie_id, '
                . ' membre_id=:membre_id '
                //. ' photo_id=:photo_id '
                . ' WHERE id_annonce = ' . $_GET['id_annonce'];
            $stmt = $pdo->prepare($query);

            $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
            $stmt->bindValue(':description_courte', $description_courte, PDO::PARAM_STR);
            $stmt->bindValue(':description_longue', $description_longue, PDO::PARAM_STR);
            $stmt->bindValue(':prix', $prix, PDO::PARAM_STR);
            // $stmt->bindParam(':photo', $nomPhoto1, PDO::PARAM_STR);
            $stmt->bindValue(':adresse', $adresse, PDO::PARAM_STR);
            $stmt->bindValue(':cp', $cp, PDO::PARAM_INT);
            $stmt->bindValue(':ville', $ville, PDO::PARAM_STR);
            $stmt->bindValue(':pays', $pays, PDO::PARAM_STR);
            $stmt->bindValue(':categorie_id', $categorie, PDO::PARAM_INT);
            $stmt->bindValue(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_INT);
            //$stmt->bindValue(':photo_id', $id_photo_annonce, PDO::PARAM_INT);


            // echo '<pre> ici dump $stmt ';
            // var_dump($query);
            // echo '</pre>';

             

        } 

    }// FIN EMPTY ERRORS
        

        if ($stmt->execute()) {
            $success = true;
            $message = 'Votre annonce a bien été modifiée';
            setFlashMessage($message);

            header('Location: profil.php');
            die;
            
        } else {
            $errors['bdd'] = 'Une erreur est survenue';
        }
    } // FIN !empty $post


$query = 'SELECT * FROM membre ORDER BY pseudo';
$stmt = $pdo->query($query);
$pseudo = $stmt->fetchAll();

$query = 'SELECT * FROM categorie ORDER BY titre';
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll();

// Insertion du fichier haut de page
include 'layout/top.php';

// Message d'erreur (En cas de formulaire erroné)
if (!empty($errors)) :
?>

<div class="alert alert-danger" role="alert">
    <strong>
        <?= (isset($errors['bdd']))
    ? $errors['bdd']
    : 'Le formulaire contient des erreurs';
        ?>
    </strong>
</div>

<?php
endif;
?>

<h2 class="my_titles"><?php if (isset($_GET['id_annonce'])) {echo 'Modification';} else {echo 'Nouvelle';}?> Annonce <?php if (isset($_GET['id_annonce'])) {echo 'N°' . ($_GET['id_annonce']) ;} ?></h2>      

    <div class="col-md-12">   
               
<!--Début du formulaire-->
        <form method="post" enctype="multipart/form-data" class="col-sm-offset-3 col-sm-6">
           
            <!-- Titre annonce -->
            <div class="form-group <?php displayErrorClass('titre', $errors); ?>">
                <label for="titre" class="control-label">Titre</label>
                <input id="titre" name="titre" type="titre" class="form-control" placeholder="Titre de l'annonce" value="<?= $titre; ?>">
                <?php displayErrorMsg('titre', $errors); ?>
            </div>

            <!-- Description courte -->       
            <div class="form-group <?php displayErrorClass('description_courte', $errors); ?>">
                <label class='control-label'>Description courte</label>
                <textarea class="form-control" name="description_courte" placeholder="Description courte de l'annonce"><?= $description_courte; ?></textarea>
                <?php displayErrorMsg('description_courte', $errors); ?>
            </div>

            <!-- Description longue -->
            <div class="form-group <?php displayErrorClass('description_longue', $errors); ?>">
                <label class='control-label'>Description longue</label>
                <textarea class="form-control" name="description_longue" placeholder="Description longue de l'annonce"><?= $description_longue; ?></textarea>
                <?php displayErrorMsg('description_longue', $errors); ?>
            </div>

            <!-- Prix -->                      
            <div class="form-group <?php displayErrorClass('prix', $errors); ?>">
                <label class='control-label'>Prix</label>
                <input class="form-control" type="text" name="prix" placeholder="Prix figurant dans l'annonce..." value="<?= $prix; ?>">
                <?php displayErrorMsg('prix', $errors); ?>
            </div>

            <!-- Catégorie -->
            <div class="form-group <?php displayErrorClass('categorie', $errors); ?>">
                <label class='control-label'>Catégorie</label>
                <select class="form-control" name="categorie">
                    <option value="">Choisissez...</option>
                    <?php
                    foreach ($categories as $cat) :
                    $selected = ($cat['id_categorie'] == $categorie) ? 'selected' : '';
                    ?>
                    <option value="<?= $cat['id_categorie']; ?>" <?= $selected; ?> ><?= $cat['titre']; ?></option>
                    <?php   endforeach; ?>
                </select>    
                <?php displayErrorMsg('categorie', $errors); ?>
            </div>

            <!-- Adresse -->
            <div class="form-group <?php displayErrorClass('adresse', $errors); ?>">
                <label class='control-label'>Adresse</label>
                <input class="form-control" type="text" name="adresse" placeholder="Adresse postale figurant dans l'annonce..." value="<?= $adresse; ?>">
                <?php displayErrorMsg('adresse', $errors); ?>
            </div>

            <!-- Code Postal -->
            <div class="form-group <?php displayErrorClass('cp', $errors); ?>">
                <label class='control-label'>Code postal</label>
                <input class="form-control" type="text" name="cp" placeholder="Code postal figurant dans l'annonce..." value="<?= $cp; ?>">
                <?php displayErrorMsg('cp', $errors); ?>
            </div>

            <!-- Ville -->
            <div class="form-group <?php displayErrorClass('ville', $errors); ?>">
                <label class='control-label'>Ville</label>
                <input class="form-control" type="text" name="ville" placeholder="Ville" value="<?= $ville; ?>">
                <?php displayErrorMsg('ville', $errors); ?>
            </div>

            <!-- Pays -->
            <div class="form-group <?php displayErrorClass('pays', $errors); ?>">
                <label class='control-label'>Pays</label>
                <input class="form-control" type="text" name="pays" placeholder="Pays" value="<?= $pays; ?>">
                <?php displayErrorMsg('pays', $errors); ?>
            </div>   

<!-- Section photos -->
            <!-- Supprimée suite à un problème qui affecte les modifications -->


            <div class="col-sm-offset-4 col-md-4 text-center"> 
                <button id="singlebutton" class="btn btn-primary">Enregistrer</button> 
            </div>
            <br><br>
        </form> 
<!-- Fin formulaire -->

<div class="row">
    <div class="col-md-12">
        <br><br>
        <a href="profil.php"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span> Retour vers Mes annonces</a><br><br>
    </div>
</div>

    <hr style="width: 1000px"> 
        
<?php
// Insertion de la section bas de page
include 'layout/bottom.php';
?>