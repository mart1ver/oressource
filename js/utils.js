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

/**
 * Permet d'acceder aux parametres de l'url (query/search/get parameters)
 * sous la forme d'un objet js.
 */
function process_get() {
  const val = {};
  const query = new URLSearchParams(window.location.search.slice(1)).entries();
  for (const pair of query) {
    val[pair[0]] = pair[1];
  }
  return val;
}