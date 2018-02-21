<?php

// Inclusion du fichier d'initialisation
include 'include/init.php';

// Sécurité (l'utilisateur doit être connecté pour accéder à cette page) 
memberSecurity();

// Nombre d'annonces appartenant au membre connecté
$query = 'SELECT COUNT(id_annonce) FROM annonce WHERE membre_id = ' . (int)$_SESSION['membre']['id_membre'];
$stmt = $pdo->query($query);
$nbAnnonces = $stmt->fetchColumn();

// Nombre de commentaires déposés par le membre connecté
$query = 'SELECT COUNT(membre_id) FROM commentaire WHERE membre_id = ' . (int)$_SESSION['membre']['id_membre'];
$stmt = $pdo->query($query);
$nbCommentairesDeposes = $stmt->fetchColumn();

// Nombre de notes reçus par le membre connecté
$query = 'SELECT COUNT(membre_id2) FROM note WHERE membre_id2 = ' . (int)$_SESSION['membre']['id_membre'];
$stmt = $pdo->query($query);
$nbNotesRecus = $stmt->fetchColumn();

// Nombre de notes données par le membre connecté à d'autres membres
$query = 'SELECT COUNT(membre_id1) FROM note WHERE membre_id1 = ' . (int)$_SESSION['membre']['id_membre'];
$stmt = $pdo->query($query);
$nbNotesDonnees = $stmt->fetchColumn();

$errors = [];
$pseudo = $mdp = $confirmation_mdp = $civilite = $nom = $prenom = $email = $telephone = '';

if (!empty($_POST)) {

    sanitizePost();

    extract($_POST);
    
    // Champs "pseudo"
    if (empty($_POST['pseudo'])) {
        $errors['pseudo'] = 'Le pseudo est obligatoire';
    } elseif (strlen($_POST['pseudo']) < 6) {
        $errors['pseudo'] = 'Le pseudo doit contenir au minimum 6 caractères';
	} elseif (strlen($_POST['pseudo']) > 20){
		$errors['pseudo'] = 'Le pseudo doit contenir au maximum 20 caractères';
	} elseif ( !preg_match('/^[a-zA-Z0-9]{6,20}$/', $pseudo)) {
		$errors['pseudo'] = 'Pseudo entre 6 et 20 caractères. Caractères acceptés : chiffres de 0-9, lettres de A-Z et de a-z. Pas d\'espaces';    
    }
    
    // Champs "civilité"
    if (empty($_POST['civilite'])) {
        $errors['civilite'] = 'La civilité est obligatoire';
    }
    
    // Champs "nom"
    if (empty($_POST['nom'])) {
        $errors['nom'] = 'Le nom est obligatoire';
    } elseif (strlen($_POST['nom']) > 20){
        $errors['nom'] = 'Le nom doit contenir au maximum 20 caractères';
    }    

    // Champs "prenom"
    if (empty($_POST['prenom'])) {
        $errors['prenom'] = 'Le prénom est obligatoire';
    } elseif (strlen($_POST['prenom']) > 20){
        $errors['prenom'] = 'Le prénom doit contenir au maximum 20 caractères';
    }

    // Champs "email"
    if (empty($_POST['email'])) {
        $errors['email'] = 'L\'email est obligatoire';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Cette adresse email n\'est pas valide';
    } 
    
    // Champs "telephone"
    if (empty($_POST['telephone'])) {
        $errors['telephone'] = 'Le téléphone est obligatoire';
    } elseif (strlen($_POST['telephone']) != 10 || !ctype_digit($_POST['telephone'])) {
        $errors['telephone'] = 'Le numéro de téléphone est invalide. Il doit être composé de 10 chiffres';
    }
    
    // Champs "mdp"
    if (empty($_POST['mdp'])) {
        $errors['mdp'] = 'Le champs "Mot de passe" est obligatoire';
    } elseif (strlen($_POST['mdp']) < 8) {
        $errors['mdp'] = 'Le mot de passe doit faire au moins 8 caractères';
	} elseif ($_POST['mdp'] == $_POST['pseudo']){
		$errors['mdp'] = 'Le mot de passe doit être différent de votre pseudo.';
    } elseif (($_POST['mdp']) != ($_POST['confirmation_mdp'])) {
        $errors['confirmation_mdp'] = 'Le mot de passe de confirmation n\'est pas identique au mot de passe initial';
    }    

    if (empty($errors)) {
        
        // Requête pour mise à jour des éléments dans la table membre
        if (isset($_SESSION['membre']['id_membre'])) {
            $query = 'UPDATE membre SET pseudo = :pseudo, civilite = :civilite, nom = :nom, prenom = :prenom, email = :email, telephone = :telephone WHERE id_membre = :id_membre';   
            
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
            $stmt->bindValue(':civilite', $civilite, PDO::PARAM_STR);
            $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':telephone', $telephone, PDO::PARAM_INT);            
            $stmt->bindValue(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
        }
        
        // Exécution de la requete
        if ($stmt->execute()) {
            
            $success = true;
            setFlashMessage('Les modifications de vos données personnelles ont bien été modifiées !', 'success', 'glyphicon-edit');
            header('Location: profil.php');
            die;
            
        } else {
            $errors['bdd'] = 'Une erreur est survenue';
        }
    } // end if (empty($errors)) {
         
} elseif (isset($_SESSION['membre']['id_membre'])) {

    // Récupération des données de l'utilsateur connecté dans la table membre 
    $query = 'SELECT * FROM membre WHERE id_membre =' . $_SESSION['membre']['id_membre'];
    $stmt = $pdo->query($query);
    $membre = $stmt->fetch();

    $pseudo = $membre['pseudo'];
    $statut = $membre['statut'];
    $civilite = $membre['civilite'];
    $nom = $membre['nom'];
    $prenom = $membre['prenom'];
    $email = $membre['email'];
    $telephone = $membre['telephone'];
    $mdp = $membre['mdp'];    
    $confirmation_mdp = $membre['mdp'];    
}

// Sélection des annonces du membre connecté
$query = 'SELECT a.*, c.titre AS titre_categorie FROM annonce a JOIN categorie c ON c.id_categorie = a.categorie_id WHERE membre_id =' . $_SESSION['membre']['id_membre'];
$stmt = $pdo->query($query);
$annonces = $stmt->fetchAll();

// Sélection des commentaires déposés
$query = 'SELECT c.*, a.titre AS titre_annonce, a.photo AS photo_annonce FROM commentaire c JOIN annonce a ON a.id_annonce = c.annonce_id WHERE c.membre_id =' . $_SESSION['membre']['id_membre'];
$stmt = $pdo->query($query);
$commentairesDeposes = $stmt->fetchAll();

// Sélection des notes déposés
$query = 'SELECT n.*, m.email AS email_membre, m.prenom AS prenom_membre FROM note n JOIN membre m ON m.id_membre = n.membre_id2 WHERE n.membre_id1 =' . $_SESSION['membre']['id_membre'];
$stmt = $pdo->query($query);
$notesDeposees = $stmt->fetchAll();

// Sélection des notes reçues
$query = 'SELECT n.*, m.email AS email_membre, m.prenom AS prenom_membre FROM note n JOIN membre m ON m.id_membre = n.membre_id1 WHERE n.membre_id2 =' . $_SESSION['membre']['id_membre'];
$stmt = $pdo->query($query);
$notesRecues = $stmt->fetchAll();

// Moyenne des notes de l'utilisateur
$query6 = 'SELECT ROUND(avg(note),1) AS notation FROM note WHERE membre_id2 = ' . $_SESSION['membre']['id_membre'];
$stmt = $pdo ->query($query6);
$avgNotes = $stmt -> fetch();

// Insertion du fichier haut de page
include 'layout/top.php';

if (!empty($errors)) :

?>
   
    <div class="alert alert-danger" role="alert" >
        <strong><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?= (isset($errors['bdd'])) ? $errors['bdd'] : 'Le formulaire contient des erreurs !'; ?></strong>
    </div>
   
<?php

endif; // Fin if (!empty($errors)) :

?>

<?= displayFlashMessage() ?>
   
<!-- Section identité du membre connecté -->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
           
            <div class="well">
                    
                <h2>
                    <?= $_SESSION['membre']['prenom'] ?><br>
                </h2>
                <hr style="border: 1px solid #ddd;">
               
                <p style="font-size: 16px;">
                

                    <?php if ($avgNotes['notation'] >= '4.5') : ?>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                    <?php elseif ($avgNotes['notation'] >= '3.5') : ?>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                    <?php elseif ($avgNotes['notation'] >= '2.5') : ?>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                    <?php elseif ($avgNotes['notation'] >= '1.5') : ?>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                    <?php elseif ($avgNotes['notation'] >= '0.5') : ?>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                    <?php else : ?>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #DDD;"></span>
                    <?php endif; ?>

                    <br><?= $avgNotes['notation']; ?> / 5.0
                </p>
                
                <p>
                    Membre depuis : <?= $_SESSION['membre']['date_enregistrement'] ?>
                </p>
            
            </div> <!-- end <div class="well"> -->
        </div> <!-- end <div class="col-md-8 col-md-offset-2"> -->
    </div> <!-- end <div class="row"> -->
   
<!-- Données personnelles -->
<h2 class="my_titles">Mes données personnelles</h2>
    <hr>

    <form method="post">
        <div class="row">
            <div class="col-sm-6">
               
                <div class="row">
                    <div class="col-md-4 col-md-offset-1 form-group <?php displayErrorClass('civilite', $errors); ?>">
                        <label class='control-label'>Civilité</label>
                        <select name="civilite" class="form-control">
                            <option value="">Choisissez...</option>
                            <option value="m" <?php if($civilite == 'm') {echo 'selected';} ?>>Monsieur</option>
                            <option value="f" <?php if($civilite == 'f') {echo 'selected';} ?>>Madame</option>
                        </select>
                        <?php displayErrorMsg('civilite', $errors); ?>                        
                    </div>
                </div> <!-- end <div class="row"> -->
                
                <div class="row">
                    <div class="col-md-5 col-md-offset-1 form-group <?php displayErrorClass('prenom', $errors); ?>">
                        <label class='control-label'>Prénom</label>
                        <input class="form-control" type="text" name="prenom" placeholder="Indiquer votre prénom..." value="<?= $prenom; ?>">
                        <?php displayErrorMsg('prenom', $errors); ?>                        
                    </div>
                    <div class="col-md-5 form-group <?php displayErrorClass('nom', $errors); ?>">
                        <label class='control-label'>Nom</label>
                        <input class="form-control" type="text" name="nom" placeholder="Indiquer votre nom..." value="<?= $nom; ?>">
                        <?php displayErrorMsg('nom', $errors); ?>                        
                    </div>
                </div> <!-- end <div class="row"> -->
                
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 form-group <?php displayErrorClass('email', $errors); ?>">
                        <label class='control-label'>Email</label>
                        <input class="form-control" type="text" name="email" placeholder="Indiquer votre email..." value="<?= $email; ?>">
                        <?php displayErrorMsg('email', $errors); ?>                        
                    </div>
                </div> <!-- end <div class="row"> -->
                
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 form-group <?php displayErrorClass('telephone', $errors); ?>">
                        <label class='control-label'>Téléphone</label>
                        <input class="form-control" type="text" name="telephone" placeholder="Indiquer votre numéro de téléphone..." value="<?= $telephone; ?>">
                        <?php displayErrorMsg('telephone', $errors); ?>                        
                    </div>
                </div> <!-- end <div class="row"> -->
                
            </div> <!-- end <div class="col-sm-6"> -->       
        
            <div class="col-sm-6">
               
                <div class="row">
                    <div class="col-xs-4 col-xs-offset-4 text-center">
                        <h1><span class="glyphicon glyphicon-user" aria-hidden="true"></span></h1>
                    </div>
                </div> <!-- end <div class="row"> -->
                
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 form-group text-center <?php displayErrorClass('pseudo', $errors); ?>">
                        <label class='control-label'>Pseudo</label>
                        <input class="form-control" type="text" name="pseudo" placeholder="Choisissez un pseudo..." value="<?= $pseudo; ?>">
                        <?php displayErrorMsg('pseudo', $errors); ?>                        
                    </div>
                </div> <!-- end <div class="row"> -->
                
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 form-group text-center <?php displayErrorClass('mdp', $errors); ?>">
                        <label class='control-label'>Mot de passe actuel</label>
                        <input class="form-control" type="password" name="mdp" placeholder="Choisissez un mot de passe..." value="<?= $mdp; ?>">
                        <?php displayErrorMsg('mdp', $errors); ?>                        
                    </div>
                </div> <!-- end <div class="row"> -->
                
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 form-group text-center <?php displayErrorClass('confirmation_mdp', $errors); ?>">
                        <label class='control-label'>Confirmation du mot de passe</label>
                        <input class="form-control" type="password" name="confirmation_mdp" placeholder="Confirmer votre mot de passe..." value="<?= $confirmation_mdp; ?>">
                        <?php displayErrorMsg('confirmation_mdp', $errors); ?>                        
                    </div>               
                </div> <!-- end <div class="row"> -->
                
            </div> <!-- end <div class="col-sm-6"> -->  
        </div> <!-- end <div class="row"> -->

        <div class="form-group text-center" style="margin-bottom: 35px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Valider les modifications</button>
        </div>
            
    </form> <!-- Fin du formulaire -->
   
   
<!-- Annonces publiées -->   
<h2 class="my_titles">Mon tableau de bord</h2>
    <hr>
    
<!-- Nombre d'annonces publiées par l'utilisateur connecté -->   
    <p class="bold">
        <span class="glyphicon glyphicon-inbox my_glyph" aria-hidden="true"></span>
         <?= $nbAnnonces; ?> <span class="nb_annonces">annonce(s) en ligne.</span>
    </p>

<!-- Les annonces du membre connecté -->
<p class="text-center">
    <a href="creation_annonce.php">Ajouter une annonce</a>
</p>
<div class="table-responsive">              
    <div class="col-md-12">
        <table width="100%" class="table">
            <tr style="background-color: #222; color: #9d9d9d; border: 1px solid #fff; font-size: 12px;">
                <th>Id Annonce</th>
                <th>Titre</th>
                <th>Description courte</th>
                <th>Description longue</th>
                <th>Prix</th>
                <th>Photo</th>
                <th>Adresse</th>
                <th>CP</th>
                <th>Ville</th>
                <th>Pays</th>
                <th>Catégorie</th>
                <th>Date enregistrement</th>
                <th>Actions</th>
            </tr>
            

<!-- Boucle foreach pour parcourir une à une chaque annonce -->
        <?php foreach ($annonces as $element) :
        ?>
        <tr style="background-color: #F9F9F9; font-size: 10px;">
            <td><?= $element['id_annonce']; ?></td>
            <td><?= $element['titre']; ?></td>
            <td><?= nl2br($element['description_courte']); ?></td>
            <td><?= nl2br($element['description_longue']); ?></td>
            <td><?= $element['prix']; ?></td>
            <td><?= $element['photo']; ?></td>
            <td><?= $element['adresse']; ?></td>
            <td><?= $element['cp']; ?></td>
            <td><?= $element['ville']; ?></td>
            <td><?= $element['pays']; ?></td>
            <td><?= $element['titre_categorie']; ?></td>
            <td><?= $element['date_enregistrement']; ?></td>
            <td class="text-right">
                <!-- Visualisation d'une annonce -->
                <a class="btn btn-info" href="detail_annonce.php?id_annonce=<?= $element['id_annonce']; ?>">
                    <span class="glyphicon glyphicon-zoom-in"></span></a>

                <!-- Modification d'une annonce -->     
                <a class="btn btn-primary" href="modif_annonce.php?id_annonce=<?= $element['id_annonce']; ?>">
                    <span class="glyphicon glyphicon-edit"></span></a>

                <!-- Suppression d'une annonce (Modal) --> 
                <a class="btn btn-danger" data-toggle="modal" data-target="#myModal<?= $element['id_annonce']; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>  

                <!-- Modal pour confirmation de suppression d'un membre -->
                <div class="modal fade" id="myModal<?= $element['id_annonce']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 style="color: #a18131;" class="text-center modal-title" id="myModalLabel">Confirmation de la suppression</h3>
                            </div>
                            <div class="text-center modal-body">
                                <h4>Êtes-vous sûr de vouloir supprimer cet élément ?</h4>
                            </div>
                            <div class="modal-footer">
                                <a href="supp_annonce.php?id_annonce=<?= $element['id_annonce']; ?>">
                                    <button type="button" class="btn btn-danger">Supprimer</button>
                                </a>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                            </div>

                        </div> <!-- Fin modal-content -->
                    </div> <!-- Fin modal-dialog -->
                </div> <!-- Fin modal fade -->      

            </td>
        </tr>

        <?php
        endforeach;
        ?>
    </table>
   
<!-- Commentaires -->   
        <h2 class="my_titles" style="margin-top: 40px;">Mes commentaires</h2>
    <hr>
    
<!-- Nombre de commentaires déposés -->   
    <p class="bold">
        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
        Vous avez déposé <?= $nbCommentairesDeposes; ?> commentaire(s) !
    </p>    
    
<!-- Tableau des commentaires -->   
    <table class="table">
        <tr style="background-color: #222; color: #9d9d9d; border: 1px solid #fff; font-size: 12px;">
           <th>Date de dépôt</th>
           <th>Annonce concernée</th>
           <th>Commentaire déposé</th>
           <th></th>
        </tr>    

        <!-- Boucle foreach pour parcourir un à un chaque commentaire -->
        <?php foreach ($commentairesDeposes as $commentaire) : ?>       
       
            <tr style="background-color: #F9F9F9; font-size: 10px;">
                <td><?= $commentaire['date_enregistrement']; ?></td>
                <td><?= $commentaire['titre_annonce']; ?></td>
                <td><?= $commentaire['commentaire']; ?></td>
                <td class="text-right">
                    <a href="<?= RACINE_WEB ?>detail_annonce.php?id_annonce=<?= $commentaire['annonce_id']; ?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Voir l'annonce correspondante</a>
                </td>
            </tr>
                
        <?php endforeach; ?>
                
    </table>  
    
    
<!-- Section "NOTES-AVIS" -->   

    <h2 class="my_titles" style="margin-top: 40px;">Avis et notes</h2>
    <hr>

<!-- Nombre de notes attribuées -->   

    <p class="bold">
        <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
        Vous avez été noté par <?= $nbNotesRecus; ?> membre(s) !
    </p>    
    
<!-- Tableau des notes & avis (attribués par les autres membres) -->   

    <table class="table">
        <tr style="background-color: #222; color: #9d9d9d; border: 1px solid #fff; font-size: 12px;">
           <th>Date de dépôt</th>
           <th>Attribué par</th>
           <th></th>
           <th>Note</th>
           <th>Avis déposé</th>
        </tr>    

        <!-- Boucle foreach pour parcourir une à une chaque note -->
        <?php foreach ($notesRecues as $note) : ?>       

            <tr style="background-color: #F9F9F9; font-size: 10px;">
                <td><?= $note['date_enregistrement']; ?></td>
                <td><?= $note['prenom_membre']; ?></td>
                <td><?= $note['email_membre']; ?></td>
                <td>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                    <?= $note['note']; ?>
                </td>
                <td><?= cutText($note['avis'], 100); ?></td>
            </tr>
                
        <?php endforeach; ?>
                
    </table>                                      
    
<!-- Nombre de notes attribué aux autres membres -->   
    
    <p class="bold">
        <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
        Vous avez attribué une note à <?= $nbNotesDonnees; ?> membre(s) !
    </p>
    
<!-- Tableau des notes & avis (attribués aux autres membres) -->   
    <table class="table">
        <tr style="background-color: #222; color: #9d9d9d; border: 1px solid #fff; font-size: 12px;">
           <th>Date de dépôt</th>
           <th>Attribué à</th>
           <th></th>
           <th>Note</th>
           <th>Avis déposé</th>
        </tr>    
        
        <!-- Boucle foreach pour parcourir une à une chaque note -->
        <?php foreach ($notesDeposees as $note) : ?>       
       
            <tr style="background-color: #F9F9F9; font-size: 10px;">
                <td><?= $note['date_enregistrement']; ?></td>
                <td><?= $note['prenom_membre']; ?></td>
                <td><?= $note['email_membre']; ?></td>
                <td>
                    <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span>
                    <?= $note['note']; ?>
                </td>
                <td><?= cutText($note['avis'], 100); ?></td>
            </tr>
                
        <?php endforeach; ?>
                
    </table> 
    
<div class="row">
    <div class="col-md-12">
        <br><br>
        <a href="index.php"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span> Retour vers l'accueil</a><br><br>
    </div>
</div>
   
    <hr style="width: 1000px">             
    
<?php

// Insertion de la section bas de page
include 'layout/bottom.php';

?>