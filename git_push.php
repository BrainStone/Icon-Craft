<?php
  require_once("libraries/passwords.php");
  require_once("libraries/util.php");
  
  header("Content-type: text/html;charset=utf-8");

  $headers = getallheaders();
  
  if(strpos($headers["User-Agent"], "GitHub-Hookshot/") === 0) {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body);
    
    if(($headers["X-Github-Event"] == "push") && ($data->ref == "refs/heads/master")) {
      $algorithm = explode("=", $headers["X-Hub-Signature"])[0];
      
      if($headers["X-Hub-Signature"] == ("$algorithm=" . hash_hmac($algorithm, $request_body, $passwords->github->secret))) {
        passthru("git reset --hard && git clean -f && git pull");
      }
    }
  } else {
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
?>