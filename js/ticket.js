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

class Ticket {
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
    return [ ...this.items.values() ];
  }

  entries() {
    return [ ...this.items.entries() ];
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

// TODO: separer la logique de l'UI.
// Gros Hack pour pouvoir gerer les sorties... On revoie une
// fonction specialisee.
// Attention pretrairement ne fait que retourner la masse sauf si on est une sortie poubelles.
function connection_UI_ticket(numpad, ticket, typesItems, pretraitement = ((a, ..._) => a)) {
  const totalUI = document.getElementById('massetot');
  const transaction = document.getElementById('transaction');
  // Ne bind pas this... A explorer.
  return (event) => {
    const id = parseInt(event.currentTarget.id, 10);
    const type_dechet = typesItems[id - 1];
    const value = pretraitement(numpad.value, type_dechet.masse_bac); // retourne la masse sauf pour les poubelles.
    if (value > 0.00) {
      if (value <= window.OressourceEnv.masse_max) {

        const id_last_insert = ticket.push({
          masse: value,
          type: id
        });
        // Update UI pour du ticket
        const type_dechet = typesItems[id - 1];
        ticket_update_ui(transaction, totalUI, Object.assign(type_dechet, {id_last_insert, ticket}), value);
        // Clear du numpad.
        numpad.reset_numpad();
      } else {
        numpad.error('Masse supérieure aux limites de pesée de la balance.');
      }
    } else {
      numpad.error('Masse entrée inférieure au poids du conteneur ou inférieure ou égale à 0.');
    }
  };
}

/*
 * Prepare une liste de bouton clickable dans l'UI pour la saisie.
 * @param {Object} { id {Int}, nom{str}, couleur{str} } objet issue de la database.
 * @param {Function} action fonction a faire en cas de click sur le button.
 * @returns {Element|html_saisie_item.button}
 */
function html_saisie_item( { id, nom, couleur }, action) {
  const button = document.createElement('button');
  button.setAttribute('id', id);
  button.setAttribute('class', 'btn btn-default');
  button.setAttribute('style', 'margin-left:8px; margin-top:16px;');
  button.innerHTML = `<span class="badge" id="cool" style="background-color:${couleur}">${nom}</span>`;
  button.addEventListener('click', action, false);
  return button;
}

// TODO revoir le design...
function ticket_update_ui(container, totalUI, bag, value) {
  // Constitution du panier
  const { _, nom, couleur, id_last_insert, ticket } = bag;
  const li = document.createElement('li');
  li.setAttribute('class', 'list-group-item');
  const btn = '<button class="btn btn-danger"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></button>';
  const p = `<p style="margin-left: 5px; display: inline-block">${nom}</p>`;
  li.innerHTML = `${btn}${p}<span class="badge" style="display: inline-block; margin-top: 9px; background-color:${couleur}">${value}</span>`;
  li.getElementsByTagName('button')[0].addEventListener('click', () => {
    ticket.remove(id_last_insert);
    const total = window.tickets.reduce((acc, ticket) => acc + ticket.total, 0);
    totalUI.textContent = `Masse totale: ${total} Kg.`;
    li.remove();
  });
  container.appendChild(li);
  // Update de l'UI pour la masse du panier.
  const total = window.tickets.reduce((acc, ticket) => acc + ticket.total, 0);
  totalUI.textContent = `Masse totale: ${total} Kg.`;
}

function recycleur_reset() {
  const select = document.getElementById('id_type_action');
  // Reactivation des options du select
  select.value = '';
  select.selectedIndex = '0';
  select[0].setAttribute('selected', true);
  Array.from(select.children).slice(1).forEach((element) => {
    element.removeAttribute('selected');
    element.removeAttribute('disabled');
    element.disabled = false;
    element.selected = false;
  });
  const ui = document.getElementById('list_evac');
  const btnList = Array.from(ui.children).forEach((button) => {
    button.setAttribute('style', 'display: none; visibility: hidden');
  });
}

/* Fonction de remise à zéro de l'interface graphique et des tickets en cours.
 * @return {undefined}
 */
function tickets_clear(type) {
  // On supprime tout le ticket en cours.
  const range = document.createRange();
  range.selectNodeContents(document.getElementById('transaction'));
  range.deleteContents();
  document.getElementById('commentaire').value = '';
  document.getElementById('massetot').textContent = 'Masse totale: 0 Kg.';
  if (type === 'collecte' || type === 'sorties') {
    document.getElementById('localite').selectedIndex = '0';
  }
  if (type === 'sortiesr') {
    recycleur_reset();
  }
  if (type !== 'sortiesp' && type !== 'sortiesd') {
    document.getElementById('id_type_action').selectedIndex = '0';
  }

  // On reset TOUT les tickets. En general il y en aura qu'un...
  window.tickets.forEach((ticket) => {
    ticket.reset();
  });
}

// TODO:
// Faire une Feuille de style minimaliste plutot que ce hack.
// Avec pour media: printer
// Ou construire une page en arriere plan.
function impression_ticket(encaisse) {
  const print_html = `
    <body>
      <p>${document.querySelector('.active a').innerHTML}</p>
      <p>${window.OressourceEnv.structure}</p>
      <p>${window.OressourceEnv.adresse}</p>
      <p>---------------------------------------------------------------------</p>
      <p>${document.getElementById('massetot').innerHTML}</p>
      <p>---------------------------------------------------------------------</p>
      <p>${document.getElementById('transaction').innerHTML}<p>
    </body>`;
  const oldstr = document.body.innerHTML;
  document.body.innerHTML = print_html;
  window.print();
  document.body.innerHTML = oldstr;
  encaisse();
  tickets_clear();
}

function login(onSuccess = undefined) {
  const html = `
  <div class="popup">
  <form id="formLogin" class="form-signin" method="post">
    <h2 class="form-signin-heading">Veuillez vous reconnecter</h2>
    <label class="sr-only" for="mail">Mail :</label>
    <input id="mail" class="form-control" name="mail" type="email" placeholder="Courriel" autofocus>
    <label class="sr-only" for="pass">Mot de passe :</label>
    <input id="pass" class="form-control" name="pass" type="password" placeholder="Mot de passe">
    <button id="postLogin" class="btn btn-lg btn-primary btn-block glyphicon glyphicon-log-in" type="submit"> Login</button>
  </form>
  </div>`;
  const container = document.createElement('div');
  container.setAttribute('class', 'overlay');
  container.innerHTML = html;
  document.getElementsByTagName('body')[0].appendChild(container);
  container.setAttribute('style', 'visibility: visible; opacity: 1;');
  document.getElementById('formLogin').addEventListener('submit', (event) => {
    event.preventDefault();
    const form = new FormData(document.getElementById('formLogin'));
    const username = form.get('mail');
    const password = form.get('pass');
    const fetchPromise = fetch('../moteur/login_post.php', {
      method: 'POST',
      credidentials: 'include',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json; charset=utf-8'
      },
      body: JSON.stringify({ username, password })
    }).then(status)
      .then((json) => {
        container.setAttribute('style', 'visibility: hidden;  opacity: 0;');
        if (onSuccess) {
          onSuccess();
        }
      }).catch((ex) => {
      console.log('Error:', ex);
    });
  }, false);
}

function status(response) {
  if (response.status >= 200
          && response.status < 300) {
    return response;
  } else if (response.status === 401) {
    const error = new Error(response.statusText);
    error.response = response;
    error.status = response.status;
    throw error;
  } else {
    console.error(response.statusText);
    return response;
  }
}

// TODO Vrai gestion de la reponse... (future mise en attente...)
function post_data(url, data, onSuccess) {
// See https://github.com/github/fetch
  fetch(url, {
    credentials: 'include',
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json; charset=utf-8'
    },
    body: JSON.stringify(data)
  }).then(status)
    .then(() => onSuccess(data.classe))
    .catch((ex) => {
      if (ex.status === 401) {
        login(() => {
          post_data(url, data, onSuccess.classe);
        });
      } else {
        console.log('Error:', ex);
      }
  });
}

function strategie_validation(metadata) {
  const classe = metadata.classe;
  if (classe === 'sortiesp' || classe === 'sortiesd') {
    return (_) => true;
  } else if (classe === 'collecte' || classe === 'sorties') {
    return (obj) => obj.id_type_action > 0 && obj.localite > 0;
  } else { // Reste des cas.
    return (obj) => obj.id_type_action > 0;
  }
}

/*
 * On retourne une closure qui correspond a un encaissement valide.
 * On fait ca pour pouvoir gerer les differents types de retours vu que les ID
 * Sont relatives... Par exemple dans les sorties de dechets et d'objet.
 *
 * @param {string} url Url de la requete post.
 * @param {Objects} tickets Mixin de tickets la clef servira a les reconnaitre cote serveur.
 */
function make_encaissement(url, tickets, metadata) {
  return () => {

    // On recupere les sommes de differents tickets.
    const sum = Object.values(tickets).reduce((acc, ticket) => {
      return acc + ticket.size;
    }, 0);
    const form = new FormData(document.getElementById('formulaire'));
    const formdata = {
      localite: parseInt(form.get('localite'), 10),
      id_type_action: parseInt(form.get('id_type_action'), 10),
    };
    const test = strategie_validation(metadata);
    if (sum > 0 && test(formdata)) {
      const commentaire = form.get('commentaire').trim(); // On enleve les espaces inutiles.
      const antidate = form.get('antidate');
      const data = {
        id_point: window.OressourceEnv.id_point,
        id_user: window.OressourceEnv.id_user,
        commentaire,
        antidate
      };
      // Petit hack pour merger differents types d'objets a envoyer.
      const items = Object.entries(tickets).reduce((acc, [type, ticket]) => {
        // [type] c'est parceque la clef est a calculee
        return Object.assign({ }, acc, { [type]: ticket.to_array() });
      }, { });
      // Object.assign sert a mixer des Objets Javascript.
      post_data(url, Object.assign({ }, data, formdata, items, metadata), tickets_clear);
    }
  };
}
