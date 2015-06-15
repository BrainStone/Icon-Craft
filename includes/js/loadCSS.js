function loadCSS() {
	var head = window.document.getElementsByTagName("head")[0];
	var loadCSS = window.document.getElementById("loadCSS");

	head.innerHTML += loadCSS.innerHTML;
	head.removeChild(loadCSS);
}

setTimeout( loadCSS );