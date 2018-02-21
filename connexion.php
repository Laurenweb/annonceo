<?php
// Inclusion du fichier d'initialisation
include 'include/init.php';


$errors = [];
$email = $mdp = '';


// Activation du formulaire
if (!empty($_POST)) {
    sanitizePost();
    extract($_POST);

    if (empty($_POST['email'])) {
        $errors['email'] = "L'email est obligatoire";
    }

    if (empty($_POST['mdp'])) {
        $errors['mdp'] = 'Le mot de passe est obligatoire';
    }
    
    if (empty($errors)) {

        $query = 'SELECT * FROM membre WHERE email = :email AND mdp = :mdp';

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':mdp', sha1($mdp), PDO::PARAM_STR);
        $stmt->execute();

        $membre = $stmt->fetch();

        if (!empty($membre)) {
            unset($membre['mdp']);
            $_SESSION['membre'] = $membre;  
            
            header('Location: index.php'); 
            die;  
        } else {
            $errors['connexion'] = 'Email ou mot de passe incorrect';
        }
    }
}

// Insertion de la section haut de page
include 'layout/top.php';

// Message d'erreurs en cas de formulaire rempli de manière erronée
if (!empty($errors)) :
?>
<div class="alert alert-danger" role="alert">
    <strong>
        <?= (isset($errors['connexion']))
    ? $errors['connexion']
    : 'Le formulaire contient des erreurs';
        ?>
    </strong>
</div>

<?php
endif;
?>

<!-- Titre de la page -->
<h1 class="my_titles">Connexion</h1>

<!-- Message de succès suite à la soumission du formulaire d'inscription -->
<div class="col-sm-offset-3 col-sm-6">
    <?php
    displayFlashMessage();
    ?>
</div>

<!-- ---------------- Formulaire de connexion ---------------- -->

<form method="post" class="col-sm-offset-3 col-sm-6">
    <legend>Saisissez vos identifiants</legend>
    
    <!-- Champ "Email" -->
    <div class="form-group <?php displayErrorClass('email', $errors); ?>">
        <label for="email" class="control-label">Email</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="Votre email" value="<?= $email; ?>">
        <?php displayErrorMsg('email', $errors); ?>
    </div>

    <!-- Champ "Mot de passe" -->
    <div class="form-group <?php displayErrorClass('mdp', $errors); ?>">
        <label for="mdp" class="control-label">Mot de passe</label>
        <input id="mdp" name="mdp" type="password" class="form-control" placeholder="Votre mot de passe" value="<?= $mdp; ?>">
        <?php displayErrorMsg('mdp', $errors); ?>
    </div>
    
    <!-- Bouton "Connexion" -->
    <div class="col-sm-offset-4 col-md-4 text-center"> 
        <button id="singlebutton" name="singlebutton" class="btn btn-primary">Connexion</button> 
    </div>
    <br>
    <hr />
</form>
<!-- ---------------- Fin du formulaire  ---------------- -->

<?php
// Insertion de la section bas de page
include 'layout/bottom.php';
?>