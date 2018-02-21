<?php
include '../include/init.php';

adminSecurity(); // Pour éjecter les personnes autres que l'admin qui tentent d'accéder à la page


// Pagination
$nbNotesParPage = 8;

$query = 'SELECT COUNT(*) FROM commentaire';
$stmt = $pdo->query($query);
$nbNote = $stmt->fetchColumn();

$nbPages = ceil($nbNote / $nbNotesParPage);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = $nbNotesParPage;
$offset = ($page - 1) * $nbNotesParPage;

// Requête pour récupérer toutes les notes en jonction avec la membre (membres correspondants)
$query = 'SELECT n.*, m.email AS email_membre, d.email AS email_d_membre FROM note n 
JOIN membre m ON m.id_membre = n.membre_id1
JOIN membre d ON d.id_membre = n.membre_id2
ORDER BY id_note LIMIT ' . $limit . ' OFFSET ' . $offset;

$stmt = $pdo->query($query);
$notes = $stmt->fetchAll();

// Insertion de la section haut de page
include '../layout/top.php';

?>

 <?= displayFlashMessage() ?> 

<h1 class="text-left" style="color: #a18131; font-family: 'Architects Daughter', cursive">Gestion des notes</h1>
<hr>

<!-- Gestion des commentaires (Admin) -->
<div class="table-responsive"> 
    <table class="table">
        <tr style="background-color: #222; color: #9d9d9d; border: 1px solid #fff; font-size: 12px;">
           <th>Id note</th>
           <th>Membre_Id1</th>
           <th>Membre_Id2</th>
           <th>Note</th>
           <th>Avis</th>
           <th>Date enregistrement</th>
           <th>Actions</th>
        </tr>    

<!-- Boucle foreach pour afficher chaque note l'une après l'autre -->
        <?php
        foreach ($notes as $note) :
        ?>  

        <tr style="background-color: #F9F9F9; font-size: 12px;">
            <td><?= $note['id_note'] ?></td>
            <td><?= $note['membre_id1'] . ' - ' . $note['email_membre'] ?></td>
            <td><?= $note['membre_id2'] . ' - ' . $note['email_d_membre'] ?></td>
            <td>
                <span class="glyphicon glyphicon-star" aria-hidden="true" style="color: #EFD242;"></span> 
                <?= $note['note']; ?>
            </td>
            <td><?= cutText($note['avis'], 100); ?></td>
            <td><?= $note['date_enregistrement']; ?></td>
            <td class="text-right">
                <a href="note-delete.php?id_note=<?= $note['id_note']; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Supprimer la note</a>
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