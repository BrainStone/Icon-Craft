<?php
  require_once("render.php");

  function render_crafting($texture_file, $images, $postions) {
    global $size_x, $size_y;

    list($size_x, $size_y) = getimagesize($texture_file);
    $size_x *= 32;
    $size_y *= 32;

    $texture = imagecreatetruecolor($size_x, $size_y);
    
    // Transparentbackground
    imagealphablending($texture, true);
    imagesavealpha($texture, true);
    $trans = imagecolorallocatealpha($texture, 0, 0, 0, 127);
    imagefill($texture, 0, 0, $trans);

    imagecopyresampled($texture, imagecreatefrompng($texture_file), 0, 0, 0, 0, $size_x, $size_y, $size_x / 32, $size_y / 32);

    $size = sizeof($images);

    for($i = 0; $i < $size; $i++) {
      imagecopyresampled($texture, $images[$i], $postions[$i][0] * 32, $postions[$i][1] * 32, 0, 0, 512, 512, 2048, 2048);

      imagedestroy($images[$i]);
    }

    return $texture;
  }
?>