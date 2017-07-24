"use strict";

var what = "";

function rendu() {
  if(document.getElementById('rendub').value > 0) {
    document.getElementById('renduc').value = document.getElementById('rendub').value - document.getElementById('rendua').value;
  }
}

function fokus(that) {
  what = that;
}

function moyens(moy) {
  document.getElementById('moyen').value = moy;
}

function often(that) {
  if (document.getElementById('ptot').value > 0 && isNaN(parseInt(document.getElementById('id_type_objet').value))){
    what.value += that.value;
    document.getElementById('quantite').value ="";
    document.getElementById('prix').value ="";
    if (that.value == "c") { what.value = ""; }
    if (document.getElementById('rendub').value > 0) {
      document.getElementById('renduc').value = document.getElementById('rendub').value -  document.getElementById('rendua').value;
    }
  }
  if (isNaN(parseInt(document.getElementById('id_type_objet').value))) {
  } else {
    if (that == null) {
      document.getElementById('quantite').value ="" ; what = document.getElementById('quantite');
    }
    if (that.value == "c") { what.value = ""; }
    else { what.value += that.value; }
  }
}

function often_stats(that) {
  if (document.getElementById('mtot').value > 0 && isNaN(parseInt(document.getElementById('id_type_objet').value))){
    what.value += that.value;
    document.getElementById('quantite').value ="";
        if (that.value == "c") { what.value = ""; }

  }
  if (isNaN(parseInt(document.getElementById('id_type_objet').value))) {
  } else {
    if (that == null) {
      document.getElementById('quantite').value ="" ; what = document.getElementById('quantite');
    }
    if (that.value == "c") { what.value = ""; }
    else { what.value += that.value; }
  }
}

function suprime(nsligne) {
  if (parseInt(document.getElementById('nlignes').value) > 1) {
    var numero_ligne = nsligne.substr(5); // sous_chaine = le numero uniquement
    document.getElementById('narticles').value = parseInt(document.getElementById('narticles').value) - parseInt(document.getElementById('tquantite'+numero_ligne).value);
    document.getElementById('ptot').value = parseFloat(document.getElementById('ptot').value) - (parseFloat(document.getElementById('tprix'+numero_ligne).value)*parseFloat(document.getElementById('tquantite'+numero_ligne).value));
    document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2)+' €';
    document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('ptot').value).toFixed(2)+'€</span></li>';
    document.getElementById('tquantite'+numero_ligne).value= "0";
    document.getElementById('tprix'+numero_ligne).value= "0";
    document.getElementById('nlignes').value = parseInt(document.getElementById('nlignes').value) - 1;
    document.getElementById(nsligne).remove();
    document.getElementById('rendua').value = document.getElementById('ptot').value;
  } else {
    window.location.reload();
  }
}

function switchlot(state) {
  function lot_or_unite(type, label, prix_string, masse_string, bg_color) {
    document.getElementById('sul').value = type;
    document.getElementById('labellot').innerHTML = label;
    document.getElementById('labelpul').innerHTML = prix_string;
    document.getElementById('panelcalc').style.backgroundColor = bg_color;
    if(document.getElementById('masse') )
    {
     document.getElementById('labelmasse').innerHTML = masse_string;
    }
  }
  if (state == false) {
    lot_or_unite("lot", "vente au: ", "Prix du lot: ", "Masse du lot: ", "#E8E6BC");
  } else {
    lot_or_unite("unite", "vente à: ", "Prix unitaire:", "Masse unitaire: " , "white");
  }
}

function switchlot_stats(state) {
  function lot_or_unite(type, label, masse_string, bg_color) {
    document.getElementById('sul').value = type;
    document.getElementById('labellot').innerHTML = label;
    document.getElementById('panelcalc').style.backgroundColor = bg_color;
    if(document.getElementById('masse') )
    {
     document.getElementById('labelmasse').innerHTML = masse_string;
    }
  }
  if (state == false) {
    lot_or_unite("lot", "Pesée au: ",  "Masse du lot: ", "#E8E6BC");
  } else {
    lot_or_unite("unite", "Pesée à: ",  "Masse unitaire: " , "white");
  }
}

function ajout() {
  if (document.getElementById('id_type_objet').value == ""){}else{

  if (document.getElementById('sul').value == "unite") {
    var prixtemp = document.getElementById('prix').value;
    prixtemp = prixtemp.replace(",", ".");
    document.getElementById('prix').value = prixtemp;

   if(document.getElementById('masse') )
          {
    var massetemp = document.getElementById('masse').value;
    massetemp = massetemp.replace(",", ".");
    if (force_pes_vente == "oui" && isNaN(parseFloat(massetemp))) {return;}
    if (isNaN(parseFloat(massetemp))) {document.getElementById('masse').value = "";}else{
    document.getElementById('masse').value = parseFloat(massetemp);
  }


          }

    if (isNaN((parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2))) {
    } else {
      if (isNaN(parseInt(document.getElementById('nlignes').value))) {
        document.getElementById('nlignes').value = 1;
      } else {
        document.getElementById('nlignes').value=parseInt(document.getElementById('nlignes').value)+ 1;
      }

      if (isNaN(parseInt(document.getElementById('narticles').value))) {
        document.getElementById('narticles').value = document.getElementById('quantite').value;
      } else {
        document.getElementById('narticles').value=parseInt(document.getElementById('narticles').value)+parseInt(document.getElementById('quantite').value);
      }

      if (isNaN(parseInt(document.getElementById('ptot').value))) {
        document.getElementById('ptot').value = document.getElementById('prix').value*document.getElementById('quantite').value;
      } else {
        document.getElementById('ptot').value=parseFloat(document.getElementById('ptot').value)+parseFloat(document.getElementById('prix').value*document.getElementById('quantite').value);
        document.getElementById('rendua').value = document.getElementById('ptot').value
      }
        if(document.getElementById('masse') )
          {
            var fait = "non" ;
            if (isNaN(parseInt(document.getElementById('masse').value))) {

            document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+parseFloat(parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2)+'€'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
        +'<input type="hidden"  id="tprix'+parseInt(document.getElementById('nlignes').value)+'" name="tprix'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('prix').value+'"></li>';
        document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('ptot').value).toFixed(2)+'€</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2)+' €';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('prix').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      fait = "oui";
            }
      if(fait == "non"){
      document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+parseFloat(parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2)+'€'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value+", "+parseFloat(document.getElementById('masse').value*parseFloat(document.getElementById('quantite').value))+" Kgs."
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
        +'<input type="hidden"  id="tprix'+parseInt(document.getElementById('nlignes').value)+'" name="tprix'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('prix').value+'">'
        +'<input type="hidden"  id="tmasse'+parseInt(document.getElementById('nlignes').value)+'" name="tmasse'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('masse').value+'"></li>';
      document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('ptot').value).toFixed(2)+'€</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2)+' €';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('prix').value = "";
      document.getElementById('masse').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      fait = "oui";}
         }else
         {

          document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+parseFloat(parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2)+'€'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
        +'<input type="hidden"  id="tprix'+parseInt(document.getElementById('nlignes').value)+'" name="tprix'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('prix').value+'"></li>';
        document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('ptot').value).toFixed(2)+'€</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2)+' €';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('prix').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      }
    }
  } else {
    var prixtemp = document.getElementById('prix').value;

    prixtemp = prixtemp.replace(",", ".");
    document.getElementById('prix').value = prixtemp;
    if(document.getElementById('masse') )
          {
    var massetemp = document.getElementById('masse').value;
    massetemp = massetemp.replace(",", ".");
    if (force_pes_vente == "oui" && isNaN(parseFloat(massetemp))) {return;}
    if (isNaN(parseFloat(massetemp))) {document.getElementById('masse').value = "";}else{
    document.getElementById('masse').value = parseFloat(massetemp);
  }
          }

    if (isNaN((parseFloat(document.getElementById('prix').value)*parseFloat(document.getElementById('quantite').value)).toFixed(2))) {
    } else {
      if (isNaN(parseInt(document.getElementById('nlignes').value))) {
        document.getElementById('nlignes').value = 1;
      } else {
        document.getElementById('nlignes').value=parseInt(document.getElementById('nlignes').value)+ 1;
      }

      if (isNaN(parseInt(document.getElementById('narticles').value))) {
        document.getElementById('narticles').value = document.getElementById('quantite').value;
      } else {
        document.getElementById('narticles').value=parseInt(document.getElementById('narticles').value)+parseInt(document.getElementById('quantite').value);
      }

      if (isNaN(parseInt(document.getElementById('ptot').value))) {
        document.getElementById('ptot').value = document.getElementById('prix').value;
        document.getElementById('rendua').value = document.getElementById('ptot').value ;
      } else {
        document.getElementById('ptot').value=parseFloat(document.getElementById('ptot').value)+parseFloat(document.getElementById('prix').value);
      }
      if(document.getElementById('masse'))
          {
            var fait = "non" ;
            if (isNaN(parseInt(document.getElementById('masse').value))) {

              document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+parseFloat(document.getElementById('prix').value).toFixed(2)+'€'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
        +'<input type="hidden"  id="tprix'+parseInt(document.getElementById('nlignes').value)+'" name="tprix'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('prix').value/document.getElementById('quantite').value+'"></li>';
      document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('ptot').value).toFixed(2)+'€</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2)+' €';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('prix').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      fait = "oui";
            }
      if(fait == "non"){
      document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+parseFloat(document.getElementById('prix').value).toFixed(2)+'€'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value+", "+parseFloat(document.getElementById('masse').value)+" Kgs."
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
        +'<input type="hidden"  id="tprix'+parseInt(document.getElementById('nlignes').value)+'" name="tprix'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('prix').value/document.getElementById('quantite').value+'">'
        +'<input type="hidden"  id="tmasse'+parseInt(document.getElementById('nlignes').value)+'" name="tmasse'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('masse').value+'"></li>';
      document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('ptot').value).toFixed(2)+'€</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2)+' €';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('prix').value = "";
      document.getElementById('masse').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      fait = "oui"}
         }else
         {

          document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+parseFloat(document.getElementById('prix').value).toFixed(2)+'€'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
        +'<input type="hidden"  id="tprix'+parseInt(document.getElementById('nlignes').value)+'" name="tprix'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('prix').value/document.getElementById('quantite').value+'"></li>';
      document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('ptot').value).toFixed(2)+'€</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2)+' €';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('prix').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
         }
    }
  }

  if (document.getElementById('rendub').value - document.getElementById('rendua').value > 0) {
    document.getElementById('renduc').value = document.getElementById('rendub').value - document.getElementById('rendua').value;
  }
  document.getElementById('rendua').value = document.getElementById('ptot').value ;


}

}



function ajout_stats() {
  if (document.getElementById('id_type_objet').value == ""){}else{

  if (document.getElementById('sul').value == "unite") {


   if(document.getElementById('masse') )
          {
    var massetemp = document.getElementById('masse').value;
    massetemp = massetemp.replace(",", ".");
    if (force_pes_vente == "oui" && isNaN(parseFloat(massetemp))) {return;}
    if (isNaN(parseFloat(massetemp))) {document.getElementById('masse').value = "";}else{
    document.getElementById('masse').value = parseFloat(massetemp);
  }


          }

    if (1 == 2 ) {
    } else {
      if (isNaN(parseInt(document.getElementById('nlignes').value))) {
        document.getElementById('nlignes').value = 1;
      } else {
        document.getElementById('nlignes').value=parseInt(document.getElementById('nlignes').value)+ 1;
      }

      if (isNaN(parseInt(document.getElementById('narticles').value))) {
        document.getElementById('narticles').value = document.getElementById('quantite').value;
      } else {
        document.getElementById('narticles').value=parseInt(document.getElementById('narticles').value)+parseInt(document.getElementById('quantite').value);
      }

      if (isNaN(parseInt(document.getElementById('mtot').value))) {

      } else {
        document.getElementById('mtot').value=parseFloat(document.getElementById('mtot').value)+parseFloat(document.getElementById('masse').value*document.getElementById('quantite').value);

      }
        if(document.getElementById('masse') )
          {
            var fait = "non" ;
            if (isNaN(parseInt(document.getElementById('masse').value))) {

            document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">';
        document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      fait = "oui";
            }
      if(fait == "non"){
      document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value+", "+parseFloat(document.getElementById('masse').value*parseFloat(document.getElementById('quantite').value))+" Kgs."
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
        +'<input type="hidden"  id="tmasse'+parseInt(document.getElementById('nlignes').value)+'" name="tmasse'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('masse').value+'"></li>';
      document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('masse').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      fait = "oui";}
         }else
         {

          document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">';
        document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      }
    }
  } else {
    if(document.getElementById('masse') )
          {
    var massetemp = document.getElementById('masse').value;
    massetemp = massetemp.replace(",", ".");
    if (force_pes_vente == "oui" && isNaN(parseFloat(massetemp))) {return;}
    if (isNaN(parseFloat(massetemp))) {document.getElementById('masse').value = "";}else{
    document.getElementById('masse').value = parseFloat(massetemp);
  }
          }

    if (1 == 2) {
    } else {
      if (isNaN(parseInt(document.getElementById('nlignes').value))) {
        document.getElementById('nlignes').value = 1;
      } else {
        document.getElementById('nlignes').value=parseInt(document.getElementById('nlignes').value)+ 1;
      }

      if (isNaN(parseInt(document.getElementById('narticles').value))) {
        document.getElementById('narticles').value = document.getElementById('quantite').value;
      } else {
        document.getElementById('narticles').value=parseInt(document.getElementById('narticles').value)+parseInt(document.getElementById('quantite').value);
      }

      if (isNaN(parseInt(document.getElementById('mtot').value))) {
        document.getElementById('mtot').value = document.getElementById('masse').value;

      } else {
        document.getElementById('mtot').value = parseFloat(document.getElementById('mtot').value) + parseFloat(document.getElementById('masse').value);
      }
      if(document.getElementById('masse'))
          {
            var fait = "non" ;
            if (isNaN(parseInt(document.getElementById('masse').value))) {

              document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">';
      document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      fait = "oui";
            }
      if(fait == "non"){
      document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value+", "+parseFloat(document.getElementById('masse').value)+" Kgs."
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">'
        +'<input type="hidden"  id="tmasse'+parseInt(document.getElementById('nlignes').value)+'" name="tmasse'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('masse').value+'"></li>';
      document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('masse').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
      fait = "oui"}
         }else
         {

          document.getElementById('liste').innerHTML += '<li class="list-group-item" name="ligne'+parseInt(document.getElementById('nlignes').value)+'" id="ligne'+parseInt(document.getElementById('nlignes').value)+'"><span class="badge">'+'</span><span class="glyphicon glyphicon-remove" aria-hidden="true"    onclick="javascirpt:suprime('+"'ligne"+parseInt(document.getElementById('nlignes').value)+"');"+'"></span>&nbsp;&nbsp;'+document.getElementById('quantite').value+' * '+document.getElementById('nom_objet0').value
        +'<input type="hidden"  id="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_type_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_type_objet').value+'">'
        +'<input type="hidden"  id="tid_objet'+parseInt(document.getElementById('nlignes').value)+'" name="tid_objet'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('id_objet').value+'">'
        +'<input type="hidden"  id="tquantite'+parseInt(document.getElementById('nlignes').value)+'" name="tquantite'+parseInt(document.getElementById('nlignes').value)+'"value="'+document.getElementById('quantite').value+'">';
      document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : '+document.getElementById('narticles').value+' article(s) pour : <span class="badge" style="float:right;">'+parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.</span></li>';
      document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('mtot').value).toFixed(2)+' Kgs.';
      document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
      document.getElementById('quantite').value = "";
      document.getElementById('id_type_objet').value = "";
      document.getElementById('id_objet').value = "";
      document.getElementById('nom_objet0').value = "";
         }
    }
  }



}

}







function edite(nom,prix,id_type_objet,id_objet) {
  document.getElementById('nom_objet').innerHTML = "<label>"+nom+"</label>";
  document.getElementById('quantite').value = "1";
  document.getElementById('prix').value = parseFloat(prix);
  document.getElementById('id_type_objet').value = parseFloat(id_type_objet);
  document.getElementById('id_objet').value = parseFloat(id_objet);
  document.getElementById('nom_objet0').value = nom;
}
function edite_stats(nom,id_type_objet,id_objet) {
  document.getElementById('nom_objet').innerHTML = "<label>"+nom+"</label>";
  document.getElementById('quantite').value = "1";
  document.getElementById('id_type_objet').value = parseFloat(id_type_objet);
  document.getElementById('id_objet').value = parseFloat(id_objet);
  document.getElementById('nom_objet0').value = nom;
  document.getElementById("masse").focus();
}

function encaisse() {
  if ((parseInt(document.getElementById('nlignes').value) >= 1)
      && ((document.getElementById('quantite').value == "")
        || (document.getElementById('quantite').value == "0"))
      && ((document.getElementById('prix').value == "")
        || (document.getElementById('prix').value == "0"))) {
    document.getElementById('comm').value = document.getElementById('commentaire').value;
    document.getElementById("formulaire").submit();
  }
}


function encaisse_stats() {
  if ((parseInt(document.getElementById('nlignes').value) >= 1)
      && ((document.getElementById('quantite').value == "")
        || (document.getElementById('quantite').value == "0"))
      && ((document.getElementById('masse').value == "")
        || (document.getElementById('masse').value == "0"))) {
    document.getElementById('comm').value = document.getElementById('commentaire').value;
    document.getElementById("formulaire").submit();
  }
}
