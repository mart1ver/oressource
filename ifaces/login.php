<?php
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
include "tete.php" ?>
<div class="wrapper">
  <form id="formLogin" class="form-signin" method="post">
    <h2 class="form-signin-heading">Veuillez vous connecter</h2>
    <label class="sr-only" for="mail">Mail :</label>
    <input id="mail" class="form-control" name="mail" type="email" placeholder="Courriel" autofocus>
    <label class="sr-only" for="pass">Mot de passe :=</label>
    <input id="pass" class="form-control" name="pass" type="password" placeholder="Mot de passe">
    <button id="postLogin" class="btn btn-lg btn-primary btn-block glyphicon glyphicon-log-in" type="submit"> Login</button>
  </form>
</div>
<script src="../js/ticket.js" type="text/javascript"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
      const div = document.createElement('div');
      div.setAttribute('class', 'alert alert-danger');
      div.setAttribute('style', 'width:80%;margin:auto; visibility: hidden; display none');
      div.textContent = 'Mauvais identifiant ou mot de passe.';
      const body = document.getElementsByTagName('body')[0];
      body.insertBefore(div, body.firstChild);
    document.getElementById('formLogin').addEventListener('submit', (event) => {
      event.preventDefault();
      const form = new FormData(document.getElementById('formLogin'));
      const username = form.get('mail');
      const password = form.get('pass');
      fetch('../moteur/login_post.php', {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify({ username, password })
      }).then(status)
        .then((json) => {
          // redirection vers l'index en attendant de pouvoir faire mieux.
          window.location.href = '../ifaces/index.php';
         }).catch((ex) => {
           div.setAttribute('style', 'width:80%; margin:auto; visibility: visible; display: block');
       });
    }, false);
  }, false);
</script>
<?php include "pied.php";
