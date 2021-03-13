import "./js/ajax.js";
import "./js/burger-menu.js";
import "./js/tabs.js";

import "./scss/styles.scss";

function requireAll(r) {
    r.keys().forEach(r);
  }
  
  requireAll(require.context('./imgs/', true, /\.svg$/));