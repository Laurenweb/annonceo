<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity(); // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page

// Requête de gestion des erreurs
$errors = [];
$titre = $motscles = '';

if (!empty($_POST)) {
    sanitizePost();
    extract($_POST);

    if (empty($_POST['titre'])) {
        $errors['titre'] = 'Le titre est obligatoire';
    } elseif (strlen($_POST['titre']) > 50) {
        $errors['titre'] = 'Le titre doit faire au maximum 50 caractères';
    } else {
        $query = "SELECT COUNT(*) FROM categorie WHERE titre = " . $pdo->quote($_POST['titre']);

        if (isset($_GET['id_categorie'])) {
            $query .= ' AND id_categorie != ' . $_GET['id_categorie'];
        }

        $stmt = $pdo->query($query);
        $nbCategorie = $stmt->fetchColumn();
        if (0 != $nbCategorie){
            $errors['titre'] = 'Cette catégorie existe déjà.';
        }
    }
    
    if (empty($_POST['motscles'])) {
        $errors['motscles'] = 'Les mots clés sont obligatoires';
    }
    
    if (empty($errors)) {

// Préparation de la requête (Insertion table catégorie)
        if (isset($_GET['id_categorie'])) {
            $query = 'UPDATE categorie SET titre = :titre, motscles = :motscles WHERE id_categorie = :id_categorie';

            $stmt = $pdo->prepare($query);

            $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
            $stmt->bindValue(':motscles', $motscles, PDO::PARAM_STR);
            $stmt->bindValue(':id_categorie', $_GET['id_categorie'], PDO::PARAM_INT); 

        } else {        

            $query = "INSERT INTO categorie (titre, motscles) VALUES (:titre, :motscles)";

            $stmt = $pdo->prepare($query);

            $stmt->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
            $stmt->bindValue(':motscles', $_POST['motscles'], PDO::PARAM_STR);
        }
      
// Pour exécuter la requête
        if ($stmt->execute()) {
            $message = isset($_GET['id_categorie'])
                ? 'La catégorie "' . $_POST['titre'] . '" a été modifiée !'
                : 'La catégorie "' . $_POST['titre'] . '" a été créée !';
            $type = isset($_GET['id_categorie'])
                ? 'success'
                : 'success';
            $glyphicon = isset($_GET['id_categorie'])
                ? 'glyphicon-edit'
                : 'glyphicon-ok';

            setFlashMessage($message, $type, $glyphicon);
            header('Location: categories.php');
            die;

        } else {
            $errors['bdd'] = 'Une erreur est survenue';
        }
    }
        
} elseif (isset($_GET['id_categorie'])) {
    
    $query = 'SELECT * FROM categorie WHERE id_categorie = ' . $_GET['id_categorie'];
    $stmt = $pdo->query($query);
    $categorie = $stmt->fetch();
    
// Redirection vers la page "Catégorie"
    if(empty($categorie)) { 
        header('Location: categories.php');
        die;  
    }
    
    $titre = $categorie['titre'];
    $motscles = $categorie['motscles'];
}

// Insertion de la section haut de page
include '../layout/top.php'; 

    if (!empty($errors)):
?>

<!-- Message d'erreur si formulaire rempli de manière erronée -->
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

<!-- Titre de la page -->
<h1 style="color: #a18131; font-family: 'Architects Daughter', cursive"><?php if (isset($_GET['id_categorie'])) {echo 'Modification';} else {echo 'Nouvelle';}?> catégorie</h1>


<!-- Formulaire-->
<form method="post" class="col-sm-offset-3 col-sm-6">
    
    <!-- Champ Nom -->
    <div class="form-group <?php displayErrorClass('titre', $errors); ?>">
        <label for="titre" class="control-label">Nom</label>
        <input name="titre" type="text" class="form-control" value="<?= $titre; ?>"><?php displayErrorMsg('titre', $errors); ?>
    </div>
    
    <!-- Champ mots-clés -->
    <div class="form-group <?php displayErrorClass('motscles', $errors); ?>">
        <label for="motscles" class="control-label">Mots Clés</label>
        <input name="motscles" type="text" class="form-control" value="<?= $motscles; ?>"><?php displayErrorMsg('motscles', $errors); ?>
    </div>
    
    <button class="btn">Envoyer</button>
    <br><br>
</form>


<div class="row">
    <div class="col-md-12">
        <br><br>
        <a href="categorie.php"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span> Retour vers Gestion des catégories</a><br><br>
    </div>
</div>


<hr style="width: 1000px"> 

<?php
// Insertion de la section bas de page
include '../layout/bottom.php';
?>



