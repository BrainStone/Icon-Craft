<?php
  require_once("common_renderer.php");
  
  $size = 2048;
  
  function render_block($left_side, $top_side, $right_side) {  
    global $size;
    
    $x1 = (2 - sqrt(3)) * 0.25 * $size;
    $x2 = 0.5 * $size;
    $x3 = (2 + sqrt(3)) * 0.25 * $size;
    
    $y1 = 0;
    $y2 = 0.25 * $size;
    $y3 = 0.5 * $size;
    $y4 = 0.75 * $size;
    $y5 = $size;
    
    $first_poligon = array(
                $x1, $y2,
                $x2, $y3,
                $x2, $y5,
                $x1, $y4,
                );
    $second_poligon = array(
                $x1, $y2,
                $x2, $y1,
                $x3, $y2,
                $x2, $y3,
                );          
    $third_poligon = array(
                $x2, $y3,
                $x3, $y2,
                $x3, $y4,
                $x2, $y5,
                );          
    
    $im = imagecreatetruecolor($size, $size);
    
    // Transparentbackground
    imagealphablending($im, false);
    imagesavealpha($im, true);
    $trans = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefill($im, 0, 0, $trans);
    
    $white = imagecolorallocate($im, 255, 255, 255);
    $red = imagecolorallocate($im, 255, 0, 0);
    
    imagetranslatedtexture($im, $first_poligon, imagelight(load_png($left_side), 42));
    imagetranslatedtexture($im, $second_poligon, load_png($top_side));
    imagetranslatedtexture($im, $third_poligon, imagelight(load_png($right_side), 84));
    
    return $im;
  }
?>