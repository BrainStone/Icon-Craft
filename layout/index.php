<?php
header("Content-Type: text/html;charset=utf-8");

require_once("../libraries/language.php");

// Cache the page
$max_cache_lifetime = 1800;
require_once("../libraries/cache.php");
// Make HTML as small as possible!
require_once("../libraries/minimize.php");

?>
<!doctype html>
<html lang="<?php echo $_SESSION["language"]; ?>">
	<head>
		<meta charset="utf-8">
		<title>Unbenanntes Dokument</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Styles -->
		<link type="text/css" rel="stylesheet" href="css/r.css">
		<link type="text/css" rel="stylesheet" href="css/sss.css" media="all">
		<noscript id="loadCSS">
			<link type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
			<link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans">
		</noscript>

		<!-- Scripts -->
		<script defer src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script defer src="js/sss.js"></script>
		<script defer src="js/r.js"></script>
	</head>

	<body>
		<div class="anchor" id="top"></div>
		<div class="pre-header">
			<?php
				print_language_selector();
			?>

		</div>
		<div class="header-background"></div>
		<div class="header">
			<div class="container">
				<div class="float-left header-title">
					<h1>Icon-Craft</h1>
				</div>
				<div class="header-navigation float-right">
					<ul>
						<li><a href="#1">Lorem</a></li>
						<li><a href="#2">Ipsum</a></li>
						<li><a href="#3">Das Projekt</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="main">

			<div class="slider">
				<div class="slider-image" style="background-image: url('//dedelner.net/wp-content/uploads/2015/05/2015-05-30_21.50.55.jpg');"></div>
				<div class="slider-image" style="background-image: url('//dedelner.net/wp-content/uploads/2015/05/2015-05-30_16.31.42.jpg');"></div>
				<div class="slider-image" style="background-image: url('//dedelner.net/wp-content/uploads/2015/05/2015-05-24_22.05.17.jpg');"></div>
			</div>
			<div class="dark-green">
				<div class="container">
					<div class="r-1-2">
						<h2>Auf dem neuesten Stand?</h2>
						<p>Durch unseren kostenlosen Newsletter wirst du regelmäßig mit den neuesten informationen versorgt.</p>
					</div>
					<div class="r-1-2">
						<form>
							<input type="email" placeholder="E-Mail">
							<input type="submit" value="Newsletter abonnieren">
						</form>
					</div>
				</div>
			</div>
			<div class="anchor" id="1"></div>
			<div class="white">

				<div class="container">

					<div class="r-1">
						<h2>Projekte</h2>
					</div>

					<div class="r-1-3">
						<h3>Projekt 1</h3>
						<p>Kurzer Text.</p>
					</div>

					<div class="r-1-3">
						<h3>Projekt 2</h3>
						<p>Kurzer Text.</p>
					</div>

					<div class="r-1-3">
						<h3>Projekt 3</h3>
						<p>Kurzer Text.</p>
					</div>

				</div>

			</div>

			<div class="anchor" id="2"></div>
			<div class="dark-grey">
				<div class="container">
					<div class="r-1">
						<h2>So viele Farben...</h2>
						<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
						<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
						<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
					</div>
				</div>
			</div>

			<div class="anchor" id="3"></div>
			<div class="grey">
				<div class="container">
					<div class="r-1">
						<h2>Hübsch nicht?</h2>
						<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
						<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
						<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
					</div>
				</div>
			</div>
		</div>

		<div class="footer">
			<div class="r-1">
				<div class="float-left">Design by <a href="https://paradox.network">paradox.network</a></div>
				<div class="float-right"><a href="https://paradox.network">Impressum</a></div>
			</div>
		</div>

		<a class="top" href="#top"><i class="fa fa-arrow-up"></i></a>
	</body>
</html>
