"use strict";

// On fait un module via une fermeture Lexicale(closure).
function AJAX(method, url, callback) {
  return function() {
    const XHR = new window.XMLHttpRequest();

    XHR.open('GET', url , true);
    XHR.onload = function() {
      if (this.status >= 200 && this.status < 400) {
        const json = JSON.parse(this.responseText);
        callback(json)
      } else {
        // We reached our target server, but it returned an error
        console.log("Connection done: server returned Status: " + this.status);
      }
    }

    XHR.onerror = function(e) { console.log("Connection error" + e); };
    XHR.send();
  }
};

(function() {
  const url = window.location.origin + "/moteur/ajax_handler.php" + window.location.search;
  AJAX('GET', url + '&type=collect', function(json) {
    make_graph('graphmasse', json.data, json.colors, "Kg");
  })();
  AJAX('GET', url + '&type=trash', function(json) {
    make_graph('graph2masse', json.data, json.colors, "Kg");
  })();
  AJAX('GET', url + '&type=loca', function(json) {
    make_graph('graphloca', json.data, json.colors, 'Kg');
  })();
  AJAX('GET', url + '&type=loca', function(json) {
    make_graph('graph2loca', json.data, json.colors, 'Kg');
  })();
})();
