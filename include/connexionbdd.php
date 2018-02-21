<?php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

// Site local (via Localhost)
// $pdo = new PDO('mysql:host=localhost;dbname=annonceo', 'root', '', $options);

// Site en ligne (via hébergeur 1&1)
$pdo = new PDO('mysql:host=db681868236.db.1and1.com;dbname=db681868236', 'dbo681868236', 'Annonceo3', $options);

?>