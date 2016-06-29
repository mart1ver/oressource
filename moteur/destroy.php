<?php
		session_start();
// Suppression des variables de session et de la session
	$_SESSION = array();
		session_destroy();
// Suppression des cookies de connexion automatique
		setcookie('login', '');
		setcookie('pass', '');
		if($_GET['motif'] == 1)
			{
        header ('location:../ifaces/login.php?msg=Vous avez été deconnecté pour caouse de longue inactivité, votre saisie à tout de meme été prise en compte. ');
			}else{
		header ('location:../ifaces/login.php');
				}
?>
