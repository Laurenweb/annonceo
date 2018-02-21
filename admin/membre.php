<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity(); // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page

// Pagination --------
$nbMembresParPage = 6;

$query = 'SELECT COUNT(*) FROM membre';
$stmt = $pdo->query($query);
$nbMembres = $stmt->fetchColumn(); // nb total de membres

$nbPages = ceil($nbMembres / $nbMembresParPage);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = $nbMembresParPage;
$offset = ($page - 1) * $nbMembresParPage;

// Requête pour récupérer tous les membres inscrits
$query = 'SELECT * FROM membre ORDER BY id_membre LIMIT ' . $limit . ' OFFSET ' . $offset;
$stmt = $pdo->query($query);
$membres = $stmt->fetchAll();

// Insertion de la section haut de page
include '../layout/top.php';

?>

<?= displayFlashMessage() ?>


<h1 class="text-left" style="color: #a18131; font-family: 'Architects Daughter', cursive">Gestion des membres</h1>
<hr>

<!-- Ajout d'un nouveau membre -->
    <p class="text-center">
        <a href="membre-edit.php">
            <button class="btn btn-default">
                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Ajouter un nouveau membre
            </button>
        </a>    
    </p>

<!-- Gestion des membres (Admin) -->
<div class="table-responsive">
    <table class="table">
        <tr style="background-color: #222; color: #9d9d9d; border: 1px solid #fff; font-size: 12px;">
           <th>#</th>
           <th>Pseudo</th>
           <th>Mot de passe</th>
           <th>Nom</th>
           <th>Prénom</th>
           <th>Email</th>
           <th>Téléphone</th>
           <th>Civilité</th>
           <th>Statut</th>
           <th>Date d'enregistrement</th>
           <th></th>
        </tr>    

<!-- Boucle foreach pour afficher chaque membre l'un après l'autre -->
        <?php foreach ($membres as $membre) : ?>

            <tr style="background-color: #F9F9F9; font-size: 12px;">
                <td><?= $membre['id_membre']; ?></td>
                <td><?= $membre['pseudo']; ?></td>
                <td><?= $membre['mdp']; ?></td>
                <td><?= $membre['civilite']; ?></td>
                <td><?= $membre['nom']; ?></td>
                <td><?= $membre['prenom']; ?></td>
                <td><?= $membre['email']; ?></td>
                <td><?= $membre['telephone']; ?></td>
                <td><?= $membre['statut']; ?></td>
                <td><?= $membre['date_enregistrement']; ?></td>
                <td class="text-right">
                    <!-- Modification d'un membre --> 
                    <a class="btn btn-primary" href="membre-edit.php?id_membre=<?= $membre['id_membre']; ?>"><span class="glyphicon glyphicon-edit"></span></a>
                    
                    <!-- Suppression d'un membre (Modal) --> 
                    <a class="btn btn-danger" data-toggle="modal" data-target="#myModal<?= $membre['id_membre']; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                    
                    <!-- Modal pour confirmation de suppression d'un membre -->

                    <div class="modal fade" id="myModal<?= $membre['id_membre']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    <a href="membre-delete.php?id_membre=<?= $membre['id_membre']; ?>">
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
    
<!-- Pagination -->    
    <nav class="text-center" aria-label="Page navigation">
        <ul class="pagination">
            <li>
                <a href="?page-1<?= $nbPages; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            
            <?php for ($i = 1; $i <= $nbPages; $i++) :    ?>

                <li><a href="?page=<?= $i; ?>"><?= $i; ?></a></li>
            
            <?php endfor; ?>
           
            <li>
                <a href="?page=<?= $nbPages; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>        
        </ul>
    </nav>     
    
<?php

// Insertion de la section bas de page
include '../layout/bottom.php';

?>