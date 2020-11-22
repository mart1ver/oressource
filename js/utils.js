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
 * Transforme un objet JavaScript en une QueryString
 * @param {Object} obj object clef-valeur a transformer en QueryQtring
 * @returns {String} string representant une QueryString.
 *
 * ## Exemple:
 * ```js
 * > toQueryString({start: "01-01-2020", end: "01-01-2021"})
 * "start=01-01-2020&end=01-01-2021"
 * ```
 */
const toQueryString = (obj) => (Object
  .entries(obj)
  .map(([ key, val ]) => `${encodeURIComponent(key)}=${encodeURIComponent(val)}`)
  .join('&')
);

/**
 * Convertis une date au format américain en format francophone avec jour de la semaine
 *
 * @param {{label: string}} `label` Represente une chaine au format
 *   américain YYYY-MM-DD comme: 2020-09-02.
 * @returns {string} Chaine representant une date au format dddd DD/MM/YYYY
 *
 * ## Exemple:
 *
 * ```js
 * //en locale FR-fr
 * > dateUStoFR('2020-09-02)
 * `mercredi 02/09/2020`
 * ```
 */
const dateUStoFR = ({ label }) => new moment(label, 'YYYY-MM-DD').format('dddd DD/MM/YYYY');

/**
 * Permet d'acceder aux parametres de l'url (query/search/get parameters)
 * sous la forme d'un objet js.
 * @global Récupére les paramètres de l'URL `URLSearchParams`.
 * @returns {object} objet representant les paramètres de la query string.
 */
function processGet() {
  const queryString = new URLSearchParams(window.location.search.slice(1));
  return Object.fromEntries(queryString);
}

/* datepicker
 * Fonctions en rapport avec le Datepicker
 */

/** Fonction pour proposer une configuration par défaut a MomentJS.
 *
 * @typedef {{date1: string, date2: string}} dateInterval de date compris entre
 *  `date1` et `date2` au format DD-MM-YYYY.
 * @param {MomentConfig} data
 *
 * Modifie l'element DOM a la CSSQuery suivante: `#reportrange span`.
 */
function setDatepicker(dateInterval) {
  const startDate = moment(dateInterval.date1, 'DD-MM-YYYY');
  const endDate = moment(dateInterval.date2, 'DD-MM-YYYY');
  $('#reportrange span').html(`${startDate.format('DD MMMM YYYY')} - ${endDate.format('DD MMMM YYYY')}`);
  const options = {
    startDate,
    endDate,
    minDate: moment('01/01/2010', 'MM/DD/YYYY'),
    maxDate: moment('12/31/2030', 'MM/DD/YYYY'),
    dateLimit: { days: 800 },
    showDropdowns: true,
    showWeekNumbers: true,
    timePicker: false,
    timePickerIncrement: 1,
    timePicker12Hour: true,
    ranges: {
      "Aujourd'hui": [ moment(), moment() ],
      hier: [ moment().subtract(1, 'days'), moment().subtract(1, 'days') ],
      '7 derniers jours': [ moment().subtract(6, 'days'), moment() ],
      '30 derniers jours': [ moment().subtract(29, 'days'), moment() ],
      'Ce mois': [
        moment().startOf('month'),
        moment().endOf('month'),
      ],
      'Le mois deriner': [
        moment().subtract(1, 'month').startOf('month'),
        moment().subtract(1, 'month').endOf('month'),
      ],
    },
    opens: 'left',
    buttonClasses: [ 'btn btn-default' ],
    applyClass: 'btn-small btn-primary',
    cancelClass: 'btn-small',
    locale: {
      separator: ' au ',
      format: 'DD/MM/YYYY',
      applyLabel: 'Appliquer',
      cancelLabel: 'Annuler',
      fromLabel: 'Du',
      toLabel: 'Au',
      customRangeLabel: 'Période libre',
      daysOfWeek: [ 'Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa' ],
      monthNames: [
        'Janvier', 'Fevrier', 'Mars',
        'Avril', 'Mai', 'Juin',
        'Juillet', 'Aout', 'Septembre',
        'Octobre', 'Novembre', 'Decembre',
      ],
      firstDay: 1,
    },
  };
  return options;
}

// TODO: Fix a potential XSS problem with query string.
function bindDatepicker(options, { base, query }) {
  const cb = (start, end, label) => $('#reportrange span').html(`${start.format('DD MMMM YYYY')} - ${end.format('DD MMMM YYYY')}`);
  $('#reportrange').daterangepicker(options, cb);

  $('#reportrange').on('apply.daterangepicker', (ev, picker) => {
    const queryString = toQueryString({
      ...query,
      ...{
        date1: picker.startDate.format('DD-MM-YYYY'),
        date2: picker.endDate.format('DD-MM-YYYY'),
      },
    });
    window.location.href = `${base}?${queryString}`;
  });

  $('#options1').click(() => {
    $('#reportrange').data('daterangepicker').setOptions(options, cb);
  });

  $('#destroy').click(() => {
    $('#reportrange').data('daterangepicker').remove();
  });
}

/*
 * Fonctions pour Morris
 */
const MorrisBar = (obj, element, labels, unit, couleur) => {
  if (obj.data.length !== 0) {
    return new Morris.Bar({
      element,
      hideHover: 'auto',
      data: obj.data,
      xkey: 'time',
      ykeys: [ 'nombre' ],
      labels: [ labels ],
      xLabelFormat: dateUStoFR,
      postUnits: unit,
      resize: true,
      barColors: [ couleur ],
      goals: [ obj.sum / obj.data.length ],
    });
  }
};

const graphMorris = (obj, element, unit = ' Kg') => {
  if (obj.data.length !== 0) {
    return new Morris.Donut({
      element,
      data: obj.data,
      backgroundColor: '  #ccc',
      labelColor: '#060',
      colors: obj.colors,
      formatter: (x) => `${x} ${unit}`,
    });
  }
};

/* GUI
 * Fonctions de Gestion de l'interface utilisateur des collectes et sorties
 */

const fillSelect = (select, array) => {
  array.forEach(({ id, nom }) => {
    const option = document.createElement('option');
    option.value = id;
    option.innerHTML = nom;
    select.appendChild(option);
  });
};

/**
 * Prepare une liste de bouton clickable dans l'UI pour la saisie.
 * @param {{ id: number, nom: string, couleur: string }} objet issue de la database.
 * @param {Function} action fonction a faire en cas de click sur le button.
 * @returns {HTMLButtonElement} Boutton crée
 */
function htmlSaisieItem({ id, nom, couleur }, action) {
  const button = document.createElement('button');
  button.setAttribute('id', id);
  button.setAttribute('class', 'btn btn-default');
  button.setAttribute('style', 'margin-left:8px; margin-top:16px;');
  button.innerHTML = `<span class="badge" style="background-color:${couleur}">${nom}</span>`;
  button.addEventListener('click', action, false);
  return button;
}

const fillItems = (div, types, push) => {
  types.forEach((e) => div.appendChild(htmlSaisieItem(e, push)));
};

// TODO revoir le design...
/**
 * Fonction de mise a jour du ticket des sorties et collectes.
 * @param {Node} container HTML sur lequel ajouter un item.
 * @param {HTMLElement} totalUI HTMLElement contenant la masse total a afficher.
 * @param {Object} bag
 * @param {number} value
 */
function ticketUpdateUI(container, totalUI, bag, value) {
  // Constitution du panier
  const {
    _, nom, couleur, id_last_insert, ticket,
  } = bag;
  const li = document.createElement('li');
  li.setAttribute('class', 'list-group-item');
  const span = '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
  li.innerHTML = `${span}&nbsp;&nbsp;${nom}<span class="badge" style="background-color:${couleur}">${value}</span>`;

  li.getElementsByTagName('span')[0].addEventListener('click', () => {
    ticket.remove(id_last_insert);
    totalUI.textContent = `Masse totale: ${sumMasseTickets(window.OressourceEnv.tickets)} Kg.`;
    li.remove();
  });

  container.append(li);
  // Update de l'UI pour la masse du panier.
  totalUI.textContent = `Masse totale: ${sumMasseTickets(window.OressourceEnv.tickets)} Kg.`;
}

// TODO: separer la logique de l'UI.
// Gros Hack pour pouvoir gerer les sorties... On revoie une
// fonction specialisee.
// Attention pretrairement ne fait que retourner la masse sauf si on est une sortie poubelles.
function connection_UI_ticket(numpad, ticket, typesItems, pretraitement = ((a, ..._) => a)) {
  const totalUI = document.getElementById('massetot');
  const transaction = document.getElementById('transaction');
  return (event) => {
    const id = parseInt(event.currentTarget.id, 10);
    // HACK: darnuria Change typesItems into a map latter.
    const type_dechet = typesItems.find((e) => e.id === id);
    const value = pretraitement(numpad.value, type_dechet.masse_bac); // retourne la masse sauf pour les poubelles.
    if (value > 0.00 && !Number.isNaN(id)) {
      if (value <= window.OressourceEnv.masse_max) {
        const item = {
          masse: value,
          type: id,
          show() {
            return `<p>${this.name} : ${this.masse} kg</p>`;
          },
        };

        const id_last_insert = ticket.push(item);
        ticketUpdateUI(transaction, totalUI, {
          ...type_dechet,
          ...{ id_last_insert, ticket },
        }, value);
        numpad.reset_numpad();
      } else {
        numpad.error('Masse supérieure aux limites de pesée de la balance.');
      }
    } else {
      numpad.error('Masse entrée inférieure au poids du conteneur ou inférieure ou égale à 0.');
    }
  };
}

/**
 * Fonction pour facilité la mise en place de la logique de la GUI.
 *
 * @param url {string} - Url a contacter avec Fetch ( voir post_data())
 * @param encaisse {Function} - Fonction à appeller si on click sur le bouton.
 */
function initUI(url, encaisse) {
  const send = post_data(url, encaisse, ticketsClear);
  const sendAndPrint = post_data(url, encaisse, ticketsClear, impressionTicket);
  document.getElementById('encaissement').addEventListener('click', send, false);
  document.getElementById('impression').addEventListener('click', sendAndPrint, false);
  document.getElementById('formulaire').addEventListener('submit', (e) => {
    e.preventDefault();
    send();
  }, false);
}

/**
 * Fonction de reset spéciale pour les sorties recyclages.
 */
function recycleurReset() {
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
  Array.from(document.getElementById('list_evac').children)
    .forEach((btn) => btn.setAttribute('style', 'display: none; visibility: hidden'));
}

/** Fonction de remise à zéro de l'interface graphique et des tickets en cours.
 * @param {string} classe soit une sortie soit une collecte.
 */
function ticketsClear({ classe }) {
  // On supprime tout le ticket en cours.
  const range = document.createRange();
  range.selectNodeContents(document.getElementById('transaction'));
  range.deleteContents();
  document.getElementById('commentaire').value = '';
  document.getElementById('massetot').textContent = 'Masse totale: 0 Kg.';
  if (classe === 'collecte') {
    document.getElementById('localite').selectedIndex = '0';
  }
  if (classe === 'sortiesr') {
    recycleurReset();
  }
  if (classe !== 'sortiesp' && classe !== 'sortiesd') {
    document.getElementById('id_type_action').selectedIndex = '0';
  }

  // On reset TOUT les tickets. En general il y en aura qu'un...
  window.OressourceEnv.tickets.forEach((t) => t.reset());
}

/** Transforme une classe sous forme d'une chaine humainement representable.
 * @param {string} classe de sortie/entrée/vente representée par une string
 * @returns {string} chaine humainement comprehensible.
 */
function classeToName(classe) {
  switch (classe) {
  case 'collecte': return 'Collecte';
  case 'ventes': return 'Vente';
  case 'sortiesr': return 'Sortie recycleur';
  case 'sorties': return 'Sortie don';
  case 'sortiesc': return 'Sortie partenaire';
  case 'sortiesp': return 'Sortie poubelles';
  case 'sortiesd': return 'Sortie décheterie';
  default: throw Error('Classe invalide');
  }
}

/**
 * Code de gestion du login
 */
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
    fetch('../moteur/login_post.php', {
      method: 'POST',
      credidentials: 'include',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json; charset=utf-8',
      },
      body: JSON.stringify({ username, password }),
    }).then(status)
      .then((_) => {
        container.setAttribute('style', 'visibility: hidden;  opacity: 0;');
        if (onSuccess) {
          onSuccess();
        }
      }).catch((ex) => {
        console.log('Error:', ex);
      });
  }, false);
}

/**
 * Dans cette fonction on traite le status code de la réponse donnée par le serveur.
 * entre 200 et 300 on estime que c'est un succèes.
 *
 * Si le status code vaut 401, c'est une erreur.
 * Sinon c'est un warning non critique.
 *
 * <https://en.wikipedia.org/wiki/List_of_HTTP_status_codes>
 *
 * @throws - Si on rencontre une erreur critique on lève une exception...
 * Note Axel: Je suis pas vraiment sur qu'on la récupére pour l'instant ;)
 *
 * @param response {object} - Réponse du serveur
 * @return {Object} - Le corps JSON de la réponse ou la réponse brute si erreur non fatale.
 *   Sinon un leve une exeception
 */
function status(response) {
  if (response.status >= 200
    && response.status < 300) {
    return response.json();
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

/**
 *  * TODO: Vrai gestion de la reponse... (future mise en attente...)
 * See: <https://github.com/github/fetch>
 *
 * Effecture l'envoi des données au format JSON en utf-8 en POST nos données,
 *   Et applique des post-traitement défini par les fonctions onFinalise et OnImpress.
 *
 * @param {url} - Url ou envoyer nos données
 * @param {Function} - Fonction à appeller pour obtenir nos données,
 *  Cette cloture est construite par `prepare_data()`.
 * @param {Function} - OnFinalise clotûre appellée après avoir soumis les données et obtenu une
 *  réponse
 * @param {Function} - OnImpress, par defaut ne fait rien, sinon effecture l'impression du ticket.
 * @return {Function} - Clotûre qui sera appellée une fois l'encaissement prêt a l'envoi.
*/
function post_data(url, getData, onFinalise, onImpress = (_, a) => a) {
  return () => {
    const data = getData();
    if (Object.keys(data).length > 0) {
      fetch(url, {
        credentials: 'include',
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json; charset=utf-8',
        },
        body: JSON.stringify(data),
      }).then(status)
        .then((response) => onImpress(data, response))
        .then((response) => onFinalise(data, response))
        .catch((ex) => {
          if (ex.status === 401) {
            login(() => {
              post_data(url, data, onSuccess);
            });
          } else {
            console.log('Error:', ex);
          }
        });
    }
  };
}

/**
 * Fonction permetant d'avoir un traitement générique dans encaisse() des différentes
 * stratégies de vérification d'une entrée utilisateur via le formulaire.
 *
 * @param {string} classe de sortie ou collecte
 * @return {Function} Clotûre permetant de valider certains champs d'un encaissement/enregistement
 *  Cette clotûre sera du type Objet -> boolean.
 */
function strategie_validation({ classe }) {
  if (classe === 'sortiesp' || classe === 'sortiesd') {
    return (_) => true;
  } else if (classe === 'collecte') {
    return (obj) => obj.id_type_action > 0 && obj.localite > 0;
  } else {
    return (obj) => obj.id_type_action > 0;
  }
}

/**
 * On retourne une closure qui correspond a un encaissement valide.
 * On fait ca pour pouvoir gerer les differents types de retours vu que les ID
 * Sont relatives... Par exemple dans les sorties de dechets et d'objet.
 *
 * @param {String}    - Url de la requete post.
 * @param {Objects}   - Tickets Mixin de tickets la clef servira a les reconnaitre cote serveur.
 * @return {Function} - Closure capable de gérer un encaissement.
 */
function prepare_data(tickets, metadata) {
  const test = strategie_validation(metadata);
  return () => {
    // On recupere les sommes de differents tickets.
    const sum = Object.values(tickets).reduce((acc, t) => acc + t.size, 0.0);
    const form = new FormData(document.getElementById('formulaire'));
    const formdata = {
      localite: parseInt(form.get('localite'), 10),
      id_type_action: parseInt(form.get('id_type_action'), 10),
    };

    if (sum > 0 && test(formdata)) {
      const commentaire = form.get('commentaire').trim(); // On enleve les espaces inutiles.
      const date = form.get('date');
      const data = {
        id_point: window.OressourceEnv.id_point,
        id_user: window.OressourceEnv.id_user,
        commentaire,
        date,
      };
      // Petit hack pour merger differents types d'objets a envoyer.
      const items = Object.entries(tickets).reduce((acc, [ type, ticket ]) => ({
        ...acc,
        ...{ [type]: ticket.to_array() },
      }), {});

      return {
        ...data, ...formdata, ...items, ...metadata,
      };
    } else {
      return {};
    }
  };
}

/*
 * Code de gestion de l'impression
 */

const dashBreak = '<p>-----------------------------------</p>';

/**
 * Fonction qui permet d'afficher les différents types de Tickets dans le cas des pesées qui
 * gérent deux types de sortie en une.
 *
 * @param data {Object} - Content au moins un ticket items ou evacs valide.
 * @return     {String} - Chaine contenant les différents types de dechet d'un Ticket.
 * utilise les variables globales:
 * - window.OressourceEnv.types_evac
 * - window.OressourceEnv.types_dechet
 */
function showTickets(data) {
  const item = (data.hasOwnProperty('items') && data.items.length > 0
    ? `<p>Ventes</p>${dashBreak}${showTicket(data.items, window.OressourceEnv.types_dechet)}`
    : '');
  return item + (data.hasOwnProperty('evacs') && data.evacs.length > 0
    ? `<p>Matériaux</p>${dashBreak}${showTicket(data.evacs, window.OressourceEnv.types_evac)}`
    : '');
}

/**
 * Cette fonction construit le corps du ticket de caisse pour un type particulier (evac ou items)
 * On itére sur l'ensemble des données pour avoir un <p/> par entree dans notre ticket.
 *
 * @param data  {Ticket} - Le ticket que l'on désire ensuite afficher
 * @param types {object} - types possibles de tickets (evac, items)
 */
function showTicket(data, types) {
  // Hack on ajoute les noms a la volées pour les sorties/collectes.
  const newData = data.map((item) => {
    if (item.hasOwnProperty('name')) {
      return item;
    } else {
      const name = types.find(({ id }) => item.type === id).nom;
      return { ...item, name };
    }
  });

  return newData.map((item) => item.show()).join('');
}

/**
 * Renvoie le nom representable pour un humain associé a un id de moyen de paiement.
 * @param {Array<Object>} moyens de paiement disponnible données issues de la DB.
 * @param {number} moyenId moyenId represente un id valide de moyen de paiement.
 * @returns {string} Nom du moyen de paiement ou "Moyen de paiement inconnu!"
 */
const showMoyen = (moyens, moyenId) => (
  moyens.find(({id}) => id === moyenId).nom || "moyen de paiement inconnu!"
);

/**
 * Represente un moyen de paiement comme une chaine de caractère.
 * pour l'interface des tickets de caisse.
 * @param {number} moyenId represente un id valide de moyen de paiement.
 * @returns {string} chaine formatté HTML decrivant le moyen de paiment ou rien.
 */
const showPaiement = (moyenId) => {
  const moyens = window.OressourceEnv.moyens_paiement || null;
  return (moyens ? `<p>Payé en ${showMoyen(moyens, moyenId)}</p>` : '');
};

/**
 * Cette fonction construit une <IFrame/> et l'imprime et l'iframe est détruite
 * après impression.
 *
 * @param data { Ticket } - Un ticket de sortie ou entrée valide.
 * @param response { object } - La réponse du serveur.
 *
 * La fonction utilise les variables globales suivantes:
 * @globals window.OressourceEnv.structure
 * @globals window.OressourceEnv.adresse
 * @global moment bibliothèque d'internationnalisation des dates
 */
function impressionTicket(data, response, tvaStuff = () => '') {
  const title = classeToName(data.classe);
  // Hack affreux pour gérer les ventes car on utilise pas un objet si global dans leur gestion.
  const html = `
    <head>
    <meta charset="utf-8">
    <title>Ticket ${title} &#x2116;${response.id}</title>
    <link rel="stylesheet" href="../css/ticket_impression.css" type="text/css">
    </head>
    <body>
      <p>${document.querySelector('h1').innerHTML}</p>
      <p>${window.OressourceEnv.structure}</p>
      <p>${window.OressourceEnv.adresse}</p>
      ${dashBreak}
      <p>Type: ${classeToName(data.classe)} &#x2116;${response.id}</p>
      ${tvaStuff()}
      ${showPaiement(data.id_moyen)}
      <p>Ticket client à conserver.</p>
      <p>Date d'édition du ticket : ${moment().format('DD/MM/YYYY - HH:mm')}</p>
      ${showTickets(data)}
    </body>
  </html>`;

  const iframe = document.createElement('iframe');
  function closePrint() {
    document.body.removeChild(this.__container__);
  }

  iframe.onload = function () {
    this.contentWindow.__container__ = this;
    this.contentWindow.onbeforeunload = closePrint;
    this.contentWindow.onafterprint = closePrint;
    this.contentWindow.focus();
    this.contentWindow.print();
  };
  iframe.style.visibility = 'hidden';
  iframe.style.position = 'fixed';
  iframe.style.right = 0;
  iframe.style.bottom = 0;
  iframe.src = 'about:blank';
  iframe.srcdoc = html;
  document.body.appendChild(iframe);
  return response;
}
