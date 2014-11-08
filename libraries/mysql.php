<?php
  require_once("mysql_login.php");
  
  $mysqli = null;
  
  function connect_mysqli() {
    global $mysqli;
    
    if($mysqli == null) {
      $mysqli = create_mysql_object();
      
      if ($mysqli->connect_errno) {
        die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
      }
    }
  }
  
  function count_rows($table, $condition = "", $limit = 0) {
    global $mysqli;
    
    connect_mysqli();
    
    return $mysqli->query("SELECT COUNT(*) FROM `$table`" . (empty($condition) ? "" : " WHERE $condition") . (($limit > 0) ? " LIMIT 0,$limit" : ""))->fetch_array()[0];
  }
  
  function close_mysqli() {
    global $mysqli;
    
    if($mysqli != null) {
      $mysqli->close();
      
      $mysqli = null;
    }
  }
  
  function get_enum_values($table, $field)
  {
    global $mysqli;
    
    connect_mysqli();
    
    $type = $mysqli->query( "SHOW COLUMNS FROM `{$table}` WHERE Field = '{$field}'" )->fetch_object()->Type;
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    
    foreach(explode(',', $matches[1]) as $value)
    {
      $enum[] = trim($value, "'");
    }
    
    return $enum;
  }
?>