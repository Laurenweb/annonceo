<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity(); // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page

if (isset($_GET['id_categorie'])) {

    // Requête pour supprimer la catégorie
    $query = 'DELETE FROM categorie WHERE id_categorie = ' . (int)$_GET['id_categorie'];
    
    $pdo->exec($query);

    setFlashMessage('La catégorie N°' . $_GET['id_categorie'] . ' a été supprimée !', 'danger', 'glyphicon-trash');

}
// Redirection vers la page des catégories
    header('Location: categorie.php');
    die;

?>

