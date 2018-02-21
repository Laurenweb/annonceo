<?php
ob_start(); // Correction du bug concernant la redirection après connexion et déconnexion

session_start();

// Site local (via Localhost)
// --------------------------
// define('RACINE_WEB', '/php/annonceo/');
// define('PHOTO_WEB', RACINE_WEB . 'photo/');
// define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] . '/php/annonceo/');
// define('PHOTO_SITE', RACINE_SITE . 'photo/');

// require __DIR__ . '/connexionbdd.php';
// require __DIR__ . '/fonctions.php';

?>

<?php
// session_start();

// Site en ligne (via hébergeur 1&1)
// ---------------------------------
define('RACINE_WEB', '/annonceo/');
define('PHOTO_WEB', RACINE_WEB . 'photo/');
define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] . '/annonceo/');
define('PHOTO_SITE', RACINE_SITE . 'photo/');

require __DIR__ . '/connexionbdd.php';
require __DIR__ . '/fonctions.php';

?>
