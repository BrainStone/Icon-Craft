<?php
  function load_png($texture) {
    $texture = strtolower($texture);
    
    if(strpos(":", $texture) === false) {
      $modid = "minecraft";
    } else {
      list($modid, $texture) = explode(":", $texture);
    }
    
    // Attempt to open 
    $im = @imagecreatefrompng("../images/$modid/$texture.png");
    
    // See if it failed
    if(!$im)
    {
      // Create a blank image
      $im  = imagecreatetruecolor(2, 2);
      $tc  = imagecolorallocate($im, 255, 0, 255);
      
      imagesetpixel($im, 0, 0, $tc);
      imagesetpixel($im, 1, 1, $tc);
    }
    
    return $im;
  }
  
  function imagetranslatedtexture($im, $coords, $texture) {
    $size_x = imagesx($texture);
    $size_y = imagesy($texture);
    
    $dx1 = ($coords[2] - $coords[0]) / $size_x;
    $dy1 = ($coords[3] - $coords[1]) / $size_x;
    
    $dx2 = ($coords[6] - $coords[0]) / $size_y;
    $dy2 = ($coords[7] - $coords[1]) / $size_y;
    
    $xpos = $coords[0];
    
    for($x = 0; $x < $size_x; $x++) {
      $ypos = $coords[1] + ($x * $dy1);
      
      for($y = 0; $y < $size_y; $y++) {
        imagefilledpolygon($im, array(
              $xpos,               $ypos,
              $xpos + $dx1,        $ypos + $dy1,
              $xpos + $dx1 + $dx2, $ypos + $dy1 + $dy2,
              $xpos + $dx2,        $ypos + $dy2
          ), 4, imagecolorat($texture, $x, $y));
        
        $xpos += $dx2;
        $ypos += $dy2;
      }
      
      $xpos += $dx1 - ($size_y * $dx2);
    }
  }
  
  function imagelight($im, $light) {
    $light = min(255, max(0, $light));
    
    if($light < 128) {
      $color = imagecolorallocatealpha($im, 0, 0, 0, $light);
    } else {
      $color = imagecolorallocatealpha($im, 255, 255, 255, 255 - $light);
    }
    
    imagefilledrectangle($im, 0, 0, imagesx($im), imagesy($im), $color);
    
    return $im;
  }
  
  header("Content-Type: image/png");
  
  $final_size = isset($_GET["size"])? $_GET["size"] : 512;
  
  $size = 2048;
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
  
  if(rand(0, 1) == 1) {
    imagetranslatedtexture($im, $first_poligon, imagelight(load_png("BrainStoneMod:brainLogicBlockOffC"), 42));
    imagetranslatedtexture($im, $second_poligon, load_png("BrainStoneMod:brainStoneMachineTop"));
    imagetranslatedtexture($im, $third_poligon, imagelight(load_png("BrainStoneMod:brainLogicBlockOnQ"), 84));
  } else {
    imagetranslatedtexture($im, $first_poligon, imagelight(load_png("diamond_block"), 42));
    imagetranslatedtexture($im, $second_poligon, load_png("minecraft:diamond_block"));
    imagetranslatedtexture($im, $third_poligon, imagelight(load_png("diamond_block"), 84));
  }
  
  // Resizing
  $image = imagecreatetruecolor($final_size, $final_size);
  imagealphablending($image, false);
  imagesavealpha($image, true);
  imagecopyresampled($image, $im, 0, 0, 0, 0, $final_size, $final_size, $size, $size);
  
  imagepng($image);
  imagepng($im, "../cache/cache.png");
?>