import "./js/main.js";
import "./scss/styles.scss";


function requireAll(r) {
    r.keys().forEach(r);
  }
  
  requireAll(require.context('./imgs/', true, /\.svg$/));