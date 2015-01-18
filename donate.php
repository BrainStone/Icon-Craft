<?php
  require_once("libraries/passwords.php");

  header("HTTP/1.1 303 See Other");
  header("Location: " . $passwords->donate_link);
?>