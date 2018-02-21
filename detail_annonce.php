<?php
// Inclusion du fichier d'initialisation
include 'include/init.php';

// Insertion du fichier haut de page
include 'layout/top.php';

// Requête pour joindre les tables "annonce" et "membre" (Pour l'affichage de l'annonce et le membre l'ayant postée)
$query = 'SELECT * FROM annonce a JOIN membre m on a.membre_id=m.id_membre WHERE id_annonce = ' . $_GET['id_annonce'];
$stmt = $pdo->query($query);
$annonce = $stmt->fetch();

$query = 'SELECT * FROM photo WHERE photo1 = "' . $annonce['photo'] . '"';
$stmt = $pdo->query($query);
$photo = $stmt->fetch();

// Requête pour insertion des commentaires dans la table commentaire
if (!empty($_POST['commentaire'])) {
    $query = 'INSERT INTO commentaire(membre_id, annonce_id, commentaire, date_enregistrement) VALUES (:membre_id, :annonce_id, :commentaire, NOW())';

    $stmt = $pdo->prepare($query);

    $stmt->bindValue(':membre_id', $_SESSION['membre']['id_membre'], PDO::PARAM_INT);
    $stmt->bindValue(':annonce_id', $_GET['id_annonce'], PDO::PARAM_INT);
    $stmt->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);

// Exécution de la requête pour insertion dans la table commentaire
if (@$stmt->execute()) {
    $success = true;
    setFlashMessage('Votre commentaire "' . $_POST['commentaire'] . '" a bien été publié !', 'success', 'glyphicon-thumbs-up');
              
} else {
    $errors[] = 'Une erreur est survenue';
    }
}

// Requête pour affichage des commentaires sur la page
$query = 'SELECT c.*, m.prenom FROM commentaire c JOIN membre m on c.membre_id=id_membre WHERE annonce_id = ' . $_GET['id_annonce'];
$stmt=$pdo->query($query);
$commentaires = $stmt->fetchAll(); 


?>

<!-- Affichage du titre de l'annonce -->
<div class="row">
    <div class="col-md-6">
        <h3><?= $annonce['titre']; ?> <?php displayFlashMessage(); ?><small class="prix">- <?= $annonce['prix']; ?> €</small></h3>
        <br>
        <span>
            <span class="">Posté par : <?= $annonce['pseudo']; ?></span> 
        </span>
    </div>

<!-- Pour contacter le vendeur -->
    <div class="col-md-6"> 
        <div class="form-inline text-right">
            <span>Contacter le vendeur</span>
            
            <button class="btn btn-warning" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-earphone" aria-hidden="true"> </span> Voir le numéro
            </button>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title text-left" id="myModalLabel">Coordonnées du vendeur</h4>
                        </div>
                        <div class="modal-body text-center"><?= $annonce['pseudo']; ?><br> <strong> <?= $annonce['telephone']; ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            
<!-- Formulaire d'envoi de mail au vendeur -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#myModal2">
                <span class="glyphicon glyphicon-envelope" aria-hidden="true"> </span> Envoyer un email
            </button>
            <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title text-center" id="myModalLabel2">Envoyer un email à <br> "<?= $annonce['pseudo']; ?>"</h4>
                        </div>
                        <div class="modal-body text-center">
                            <div class="row">
                                <form method="post" action="">
                                    <div class="form-group">
                                        <label class="control-label" for="sujet">Sujet</label><br>
                                        <input class="form-control" name="sujet" id="sujet" placeholder="sujet" type="text"><br><br>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="message">Message</label>
                                        <br>
                                        <textarea class="form-control" name="message" placeholder="message"></textarea><br>
                                        <br>
                                        <input value="Envoyer" type="submit" name="email" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Fin du Formulaire mail -->
            
        </div> 
        
    </div> <!-- Fin de class="col-md-6" -->
           
</div>  <!-- Fin de class="row" -->

<!-- Affichage de la photo principale et des photos supplémentaires -->
<div class="col-md-6 margin text-center">
    
    <div class="row pull-left">
        <a href="<?= PHOTO_WEB . $annonce['photo']; ?>" data-toggle="lightbox" data-gallery="example-gallery">
            <img src="<?= PHOTO_WEB . $annonce['photo']; ?>" style="max-height: 280px;" class="img-fluid">
        </a> 
    </div>
    
    <div class="row text-center">

        <div class="col-md-3 col-xs-6">
            <?php if (!empty($photo['photo2'])) : ?>
            <a href="<?= PHOTO_WEB . $photo['photo2']; ?>" data-toggle="lightbox" data-gallery="example-gallery">
                <img src="<?= PHOTO_WEB . $photo['photo2']; ?>" width="100%;" class="img-fluid">
            </a> 
            <?php endif; ?>
        </div>

        <div class="col-md-3 col-xs-6">
            <?php if (!empty($photo['photo3'])) : ?>
            <a href="<?= PHOTO_WEB . $photo['photo3']; ?>" data-toggle="lightbox" data-gallery="example-gallery">
                <img src="<?= PHOTO_WEB . $photo['photo3']; ?>" width="100%;" class="img-fluid">
            </a> 
            <?php endif; ?>
        </div>

        <div class="col-md-3 col-xs-6">
            <?php if (!empty($photo['photo4'])) : ?>
            <a href="<?= PHOTO_WEB . $photo['photo4']; ?>" data-toggle="lightbox" data-gallery="example-gallery">
                <img src="<?= PHOTO_WEB . $photo['photo4']; ?>" width="100%;" class="img-fluid">
            </a> 
            <?php endif; ?>
        </div>

        <div class="col-md-3 col-xs-6">
            <?php if (!empty($photo['photo5'])) : ?>
            <a href="<?= PHOTO_WEB . $photo['photo5']; ?>" data-toggle="lightbox" data-gallery="example-gallery">
                <img src="<?= PHOTO_WEB . $photo['photo5']; ?>" width="100%;" class="img-fluid">
            </a> 
            <?php endif; ?>
        </div>             
    </div>

</div>

<div class="col-md-6">
    <p class="text-right"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Mise en ligne le <?= $annonce['date_enregistrement']; ?></p>
    <hr /> 
    <h3 style="font-size: 20px;">Description</h3>
    <p><?= $annonce['description_longue']; ?></p>
    <hr /> 
</div>


<!-- Localisation géographique du lieu de l'annonce -->
<div class="row">
    <div class="col-md-4">
        <p><?= '<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> Adresse : ' . $annonce['adresse'] . ', ' . $annonce['cp'] . ', ' . $annonce['ville']; ?></p>           
    </div>
    <iframe src="https://maps.google.it/maps?q=<?php echo $annonce['adresse'];?>&output=embed" width="100%" height="200" frameborder="0" allowfullscreen></iframe>
</div>


<!-- Espace commentaire -->
<div>
    <h3 class="my_font">Commentez !</h3>
    <?php
    if (isUserConnected()):
    ?>  
        
    <form method="POST">
        <div class="col-sm-8">
            <div class="well">
                <div class="media-left media-middle avatar">
                    <a href="#">
                        <img class="media-object" src="images/user.png" height="50px">
                    </a>
                </div>
                <div class="media-body">
                <textarea name="commentaire" class="form-control" placeholder="Ecrire un commentaire..."></textarea><br>
                <input type="submit" value="Publier" name="submit_commentaire" /></div>
            </div>
            <hr />
        </div>  
    </form> 
</div>
<div class="clear"></div>
    <?php 
    else:
    ?>
<div class="well">
        <p>
           <a href="<?= RACINE_WEB; ?>inscription.php">Inscrivez-vous</a> ou
           <a href="<?= RACINE_WEB; ?>connexion.php">connectez-vous</a> pour publier un commentaire.
        </p>
    </div>
    <?php
    endif;
    ?>
<br>
<br>

<!-- Boucle foreach pour afficher les commentaires par annonce -->
<?php foreach ($commentaires as $comments) {
        echo $comments ['prenom'] . '<br>';
        echo $comments ['commentaire'] . '<br>';
        echo "Posté le " . $comments ['date_enregistrement'] . '<br>';
}  
?>  

<div class="row">
    <div class="col-md-12">
        <br><br>
        <a href="index.php"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span> Retour vers l'accueil</a><br><br>
    </div>
</div>

<hr /> 


<?php
// Insertion du fichier bas de page
include 'layout/bottom.php';
