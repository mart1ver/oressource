<?php

/*
  Oressource
  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
