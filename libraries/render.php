<?php
  require_once("mysql.php");

  function image_from_cache($modid, $type, $item) {
    global $size_x, $size_y;

    $cache_path = "../cache/render/$modid/$type";
    $cache_file = "$cache_path/$item.png";
    
    if(file_exists($cache_file)) {
      $im = imagecreatefrompng($cache_file);
      touch($cache_file);
      
      $size_x = imagesx($im);
      $size_y = imagesy($im);
    } elseif(file_exists("$cache_file.optimized")) {
      $im = imagecreatefrompng("$cache_file.optimized");
      touch("$cache_file.optimized");
      
      $size_x = imagesx($im);
      $size_y = imagesy($im);
    } else {
      $im = null;
    }

    return $im;
  }

  function cache_image($modid, $type, $item, $im) {
    $cache_path = "../cache/render/$modid/$type";
    $cache_file = "$cache_path/$item.png";
    
    @mkdir($cache_path, 0775, true);
    imagepng($im, $cache_file);
  }

  function crafting_common($cache_name, $texture, $params, $positions, $size_factor) {
    global $im, $final_size_x, $final_size_y;

    list($final_size_x, $final_size_y) = getimagesize($texture);
    $final_size_x *= $size_factor;
    $final_size_y *= $size_factor;

    $im = image_from_cache("minecraft", "crafting", $cache_name);

    if($im === null) {
      $images = array();

      foreach ($params as $image) {
        $images[] = block(array($image));
      }

      $im = render_crafting($texture, $images, $positions);

      cache_image("minecraft", "crafting", $cache_name, $im);
    }
  }

  function crafting($params) {
    global $im, $final_size_x, $final_size_y;
    require_once("renderers/crafting_renderer.php");

    array_shift($params);
    $arguments = sizeof($params);

    if(($arguments == 5) || ($arguments == 6)) {
      $size_factor = (isset($params[5]) && is_numeric($params[5])) ? min(512, max(16, intval($params[5]))) / 16 : 2;
      $positions = array(array(6, 14), array(24, 14), array(6, 32), array(24, 32), array(62, 24));
      $field_size = 2;

      if(isset($params[5])) unset($params[5]);
    } elseif (($arguments == 10) || ($arguments == 11)) {
      $size_factor = (isset($params[10]) && is_numeric($params[10])) ? min(512, max(16, intval($params[10]))) / 16 : 2;
      $positions = array(array(6, 15), array(24, 15), array(42, 15), array(6, 33), array(24, 33), array(42, 33), array(6, 51), array(24, 51), array(42, 51), array(100, 33));
      $field_size = 3;

      if(isset($params[10])) unset($params[10]);
    } else {
      require_once("renderers/block_renderer.php");
      
      $im = render_block("", "", "");

      return;
    }

    crafting_common(implode("_", $params), "../images/minecraft/crafting/crafting${field_size}x${field_size}.png", $params, $positions, $size_factor);
  }

  function smelting($params) {
    global $im, $final_size_x, $final_size_y;
    require_once("renderers/crafting_renderer.php");

    array_shift($params);
    $arguments = sizeof($params);

    if(($arguments == 3) || ($arguments == 4)) {
      $size_factor = (isset($params[3]) && is_numeric($params[3])) ? min(512, max(8, intval($params[3]))) / 16 : 2;
      $positions = array(array(16, 7), array(16, 43), array(76, 25));

      if(isset($params[3])) unset($params[3]);
    } else {
      require_once("renderers/block_renderer.php");
      
      $im = render_block("", "", "");

      return;
    }

    crafting_common(implode("_", $params), "../images/minecraft/crafting/furnace.png", $params, $positions, $size_factor);
  }

  function block($params) {
    global $mysqli;

    $item = $params[0];

    if($item == "") {
      $im = imagecreatetruecolor(2048, 2048);
    
      // Transparentbackground
      imagealphablending($im, true);
      imagesavealpha($im, true);
      $trans = imagecolorallocatealpha($im, 0, 0, 0, 127);
      imagefill($im, 0, 0, $trans);

      return $im;
    }

    $number = null;

    if(preg_match("/^\\d{1,2}x/", $item)) {
      list($number, $item) = explode("x", $item, 2);
    } 
    
    if(strpos($item, ":") === false) {
      $modid = "minecraft";
    } else {
      list($modid, $item) = explode(":", $item);
    }
    
    if(strpos($item, ";") === false) {
      $meta = 0;
    } else {
      list($item, $meta) = explode(";", $item);
      
      $meta = min(15, max(0, intval($meta)));
    }  
    
    $result = $mysqli->query("SELECT `Meta`, `RenderAs`, (SELECT `File` FROM `RenderTypes` WHERE `ID` = `RenderType` LIMIT 1) AS `RenderFile`, `Textures` FROM `RenderData` WHERE `ModID` = (SELECT `ID` FROM `ModIDs` WHERE `ModID` = '" . $mysqli->real_escape_string($modid) . "' LIMIT 1) AND `Name` = '" . $mysqli->real_escape_string($item) . "' AND (`Meta` = '*' OR `Meta` = '$meta') ORDER BY `Meta` ASC LIMIT 1");
    
    if($result->num_rows) {
      $row = $result->fetch_assoc();
      
      if($row["Meta"] != "*") $item .= "_" . $row["Meta"];
      
      switch($row["RenderAs"]) {
      case "Block":
        $im = image_from_cache($modid, "blocks", $item);
        
        if($im === null) {
          require_once("renderers/block_renderer.php");
          
          list($left, $top, $right) = explode(",", $row["Textures"]);        
          $im = render_block($left, $top, $right);
          
          cache_image($modid, "blocks", $item, $im);
        }
        
        break;
      case "Item":
        require_once("renderers/item_renderer.php");
        
        $im = render_item($row["Textures"]);
        
        break;
      }

      /*require_once("new_renderer.php");

      $render_file = "render_scripts/$modid/" . $row["RenderFile"];
      $render_data = json_decode(file_get_contents($render_file));

      $renderer = new Renderer($render_data, explode(",", $row["Textures"]));
      $im = $renderer->render();
      $size = imagesx($im);

      if($render_data->cache != 0) {
        $cache_path = "../cache/render/$modid/" . $render_data->cache;
        $cache_file = "$cache_path/$item.png";
        
        @mkdir($cache_path, 0775, true);
        imagepng($im, $cache_file);
      }*/
    } else {
      require_once("renderers/block_renderer.php");
      
      $im = render_block("", "", "");
    }

    if(($number !== null) && ($number != 1)) {
      $width = imagettfbbox(768, 0, "../includes/css/fonts/Minecraftia.ttf", $number)[2];

      $white = imagecolorallocate($im, 255, 255, 255);
      $black = imagecolorallocate($im, 63, 63, 63);

      imagettftext($im, 768, 0, 2176 - $width, 2048, $black, "../includes/css/fonts/Minecraftia.ttf", $number);
      imagettftext($im, 768, 0, 2048 - $width, 1920, $white, "../includes/css/fonts/Minecraftia.ttf", $number);
    }

    return $im;
  }
  
  header("HTTP/1.1 200 Ok");
  header("Expires: " . gmdate("D, d M Y H:i:s \G\M\T", time() + 604800));
  header("Pragma: public");
  header('Cache-Control: "public, must-revalidate, proxy-revalidate"');
  
  $params = explode("/", urldecode(strtolower($_SERVER["REQUEST_URI"])));
  $im = null;
  array_shift($params);
  connect_mysqli();
  $number_of_params = sizeof($params);

  if(preg_match("/\.([^\.]+)$/", $params[$number_of_params - 1], $extension)) {
    $params[$number_of_params - 1] = preg_replace("/\.[^.]+$/", "", $params[$number_of_params - 1]);
    $available_extensions = imagetypes();
    $extension = strtolower($extension[1]);

    if(($extension == "gif") && ($available_extensions & IMG_GIF)) {
      header("Content-Type: image/gif");
      
      $create_image = "imagegif";
    } elseif ((($extension == "jpg") || ($extension == "jpeg")) && ($available_extensions & IMG_JPG)) {
      header("Content-Type: image/jpeg");
      
      $create_image = "imagejpeg";
    } elseif (($extension == "png") && ($available_extensions & IMG_PNG)) {
      header("Content-Type: image/png");

      $create_image = "imagepng";
    } elseif (($extension == "wbmp") && ($available_extensions & IMG_WBMP)) {
      header("Content-Type: image/vnd.wap.wbmp");
      
      $create_image = "imagewbmp";
    } elseif (($extension == "xbm") && ($available_extensions & IMG_XPM)) {
      header("Content-Type: image/xbm");
      
      $create_image = "imagexbm";
    } else {
      header("Content-Type: image/png");

      $create_image = "imagepng";
    }
  } else {
    header("Content-Type: image/png");

    $create_image = "imagepng";
  }
  
  if($params[0] == "crafting") {
    crafting($params);
  } elseif($params[0] == "smelting") {
    smelting($params);
  } elseif($params[0] == "brewing") {
    // TODO Brewing
  } elseif($params[0] == "special") {
    // Custom item creation processes like custom furnaces from mods
    // TODO Special
  } else {
    $im = block($params);

    $final_size = (isset($params[1]) && is_numeric($params[1])) ? min(4096, max(8, intval($params[1]))) : 32;
  }

  if($im === null) {
    header("HTTP/1.1 404 Not Found");

    require_once("renderers/block_renderer.php");
      
    $im = render_block("", "", "");

    $final_size = 512;
  }

  if((!isset($final_size_x) || !isset($final_size_y)) && isset($final_size)) {
    $final_size_x = $final_size;
    $final_size_y = $final_size;
  }

  if((!isset($size_x) || !isset($size_y)) && isset($size)) {
    $size_x = $size;
    $size_y = $size;
  }
  
  // Resizing
  $image = imagecreatetruecolor($final_size_x, $final_size_y);
  imagealphablending($image, false);
  imagesavealpha($image, true);
  imagecopyresampled($image, $im, 0, 0, 0, 0, $final_size_x, $final_size_y, $size_x, $size_y);
  
  $create_image($image);
?>
