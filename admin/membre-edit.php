<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity();  // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page

$errors = [];
$pseudo = $mdp = $confirmation_mdp = $statut = $civilite = $nom = $prenom = $email = $telephone = '';

// Gestion des erreurs
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
    
    // Champs "statut"
    if (empty($_POST['statut'])) {
        $errors['statut'] = 'Le statut est obligatoire';
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

    // Champs "prénom"
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
    } else {
        $query = "SELECT COUNT(*) FROM membre WHERE email = " . $pdo->quote($_POST['email']);
        
        if (isset($_GET['id_membre'])) {
            $query .= ' AND id_membre != ' . $_GET['id_membre'];
        }
        
        $stmt = $pdo->query($query);
        $nbMembre = $stmt->fetchColumn();
        if (0 != $nbMembre){
            $errors['email'] = 'Cette adresse email existe déjà';
        }
    }    
    
    // Champs "téléphone"
    if (empty($_POST['telephone'])) {
        $errors['telephone'] = 'Le téléphone est obligatoire';
    } elseif (strlen($_POST['telephone']) != 10 || !ctype_digit($_POST['telephone'])) {
        $errors['telephone'] = 'Le numéro de téléphone est invalide. Doit être composé de 10 chiffres';
    }
    
    // Champs "Mot de passe"
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
        
        // Requête pour MAJ dans la table membre (si id_membre est existant et a été récupéré alors maj membre)
        if (isset($_GET['id_membre'])) {
            $query = 'UPDATE membre SET pseudo = :pseudo, civilite = :civilite, nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, statut = :statut WHERE id_membre = :id_membre';   
            
            $stmt = $pdo->prepare($query);
            
            $stmt->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
            $stmt->bindValue(':statut', $statut, PDO::PARAM_STR);
            $stmt->bindValue(':civilite', $civilite, PDO::PARAM_STR);
            $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':telephone', $telephone, PDO::PARAM_INT);            
            $stmt->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);                   
            
        } else {
            
            // Préparation de la requête pour insertion dans la table membre (si id_membre non existant alors nouveau membre)
            $query = "INSERT INTO membre (pseudo, statut, civilite, nom, prenom, email, telephone, mdp, date_enregistrement) VALUES (:pseudo, :statut, :civilite, :nom, :prenom, :email, :telephone, :mdp, NOW())";
            
            $stmt = $pdo->prepare($query);
            
            $stmt->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $stmt->bindValue(':statut', $_POST['statut'], PDO::PARAM_STR);
            $stmt->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
            $stmt->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
            $stmt->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $stmt->bindValue(':telephone', $_POST['telephone'], PDO::PARAM_INT);
            $stmt->bindValue(':mdp', sha1($_POST['mdp']), PDO::PARAM_STR);
        }
        
        // Execution de la requête
        if ($stmt->execute()) {
            $message = isset($_GET['id_membre'])
                ? 'Les informations de "' . $_POST['pseudo'] . '" ont été modifiées !'
                : 'Le membre "' . $_POST['pseudo'] . '" a été créé !';
            $type = isset($_GET['id_membre'])
                ? 'success'
                : 'success';
            $glyphicon = isset($_GET['id_membre'])
            ? 'glyphicon-edit'
            : 'glyphicon-ok';
            
            setFlashMessage($message, $type, $glyphicon);
            header('Location: membre.php');
            die;

        } else {
            $errors['bdd'] = 'Une erreur est survenue';
        }
    } // end if (empty($errors)) {

}  elseif (isset($_GET['id_membre'])) { // end if (!empty($_POST)) {

// Récupération des informations du membre si id_membre existant et récupéré
        $query = 'SELECT * FROM membre WHERE id_membre =' . $_GET['id_membre'];
        $stmt = $pdo->query($query);
        $membre = $stmt->fetch();
        
        if (empty($membre)) {            
            header('Location: membre.php');
            die;
        }
    
        $pseudo = $membre['pseudo'];
        $statut = $membre['statut'];
        $civilite = $membre['civilite'];
        $nom = $membre['nom'];
        $prenom = $membre['prenom'];
        $email = $membre['email'];
        $telephone = $membre['telephone'];
        $mdp = $membre['mdp'];
        $confirmation_mdp = $membre['mdp'];        
    
    } // end elseif (isset($_GET['id_membre'])) {

// Insertion de la section haut de page
include '../layout/top.php';

displayFlashMessage();

if (!empty($errors)) :

?>
   
    <div class="alert alert-danger" role="alert" >
        <strong><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?= (isset($errors['bdd'])) ? $errors['bdd'] : 'Le formulaire contient des erreurs !'; ?></strong>
    </div>
   
<?php

endif; // end if (!empty($errors)) :

?>

<!-- Titre de la page -->
   
<h1 style="color: #a18131; font-family: 'Architects Daughter', cursive"><?php if (isset($_GET['id_membre'])) {echo 'Modification d\'un membre';} else {echo 'Ajout d\'un nouveau membre';} ?></h1>
    <hr>  

<!-- Formulaire modification membre -->

    <form class="col-lg-10 col-lg-offset-1 form-horizontal" method="post">         
            
        <div class="form-group <?php displayErrorClass('pseudo', $errors); ?>">
            <label class='control-label'>Pseudo</label>
            <input class="form-control" type="text" name="pseudo" placeholder="Choisissez un pseudo..." value="<?= $pseudo; ?>">
            <?php displayErrorMsg('pseudo', $errors); ?>
        </div>

        <div class="form-group <?php displayErrorClass('statut', $errors); ?>">
            <label class='control-label'>Statut</label>
            <select name="statut" class="form-control">
                <option value="">Choisissez...</option>
                <option value="admin" <?php if($statut == 1) {echo 'selected';} ?>>Admin</option>
                <option value="membre" <?php if($statut == 0) {echo 'selected';} ?>>Membre</option>
            </select>
            <?php displayErrorMsg('statut', $errors); ?>
        </div>

        <div class="form-group <?php displayErrorClass('civilite', $errors); ?>">
            <label class='control-label'>Civilité</label>
            <select name="civilite" class="form-control">
                <option value="">Choisissez...</option>
                <option value="m" <?php if($civilite == 'm') {echo 'selected';} ?>>Monsieur</option>
                <option value="f" <?php if($civilite == 'f') {echo 'selected';} ?>>Madame</option>
            </select>
            <?php displayErrorMsg('civilite', $errors); ?>
        </div>

        <div class="form-group <?php displayErrorClass('prenom', $errors); ?>">
            <label class='control-label'>Prénom</label>
            <input class="form-control" type="text" name="prenom" placeholder="Indiquer votre prénom..." value="<?= $prenom; ?>">
            <?php displayErrorMsg('prenom', $errors); ?>
        </div>

        <div class="form-group <?php displayErrorClass('nom', $errors); ?>">
            <label class='control-label'>Nom</label>
            <input class="form-control" type="text" name="nom" placeholder="Indiquer votre nom..." value="<?= $nom; ?>">
            <?php displayErrorMsg('nom', $errors); ?>
        </div>

        <div class="form-group <?php displayErrorClass('email', $errors); ?>">
            <label class='control-label'>Email</label>
            <input class="form-control" type="text" name="email" placeholder="Indiquer votre email..." value="<?= $email; ?>">
            <?php displayErrorMsg('email', $errors); ?>
        </div>

        <div class="form-group <?php displayErrorClass('telephone', $errors); ?>">
            <label class='control-label'>Téléphone</label>
            <input class="form-control" type="text" name="telephone" placeholder="Indiquer votre numéro de téléphone..." value="<?= $telephone; ?>">
            <?php displayErrorMsg('telephone', $errors); ?>
        </div>

        <div class="form-group <?php displayErrorClass('mdp', $errors); ?>">
            <label class='control-label'>Mot de passe</label>
            <input class="form-control" type="password" name="mdp" placeholder="Choisissez un mot de passe..." value="<?= $mdp; ?>">
            <?php displayErrorMsg('mdp', $errors); ?>
        </div>

        <div class="form-group <?php displayErrorClass('confirmation_mdp', $errors); ?>">
            <label class='control-label'>Confirmation du mot de passe</label>
            <input class="form-control" type="password" name="confirmation_mdp" placeholder="Confirmer votre mot de passe..." value="<?= $confirmation_mdp; ?>">
            <?php displayErrorMsg('confirmation_mdp', $errors); ?>
        </div>      

        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">VALIDER</button>
        </div>   
        <br><br>  
    </form> <!-- end <form class="col-lg-10 col-lg-offset-1 form-horizontal" method="post"> -->
    
<div class="row">
    <div class="col-md-12">
        <br><br>
        <a href="membre.php"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span> Retour vers Gestion des membres</a><br><br>
    </div>
</div>

<hr style="width: 1000px"> 

<?php

// Insertion de la section bas de page
include '../layout/bottom.php';
?>