"use strict";

function verif_form_sortie() {
  if (parseInt(document.getElementById('najout').value) >= 1) {
    document.getElementById("formulaire").submit();
  }
}

function submanut(x) {
  var number = document.getElementById("number");

  if ((number.value - x) > 0 ) {
    number.value = (number.value - x).toFixed(2);
  }
}

function number_write(x) {
  var text_box = document.getElementById("number");
  text_box.value = text_box.value + x;
}

function number_clear() {
  document.getElementById("number").value = "";
}

// FIX: changer les parametres pour que cette fonction reçoive des elements DOM
// et non des ID.
function tdechet_write(nom, id, masse_max) {
  const number = document.getElementById("number");
  const number_value = parseFloat(number.value);

  if (number_value > 0
      && number_value < masse_max) {
    const masseTotale = document.getElementById("massetot");
    masseTotale.textContent = (parseFloat(masseTotale.textContent) + number_value).toFixed(2);

    var nom = document.getElementById(nom);
    nom.textContent = (parseFloat(nom.textContent) + number_value).toFixed(2);

    var id = document.getElementById(id);
    id.value = (parseFloat(id.value) + number_value).toFixed(2);

    number.value = "";
    const nAjout = document.getElementById("najout");
    nAjout.value = parseInt(nAjout.value) + 1;
  }
}

/*
 * nom: HTML <span>
 * id: HTML <input>
 * masse_max: float
 * masse_bac: float
 */
function tdechet_write_poubelle(nom, id, masse_max, masse_bac) {
  const number = document.getElementById("number");
  const number_value = parseFloat(number.value);
  const new_number = number_value - masse_bac;

  if (new_number > 0.0
      && number_value <= masse_max) {
    const masseTotale = document.getElementById("massetot");
    masseTotale.textContent = (parseFloat(masseTotale.textContent) + new_number).toFixed(2);

    id.value = (parseFloat(id.value) + new_number).toFixed(2);
    nom.textContent = (parseFloat(nom.textContent) + new_number).toFixed(2);
    number.value = "";

    const nAjout = document.getElementById("najout");
    nAjout.value = parseInt(nAjout.value) + 1;
  }
}

function recocom() {
  document.getElementById("commentaire").value =
    document.getElementById("commentaireini").value;
}

function tdechet_add(pesee_max) {
  const number = document.getElementById('number');
  const n = parseFloat(number.value);

  if (n > 0
      && n < pesee_max) {
    const nAjout = document.getElementById('najout');
    nAjout.value = parseInt(nAjout.value) + 1;

    const ref = document.getElementById("sel_filiere");
    const tabref = ref.value.split('|');

    const id_filiere = document.getElementById("id_filiere");
    id_filiere.value = tabref[0];

    const id_type_dechet = document.getElementById("id_type_dechet");
    id_type_dechet.value = tabref[1];

    const type_dechet = document.getElementById("type_dechet");
    type_dechet.value = tabref[2];

    ref.disabled = true;
    document.getElementById(tabref[1]).textContent = parseFloat(document.getElementById(tabref[1]).textContent) + n;
    document.getElementById("m"+tabref[1]).value = parseFloat(document.getElementById("m"+tabref[1]).value) + n;
    const masse_totale = document.getElementById("massetot");
    masse_totale.textContent = parseFloat(masse_totale.textContent) + n;
    number.value = "";
  }
}
