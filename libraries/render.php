<?php
  require_once("mysql.php");
  
  $params = array_shift(explode("/", strtolower($_SERVER["REQUEST_URI"])));
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
    
    $result = $mysqli->query("SELECT `RenderAs`, (SELECT `File` FROM `RenderTypes` WHERE `ID` = `RenderType` LIMIT 1) AS `RenderFile`, `Textures` FROM `RenderData` WHERE `ModID` = (SELECT `ID` FROM `ModIDs` WHERE `Name` = '" . $mysqli->real_escape_string($modid) . "' LIMIT 1) AND `Name` = '" . $mysqli->real_escape_string($item) . "' LIMIT 1");
    
    if($result->num_rows) {
      $row = $result->fetch_assoc();
      
      switch($row["RenderAs"]) {
      case "Block":
        require_once("renderers/block_renderer.php");
        
        list($left, $top, $right) = explode(",", $row["Textures"]);        
        $im = render_block($left, $top, $right);
        
        break;
      case "Item":
        require_once("renderers/item_renderer.php");
        
        $im = render_item($row["Textures"]);
        
        break;
      }
    } else {
      require_once("renderers/block_renderer.php");
      
      $im = render_block("diamond_block", "diamond_block", "diamond_block");
    }
    
    $final_size = (isset($params[1]) && is_numeric($params[1])) ? intval($params[1]) : 512;
  }
  
  //$im = render_block("BrainStoneMod:brainLogicBlockOffC", "BrainStoneMod:brainStoneMachineTop", "BrainStoneMod:brainLogicBlockOnQ");
  
  // Resizing
  $image = imagecreatetruecolor($final_size, $final_size);
  imagealphablending($image, false);
  imagesavealpha($image, true);
  imagecopyresampled($image, $im, 0, 0, 0, 0, $final_size, $final_size, $size, $size);
  
  header("Content-Type: image/png");
  
  imagepng($image);
  //imagepng($im, "../cache/cache.png");
?>