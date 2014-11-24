<?php
  require_once($_SERVER["DOCUMENT_ROOT"] . "/libraries/uptime.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/libraries/language.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/libraries/util.php");
  
  function percentage($percentage) {
    $percentage_circle = (($percentage >= 0) ? (1.0 - round($percentage, 4)) : 1.0) * 180.0 * M_PI;
    $percentage_display = ($percentage >= 0) ? number_format(round($percentage * 100, 2), 2) . " %" : "n/a";
    $color = "#" . dechex(get_color_from_gradient($percentage * 100, array(0 => 0x781003, 80 => 0xE31820, 90 => 0xF76820, 95 => 0xD7D820, 99 => 0x2C9F1E, 100 => 0x128007)));
    
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