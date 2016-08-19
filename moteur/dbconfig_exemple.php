<?php
// Changer ces valeurs selon votre configuration de systeme de base de donnée.
$host='localhost';
$base='oressource';
$user='oressource';
$pass='mot_de_passe_a_changer';

// Configuration interne de Oressource
try {
	$bdd = new PDO("mysql:host=$host;dbname=$base;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die('Connexion échouée : ' . $e->getMessage());
}
