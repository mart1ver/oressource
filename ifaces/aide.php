<?php session_start();
/*
+  Oressource
+  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers
+
+  This program is free software: you can redistribute it and/or modify
+  it under the terms of the GNU Affero General Public License as
+  published by the Free Software Foundation, either version 3 of the
+  License, or (at your option) any later version.
+
+  This program is distributed in the hope that it will be useful,
+  but WITHOUT ANY WARRANTY; without even the implied warranty of
+  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
+  GNU Affero General Public License for more details.
+
+  You should have received a copy of the GNU Affero General Public License
+  along with this program.  If not, see <http://www.gnu.org/licenses/>.
+ */
+
+
+// Oressource 2017,

//Vérification du renseignement de la variable de session 'id':
    if (isset($_SESSION['id']) )
      {  include "tete.php" ?>
   
        <h1>need help? call me!</h1>
 <p>...and if I don't answer, bear in mind that a help file is actually under construction...and please be patient</p> 
         


<?php include "pied.php"; 
}
    else
   { 
header('Location: ../moteur/destroy.php') ; 
}
?>
       
      
