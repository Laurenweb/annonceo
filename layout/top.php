<!DOCTYPE html>
<html lang="fr">
    <head>
<!-- Description site, mots-clés & compatibilité navigateurs -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Annonceo, petites annonces entre particuliers. De nouvelles annonces chaque jour...Immobilier, Multimédia, Loisirs, Services, Emploi etc...">
        <meta name="keywords" content="annonce, vente, achat, petites annonces, particulier, bonnes affaires">

<!-- Titre du Site -->
        <title>Annonceo - Petites annonces gratuites entre particuliers</title>
        
<!-- Logo du Site (Shortcut icon) -->
        <link rel="shortcut icon" type="image/x-icon" href="./images/annonceo_favicon.png" />

<!-- Fichier CSS Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Mon fichier CSS -->
        <link rel="stylesheet" href="./css/style.css">
        
<!-- Polices -->
        <link href="https://fonts.googleapis.com/css?family=Architects+Daughter" rel="stylesheet">
    
    </head>
    <body>
       
<!-- Barre de navigation Admin -->
       
        <?php
        if (isUserAdmin()): ?>

        <nav class="navbar navbar-inverse">
            <div class="container">
                <a class="navbar-brand">ADMIN</a>
                <ul class="nav navbar-nav">
                    <li><a href="<?= RACINE_WEB; ?>admin/categorie.php">Gestion des catégories</a></li>
                    <li><a href="<?= RACINE_WEB; ?>admin/membre.php">Gestion des membres</a></li>
                    <li><a href="<?= RACINE_WEB; ?>admin/annonce.php">Gestion des annonces</a></li>
                    <li><a href="<?= RACINE_WEB; ?>admin/commentaire.php">Gestion des commentaires</a></li>
                    <li><a href="<?= RACINE_WEB; ?>admin/note.php">Gestion des notes</a></li>
                </ul>
            </div>
        </nav>
        
        <?php 
        endif; ?>
        
<!-- Barre de navigation utilisateur -->
        
        <nav class="navbar navbar-default color-nav font-color">
            <div class="container-fluid">
                <div class="navbar-header navfont-color">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="btn btn-default btn-lg my_titles" href="<?= RACINE_WEB; ?>index.php"><img src="<?= RACINE_WEB; ?>images/annonceo_logo1.png"></a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="<?= RACINE_WEB; ?>about.php">A propos</a></li>
                        <li><a href="<?= RACINE_WEB; ?>contact.php">Contact</a></li>
                    </ul>
                    <form action="<?= RACINE_WEB; ?>index.php" class="navbar-form navbar-left">
                        <div class="form-group">
                            <input type="text" name="recherche" class="form-control" placeholder="Votre recherche">
                            <button class="btn btn-default" type="submit">Rechercher</button>
                        </div>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                          
<!-- Affichage menu selon navigation utilisateur -->
                           
                            <?php // Lorsque l'utilisateur est connecté
                            
                            if (isUserConnected()):
                            ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="glyphicon glyphicon-user" aria-hidden="true"> </span> <?= getUserFullName(); ?> <span class="caret"></span> </a>
                            
                            <?php  // Lorsque l'utilisateur n'est pas connecté
                            else :
                            ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Espace Membre<span class="caret"></span></a>
                            
                            <?php 
                            endif; ?>
                            
                            <ul class="dropdown-menu">
                               
                                <?php // Lorsque l'utilisateur est connecté
                                
                                if (isUserConnected()):
                                ?>   
                                <li><a href="<?= RACINE_WEB; ?>deconnexion.php">Déconnexion</a></li>
                                <li><a href="<?= RACINE_WEB; ?>creation_annonce.php">Déposer une annonce</a></li>
                                <li><a href="<?= RACINE_WEB; ?>profil.php">Mon profil</a></li>
                                
                                <?php // Lorsque l'utilisateur n'est pas connecté
                                
                                else :
                                ?>
                                <li><a href="inscription.php">Inscription</a></li>
                                <li><a href="connexion.php">Connexion</a></li>
                                
                                <?php 
                                endif; ?>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        
    <div class="container">
        