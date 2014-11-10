<?php
  require_once("mysql.php");
  
  header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 604800));
  header("Pragma: public");
  header('Cache-Control: "public, must-revalidate, proxy-revalidate"');
  
  $params = explode("/", strtolower($_SERVER["REQUEST_URI"]));
  array_shift($params);
  connect_mysqli();
  
  if($params[0] == "crafting") {
    // TODO Crafting
  } elseif($params[0] == "smelting") {
    // TODO Smelting
  } elseif($params[0] == "brewing") {
    // TODO Brewing
  } elseif($params[0] == "special") {
    // Custom item creation processes like custom furnaces from mods
    // TODO Special
  } else {
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
        } else {
          require_once("renderers/block_renderer.php");
          
          list($left, $top, $right) = explode(",", $row["Textures"]);        
          $im = render_block($left, $top, $right);
          
          @mkdir($cache_path, 0775, true);
          imagepng($im, $cache_file);
        }
        
        break;
      case "Item":
        require_once("renderers/item_renderer.php");
        
        $im = render_item($row["Textures"]);
        
        break;
      }
    } else {
      require_once("renderers/block_renderer.php");
      
      $im = render_block("", "", "");
    }
    
    $final_size = (isset($params[1]) && is_numeric($params[1])) ? min(4096, max(16, intval($params[1]))) : 512;
  }
  
  // Resizing
  $image = imagecreatetruecolor($final_size, $final_size);
  imagealphablending($image, false);
  imagesavealpha($image, true);
  imagecopyresampled($image, $im, 0, 0, 0, 0, $final_size, $final_size, $size, $size);
  
  header("Content-Type: image/png");
  
  imagepng($image);
?>