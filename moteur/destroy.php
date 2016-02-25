<?php
		session_start();
	// Suppression des cookies de connexion automatique
		setcookie('login', '');
		setcookie('pass', '');
    // Suppression des variables de session et de la session
	    $_SESSION = array();
	    session_unset();
        session_destroy();
    //redirection vers l'index    
		header ('location:../index.php');
?>
