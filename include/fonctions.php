<?php

function sanitizeValue(&$value)
{
    $value = trim(strip_tags($value));
    // trim() supprime les espaces en début et fin de chaîne
    // strip_tags() supprime le balisage de la chaîne
}

function sanitizeArray (array &$array)
{
    array_walk($array, 'sanitizeValue'); // fonction native de PHP qui prend un tableau en premier paramètre, le parcours (walk) et applique une fonction à chaque élément
    // Méthode avec fonction anonyme c'est à dire non définie "avant" : 
    
    //array_walk($array, function(&$value) {
      //  trim(strip_tags($value))
    //});
}

function sanitizePost()
{
    sanitizeArray($_POST);
}

function displayErrorClass($nomChamp, array $errors)
{
    if (isset($errors[$nomChamp])) {
        
        echo 'has-error';
    }
}

function displayErrorMsg($nomChamp, array $errors)
{
    if (isset($errors[$nomChamp])) {
    
        echo '<span class="help-block">' . $errors[$nomChamp] . '</span>';
    }
}
// Rappel : ISSET Détermine si une variable est définie et est différente de NULL

function isUserConnected()
{
    return isset($_SESSION['membre']);
}


function getUserFullName()
{
    if(isUserConnected()) {
        return $_SESSION['membre']['prenom'] . ' ' . $_SESSION['membre']['nom'];
    }
    return '';
}

function isUserAdmin()  
{
    return isUserConnected() && $_SESSION['membre']['statut'] == '1';
}


function memberSecurity()
{
    
        if(!isUserConnected()) {
            header('Location: ' . RACINE_WEB . 'index.php');
            die;
        } 
}

function adminSecurity()
{
    if(!isUserAdmin()) {
        if(isUserConnected()) {
            header('Location: ' . RACINE_WEB . 'index.php');
        } else {
            header('Location: ' . RACINE_WEB . 'connexion.php');
        }
        
        die;
    }      
}

function setFlashMessage($message, $type = 'success')
{
    $_SESSION['flashMessage'] = [
      'message' => $message, 'type' => $type,  
    ];
}

function displayFlashMessage()
{
    if (isset($_SESSION['flashMessage'])) {
        $type = $_SESSION['flashMessage']['type'] == 'error' 
            ? 'danger' // class alert-danger du bootstrap
            : $_SESSION['flashMessage']['type'];
    
        $alert = '<div class="alert alert-'.$type. '" role="alert">' . '<strong>' . $_SESSION['flashMessage'] ['message'] . '</strong>' . '</div>';
        
        echo $alert;
        
        unset($_SESSION['flashMessage']); // Suppression du message de la session pour affichage "one shot"
}

}

function formatEuro($prix)
{
    return number_format($prix, 2, ',', '').'€';
}

?>