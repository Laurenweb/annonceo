<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity(); // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page


// Pagination
$nbCommentairesParPage = 8;

$query = 'SELECT COUNT(*) FROM commentaire';
$stmt = $pdo->query($query);
$nbCommentaire = $stmt->fetchColumn();

$nbPages = ceil($nbCommentaire / $nbCommentairesParPage);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = $nbCommentairesParPage;
$offset = ($page - 1) * $nbCommentairesParPage;

// Requête pour récupérer tous les commentaires en jonction avec les tables annonce et membre (annonces et membres correspondants)
$query = 'SELECT c.*, a.titre AS titre_annonce FROM commentaire c 
JOIN membre m ON c.membre_id = m.id_membre 
JOIN annonce a ON c.annonce_id = a.id_annonce 
ORDER BY id_commentaire 
LIMIT ' . $limit . ' OFFSET ' . $offset;

$stmt = $pdo->query($query);
$commentaires = $stmt->fetchAll();

// Insertion de la section haut de page
include '../layout/top.php';

?>

 <?= displayFlashMessage() ?> 

<h1 class="text-left" style="color: #a18131; font-family: 'Architects Daughter', cursive">Gestion des commentaires</h1>
<hr>

<!-- Gestion des commentaires (Admin) -->
<div class="table-responsive"> 
    <table class="table">
        <tr style="background-color: #222; color: #9d9d9d; border: 1px solid #fff; font-size: 12px;">
           <th>Id commentaire</th>
           <th>Id membre</th>
           <th>Titre Annonce</th>
           <th>Commentaire</th>
           <th>Date enregistrement</th>
           <th>Actions</th>
        </tr>    

<!-- Boucle foreach pour afficher chaque commentaire l'un après l'autre -->
        <?php
        foreach ($commentaires as $commentaire) :
        ?>  

        <tr style="background-color: #F9F9F9; font-size: 12px;">
            <td><?= $commentaire['id_commentaire'] ?></td>
            <td><?= $commentaire['membre_id'] ?></td>
            <td><?= $commentaire['annonce_id'] . ' - ' . $commentaire['titre_annonce'] ?></td>
            <td><?= $commentaire['commentaire'] ?></td>
            <td><?= $commentaire['date_enregistrement'] ?></td>
            <td class="text-right">
            <!-- Affichage de l'annonce correspondante -->
                <a class="btn btn-info" href="<?= RACINE_WEB ?>detail_annonce.php?id_annonce=<?= $commentaire['annonce_id']; ?>"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span></a>
            <!-- Suppression du commentaire (Modal) -->
                <a class="btn btn-danger" data-toggle="modal" data-target="#myModal<?= $commentaire['id_commentaire']; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>

            <!-- Modal pour confirmation de suppression des commentaires -->
                <div class="modal fade" id="myModal<?= $commentaire['id_commentaire']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                <a href="commentaire-delete.php?id_commentaire=<?= $commentaire['id_commentaire']; ?>">
                                    <button type="button" class="btn btn-danger">Supprimer</button>
                                </a>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                            </div>
                            
                        </div> <!-- Fin modal-content -->
                    </div> <!-- Fin modal-dialog -->
                </div> <!-- Fin modal fade -->
                
            </td>
        </tr>

        <?php endforeach; ?>

    </table>  
</div>

<hr />

<?php

// Insertion de la section bas de page
include '../layout/bottom.php';

?>