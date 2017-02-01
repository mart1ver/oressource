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

/*
 * Classe pour la gestion du pave numerique.
 * Le design actuel est minimaliste a terme cela serait pratique d'en faire un
 * widget a la Morris.js.
 */
class NumPad {
  /*
   * Cree un Numpad cote JS on lui passe l'element input HTML associee au numpad.
   * ex:
   * ```js
   * const numpad = new NumPad(document.getElementById('number'));
   * ```
   */
  constructor(container) {
    this.input = container;
  }

  /*
   * Permet de recuperer la valeur contenue dans l'input du pave numerique.
   */
  get value() {
    return parseFloat(this.input.value);
  }

  /*
   * Remet a 0 le numpad.
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