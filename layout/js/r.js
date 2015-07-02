// Async css
function loadCSS() {
  var loadCSS = window.document.getElementById("loadCSS");

  if(loadCSS)
    loadCSS.outerHTML = loadCSS.innerHTML;
}

window.addEventListener("load", loadCSS, false);


// Slider settings
jQuery(function($) {
  $('.slider').sss({
    slideShow : true, // Set to false to prevent SSS from automatically animating.
    startOn : 0, // Slide to display first. Uses array notation (0 = first slide).
    transition : 1000, // Length (in milliseconds) of the fade transition.
    speed : 7000, // Slideshow speed in milliseconds.
    showNav : true // Set to false to hide navigation arrows.
  });
});

// Scrolling
$(function(){
  $(window).scroll(function(){
    var winTop = $(window).scrollTop();
    if(winTop > 0){
      $("body").addClass("sticky-header");
    }else{
      $("body").removeClass("sticky-header");
    }
  });
});

$(document).ready(function() {
  $('a[href*=#]').bind("click", function(event) {
    event.preventDefault();
    var ziel = $(this).attr("href");

    $('html,body').animate({
      scrollTop: $(ziel).offset().top
    }, 1000 , function (){location.hash = ziel;});
  });
  return false;
});