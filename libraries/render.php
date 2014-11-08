<?php
  require_once("renderers/block_renderer.php");
  
  if(rand(0, 1) == 1) {
    $im = render_block("BrainStoneMod:brainLogicBlockOffC", "BrainStoneMod:brainStoneMachineTop", "BrainStoneMod:brainLogicBlockOnQ");
  } else {
    $im = render_block("diamond_block", "diamond_block", "diamond_block");
  }
  
  // Resizing
  $image = imagecreatetruecolor($final_size, $final_size);
  imagealphablending($image, false);
  imagesavealpha($image, true);
  imagecopyresampled($image, $im, 0, 0, 0, 0, $final_size, $final_size, $size, $size);
  
  header("Content-Type: image/png");
  
  imagepng($image);
  imagepng($im, "../cache/cache.png");
?>