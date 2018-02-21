<?php
// Inclusion du fichier d'initialisation
require 'include/init.php';

$errors = [];

$expediteur = $objet = $message = '';

if(!empty($_POST)) {

    sanitizePost();

    if (empty($_POST['expediteur'])) {
        $errors['expediteur'] = "Veuillez indiquer votre adresse email !";
    } elseif (!filter_var($_POST['expediteur'], FILTER_VALIDATE_EMAIL)) {
        $errors['expediteur'] = 'Adresse email invalide';
    }

    if (empty($_POST['objet'])) {
        $errors['objet'] = "Veuillez indiquer l'objet de votre message !";
    }

    if (empty($_POST['message'])) {
        $errors['message'] = 'Veuillez indiquer un message !';
    }    

    if (empty($errors)) {

        $headers = 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type: text/html; charset=ISO-8859-1'."\n";
        $headers .= 'Reply-To: ' . $_POST['expediteur'] . "\n";
        $headers .= 'From: "' . ucfirst(substr($_POST['expediteur'], 0, strpos($_POST['expediteur'], '@'))) . '"<'.$_POST['expediteur'].'>' . "\n"; $headers .= 'Delivered-to: annonceo@email.com' . "\n";

        mail("annonceo@email.com", $_POST['objet'], $_POST['message'], $headers);

        // Message de confirmation
        setFlashMessage('Votre message a bien été envoyé !', 'success', 'glyphicon-send');
        header('Location: index.php');
        die;      

    } 
}

// Insertion de la section haut de page
require 'layout/top.php';

if (!empty($errors)) :

?>    

<div class="alert alert-danger" role="alert" >
    <strong><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Le formulaire contient des erreurs !</strong>
</div>  

<?php

endif;

?>

<?= displayFlashMessage() ?>
 
<div class="container">
    <fieldset>
        <h1 class="text-center my_titles my_font">Contactez-nous</h1>
        <br>
        <div class="col-md-8 col-md-offset-2 thumbnail">
            <legend class="text-center">Des questions, des suggestions ? <br>Un problème de fonctionnement sur le site ou un litige ? <br>N'hésitez pas à nous contacter.</legend>
            <hr class="light">
            
<!-- FORMULAIRE DE CONTACT -->

            <form method="post">

                <div class="row">
                    <div class="col-md-8 col-md-offset-2 form-group <?php displayErrorClass('expediteur', $errors); ?>">
                        <label for="expediteur" class="control-label">Adresse email</label><br>
                        <?php if (isUserConnected()) : ?>
                        <input class="form-control" type="text" name="expediteur" id="expediteur" placeholder="Indiquer votre adresse email..." value ="<?= $_SESSION['membre']['email']; ?>">
                        <?php else : ?>
                        <input class="form-control" type="text" name="expediteur" id="expediteur" placeholder="Indiquer votre adresse email...">
                        <?php endif; ?>                
                        <?php displayErrorMsg('expediteur', $errors); ?>                 
                    </div>
                </div> <!-- <div class="row"> -->

                <div class="row">
                    <div class="col-md-8 col-md-offset-2 form-group <?php displayErrorClass('objet', $errors); ?>">
                        <label for="objet" class="control-label">Objet</label>
                        <input class="form-control" type="text" name="objet" placeholder="Objet de votre message...">
                        <?php displayErrorMsg('objet', $errors); ?>                
                    </div>
                </div> <!-- <div class="row"> -->

                <div class="row">                
                    <div class="col-md-8 col-md-offset-2 form-group <?php displayErrorClass('message', $errors); ?>">
                        <label for="message" class='control-label'>Message</label>
                        <textarea class="form-control" style="min-height: 250px;" name="message" placeholder="Indiquer votre message..."></textarea>
                        <?php displayErrorMsg('message', $errors); ?>                
                    </div>
                </div> <!-- <div class="row"> -->
                
                <div class="form-group text-center">
                    <button type="submit" value="envoyer l'email" class="btn btn-primary">ENVOYER</button>
                </div>

            </form> <!-- Fin du formulaire -->

            <blockquote class="text-center ml-50 mr-50" style="border-right : solid 5px #eee">
                <h3>Annonceo</h3>
                <p>300 Boulevard de Vaugirard</p>
                <p>75015 Paris (FRANCE)</p>
                <p><span class="glyphicon glyphicon-phone"></span> 01 44 55 66 77</p>
                <p><span class="glyphicon glyphicon-mail"></span><a href="#">annonceo@email.com</a></p>
            </blockquote>
                <br>
        </div> <!-- fin div class="col-md-8 col-md-offset-2 thumbnail" -->
    </fieldset>
</div>

<hr style="width: 1000px"> 

<?php
// Insertion de la section bas de page
require 'layout/bottom.php';
?>