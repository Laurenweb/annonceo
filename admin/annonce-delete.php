<?php 
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity(); // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page

if (isset($_GET['id_annonce'])) {

    // Sélection de toutes les infos de l'annonce concernée
    $query = 'SELECT * FROM annonce WHERE id_annonce = ' . $_GET['id_annonce'];
    $stmt = $pdo->query($query);
    $annonce = $stmt->fetch();

    // Sélection de la photo principale de l'annonce
    $query = 'SELECT photo FROM annonce WHERE id_annonce = ' . (int)$_GET['id_annonce'];
    $stmt = $pdo->query($query);
    $photo1 = $stmt->fetchColumn();

    // Sélection des autres photos de l'annonce
    $query = 'SELECT photo2 FROM photo WHERE id_photo = "' . $annonce['photo_id'] . '"';
    $stmt = $pdo->query($query);
    $photo2 = $stmt->fetchColumn();

    $query = 'SELECT photo3 FROM photo WHERE id_photo = "' . $annonce['photo_id'] . '"';
    $stmt = $pdo->query($query);
    $photo3 = $stmt->fetchColumn(); 

    $query = 'SELECT photo4 FROM photo WHERE id_photo = "' . $annonce['photo_id'] . '"';
    $stmt = $pdo->query($query);
    $photo4 = $stmt->fetchColumn(); 

    $query = 'SELECT photo5 FROM photo WHERE id_photo = "' . $annonce['photo_id'] . '"';
    $stmt = $pdo->query($query);
    $photo5 = $stmt->fetchColumn(); 

    // Pour supprimer toutes les 5 photos du dossier photo
    unlink(PHOTO_SITE . $photo1);
    unlink(PHOTO_SITE . $photo2);
    unlink(PHOTO_SITE . $photo3);
    unlink(PHOTO_SITE . $photo4);
    unlink(PHOTO_SITE . $photo5);

    // Suppression de l'annonce dans la BDD (table annonce)
    $query1 = 'DELETE FROM annonce WHERE id_annonce = ' . (int)$_GET['id_annonce'];

    $pdo->exec($query1);

    // Suppression des photos dans la BDD (table photo)
    $query2 = 'DELETE FROM photo WHERE id_photo = "' . $annonce['photo_id'] . '"';

    $pdo->exec($query2);

    setFlashMessage('L\'annonce # ' . $_GET['id_annonce'] . ' a été supprimée !', 'danger', 'glyphicon-trash');

}

header('Location: annonce.php');
die;

?>