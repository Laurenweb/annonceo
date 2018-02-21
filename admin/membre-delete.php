<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity();  // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page

if (isset($_GET['id_membre'])) {

    // Requête pour la suppression du membre
    $query = 'DELETE FROM membre WHERE id_membre = ' . (int)$_GET['id_membre'];
    
    $pdo->exec($query);

    setFlashMessage('Le membre #' . $_GET['id_membre'] . ' a été supprimé !', 'danger', 'glyphicon-trash');

}
// Redirection vers la page des membres   
    header('Location: membres.php');
    die;

?>

