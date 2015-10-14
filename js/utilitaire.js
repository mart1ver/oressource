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
  const input = document.getElementById("number");
  input.value = "";
  input.setCustomValidity("");
}

/*
 * nom: HTML <span>
 * id: HTML <input>
 * masse_max: float
 * masse_bac: float
 */

function masse_write(nom, id, masse_max, masse_bac) {
  const input = document.getElementById("number");
  const number_value = parseFloat(input.value);
  const masse_reele = number_value - masse_bac;

  if (masse_reele > 0.00) {
    if (number_value <= masse_max) {
      const masseTotale = document.getElementById("massetot");
      masseTotale.textContent = (parseFloat(masseTotale.textContent) + masse_reele).toFixed(2);

      id.value = (parseFloat(id.value) + masse_reele).toFixed(2);
      nom.textContent = (parseFloat(nom.textContent) + masse_reele).toFixed(2);
      input.value = "";

      const nAjout = document.getElementById("najout");
      nAjout.value = parseInt(nAjout.value) + 1;

      number_clear();
    } else {
      input.setCustomValidity("Masse supérieure aux limites de pesée de la balance.");
    }
  } else {
      input.setCustomValidity("Masse entrée inférieure au poids du conteneur ou inférieure ou égale à 0.");
  }
}

function recocom() {
  document.getElementById("commentaire").value =
    document.getElementById("commentaireini").value;
}

function tdechet_add(pesee_max) {
  const ref = document.getElementById("sel_filiere");
  const tabref = ref.value.split('|');

  const id_filiere = document.getElementById("id_filiere");
  id_filiere.value = tabref[0];

  const id_type_dechet = document.getElementById("id_type_dechet");
  id_type_dechet.value = tabref[1];

  const type_dechet = document.getElementById("type_dechet");
  type_dechet.value = tabref[2];

  ref.disabled = true;
  masse_write(document.getElementById(tabref[1]), document.getElementById("m"+tabref[1]), pesee_max, 0.0);
}

function parse_current_url() {
  return parse_url(window.location.search.slice(1));
}

function parse_url(url) {
  return decodeURIComponent(url)
    .split('&')
    .reduce(function (a, b) {
      b = b.split('=');
      a[b[0]] = b[1];
      return a;
    }, {});
}

function make_graph(id, data, colors, unite) {
  Morris.Donut({
    element: id,
    data: data,
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: colors,
    formatter: function (x) { return x + unite + '.'; }
  });
}
