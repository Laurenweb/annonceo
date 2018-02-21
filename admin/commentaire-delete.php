<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity();  // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page

if(isset($_GET['id_commentaire'])) {

    // Requête pour la suppression du commentaire
    $query = 'DELETE FROM commentaire WHERE id_commentaire = ' . (int)$_GET['id_commentaire'];
    $pdo->exec($query);

    setFlashMessage('Le commentaire #' . $_GET['id_commentaire'] . ' a été supprimé !', 'danger', 'glyphicon-trash');

}

// Redirection vers la page des commentaires  
    header('Location: commentaire.php');
    die;

?>

