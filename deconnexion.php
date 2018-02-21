<?php
// Inclusion du fichier d'initialisation
include 'include/init.php';

unset($_SESSION['membre']); 

$redirect = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'index.php';

header('Location: ' . $redirect); die;