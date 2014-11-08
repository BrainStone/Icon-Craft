<?php
  require_once($_SERVER["DOCUMENT_ROOT"] . "/config.inc.php");
  
  function cache_output($buffer) {
    global $cache_location;
    
    mkdir(dirname($cache_location), 0775, true);
    file_put_contents($cache_location, $buffer);
    chmod($cache_location, 0775);
    
    return $buffer;
  }
  
  if($cacheEnabled) {
    $cache_location = $_SERVER["DOCUMENT_ROOT"] . "/cache/html/" . ((session_status() == PHP_SESSION_ACTIVE)? $_SESSION["language"] : "no_session") . $_SERVER["REQUEST_URI"] . ".cache";
    
    if(file_exists($cache_location)) {
      ob_end_flush();
      
      readfile($cache_location);
      
      exit();
    } else {
      ob_start("cache_output");
    }
  }
?>