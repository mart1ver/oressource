"use strict";

function verif_form_sortie() {
  if (parseInt(document.getElementById('najout').value) >= 1) {
    document.getElementById("formulaire").submit();
  }
}

function submanut(x) {
  if ((document.getElementById("number").value - x) > 0 ) {
    var text_box = document.getElementById("number");
    text_box.value = (text_box.value - x).toFixed(2);
  }
}

function number_write(x) {
  var text_box = document.getElementById("number");
  text_box.value = text_box.value + x;
}

function number_clear() {
  document.getElementById("number").value = "";
}

function tdechet_write(nom, id, poids_max) {
  if (document.getElementById("number").value > 0
    && document.getElementById("number").value < poids_max) {
    document.getElementById("massetot").textContent = parseFloat(document.getElementById("massetot").textContent) + parseFloat(document.getElementById("number").value) ;
    document.getElementById(nom).textContent = (parseFloat(document.getElementById(nom).textContent) + parseFloat(document.getElementById("number").value)).toFixed(2)  ;
    document.getElementById(id).value = parseFloat(document.getElementById(id).value) + parseFloat(document.getElementById("number").value)  ;
    document.getElementById("number").value = "";
    document.getElementById("najout").value = parseInt(document.getElementById("najout").value) + 1;
  }
}

function recocom() {
  document.getElementById("commentaire").value =
    document.getElementById("commentaireini").value;
}

function tdechet_add(pesee_max) {
  if (document.getElementById("number").value > 0
  && document.getElementById("number").value < pesee_max) {
  document.getElementById("najout").value = parseInt(document.getElementById("najout").value)+1;
  var ref = document.getElementById("sel_filiere").value;
  var tabref = ref.split('|');
  var id_filiere = document.getElementById("id_filiere");

  id_filiere.value = tabref[0];
  var id_type_dechet = document.getElementById("id_type_dechet");
  id_type_dechet.value = tabref[1];

  var type_dechet = document.getElementById("type_dechet");
  type_dechet.value = tabref[2];

  document.getElementById("sel_filiere").disabled = true;
  document.getElementById(tabref[1]).textContent = parseFloat(document.getElementById(tabref[1]).textContent) + parseFloat(document.getElementById("number").value);
  document.getElementById("m"+tabref[1]).value = parseFloat(document.getElementById("m"+tabref[1]).value) + parseFloat(document.getElementById("number").value)  ;
  document.getElementById("massetot").textContent = parseFloat(document.getElementById("massetot").textContent) + parseFloat(document.getElementById("number").value) ;
  document.getElementById("number").value = "";
  }
}
