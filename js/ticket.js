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

export class Ticket {
  /*
   * Construit un Ticket vide. Cet Object represente un ticket
   * dans les interfaces de sortie et de collecte.
   * Pour l'instait faire un Ticket par type de sortie/collect
   * ex: un pour les evacuables et un pour les objets revalorise.
   *
   * ATTENTION: Ne met aucune contrainte sur l'objet introduit.
   * @returns {Ticket}
   */
  constructor() {
    this._total = 0.0;
    this.items = new Map();
    // this.comment = '';
    this.id_interne = 0;
  }

  /*
   * Supprime un objet du ticket par son id.
   * @param {type} id de l'objet a supprimer du ticket.
   * @returns {Object} Retourne l'objet supprimee utile en cas de undo/redo.
   */
  remove(id) {
    const item = this.items.get(id);
    this.items.delete(id);
    this._total -= item.masse;
    return item;
  }

  /*
   * Ajoute un objet au Ticket.
   * L'objet dois etre sous la forme suivante:
   * ```javascript
   * // On suppose que this.id contiens l'id de l'objet.
   * ticket.push({
   *   masse: value,
   *   type: parseInt(this.id, 10),
   * });
   * ```
   * @param {Object} {id, masse}
   * @returns {Number} Id interne pour faire le lien avec l'UI en cas de suppression.
   */
  push(item) {
    this.items.set(this.id_interne, item);
    this._total += item.masse;
    const id = this.id_interne;
    this.id_interne += 1;
    return id;
  }

  /*
   * La masse totale du ticket.
   * @type {Int} Masse totale du Ticket.
   */
  get total() {
    return this._total;
  }

  /*
   * @type {Int} Nombre d'items dans le ticket.
   */
  get size() {
    return this.items.size;
  }

  to_json() {
    return JSON.stringify(this.to_array());
  }

  to_array() {
    return [...this.items.values()];
  }

  entries() {
    return [...this.items.entries()];
  }

  /*
   * Remet a 0 le ticket.
   */
  reset() {
    this._total = 0.0;
    this.items = new Map();
    this.id_interne = 0;
  }
}

const sumMasseTickets = (ts) => ts.reduce((acc, t) => acc + t.total, 0);
