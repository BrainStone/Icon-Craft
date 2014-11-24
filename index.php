<?php
header("Content-Type: text/html;charset=utf-8");

require_once("libraries/language.php");

// Cache the page
require_once("libraries/cache.php");
// Make HTML as small as possible!
require_once("libraries/minimize.php");

?>
<!DOCTYPE HTML>
<html lang="<?php echo $_SESSION["language"]; ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="robots" content="index, follow">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="stylesheet" type="text/css" href="/includes/css/style.css">
<link rel="stylesheet" type="text/css" href="/includes/css/menu.css">
<?php
  print_hreflang();
?>
<script defer src="/includes/js/jquery-latest.min.js"></script>
<script defer src="/includes/js/script.js"></script>

<title>Icon-Craft.net</title>


</head>
<body>
<br />

<table border="0" align="center" class="index_table">
    <tr>
	<td colspan="2" class="index_header"><?php include "includes/header.php"; ?></td>
    </tr>
    <tr>
	<td colspan="2" class="index_menu"><?php include "includes/menu.php"; ?></td>
    </tr>
    <tr>
        <td class="index_site_left"><?php include "includes/uptime.php"; ?></td>
        <td class="index_site_right">3</td>
    </tr>
    <tr>
	<td colspan="2" class="index_footer"><?php include "includes/footer.php"; ?></td>
    <tr>
</table>

</body></html>