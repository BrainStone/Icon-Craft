function loadCSS() {
  var loadCSS = window.document.getElementById("loadCSS");

  if(loadCSS)
    loadCSS.outerHTML = loadCSS.innerHTML;
}

window.addEventListener("load", loadCSS, false);