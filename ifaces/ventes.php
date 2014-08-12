<?php session_start(); ?>
<?php include "tete.php" ?>
<br><br>
      <fieldset>
      <legend>
        <?php 
          
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
 
            // Si tout va bien, on peut continuer
 
            // On recupère tout le contenu de la table point de collecte
          
            $req = $bdd->prepare("SELECT * FROM points_vente WHERE id = :id ");
            $req->execute(array('id' => $_GET['numero']));
 
           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {
            echo$donnees['nom'];
           }
              $reponse->closeCursor(); // Termine le traitement de la requête
        ?>
      </legend>   
      </fieldset>     
    <div class="row">
   	<br>
      <div class="col-md-3 col-md-offset-1" >
      <label>Ticket de caisse:</label>
        <?php 
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
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {
        ?>
      <ul class="list-group">
      <li class="list-group-item">
      <span class="badge" id="<?php echo$donnees['nom']?>"style="background-color:<?php echo$donnees['couleur']?>">0</span>
        <?php echo$donnees['nom']?>
        <?php 
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
             // On recupère tout le contenu de la table point de collecte
           $req = $bdd->prepare("SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet  ");
           $req->execute(array('id_type_dechet' => $donnees['id']));
           $i = 1;
           // On affiche chaque entree une à une
           while ($donneesint = $req->fetch())
           {
           ?>
      <li class="list-group-item">
        <span class="badge"style="background-color:<?php echo$donnees['couleur']?>">0</span>
           <?php echo$donneesint['nom']?>
      </li>
           <?php }
           $req->closeCursor(); // Termine le traitement de la requête
           ?>
      </li>
           <?php }
           $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
      </ul>
      <button class="btn btn-default btn-lg">c'est pesé!</button></form>
      <button class="btn btn-default btn-lg" onclick="tdechet_clear();" >reset</button>
      <br>
      </div>  
      <div class="col-md-2" >
      <label>Type d'objet:</label><br>
            <?php 
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
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui" AND MOD(id,2)=1');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?>
      <div class="btn-group">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
      <span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
      </button>
      <ul class="dropdown-menu" role="menu">
      <li><a ><?php echo$donnees['nom']?></a></li>

      <li class="divider"></li>


  <?php 
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
             // On recupère tout le contenu de la table grille_objets
           $req = $bdd->prepare("SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet  ");
           $req->execute(array('id_type_dechet' => $donnees['id']));
           $i = 1;
           // On affiche chaque entree une à une
           while ($donneesint = $req->fetch())
           {
           ?>
    <li><a><?php echo$donneesint['nom']?></a></li>
    </li>
           <?php }
           $req->closeCursor(); // Termine le traitement de la requête
           ?> 
    </ul>
    </div>
    <br><br>
                <?php }
                $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    </div> 
    <div class="col-md-2" >
    <br><br>        
            <?php 
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
           // On recupère tout le contenu de la table type dechet
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui" AND MOD(id,2)=0');
          // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?>
    <div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span>
    </button>
    <ul class="dropdown-menu" role="menu">
    <li><a ><?php echo$donnees['nom']?></a></li>
    <li class="divider"></li>
            <?php 
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
            // On recupère tout le contenu de la table point de collecte
            $req = $bdd->prepare("SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet  ");
            $req->execute(array('id_type_dechet' => $donnees['id']));
            $i = 1;
            // On affiche chaque entree une à une
            while ($donneesint = $req->fetch())
            {
            ?>
    <li><a><?php echo$donneesint['nom']?></a></li>
    </li>
            <?php }
            $req->closeCursor(); // Termine le traitement de la requête
            ?>
    </ul>
    </div>
    <br>
    <br>
             <?php }
             $reponse->closeCursor(); // Termine le traitement de la requête
             ?>
    </div>
    <div class="col-md-4" >
    <label>Clavier</label><br>
    <div class="col-md-3" style="width: 200px;">
    <div class="row">
    <div class="input-group">
    <input type="text" class="form-control" placeholder="Masse" id="number" name="num"  ><span class="input-group-addon">Kg.</span>
    </div>
    </div>
    <br>
    <div class="row">
        <button class="btn btn-default btn-lg" onclick="number_write('1');" data-value="1">1</button>
        <button class="btn btn-default btn-lg" onclick="number_write('2');" data-value="2">2</button>
        <button class="btn btn-default btn-lg" onclick="number_write('3');" data-value="3">3</button>
    </div>
    <div class="row">
        <button class="btn btn-default btn-lg" onclick="number_write('4');" data-value="4">4</button>
        <button class="btn btn-default btn-lg" onclick="number_write('5');" data-value="5">5</button>
        <button class="btn btn-default btn-lg" onclick="number_write('6');" data-value="6">6</button>
    </div>
    <div class="row">
        <button class="btn btn-default btn-lg" onclick="number_write('7');" data-value="7">7</button>
        <button class="btn btn-default btn-lg" onclick="number_write('8');" data-value="8">8</button>
        <button class="btn btn-default btn-lg" onclick="number_write('9');" data-value="9">9</button>
    </div>
    <div class="row">
        <button class="btn btn-default btn-lg" onclick="number_clear();" data-value="C">C</button>
        <button class="btn btn-default btn-lg" onclick="number_write('0');" data-value="0">0</button>
        <button class="btn btn-default btn-lg" onclick="number_write('.');" data-value=",">,</button>
    </div>
    </div>
    </div>
    </div>
    <br><br><br>
    <div class="row">
    </div>  
    <div class="col-md-4" >
    </div>
    <div class="col-md-4" >
    </div>
    </div>
    <br>
            <?php include "pied.php" ?>
