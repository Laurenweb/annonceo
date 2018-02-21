<?php
// Insertion du fichier d'initialisation
include '../include/init.php';

adminSecurity(); // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page


// Pagination -------------------------------
$nbProduitsParPage = 3;

$query = 'SELECT COUNT(*) FROM annonce';
$stmt = $pdo->query($query);
$nbProduits = $stmt->fetchColumn();

$nbPages = ceil($nbProduits / $nbProduitsParPage);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = $nbProduitsParPage;
$offset = ($page - 1) * $nbProduitsParPage;

// Requête permettant de sélectionner toutes les annonces avec noms des membres et autres données correspondantes
$query = 'SELECT a.*, m.pseudo as pseudo_membre, c.titre as titre_categorie FROM annonce a JOIN membre m ON m.id_membre = a.membre_id 
JOIN categorie c ON c.id_categorie = a.categorie_id ORDER BY id_annonce DESC' . ' LIMIT ' . $limit . ' OFFSET ' . $offset;

$stmt = $pdo->query($query);
$annonces = $stmt->fetchAll();


// Insertion du fichier haut de page
include '../layout/top.php';


displayFlashMessage();
?>

<h1 class="text-left" style="color: #a18131; font-family: 'Architects Daughter', cursive">Gestion des annonces</h1>
<hr>

<!-- Ajout d'une nouvelle annonce -->
<p class="text-center">
    <a href="annonce-edit.php"><button class="btn btn-default">
        <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Ajouter une nouvelle annonce
        </button>
    </a>
</p>

<div class="table-responsive">              
    <div class="col-md-12">
        <table class="table">
            <tr style="background-color: #222; color: #9d9d9d; border: 1px solid #fff; font-size: 14px;">
                <th>Id Annonce</th>
                <th>Titre</th>
                <th>Description courte</th>
                <th>Description longue</th>
                <th>Prix</th>
                <th>Photo</th>
                <th>Pays</th>
                <th>Ville</th>
                <th>Adresse</th>
                <th>CP</th>
                <th>Membre</th>
                <th>Catégorie</th>
                <th>Date enregistrement</th>
                <th>Actions</th>
            </tr>
            <?php
            
// Boucle d'affichage des annonces
            foreach ($annonces as $element) :
            ?>
            <tr style="background-color: #F9F9F9; font-size: 10px;">
                <td><?= $element['id_annonce']; ?></td>
                <td><?= $element['titre']; ?></td>
                <td><?= nl2br($element['description_courte']); ?></td>
                <td><?= nl2br($element['description_longue']); ?></td>
                <td><?= $element['prix']; ?></td>
                <td><?= $element['photo']; ?></td>
                <td><?= $element['pays']; ?></td>
                <td><?= $element['ville']; ?></td>
                <td><?= $element['adresse']; ?></td>
                <td><?= $element['cp']; ?></td>
                <td><?= $element['pseudo_membre']; ?></td>
                <td><?= $element['titre_categorie']; ?></td>
                <td><?= $element['date_enregistrement']; ?></td>
                <td>
                    <!-- Visualisation d'une annonce --> 
                    <a class="btn btn-info" href="<?= RACINE_WEB; ?>detail_annonce.php?id_annonce=<?= $element['id_annonce']; ?>">
                    <span class="glyphicon glyphicon-zoom-in"></span></a>
                    
                    <!-- Modification d'une annonce --> 
                    <a class="btn btn-primary" href="annonce-edit.php?id_annonce=<?= $element['id_annonce']; ?>">
                    <span class="glyphicon glyphicon-edit"></span></a>
                    
                    <!-- Suppression d'une annonce (Modal) --> 
                    <a class="btn btn-danger" data-toggle="modal" data-target="#myModal<?= $element['id_annonce']; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>

                    <!-- Modal pour confirmation de suppression d'un membre -->
                    <div class="modal fade" id="myModal<?= $element['id_annonce']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    <a href="annonce-delete.php?id_annonce=<?= $element['id_annonce']; ?>">
                                        <button type="button" class="btn btn-danger">Supprimer</button>
                                    </a>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                </div>

                            </div> <!-- Fin modal-content -->
                        </div> <!-- Fin modal-dialog -->
                    </div> <!-- Fin modal fade -->                       
                                                                                         
                </td>
            </tr>

            <?php
            endforeach;
            ?>

        </table>

<!-- Pagination -->
        <nav class="text-center" aria-label="Page navigation">
            <ul class="pagination">
                <li>
                    <a href="?page=1" aria-label="Previous"> <!-- Renvoi vers la 1ère page -->
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                for ($i=1; $i <= $nbPages; $i++): 
                ?>
                <li><a href="?page=<?= $i; ?>"><?= $i; ?></a></li>
                <?php
                endfor;
                
                // Si nb de pages = 0 alors on renseigne =1 pour éviter une erreur dans l'url
                if ($nbPages==0) {
                    $nbPages = 1;
                }
                ?>
                
                <li>
                    <a href="?page=<?= $nbPages; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span> <!-- Renvoi vers la dernière page -->
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>  


<?php
// Insertion du fichier bas de page
include '../layout/bottom.php';
?>