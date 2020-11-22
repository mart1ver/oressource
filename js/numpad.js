'use strict';
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

function number_write(x) {
  const text_box = document.getElementById("number");
  text_box.value = text_box.value + x;
}

function number_clear() {
  const input = document.getElementById("number");
  input.value = "";
  input.setCustomValidity("");
}

/*
 * Classe pour la gestion du pave numerique.
 * Le design actuel est minimaliste a terme cela serait pratique d'en faire un
 * widget a la Morris.js.
 */
class NumPad {
  /*
   * Cree un Numpad cote JS on lui passe l'element input HTML associee au numpad.
   * ainsi que la liste des conteneurs disponnibles.
   * ```js
   * const numpad = new NumPad(document.getElementById('number'));
   * ```
   */
  constructor(root, conteneurs = []) {

    const div = document.createElement('div');
    div.setAttribute('class', 'panel panel-info');
    div.innerHTML = `
    <div id="saisie" class="panel-heading input-group">
      <input type="text" class="form-control" placeholder="Masse" id="number" name="num">
    </div>

    <div class="panel-body" >
      <div class="numpad" role="group">
        <button class="numkey btn btn-default btn-lg" onclick="number_write('1');" data-value="1">1</button>
        <button class="numkey btn btn-default btn-lg" onclick="number_write('2');" data-value="2">2</button>
        <button class="numkey btn btn-default btn-lg" onclick="number_write('3');" data-value="3">3</button>
      </div>
      <div class="numpad" role="group">
        <button class="numkey btn btn-default btn-lg" onclick="number_write('4');" data-value="4">4</button>
        <button class="numkey btn btn-default btn-lg" onclick="number_write('5');" data-value="5">5</button>
        <button class="numkey btn btn-default btn-lg" onclick="number_write('6');" data-value="6">6</button>
      </div>
      <div class="numpad" role="group">
        <button class="numkey btn btn-default btn-lg" onclick="number_write('7');" data-value="7">7</button>
        <button class="numkey btn btn-default btn-lg" onclick="number_write('8');" data-value="8">8</button>
        <button class="numkey btn btn-default btn-lg" onclick="number_write('9');" data-value="9">9</button>
      </div>
      <div class="numpad" role="group">
        <button class="numkey btn btn-default btn-lg" onclick="number_clear();" data-value="C">C</button>
        <button class="numkey btn btn-default btn-lg" onclick="number_write('0');" data-value="0">0</button>
        <button class="numkey btn btn-default btn-lg" onclick="number_write('.');" data-value=".">,</button>
      </div>
    </div>`;
    const numpad = document.createDocumentFragment();
    numpad.appendChild(div);
    if (conteneurs.length > 0) {

      const fragment = document.createDocumentFragment();
      const div = document.createElement('div');
      div.setAttribute('class', 'input-group-btn');
      div.innerHTML = `
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-minus"></span>
          <span class="caret"></span>
        </button>
        <ul id="conteneurs" class="dropdown-menu dropdown-menu-right" role="menu"></ul>`;
      fragment.appendChild(div);
      const ul = fragment.querySelector('#conteneurs');
      conteneurs.forEach((c) => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.innerText = `${c.nom}: -${c.masse} kg`;
        a.addEventListener('click', () => {
          this.substract_manutention(c.masse);
        }, false);
        li.appendChild(a);
        ul.appendChild(li);
      });
      numpad.querySelector('#saisie').appendChild(fragment);
    }
    root.appendChild(numpad);
    this.input = document.getElementById('number');
  }

  substract_manutention(masse_conteneur) {
    const new_value = this.value - masse_conteneur;
    if (new_value > 0.0) {
      this.value = (this.value - masse_conteneur).toFixed(2);
    } else {
      this.error("La masse de l'objet pesee est inferieur au poids du conteneur!");
    }
  }

  /*
   * Permet de recuperer la valeur contenue dans l'input du pave numerique.
   */
  get value() {
    return parseFloat(this.input.value);
  }

  set value(value) {
    this.input.value = value;
  }

  /*
   * Remet a zero le numpad.
   */
  reset_numpad() {
    this.input.value = '';
    this.input.setCustomValidity('');
  }

  /*
   * Permet de definir la validite du champ input du numpad.
   */
  error(msg) {
    this.input.setCustomValidity(msg);
  }
}
