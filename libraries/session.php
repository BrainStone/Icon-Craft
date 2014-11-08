<?php
  require_once("util.php");
  require_once("mysql.php");

  function generate_token() {
    $token = "";

    do {
      $token = generate_random_string(128);

    } while(count_rows("Sessions", "`Token` = '$token'", 1));

    return $token;
  }

  function start_token_session($userid) {
    global $mysqli;

    invalidate_users_token($userid);

    $token = generate_token();

    $_SESSION["ip"] = $_SERVER["REMOTE_ADDR"];
    $_SESSION["client"] = $_SERVER["HTTP_USER_AGENT"];
    $_SESSION["token"] = $token;
    $_SESSION["userid"] = $userid;
    $mysqli->query("INSERT INTO `Sessions` (`Token`, `User-ID`, `IP`) VALUES ('$token', $userid, '" . $_SERVER['REMOTE_ADDR'] . "')");
  }

  function is_token_valid($token = null, $userid = null) {
    global $mysqli;

    if($token == null) {
      if(isset($_SESSION["token"])) {
        $token = $_SESSION["token"];
      } else {
        return false;
      }
    }
    if($userid == null) {
      if(isset($_SESSION["userid"])) {
        $userid = $_SESSION["userid"];
      } else {
        return false;
      }
    }
    
    connect_mysqli();
    
    $valid = true;
    
    switch($mysqli->query("SELECT `AllowIPChange` FROM `Users` WHERE `ID` = '$userid'")->fetch_array()[0]) {
    case 'No':
      $valid = ($_SESSION["ip"] == $_SERVER["REMOTE_ADDR"]);
      break;
    case 'Minor':
      $valid = (substr($_SESSION["ip"], 0, strpos($_SESSION["ip"], '.', strpos($_SESSION["ip"], '.')+1)) == substr($_SERVER["REMOTE_ADDR"], 0, strpos($_SERVER["REMOTE_ADDR"], '.', strpos($_SERVER["REMOTE_ADDR"], '.')+1)));
      break;
    }

    return $valid && ((bool) count_rows("Sessions", "`Token` = '$token' AND `User-ID` = $userid AND `Valid` = 1", 1));
  }

  function invalidate_token($token = null) {
    global $mysqli;

    if($token == null) {
      if(isset($_SESSION["token"])) {
        $token = $_SESSION["token"];
      } else {
        return;
      }
    }

    connect_mysqli();

    $mysqli->query("UPDATE `Sessions` SET `Valid` = 0 WHERE `Token` = '$token' LIMIT 1");
  }

  function invalidate_users_token($userid = null) {
    global $mysqli;

    if($userid == null) {
      if(isset($_SESSION["userid"])) {
        $userid = $_SESSION["userid"];
      } else {
        return;
      }
    }

    connect_mysqli();

    $mysqli->query("UPDATE `Sessions` SET `Valid` = 0 WHERE `User-ID` = '$userid'");
  }
  
  function start_session() {
    if(session_id() == "") {
      session_start();
      setcookie(session_name(), session_id(), time() + ini_get("session.cookie_lifetime"));
    }
  }
?>