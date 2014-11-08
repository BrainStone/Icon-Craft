<?php
  require_once("session.php");
  require_once("util.php");
  
  function get_languages() {
    global $languages;

    $languages = array();
    
    foreach(explode("\n", file_get_contents(__DIR__ . "/translations/languages.list")) as $language) {
      $language = explode(" ", $language);
      
      $languages[$language[0]] = $language[1];
    }
  }
  
  function translate($localization) {
    global $translations;
    
    $localization = explode("|", $localization);
    
    if(isset($translations[$localization[0]])) {
      $translation = $translations[$localization[0]];
      
      if((count($localization) > 1) && (strpos($translation, "\$1") > -1)) {
        unset($localization[0]);
        
        $pattern = "([^\\|]+)";
        
        for($i = 1; $i < count($localization); $i++) {
          $pattern .= "\\|([^\\|]+)";
        }
        
        return preg_replace("/$pattern/", $translation, implode("|", $localization));
      } else {
        return $translation;
      }
    } else {
      return "";
    }
  }
  
  function find_translation($localization) {
    global $translastions;
  
    if(isset($translastions[$localization])) {
      return $translastions[$localization];
    } else {
      return null;
    }
  }
  
  function set_language($acceptlanguages = array("en", "de")) {
    if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
      $http_accept = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    else
      $http_accept = NULL;
  
    $language = $acceptlanguages[0];
    $languagelist = "(" . join($acceptlanguages, "|") . ")";
    
    if(isset($http_accept) && (strlen($http_accept) > 1)) {
    
      // Split possible languages into array
      $x = explode(",", $http_accept);
      
      foreach ($x as $val) {  
        // check for q-value and create associative array. No q-value means 1 by rule
        if(preg_match("/(.*);q=([01]?\.\d{0,4})/i", $val, $matches))
          $lang[$matches[1]] = (float)$matches[2];
        else
          $lang[$val] = 1.0;
      }
      
      arsort($lang);
      
      foreach($lang as $curlang => $qvalue) {
        if(preg_match("/$languagelist/i", $curlang, $matches)) {
          if($qvalue != 0.0) {
            $language = $matches[0];
            
            break;
          } else {
            unset($acceptlanguages[$matches[0]]);
            
            if(sizeof($acceptlanguages) == 0) {
              $_SESSION["language"] = "en";
              return;
            }
          }
        }
      }
    }
    
    $_SESSION["language"] = strtolower($language);
  }
  
  function print_language_selector() {
    global $languages;
?>
    <div class="lang_selector">
      <form method="POST"><?php
    foreach($languages as $language => $name) {
      echo "        <div class=\"$language" . (($_SESSION["language"] == $language)? " selected" : "") . "\"><div>\n          <input type=\"image\" src=\"/flags/sprite.png\" alt=\"$name\" title=\"$name\" name=\"$language\">\n        </div></div>\n";
    }
?>
      </form>
    </div>
<?php
  }
  
  function print_hreflang() {
    global $languages;
    
    $url = get_url();
    
    foreach($languages as $language => $name) {
      echo "    <link rel=\"alternate\" hreflang=\"$language\" href=\"" . preg_replace("/([^\\/]+\\/{2}[^\\/]+)\\//", "\$1/$language/", $url) . "\">\n";
    }
  } 
  
  start_session();  
  get_languages();
    
  if(!isset($_SESSION["language"]))
    set_language(array_keys($languages));
    
  $file = __DIR__ . "/translations/" . $_SESSION["language"];
  
  require_once("$file.php");
  
  if($hash != ($file_hash = sha1_file("$file.lang"))) {
    $last_index = "";
    $translations = array();
    $append_next_line = false;
    
    foreach(file("$file.lang", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
      if($append_next_line) {
        $translations[$last_index] = substr($translations[$last_index], 0, -1) . "\n$line";
      } else {
        $tmparray = explode("=", $line);
        $last_index = $tmparray[0];
        
        $translations[$last_index] = $tmparray[1];
      }
      
      $append_next_line = substr($line, -1) == "\\";
    }
    
    file_put_contents("$file.php", "<?php\n  \$hash = " . var_export($file_hash, true) . ";\n\n  \$translations = " . var_export($translations, true) . ";\n?" . ">");
  }
  
  foreach($languages as $language => $name) {
    if(isset($_POST["${language}_x"]) && isset($_POST["${language}_y"])) {
      $_SESSION["language"] = $language;
      
      header("Location: " . get_url());
      exit();
    }
  }
  
  if(isset($_GET["lang"])) {
    if(isset($languages[$_GET["lang"]])) {
      $_SESSION["language"] = $_GET["lang"];
    }
    
    unset($_GET["lang"]);
    $_SERVER["REQUEST_URI"] = substr($_SERVER["REQUEST_URI"], 3);
    
    header("Location: " . get_url());
    exit();
  }
?>