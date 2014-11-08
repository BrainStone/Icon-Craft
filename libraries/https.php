<?php
  require_once("util.php");
  
  function is_https_enabled() {
    return (!empty($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] !== 'off')) || ($_SERVER["SERVER_PORT"] == 443);
  }
  
  function force_https($permanent_redirect = false) {
    if(!is_https_enabled()) {
      if($permanent_redirect)
        header("HTTP/1.1 301 Moved Permanently");
      
      header("Location: " . get_url(true));
      exit;
    }
  }
?>