<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity();  // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page


// Requête pour l'affichage des catégories
$query = 'SELECT * FROM categorie ORDER BY id_categorie';
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll();


// Insertion de la section haut de page
include '../layout/top.php'; 

displayFlashMessage();
?>

<!-- Titre de la page --> 
<h1 class="text-left" style="color: #a18131; font-family: 'Architects Daughter', cursive">Catégories</h1>
<hr>

<!-- Ajout d'une nouvelle catégorie -->
<p class="text-center">
    <a href="<?= RACINE_WEB;?>admin/categorie-edit.php">
        <button class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Ajouter une nouvelle catégorie
        </button>
    </a>
</p>

<div class="table-responsive">  
    <table class="table">
        <tr style="background-color: #222; color: #9d9d9d; border: 1px solid #fff; font-size: 14px;">
            <th>Id Catégorie</th>
            <th>Titre</th>
            <th>Mots clés</th>
            <th>Actions</th>
        </tr>
      
<?php
    foreach($categories as $categorie):
?>

<!-- Affichage html de la liste des catégories -->      
    <tr style="background-color: #F9F9F9; font-size: 12px;">
        <td><?= $categorie['id_categorie'];?></td>
        <td><?= $categorie['titre'];?></td>
        <td><?= nl2br($categorie['motscles']); ?></td>
        <td class="text-right">
            <!-- Visualisation d'une catégorie -->
            <a class="btn btn-info" href="<?= RACINE_WEB; ?>index.php?categorie=<?= $categorie['id_categorie']; ?>">
                <span class="glyphicon glyphicon-zoom-in"></span></a>
            
            <!-- Modification d'une catégorie -->
            <a class="btn btn-primary" href="categorie-edit.php?id_categorie=<?= $categorie['id_categorie']; ?>">
                <span class="glyphicon glyphicon-edit"></span></a>

            <!-- Suppression d'une catégorie (Modal) -->              
            <a class="btn btn-danger" data-toggle="modal" data-target="#myModal<?= $categorie['id_categorie']; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>

            <!-- Modal pour confirmation de suppression d'une catégorie -->

            <div class="modal fade" id="myModal<?= $categorie['id_categorie']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                            <a href="categorie-delete.php?id_categorie=<?= $categorie['id_categorie']; ?>">
                                <button type="button" class="btn btn-danger">Supprimer</button>
                            </a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        </div>

                    </div> <!-- Fin modal-content -->
                </div> <!-- Fin modal-dialog -->
            </div> <!-- Fin modal fade -->
                                                      
        </td>
    </tr>
        
<?php endforeach;
?>    
    </table>
</div>

<br> 
<hr style="width: 1000px"> 

<?php
// Insertion de la section bas de page
include '../layout/bottom.php';
?>

