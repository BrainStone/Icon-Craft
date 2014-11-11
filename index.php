<?php
header("Content-Type: text/html;charset=utf-8");

require_once("libraries/language.php");

// Cache the page
require_once("libraries/cache.php");
// Make HTML as small as possible!
require_once("libraries/minimize.php");

if (isset($_REQUEST["type"])) $type = $_REQUEST["type"];

if ($type == "all") normal();

?>
<!DOCTYPE HTML>
<html lang="<?php echo $_SESSION["language"]; ?>">
<head>

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
<?php
  print_language_selector();
?>
<br />

<table border='0' align='center' class='index_table'>
    <tr>
	<td colspan='2' class='index_header'><?php include 'includes/header.php'; ?></td>
    </tr>
    <tr>
	<td colspan='2' class='index_menu'><?php include 'includes/menu.php'; ?></td>
    </tr>
    <tr>
    	<td class='index_site_left'>1

<?php

function normal() {

$ausgabe .= "Hier ist die ausgabe";
$ausgabe .= "hmmm";

print $ausgabe;

}

?>
	</td>
	<td class='index_site_right'>3</td>
    </tr>
    <tr>
	<td colspan='2' class='index_footer'><?php include 'includes/footer.php'; ?></td>
    <tr>
</table>

</body></html>