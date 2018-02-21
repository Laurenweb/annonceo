<?php
// Inclusion du fichier d'initialisation
include 'include/init.php';

// Requête pour préparer l'insertion des champs du formulaire
$errors = [];
$pseudo = $mdp = $confirm_mdp = $nom = $prenom = $email = $telephone = $civilite = '';

if (!empty($_POST)) {
    sanitizePost();
    extract($_POST); 

// ----------------- Pseudo -----------------
    if (empty($_POST['pseudo'])) {$errors['pseudo'] = 'Le pseudo est obligatoire';}
    
// ----------------- Mot de passe ------------------
    if (empty($_POST)) {
        $errors['mdp'] = "Le mot de passe est obligatoire";
    } elseif (strlen($_POST['mdp']) < 6) {
        $errors['mdp'] = 'Le mot de passe doit faire au moins 6 caractères';
    } elseif ($_POST['mdp'] != $_POST['confirm_mdp']) {
        $errors['mdp'] = 'Le mot de passe et sa confirmation ne sont pas identiques.';
    }
// ----------------- Nom -----------------
    if (empty($_POST['nom'])) {$errors['nom'] = 'Le nom est obligatoire';}
    
// ----------------- Prénom -----------------
    if (empty($_POST['prenom'])) {$errors['prenom'] = 'Le prénom est obligatoire';}
    
// ----------------- Email -----------------
    if (empty($_POST['email'])) {
        $errors['email'] = "L'email est obligatoire";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide";
    } else {
            $query = 'SELECT COUNT (*) FROM membre WHERE email = ' . $pdo->quote($_POST['email']);
    }
// ----------------- Téléphone -----------------
    if (empty($_POST['telephone'])) {$errors['telephone'] = "Le numéro de téléphone est obligatoire";} 
    
// ----------------- Civilité -----------------
    if (empty($_POST['civilite'])) {$errors['civilite'] = 'La civilité est obligatoire';}

    
// ---------------- Préparation requête (enregistrement dans la BDD) ----------------
    if(empty($errors)) {
        $query = "INSERT INTO membre(pseudo, mdp, nom, prenom, email, telephone, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :telephone, :civilite, 0,  NOW())";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR); 
        $stmt->bindValue(':mdp', sha1($_POST['mdp']), PDO::PARAM_STR); 
        $stmt->bindParam(':nom', $_POST['nom'], PDO::PARAM_STR); 
        $stmt->bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $stmt->bindParam(':telephone', $_POST['telephone'], PDO::PARAM_STR);
        $stmt->bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);

// Redirection vers la page "Connexion"
        if($stmt->execute()) {
            $success=true; {
                $message='Votre compte a bien été créé. Veuillez vous connecter.';
                setFlashMessage($message);
                header('Location: ' . RACINE_WEB . 'connexion.php');
                die;
            }
        } else {
            $errors[] = 'Une erreur est survenue';
        }
    }
}

// Insertion de la section haut de page 
include 'layout/top.php';
?>

<!-- Message d'erreur si formulaire rempli de manière erronée -->
<?php
if (!empty($errors)) :
?>
    <div class="alert alert-danger" role="alert"><strong>Le formulaire contient des erreurs</strong>
        <br>
        <?= implode('<br>', $errors); ?>

    </div>
<?php
endif;
?>

<?php
// Si on ne veut plus afficher le formulaire quand l'inscription s'est bien passé
if ((empty($_POST)) || empty($success)) :
?>

<!-- Titre de la page -->
<h1 class="my_titles">Inscription</h1>

<!-- ---------------- Début du formulaire  ---------------- -->

<form method="post" class="col-sm-offset-3 col-sm-6">
   
    <!-- Champ "Pseudo" -->
    <div class="form-group <?php displayErrorClass('pseudo', $errors); ?>">
        <label for="pseudo" class="control-label">Votre pseudo</label>
        <input id="pseudo" name="pseudo" type="text" class="form-control" value="<?= $pseudo; ?>">
        <?php displayErrorMsg('pseudo', $errors); ?>
    </div>
    
    <!-- Champ "Mot de passe" -->
    <div class="form-group <?php displayErrorClass('mdp', $errors); ?>">
        <label for="mdp" class="control-label">Mot de passe</label>
        <input id="mdp" name="mdp" type="password" class="form-control" value="<?= $mdp; ?>">
        <?php displayErrorMsg('mdp', $errors); ?>
    </div>

    <!-- Champ "Confirmation mot de passe" -->
    <div class="form-group">
        <label for="confirm_mdp" class="control-label">Confirmation du mot de passe</label>
        <input id="confirm_mdp" name="confirm_mdp" type="password" class="form-control" value="<?= $confirm_mdp; ?>">
    </div>
    
    <!-- Champ "Nom" -->
    <div class="form-group <?php displayErrorClass('nom', $errors); ?>">
        <label for="nom" class="control-label">Nom</label>
        <input id="nom" name="nom" type="text" class="form-control" value="<?= $nom; ?>">
        <?php displayErrorMsg('nom', $errors); ?>
    </div>

    <!-- Champ "Prénom" -->
    <div class="form-group <?php displayErrorClass('prenom', $errors); ?>">
        <label for="prenom" class="control-label">Prénom</label>
        <input id="prenom" name="prenom" type="text" class="form-control" value="<?= $prenom; ?>">
        <?php displayErrorMsg('prenom', $errors); ?>
    </div>

    <!-- Champ "Email" -->
    <div class="form-group <?php displayErrorClass('email', $errors); ?>">
        <label for="email" class="control-label">Email</label>
        <input id="email" name="email" type="email" class="form-control" value="<?= $email; ?>">
        <?php displayErrorMsg('email', $errors); ?>
    </div>
    
    <!-- Champ "Téléphone" -->
    <div class="form-group <?php displayErrorClass('telephone', $errors); ?>">
        <label for="telephone" class="control-label">Téléphone</label>
        <input id="telephone" name="telephone" type="tel" class="form-control" value="<?= $telephone; ?>">
        <?php displayErrorMsg('telephone', $errors); ?>
    </div>
    
    <!-- Champ "Civilité" -->
    <div class="form-group <?php displayErrorClass('civilite', $errors); ?>">
        <label for="civilite" class="control-label">Civilité</label>
        <select id="civilite" name="civilite" class="form-control">
            <option value="">Choisissez...</option>
            <option value="f" <?php if($civilite == 'f') {
    echo 'selected';} ?>> 
                Madame</option>
            <option value="m" <?php if($civilite == 'm') {
    echo 'selected';} ?>> 
                Monsieur</option>
        </select>
        <?php displayErrorMsg('civilite', $errors); ?>
    </div>

    <!-- Bouton de validation "Inscription" -->
    <br>
    <button class="btn">Inscription</button>
</form>

<!-- ---------------- Fin du formulaire  ---------------- -->

<?php
endif;

// Insertion de la section bas de page
include 'layout/bottom.php';
?>