<?php
  require_once("passwords.php");
  
  function send_request($api_string) {
    global $passwords;
     
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://api.pingdom.com/api/2.0/$api_string");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_USERPWD, "$passwords->pingdom->email:$passwords->pingdom->password");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("App-Key: $passwords->pingdom->appkey"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    $response = json_decode(curl_exec($curl));
    
    if(isset($response->error)) {
      $response = json_decode("{\"summary\":{\"status\":{\"totalup\":-1,\"totaldown\":2}}}");
    }
    
    return $response;
  }
  
  function get_uptime() {
    global $passwords, $uptime, $cache_file;
    
    $all_time = send_request("summary.average/$passwords->pingdom->checkid?includeuptime=true&");
    $last_month = send_request("summary.average/$passwords->pingdom->checkid?includeuptime=true&from=" . (time() - 2592000));
    
    $uptime = array("all_time"   => $all_time->status->totalup   / ($all_time->status->totalup   + $all_time->status->totaldown  ),
                    "last_month" => $last_month->status->totalup / ($last_month->status->totalup + $last_month->status->totaldown));
                    
    file_put_contents($cache_file, "<?php\n  \$last_check = " . var_export($time(), true) . ";\n\n  \$uptime = " . var_export($uptime, true) . ";\n?" . ">");
  }
  
  $uptime = array();
  $cache_file = "../cache/php/uptime.php";
  
  if(file_exists($cache_file)) {
    require_once($cache_file);
    
    if((time() - $last_check) > 3600)
      get_uptime();
  } else {
    mkdir("../cache/php", 0777, true);
    
    get_uptime();
  }
?>