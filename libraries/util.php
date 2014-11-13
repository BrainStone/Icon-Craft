<?php
  require_once("https.php");
  require_once("language.php");
  
  function generate_random_string($length = 10, $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") {
    $upper_bound = strlen($characters) - 1;
    
    $random_string = "";
    
    for ($i = 0; $i < $length; $i++) {
      $random_string .= $characters[rand(0, $upper_bound)];
    }
    
    return $random_string;
  }
  
  function get_url($https = null) {
    if($https == null)
      $https = is_https_enabled();
    
    $query_string = http_build_query($_GET);
    
    return ($https ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . (($query_string == "") ? "" : ("?$query_string"));
  }
  
  function generate_footer($links, $social_media = true) {
    echo "    <footer>\n      <div>\n";
    
    foreach($links as $text => $link) {
      echo "        <div>\n          <a href=\"$link\" title=\"$text\">$text</a>\n        </div>\n";
    }
    
    $uri = "?uri=" . urlencode(get_url());
?>
        <div class="badge">
          <a id="css_valid" target="_blank" rel="nofollow" href="http://jigsaw.w3.org/css-validator/validator<?php echo $uri; ?>" title="<?php echo $css = translate("info.css"); ?>">
            <img width="88" height="31" src="/sprite.png" alt="<?php echo $css; ?>" title="<?php echo $css; ?>">
          </a>
        </div>
        <div class="badge">
          <a id="html5_valid" target="_blank" rel="nofollow" href="http://validator.w3.org/check<?php echo $uri; ?>" title="<?php echo $html5 = translate("info.html5"); ?>">
            <img width="32" height="32" src="/sprite.png" alt="<?php echo $html5; ?>" title="<?php echo $html5; ?>">
          </a>
        </div>
<?php
    if($social_media) {
?>
        <div class="badge">
          <div class="fb-like" data-href="<?php echo get_url(); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
        </div>
        <div class="badge">
          <a id="twitter" href="https://twitter.com/share" class="twitter-share-button" data-via="BrainStoneMod" data-hashtags="Minecraft">Tweet</a>
        </div>
<?php
    }
?>
      </div>
    </footer>
<?php
  }
  
  function get_color_from_gradient($value, $gradient) {
    ksort($gradient, SORT_NUMERIC);
  
    $keys = array_keys($gradient);
    $values = array_values($gradient);
    $size = sizeof($gradient);
    
    if($value <= $keys[0])
      return $values[0];
    if($value >= $keys[$size])
      return $values[$size];
      
      $top = 1;
      
      while(($value < $keys[$top]) && ($top < $size)) {
        $top++;
      }
      
      $percentage = ($value - $keys[$top - 1]) / ($keys[$top] - $keys[$top - 1]);
      $color_low = color_to_array($values[$top - 1]);
      $color_high = color_to_array($values[$top]);
      $out = array(3);
      
      for($i = 0; $i < 3; $i++) {
        $out[$i] = $values[$top - 1] + ($percentage * ($values[$top] - $values[$top - 1]));
      }
      
      return array_to_color($out);
  }
  
  function color_to_array($color) {
    return array(($color >> 16) & 255, ($color >> 8) & 255, $color & 255);
  }
  
  function array_to_color($array) {
    return ($array[0] << 16) | ($array[1] << 8) || $array[2];
  }
  
  if(!function_exists('getallheaders')) {
    function getallheaders() {
      $headers = '';
      
      foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
          $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
      }
      
      return $headers;
    }
  } 
?>