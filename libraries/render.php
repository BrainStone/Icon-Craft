<?php
  require_once("mysql.php");

  function image_from_cache($modid, $type, $item) {
    global $size, $im;

    $cache_path = "../cache/render/$modid/blocks";
    $cache_file = "$cache_path/$item.png";
    
    if(file_exists($cache_file)) {
      $im = imagecreatefrompng($cache_file);
      touch($cache_file);
      
      $size = imagesx($im);
    } elseif(file_exists("$cache_file.optimized")) {
      $im = imagecreatefrompng("$cache_file.optimized");
      touch("$cache_file.optimized");
      
      $size = imagesx($im);
    }
  }

  function cache_image($modid, $type, $item) {
    global $size, $im;

    $cache_path = "../cache/render/$modid/blocks";
    $cache_file = "$cache_path/$item.png";
    
    @mkdir($cache_path, 0775, true);
    imagepng($im, $cache_file);
  }

  function crafting($params) {
    global $im, $final_size_x, $final_size_y;
    require_once("renderers/crafting_renderer.php");

    array_shift($params);
    $arguments = sizeof($params);

    if(($arguments == 5) || ($arguments == 6)) {
      $size_factor = (isset($params[5]) && is_numeric($params[5])) ? min(512, max(16, intval($params[1]))) / 16 : 2;
      $positions = array(array(6, 14), array(24, 14), array(6, 32), array(24, 32), array(62, 24));
      $field_size = 2;

      $final_size_x = 83 * $size_factor;
      $final_size_y = 53 * $size_factor;

      if(isset($params[5])) unset($params[5]);
    } elseif (($arguments == 10) || ($arguments == 11)) {
      $size_factor = (isset($params[10]) && is_numeric($params[10])) ? min(512, max(16, intval($params[1]))) / 16 : 2;
      $positions = array(array(6, 15), array(24, 15), array(42, 15), array(6, 32), array(24, 32), array(42, 32), array(6, 51), array(24, 51), array(42, 51), array(100, 33));
      $field_size = 3;

      $final_size_x = 125 * $size_factor;
      $final_size_y = 72 * $size_factor;

      if(isset($params[10])) unset($params[10]);
    } else {
      require_once("renderers/block_renderer.php");
      
      $im = render_block("", "", "");

      return;
    }

    image_from_cache("minecraft", "crafting", implode("_", $params));

    if($im === null) {
      $images = array();

      foreach ($params as $image) {
        $images[] = blocks(array($image));
      }

      $im = render_crafting("../images/minecraft/crafting/crafting${field_size}x${field_size}.png", $images, $positions);

      cache_image("minecraft", "crafting", implode("_", $params));
    }
  }

  function block($params) {
    global $im, $final_size;

    $item = $params[0];
    
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
        image_from_cache($modid, "blocks", $item);
        
        if($im === null) {
          require_once("renderers/block_renderer.php");
          
          list($left, $top, $right) = explode(",", $row["Textures"]);        
          $im = render_block($left, $top, $right);
          
          cache_image($modid, "blocks", $item);
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

    $final_size = (isset($params[1]) && is_numeric($params[1])) ? min(4096, max(16, intval($params[1]))) : 512;
  }
  
  header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 604800));
  header("Pragma: public");
  header('Cache-Control: "public, must-revalidate, proxy-revalidate"');
  
  $params = explode("/", strtolower($_SERVER["REQUEST_URI"]));
  $im = null;
  array_shift($params);
  connect_mysqli();
  
  if($params[0] == "crafting") {
    crafting($params);
  } elseif($params[0] == "smelting") {
    // TODO Smelting
  } elseif($params[0] == "brewing") {
    // TODO Brewing
  } elseif($params[0] == "special") {
    // Custom item creation processes like custom furnaces from mods
    // TODO Special
  } else {
    block($params);
  }

  if($im === null) {
    require_once("renderers/block_renderer.php");
      
    $im = render_block("", "", "");
  }

  if(!isset($final_size)) {
    $final_size_x = $final_size;
    $final_size_y = $final_size;
  }

  if(!isset($size)) {
    $size_x = $size;
    $size_y = $size;
  }
  
  // Resizing
  $image = imagecreatetruecolor($final_size, $final_size);
  imagealphablending($image, false);
  imagesavealpha($image, true);
  imagecopyresampled($image, $im, 0, 0, 0, 0, $final_size_x, $final_size_y, $size_x, $size_y);
  
  header("Content-Type: image/png");
  
  imagepng($image);
?>