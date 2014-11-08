<?php
  function load_png($texture) {
    $texture = strtolower($texture);
    
    if(strpos($texture, ":") === false) {
      $modid = "minecraft";
    } else {
      list($modid, $texture) = explode(":", $texture);
    }
    
    // Attempt to open
    if(strpos($texture, ";") === false) {
      $type = "";  
    } else {    
      list($type, $texture) = explode(";", $texture);
    }
    
    switch($type) {
    case "block":
      $im = @imagecreatefrompng("../images/$modid/blocks/$texture.png");
      break;
    case "item":
      $im = @imagecreatefrompng("../images/$modid/items/$texture.png");
      break;
    default:
      $im = @imagecreatefrompng("../images/$modid/blocks/$texture.png");
      if(!$im) $im = @imagecreatefrompng("../images/$modid/items/$texture.png");
    }
    
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
?>