<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity(); // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page

// Pour modifier l'annonce
$errors = [];
$titre = $description_courte = $description_longue = $prix = $categorie = $adresse = $cp = $ville = $pays = $membre = $nomPhoto1 = $photoActuelle1 = '';

// Photos supplémentaires
$nomPhoto5 = $nomPhoto2 = $nomPhoto3 = $nomPhoto4 = null;

// Photos téléchargées par l'utilisateur
$photo3 = $photo2 = $photo1 = $photo4 = $photo5 = '';


if (!empty($_POST)) {
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

    if (!empty($_FILES['photo1']['tmp_name'])) {
        if ($_FILES['photo1']['size'] > 1000000) {
            $errors['photo1'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
        }

        $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

        if (!in_array($_FILES['photo1']['type'], $allowedMimeTypes)) {
            $errors['photo1'] = 'La photo doit être une image JPG, JPEG, GIF ou PNG';
        }

        // ----------------- Photo 2 -----------------
        if (!empty($_FILES['photo2']['tmp_name'])) {
            if ($_FILES['photo2']['size'] > 1000000) {
                $errors['photo2'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
            }

            $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

            if (!in_array($_FILES['photo2']['type'], $allowedMimeTypes)) {
                $errors['photo2'] = 'La photo doit être une image JPG, JPEG, GIF ou PNG';
            }		
        }
        // ----------------- Photo 3 -----------------
        if (!empty($_FILES['photo3']['tmp_name'])) {
            if ($_FILES['photo3']['size'] > 1000000) {
                $errors['photo3'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
            }

            $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

            if (!in_array($_FILES['photo2']['type'], $allowedMimeTypes)) {
                $errors['photo2'] = 'La photo doit être une image JPG, JPEG, GIF ou PNG';
            }		
        }
        // ----------------- Photo 4 -----------------
        if (!empty($_FILES['photo4']['tmp_name'])) {
            if ($_FILES['photo4']['size'] > 1000000) {
                $errors['photo4'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
            }

            $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

            if (!in_array($_FILES['photo4']['type'], $allowedMimeTypes)) {
                $errors['photo4'] = 'La photo doit être une image JPG, JPEG, GIF ou PNG';
            }		
        }
        // ----------------- Photo 5 -----------------
        if (!empty($_FILES['photo5']['tmp_name'])) {
            if ($_FILES['photo5']['size'] > 1000000) {
                $errors['photo5'] = 'La taille de votre photo ne doit pas faire plus de 1Mo';
            }

            $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png'];

            if (!in_array($_FILES['photo5']['type'], $allowedMimeTypes)) {
                $errors['photo5'] = 'La photo doit être une image JPG, JPEG, GIF ou PNG';
            }		
        }

    }
    
    // Si le formulaire est correctement rempli -> Insert dans dans la BDD
    if (empty($errors)) {


        if (!empty($_FILES['photo1']['tmp_name'])) {
            $nomPhoto1 = uniqid() . '_' . $_FILES['photo1']['name'];
            move_uploaded_file($_FILES['photo1']['tmp_name'], PHOTO_SITE . $nomPhoto1);   
            
            // Suppression de l'ancienne photo si une nouvelle est téléchargée
            if (!empty($photoActuelle1)) {
                unlink(PHOTO_SITE . $photoActuelle1);
            } 

        }  else { 
            $nomPhoto1 = $photoActuelle1;
        }  

    // Photos supplémentaires 
        if (!empty($_FILES['photo2']['tmp_name'])) {
            $nomPhoto2 = uniqid() . '_' . $_FILES['photo2']['name'];
            move_uploaded_file($_FILES['photo2']['tmp_name'], PHOTO_SITE . $nomPhoto2);

            // Suppression de l'ancienne photo si une nouvelle est téléchargée
            if (!empty($photoActuelle2)) {
                unlink(PHOTO_SITE . $photoActuelle2);
            }

        } else { 
            $nomPhoto2 = $photoActuelle2;
        }
        if (!empty($_FILES['photo3']['tmp_name'])) {
            $nomPhoto3 = uniqid() . '_' . $_FILES['photo3']['name'];
            move_uploaded_file($_FILES['photo3']['tmp_name'], PHOTO_SITE . $nomPhoto3);

            // Suppression de l'ancienne photo si une nouvelle est téléchargée
            if (!empty($photoActuelle3)) {
                unlink(PHOTO_SITE . $photoActuelle3);
            }  

        } else { 
            $nomPhoto3 = $photoActuelle3;
        }

        if (!empty($_FILES['photo4']['tmp_name'])) {
            $nomPhoto4 = uniqid() . '_' . $_FILES['photo4']['name'];
            move_uploaded_file($_FILES['photo4']['tmp_name'], PHOTO_SITE . $nomPhoto4);

            // Suppression de l'ancienne photo si une nouvelle est téléchargée
            if (!empty($photoActuelle4)) {
                unlink(PHOTO_SITE . $photoActuelle4);
            }


        } else { 
            $nomPhoto4 = $photoActuelle4;
        }

        if (!empty($_FILES['photo5']['tmp_name'])) {
            $nomPhoto5 = uniqid() . '_' . $_FILES['photo5']['name'];
            move_uploaded_file($_FILES['photo5']['tmp_name'], PHOTO_SITE . $nomPhoto5);

            // Suppression de l'ancienne photo si une nouvelle est téléchargée
            if (!empty($photoActuelle5)) {
                unlink(PHOTO_SITE . $photoActuelle5);
            }

        } else { 
            $nomPhoto5 = $photoActuelle5;
        } 
        
    // Insertion des photos dans la BDD (Table photo)
        $query = 'INSERT INTO photo(photo1, photo2, photo3, photo4, photo5) VALUES (:photo1, :photo2, :photo3, :photo4, :photo5)';

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':photo1', $nomPhoto1, PDO::PARAM_STR);
        $stmt->bindParam(':photo2', $nomPhoto2, PDO::PARAM_STR);
        $stmt->bindParam(':photo3', $nomPhoto3, PDO::PARAM_STR);
        $stmt->bindParam(':photo4', $nomPhoto4, PDO::PARAM_STR);
        $stmt->bindParam(':photo5', $nomPhoto5, PDO::PARAM_STR);

        $stmt->execute();

        if (isset($_GET['id_annonce'])) {
            $query = 'UPDATE annonce SET'
                . ' titre=:titre, '
                . ' description_courte=:description_courte, '
                . ' description_longue=:description_longue, '
                . ' prix=:prix, '
                . ' id_categorie=:id_categorie, '
                . ' photo=:photo, '
                . ' adresse=:adresse,'
                . ' cp=:cp,'
                . ' ville=:ville, '
                . ' pays=:pays, '
                . ' id_membre=:id_membre, '
                . ' id_photo=:id_photo, '
                . ' WHERE id_annonce=:id_annonce';

            $message = 'Votre annonce a bien été modifiée'; 

        } else  {

    // Insertion dans la table si nouvelle annonce
            $query = 'INSERT INTO annonce(titre, description_courte, description_longue, prix, id_categorie, photo, adresse, cp, ville, pays, id_membre, id_photo, date_enregistrement) VALUES (:titre, :description_courte, :description_longue, :prix, :id_categorie, :photo, :adresse, :cp, :ville, :pays, :id_membre, :id_photo, NOW())';
            $message = 'Votre annonce a été publiée';
        }

        $stmt = $pdo->prepare($query);

        $query2 = 'SELECT id_photo FROM photo where photo1 = ' . $pdo->quote($nomPhoto1);
        $stmt2 = $pdo->query($query2);
        $photo_id = $stmt2->fetchColumn();

        $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindParam(':description_courte', $description_courte, PDO::PARAM_STR);
        $stmt->bindParam(':description_longue', $description_longue, PDO::PARAM_STR);
        $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindParam(':id_categorie', $categorie, PDO::PARAM_INT);
        $stmt->bindParam(':photo', $nomPhoto1, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':cp', $cp, PDO::PARAM_INT);
        $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);
        $stmt->bindParam(':pays', $pays, PDO::PARAM_STR);
        $stmt->bindValue(':id_membre', $membre, PDO::PARAM_INT);
        $stmt->bindValue(':id_photo', $photo_id, PDO::PARAM_INT);


        if (isset($_GET['id_annonce'])) {
            $stmt->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_INT);

        }

        if ($stmt->execute()) {
            $success = true;
            setFlashMessage($message);

            header('Location: annonce.php');
            die;
            
        } else {
            $errors['bdd'] = 'Une erreur est survenue';
        }
    } 

} elseif (isset($_GET['id_annonce'])) { 

    $query = 'SELECT * FROM annonce WHERE id_annonce = ' . $_GET['id_annonce'];
    $stmt = $pdo->query($query);
    $annonce_modif = $stmt->fetch();

  
    $query2 = 'SELECT * FROM photo WHERE photo1 = "' . $annonce_modif['photo'] . '"';
    $stmt = $pdo->query($query2);
    $photoAutres = $stmt->fetch();

    if (empty($annonce_modif)) {
        
        header('Location: annonce.php');
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
    $membre = $annonce_modif['membre_id'];
    $photoActuelle1 = $annonce_modif['photo'];
    $photoActuelle2 = $photoAutres['photo2'];
    $photoActuelle3 = $photoAutres['photo3'];
    $photoActuelle4 = $photoAutres['photo4'];
    $photoActuelle5 = $photoAutres['photo5'];
}

$query = 'SELECT * FROM membre ORDER BY pseudo';
$stmt = $pdo->query($query);
$pseudo = $stmt->fetchAll();

$query = 'SELECT * FROM categorie ORDER BY titre';
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll();

// Insertion du fichier haut de page
include '../layout/top.php';

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

<h1><?php if (isset($_GET['id_annonce'])) {echo 'Modification';} else {echo 'Nouvelle';}?> Annonce <?php if (isset($_GET['id_annonce'])) {echo 'N°' . ($_GET['id_annonce']) ;} ?></h1>      

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

<!-- Section photos -->

            <!-- Photo 1 -->
            <div class="row">                     
                <div class="col-md-10 form-group <?php displayErrorClass('photo1', $errors); ?>">
                    <label class='control-label'>Photo principale</label>
                    <input type="file" name="photo1">
                    <?php displayErrorMsg('photo1', $errors); ?>
                </div>
                <?php
                if (!empty($photoActuelle1)) :
                echo '<p>Photo actuelle : <img src="' . PHOTO_WEB . $photoActuelle1 . '" height="150px"></p>';
                endif;
                ?>
                <input type="hidden" name="photoActuelle1" value="<?= $photoActuelle1; ?>">
            </div> <!-- end div class="row" -->

            <!-- Photo 2 -->
            <div class="row">                     
                <div class="col-md-10 form-group <?php displayErrorClass('photo2', $errors); ?>">
                    <label class='control-label'>Photo 2</label>
                    <input type="file" name="photo2">
                    <?php displayErrorMsg('photo2', $errors); ?>
                </div>  
                <?php
                if (!empty($photoActuelle2)) :
                echo '<p>Photo actuelle : <img src="' . PHOTO_WEB . $photoActuelle2 . '" height="150px"></p>';
                endif;
                ?>                     
            </div> <!-- end div class="row" -->

            <!-- Photo 3 -->
            <div class="row">                     
                <div class="col-md-10 form-group <?php displayErrorClass('photo3', $errors); ?>">
                    <label class='control-label'>Photo 3</label>
                    <input type="file" name="photo3">
                    <?php displayErrorMsg('photo3', $errors); ?>
                </div> 
                <?php
                if (!empty($photoActuelle3)) :
                echo '<p>Photo actuelle : <img src="' . PHOTO_WEB . $photoActuelle3 . '" height="150px"></p>';
                endif;
                ?>                      
            </div> <!-- end div class="row" -->

            <!-- Photo 4 -->
            <div class="row">                     
                <div class="col-md-10 form-group <?php displayErrorClass('photo4', $errors); ?>">
                    <label class='control-label'>Photo 4</label>
                    <input type="file" name="photo4">
                    <?php displayErrorMsg('photo4', $errors); ?>
                </div> 
                <?php
                if (!empty($photoActuelle4)) :
                echo '<p>Photo actuelle : <img src="' . PHOTO_WEB . $photoActuelle4 . '" height="150px"></p>';
                endif;
                ?>                      
            </div> <!-- end div class="row" -->

            <!-- Photo 5 -->
            <div class="row">                     
                <div class="col-md-10 form-group <?php displayErrorClass('photo5', $errors); ?>">
                    <label class='control-label'>Photo 5</label>
                    <input type="file" name="photo5">
                    <?php displayErrorMsg('photo5', $errors); ?>
                </div>  
                <?php
                if (!empty($photoActuelle5)) :
                echo '<p>Photo actuelle : <img src="' . PHOTO_WEB . $photoActuelle5 . '" height="150px"></p>';
                endif;
                ?>                     
            </div> <!-- end div class="row" -->

            <div class="col-sm-offset-4 col-md-4 text-center"> 
                <button id="singlebutton" name="singlebutton" class="btn btn-primary">Enregistrer</button> 
            </div>
            <br><br>
        </form> 
<!-- Fin formulaire -->

<div class="row">
    <div class="col-md-12">
        <br><br>
        <a href="annonce.php"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span> Retour vers Gestion des annonces</a><br><br>
    </div>
</div>

<hr style="width: 1000px"> 
        
<?php
// Insertion de la section bas de page
include '../layout/bottom.php';
?>