<?php
// Inclusion du fichier d'initialisation
include 'include/init.php';

// L'utilisateur devra être connecté pour accéder à cette page (voir top.php)
isUserConnected();

$errors = [];

$titre = $description_courte = $description_longue = $prix = $categorie = $adresse = $cp = $ville = $pays = $membre = $date_enregistrement = '';

$photo1 = $photo2 = $photo3 = $photo4 = $photo5 = '';

$nomPhoto1 = '';
$nomPhoto2 = $nomPhoto3 = $nomPhoto4 = $nomPhoto5 = null;

// Messages d'erreurs (en cas de saisie erronée dans le formulaire)
if (!empty($_POST)) {
    
    sanitizePost();
    extract($_POST);

    $prix = str_replace(',', '.', $prix);

    // ----------------- Titre -----------------
    if (empty($_POST['titre'])) {
        $errors['titre'] = 'Le titre de l\'annonce est obligatoire'; 
    }        

    // ----------------- Description courte -----------------
    if (empty($_POST['description_courte'])) {
        $errors['description_courte'] = 'La description courte de l\'annonce est obligatoire';            
    }    

    // ----------------- Description longue -----------------
    if (empty($_POST['description_longue'])) {
        $errors['description_longue'] = 'La description longue de l\'annonce est obligatoire';            
    }          

    // ----------------- Prix -----------------
    if (empty($_POST['prix'])) {
        $errors['prix'] = 'Le prix de l\'annonce est obligatoire';
    } elseif (!is_numeric($prix)){
        $errors['prix'] = 'Le prix doit obligatoirement être une valeur numérique';
    } 
    
    // ----------------- Catégorie -----------------
    if (empty($_POST['categorie'])) {
        $errors['categorie'] = 'La catégorie de l\'annonce est obligatoire';   
    }

    // ----------------- Adresse -----------------
    if (empty($_POST['adresse'])) {
        $errors['adresse'] = 'L\'adresse de l\'annonce est obligatoire';            
    }    

    // ----------------- Code Postal -----------------
    if (empty($_POST['cp'])) {
        $errors['cp'] = 'Le code postal est obligatoire';
    } elseif (strlen($_POST['cp']) != 5 || !ctype_digit($_POST['cp'])) {
        $errors['cp'] = 'Le code postal est invalide';
    }     

    // ----------------- Ville -----------------
    if (empty($_POST['ville'])) {
        $errors['ville'] = 'La ville est obligatoire';
    }

    // ----------------- Pays -----------------
    if (empty($_POST['pays'])) {
        $errors['pays'] = 'Le pays est obligatoire';
    }

    // ----------------- Photo 1 (Photo principale) -----------------
    if (empty($_FILES['photo1']['tmp_name'])) {
        $errors['photo1'] = 'La photo principale est obligatoire';
    }        

    if (!empty($_FILES['photo1']['tmp_name'])) {

        if ($_FILES['photo1']['size'] > 1000000) {
            $errors['photo1'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
        }

        $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

        if (!in_array($_FILES['photo1']['type'], $allowedMimeTypes)) {
            $errors['photo1'] = 'La photo doit être une image JPG, GIF ou PNG';
        }

        // ----------------- Photo 2 -----------------
        if (!empty($_FILES['photo2']['tmp_name'])) {
            if ($_FILES['photo2']['size'] > 1000000) {
                $errors['photo2'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
            }

            $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

            if (!in_array($_FILES['photo2']['type'], $allowedMimeTypes)) {
                $errors['photo2'] = 'La photo doit être une image JPG, GIF ou PNG';
            }		
        }            

        // ----------------- Photo 3 -----------------
        if (!empty($_FILES['photo3']['tmp_name'])) {
            if ($_FILES['photo3']['size'] > 1000000) {
                $errors['photo3'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
            }

            $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

            if (!in_array($_FILES['photo3']['type'], $allowedMimeTypes)) {
                $errors['photo3'] = 'La photo doit être une image JPG, GIF ou PNG';
            }		
        } 

        // ----------------- Photo 4 -----------------
        if (!empty($_FILES['photo4']['tmp_name'])) {
            if ($_FILES['photo4']['size'] > 1000000) {
                $errors['photo4'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
            }

            $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

            if (!in_array($_FILES['photo4']['type'], $allowedMimeTypes)) {
                $errors['photo4'] = 'La photo doit être une image JPG, GIF ou PNG';
            }		
        }      

        // ----------------- Photo 5 -----------------
        if (!empty($_FILES['photo5']['tmp_name'])) {
            if ($_FILES['photo5']['size'] > 1000000) {
                $errors['photo5'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
            }

            $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

            if (!in_array($_FILES['photo5']['type'], $allowedMimeTypes)) {
                $errors['photo5'] = 'La photo doit être une image JPG, GIF ou PNG';
            }		
        }                  
    }

    if (empty($errors)) {  

        if (!empty($_FILES['photo1']['tmp_name'])) {
            $nomPhoto1 = uniqid() . '_' . $_FILES['photo1']['name'];
            move_uploaded_file($_FILES['photo1']['tmp_name'], PHOTO_SITE . $nomPhoto1);
        }

        if (!empty($_FILES['photo2']['tmp_name'])) {
            $nomPhoto2 = uniqid() . '_' . $_FILES['photo2']['name'];
            move_uploaded_file($_FILES['photo2']['tmp_name'], PHOTO_SITE . $nomPhoto2);
        }

        if (!empty($_FILES['photo3']['tmp_name'])) {
            $nomPhoto3 = uniqid() . '_' . $_FILES['photo3']['name'];
            move_uploaded_file($_FILES['photo3']['tmp_name'], PHOTO_SITE . $nomPhoto3);
        }

        if (!empty($_FILES['photo4']['tmp_name'])) {
            $nomPhoto4 = uniqid() . '_' . $_FILES['photo4']['name'];
            move_uploaded_file($_FILES['photo4']['tmp_name'], PHOTO_SITE . $nomPhoto4);
        }

        if (!empty($_FILES['photo5']['tmp_name'])) {
            $nomPhoto5 = uniqid() . '_' . $_FILES['photo5']['name'];
            move_uploaded_file($_FILES['photo5']['tmp_name'], PHOTO_SITE . $nomPhoto5);
        }

        // Insertion des photos dans la table photo
        $query = 'INSERT INTO photo(photo1, photo2, photo3, photo4, photo5) VALUES (:photo1, :photo2, :photo3, :photo4, :photo5)';

        $stmt = $pdo->prepare($query);

        $stmt->bindValue(':photo1', $nomPhoto1, PDO::PARAM_STR);
        $stmt->bindValue(':photo2', $nomPhoto2, PDO::PARAM_STR);
        $stmt->bindValue(':photo3', $nomPhoto3, PDO::PARAM_STR);
        $stmt->bindValue(':photo4', $nomPhoto4, PDO::PARAM_STR);
        $stmt->bindValue(':photo5', $nomPhoto5, PDO::PARAM_STR); 

        // Exécution de la requête 
        $stmt->execute();
        

        // Requête pour insertion de l'annonce dans la table annonce
        $query = 'INSERT INTO annonce(titre, categorie_id, description_courte, description_longue, prix, photo, adresse, cp, ville, pays, membre_id, photo_id, date_enregistrement) VALUES (:titre, :categorie_id, :description_courte, :description_longue, :prix, :photo, :adresse, :cp, :ville, :pays, :membre_id, :photo_id, NOW())';

        $stmt = $pdo->prepare($query);

        // Requête pour récupération de l'id de la photo 1 (table photo) et insertion dans photo_id (table annonce)
        $query2 = 'SELECT id_photo FROM photo where photo1 = ' . $pdo->quote($nomPhoto1);
        $stmt2 = $pdo->query($query2);
        $photo_id = $stmt2->fetchColumn();

        $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindValue(':description_courte', $description_courte, PDO::PARAM_STR);
        $stmt->bindValue(':description_longue', $description_longue, PDO::PARAM_STR);
        $stmt->bindValue(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindParam(':photo', $nomPhoto1, PDO::PARAM_STR);
        $stmt->bindValue(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindValue(':cp', $cp, PDO::PARAM_INT);
        $stmt->bindValue(':ville', $ville, PDO::PARAM_STR);
        $stmt->bindValue(':pays', $pays, PDO::PARAM_STR);
        $stmt->bindValue(':categorie_id', $categorie, PDO::PARAM_STR);
        $stmt->bindValue(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_INT);
        $stmt->bindValue(':photo_id', $photo_id, PDO::PARAM_INT);

        // Exécution de la requête pour insertion dans la table annonce
        if ($stmt->execute()) {
            $success = true;
            setFlashMessage('Votre annonce "' . $_POST['titre'] . '" a bien été publiée !', 'success', 'glyphicon-thumbs-up');
            header('Location: index.php');            
            die;            
        } else {
            $errors[] = 'Une erreur est survenue';
        }

    } 

} 

// Requête pour le champ "Catégories"
$query = 'SELECT * FROM categorie ORDER BY titre';
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll();


// Insertion de la section haut de page
include 'layout/top.php';
?>

<h1 style="color: #a18131">Déposer une annonce</h1>

<!-- Début formulaire de création annonce -->

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
            <option value="<?= $cat['id_categorie']; ?>" <?= $selected; ?>><?= $cat['titre']; ?></option>
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
    
<!-- SECTION PHOTOS -->
       
    <!-- Photo 1 -->
    <div class="row">                     
        <div class="col-md-10 form-group <?php displayErrorClass('photo1', $errors); ?>">
            <label class='control-label'>Photo principale</label>
            <input type="file" name="photo1">
            <?php displayErrorMsg('photo1', $errors); ?>
        </div>
    </div> <!-- end div class="row" -->

    <!-- Photo 2 -->
    <div class="row">                     
        <div class="col-md-10 form-group <?php displayErrorClass('photo2', $errors); ?>">
            <label class='control-label'>Photo 2</label>
            <input type="file" name="photo2">
            <?php displayErrorMsg('photo2', $errors); ?>
        </div>                       
    </div> <!-- end div class="row" -->

    <!-- Photo 3 -->
    <div class="row">                     
        <div class="col-md-10 form-group <?php displayErrorClass('photo3', $errors); ?>">
            <label class='control-label'>Photo 3</label>
            <input type="file" name="photo3">
            <?php displayErrorMsg('photo3', $errors); ?>
        </div>                       
    </div> <!-- end div class="row" -->

    <!-- Photo 4 -->
    <div class="row">                     
        <div class="col-md-10 form-group <?php displayErrorClass('photo4', $errors); ?>">
            <label class='control-label'>Photo 4</label>
            <input type="file" name="photo4">
            <?php displayErrorMsg('photo4', $errors); ?>
        </div>                       
    </div> <!-- end div class="row" -->

    <!-- Photo 5 -->
    <div class="row">                     
        <div class="col-md-10 form-group <?php displayErrorClass('photo5', $errors); ?>">
            <label class='control-label'>Photo 5</label>
            <input type="file" name="photo5">
            <?php displayErrorMsg('photo5', $errors); ?>
        </div>                       
    </div> <!-- end div class="row" -->
    
    <div class="col-sm-offset-4 col-md-4 text-center"> 
        <button id="singlebutton" name="singlebutton" class="btn btn-primary">Enregistrer</button> 
    </div>
    <br>
    <hr />
</form> <!-- Fin formulaire -->

<?php
// Insertion de la section bas de page
include 'layout/bottom.php';
?>