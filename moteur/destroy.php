<?php
		session_start();
//on récupere le motif si il existe
$motif = 0 ;
if (isset($_GET['motif'])) {
    $motif = intval($_GET['motif']);
}


// Suppression des variables de session et de la session
	$_SESSION = array();
		session_destroy();
// Suppression des cookies de connexion automatique
		setcookie('login', '');
		setcookie('pass', '');
		if($motif === 1)
			{
        header ('location:../ifaces/login.php?msg=Vous avez été deconnecté pour cause de longue inactivité, votre saisie à tout de même été prise en compte. ');
			}else{
		header ('location:../ifaces/login.php');
				}
?>
