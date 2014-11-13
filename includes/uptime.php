<?php
  require_once("libraries/uptime.php");
  require_once("libraries/language.php");
  
  function percentage($percentage) {
    $percentage_circle = (($percentage >= 0) ? (100 - round($percentage, 4)) : 1) * 180 * M_PI;
    $percentage_display = ($percentage >= 0) ? round($percentage * 100, 2) . "%" : "n/a";
    $color = ($percentage >= 0.95)? "green" : "orange";
    
    echo "<div class=\"percent\" data-pct=\"$percentage_display\"><svg viewPort=\"0 0 100 100\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">
<circle r=\"45\" cx=\"50\" cy=\"50\"></circle><circle r=\"45\" cx=\"50\" cy=\"50\" stroke-dashoffset=\"$percentage_circle\" stroke=\"$color\"></circle>
</svg></div>";
  }
?>
<table border="0">
  <tr>
    <td><?php echo translate("body.uptime.all_time"); ?></td>
    <td><?php echo translate("body.uptime.last_month"); ?></td>
  </tr>
  <tr>
    <td><?php percentage($uptime["all_time"]);   ?></td>
    <td><?php percentage($uptime["last_month"]); ?></td>
  </tr>
</table>