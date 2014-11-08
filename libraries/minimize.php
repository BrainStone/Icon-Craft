<?php
  require_once($_SERVER["DOCUMENT_ROOT"] . "/config.inc.php");
  
  function sanitize_output($buffer) {
    global $start;
    
    $search = array(
      '/<!--.*?-->|\t|(?:\r?\n(?![^<]*<\/pre>)[ \t]*)+/s',  // Remove all comments and paddings at the beginng of a new line
      '/>[^\S ]+(?![^<]*<\/pre>)/s',                        // Strip whitespaces after tags
      '/[^\S ]+(?![^<]*<\/pre>)</s',                        // Strip whitespaces before tags
      '/(\s)+(?![^<]*<\/pre>)/s',                           // Shorten multiple whitespace sequences
      '/(<script[^>]+)\stype="text\/javascript"([^>]*>)/i'  // Remove type="text/javascript" from all script tags
    );
    
    $replace = array(
      '',
      '\\1\\2>',
      '<',
      '\\1',
      '\\1\\2'
    );
    
    $buffer = preg_replace($search, $replace, $buffer);
    
    // Remove quotes around values of attributes inside of tags that consist out of numbers, letters, dashes and dots
    
    $search = "/(<(?:[^>]+?\s)?)([\w-]+=)(\"|')([\w-.]+)\\3((?:\s[^>]*)?" . ">)/si";
    $replace = '\\1\\2\\4\\5';
    
    while(preg_match($search, $buffer)){
      $buffer = preg_replace($search, $replace, $buffer);
    }
    
    return $buffer;
  }
  
  if($compressorEnabled)
    ob_start("sanitize_output");
?>