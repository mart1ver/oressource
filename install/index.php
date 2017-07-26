<?php

#
# Fonctions
#



function step_1(){
  if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pre_error'] ==''){
   header('Location: index.php?step=2');
   exit;
  }
  if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pre_error'] != '')
   echo $_POST['pre_error'];
      
  if (phpversion() < '5.0') {
   $pre_error = 'You need to use PHP5 or above for our site!<br />';
  }
  $test_mysql=is_readable('../moteur/dbconfig.php');
  if (!$test_mysql) {
   $pre_error .= 'MySQL extension needs to be loaded for our site to work!<br />';
  }
  $test_dbconfig=is_readable('../moteur/dbconfig.php');
  if (!$test_dbconfig) {
   $pre_error .= 'dbconfig.php needs to be readable for our site to be installed!';
  }

  include 'tete.php';
?>
  <h1>Installation de Oressource</h1>
  <h2>Vérification</h2>
  <table width="100%">
  <tr>
   <td>PHP Version:</td>
   <td><?php echo phpversion(); ?></td>
   <td>5.0+</td>
   <td><?php echo (phpversion() >= '5.0') ? 'Ok' : 'Not Ok'; ?></td>
  </tr>
  <tr>
   <td>MySQL:</td>
   <td><?php echo  $test_mysql ? 'On' : 'Off'; ?></td>
   <td>On</td>
   <td><?php echo $test_mysql ? 'Ok' : 'Not Ok'; ?></td>
  </tr>
  <tr>
   <td>config.php</td>
   <td><?php echo $test_dbconfig ? 'Writable' : 'Unwritable'; ?></td>
   <td>Accessible</td>
   <td><?php echo $test_dbconfig ? 'Ok' : 'Not Ok'; ?></td>
  </tr>
  </table>
  <form action="index.php?step=1" method="post">
   <input type="hidden" name="pre_error" id="pre_error" value="<?php echo $pre_error;?>" />
   <input type="submit" name="continue" value="Continue" />
  </form>
<?
  include 'pied.php';
}


function step_2(){
  if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pre_error'] ==''){
   header('Location: index.php?step=3');
   exit;
  }

  try
  {
    // On se connecte à MySQL
    include('../moteur/dbconfig.php');
  }
  catch(Exception $e)
  {
     // En cas d'erreur, on affiche un message et on arrête tout
     die('Erreur : '.$e->getMessage());
  }
  $file='../mysql/oressource.sql';
  $sql = file_get_contents($file);
  $bdd->exec($sql);

  $file='../install/data.sql';
  $sql = file_get_contents($file);
  $bdd->exec($sql);
 


  include 'tete.php';

  ?>
  <h1>Installation de Oressource</h1>
  <h2>Chargement de la base de donnée </h2>
  <form action="index.php?step=2" method="post">
   <input type="hidden" name="pre_error" id="pre_error" value="<?php echo $pre_error;?>" />
   <input type="submit" name="continue" value="Continue" />
  </form>
<?
  include 'pied.php';
}


function step_3(){
  include 'tete.php';
?>
 <h1>Installation de Oressource</h1>
 <h2>Installation Terminée</h2>
 <p>utilisateur : admin@admin.admin</p>
 <p>mot de passe: admin</p>
 <p><a href="/" >Accéder à Oressource</a></p>
<? 
 include 'pied.php';
}

#
# MAIN
# 
$step = (isset($_GET['step']) && $_GET['step'] != '') ? $_GET['step'] : '';
switch($step){
  case '1':
  step_1();
  break;
  case '2':
  step_2();
  break;
  case '3':
  step_3();
  break;
  case '4':
  step_4();
  break;
  default:
  step_1();
}


?>
