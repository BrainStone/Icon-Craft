<?php
  require_once("libraries/uptime.php");
  require_once("libraries/language.php");
  
  function percentage($percentage) {
    $percentage_circle = ($percentage >= 0) ? round($percentage, 0) : 0;
    $percentage_display = ($percentage >= 0) ? round($percentage, 2) . "%" : "n/a";
    $color = ($percentage >= 95)? "green" : "orange";
    
    echo "<div class=\"c100 dark p$percentage_circle $color\"><span>$percentage_display</span><div class=\"slice\"><div class=\"bar\"></div><div class=\"fill\"></div></div></div>";
  }
?>
<table border="0">
  <tr>
    <td><?php echo translate("body.uptime.all_time"); ?></td>
    <td><?php echo translate("body.uptime.last_month"); ?></td>
  </tr>
  <tr>
    <td><?php percentage($uptime["all_time"]   * 100); ?></td>
    <td><?php percentage($uptime["last_month"] * 100); ?></td>
  </tr>
</table>