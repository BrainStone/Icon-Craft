<?php

if (isset($_REQUEST["type"])) $type = $_REQUEST["type"];

if ($type == "all") normal();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css" href="/includes/css/style.css">
<link rel="stylesheet" type="text/css" href="/includes/css/menu.css">

<script defer src="/includes/js/jquery-latest.min.js"></script>
<script defer src="/includes/js/script.js"></script>

<title>Icon-Craft.net</title>


</head>
<body><br />

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