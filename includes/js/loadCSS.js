var alreadyLoadedCSS = false;

function loadCSS() {
  if(alreadyLoadedCSS)
    return;

  var head = window.document.getElementsByTagName("head")[0];
  var loadCSS = window.document.getElementById("loadCSS");

  if(head && loadCSS) {
     head.innerHTML += loadCSS.innerHTML;

     alreadyLoadedCSS = true;
  }
}

setTimeout( loadCSS );