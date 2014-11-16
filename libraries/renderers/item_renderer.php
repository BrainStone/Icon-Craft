<?php
  require_once("common_renderer.php");
  
  $size = 2048;
  
  function render_item($texture) {
    global $size;
  
    $im = imagecreatetruecolor($size, $size);
    imagealphablending($im, true);
    imagesavealpha($im, true);
    
    $texture = load_png($texture);
    
    imagecopyresized($im, $texture, 0, 0, 0, 0, $size, $size, imagesx($texture), imagesy($texture));
    
    return $im;
  }
?>