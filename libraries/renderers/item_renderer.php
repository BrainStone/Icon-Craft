<?php
  require_once("common_renderer.php");
  
  function render_item($texture, $size) {
    $im = imagecreatetruecolor($size, $size);
    imagealphablending($im, false);
    imagesavealpha($im, true);
    
    $texture = load_png($texture);
    
    imagecopy($im, $texture, 0, 0, 0, 0, $size, $size, imagesx($texture), imagesy($texture));
    
    return $im;
  }
?>