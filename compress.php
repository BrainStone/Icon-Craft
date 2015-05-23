<?php
  function call_404() {
    header("Content-type: text/html;charset=utf-8");
    header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html>
<head><title>404 Not Found</title></head>
<body bgcolor="white">
<center><h1>404 Not Found</h1></center>
<hr><center>nginx/1.2.1</center>
</body>
</html>
<?php
    exit();
  }

  function setHeaders() {
    global $file;
    $cache_age = 604800;

    $tsstring = gmdate("D, d M Y H:i:s \G\M\T", filemtime($file));
    $if_modified_since = isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) ? $_SERVER["HTTP_IF_MODIFIED_SINCE"] : false;

    if($_GET["type"] == "css") {
      header("Content-type: text/css;charset=utf-8");
    } elseif($_GET["type"] == "js") {
      header("Content-type: text/javascript;charset=utf-8");
    } else {
      call_404();
    }

    header("Last-Modified: $tsstring");
    header("Expires: " . gmdate('D, d M Y H:i:s \G\M\T', time() + $cache_age));
    header("Pragma: public");
    header("Cache-Control: \"max-age=$cache_age, public, must-revalidate, proxy-revalidate\"");

    if($if_modified_since && ($if_modified_since == $tsstring)) {
      header("HTTP/1.1 304 Not Modified");

      exit();
    }
  }
  
  require_once("config.inc.php");
  
  if(!isset($_GET["file"]) || !isset($_GET["type"])) call_404();  
  $file = $_SERVER["DOCUMENT_ROOT"] . $_GET["file"];
  $cache = $_SERVER["DOCUMENT_ROOT"] . "/cache/" . $_GET["type"] . "/" . $_GET["file"] . ".php";
  if(!file_exists($file)) call_404();

  setHeaders();
    
  if(!$compressorEnabled || ($_GET["file"] == "/scripts/jQuery.js")) {
    readfile($file);
    exit();
  }
  
  if(file_exists($cache)) {
    require_once($cache);
  } else {
    $hash = "";
    
    if(!file_exists(dirname($cache)))
      mkdir(dirname($cache), 0775, true);
  }
    
  if($hash != ($file_hash = sha1_file("$file"))) {
    // Compress the css or js file with the API of http://cssminifier.com or http://javascript-minifier.com

    if($_GET["type"] == "css") {
      $url = "http://cssminifier.com/raw";
    } else {
      $url = "http://javascript-minifier.com/raw";
    }
    
    $data = array("input" => file_get_contents($file));    
    $options = array(
      "http" => array(
        "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
        "method"  => "POST",
        "content" => http_build_query($data),
        "timeout" => 2
      )
    );
    
    $context  = stream_context_create($options);
    $compressed_file = @file_get_contents($url, false, $context);
    
    if($compressed_file === false) {
      $compressed_file = $data["input"];
    } else {
      file_put_contents($cache, "<?php\n  \$hash = " . var_export($file_hash, true) . ";\n\n  \$compressed_file = " . var_export($compressed_file, true) . ";\n?" . ">");
      chmod($cache, 0775);
    }
  }
  
  echo $compressed_file;
?>