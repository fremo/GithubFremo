<?php
include ("db.ink.php");

?>
<!doctype html>
<!--[if IE 6]><html lang="no" class="no-js ie6"><![endif]-->
<!--[if (gt IE 6)|!(IE)]><html lang="no" class="no-js"><![endif]-->
<head>
	<meta charset="utf-8">
    
	<title>oversikt | Widerøes skråfotoarkiv | Overdalsveien</title>

	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="description" content="Fortell hvor dette bilde er tatt">
	<meta name="author" content="fred Moerman">
	<meta name="designer" content="fred Moerman" />
	<meta name="robots" content="noindex">


	<link rel="stylesheet" href="stil/reset.css">
	<link rel="stylesheet" href="stil/stil.css?v=1.0">

	<link rel="stylesheet" href="xtr/leaflet.css" />
	<script src="xtr/leaflet.js"></script>
	<link rel="stylesheet" href="xtr/screen.css" />
	<link rel="stylesheet" href="xtr/MarkerCluster.css" />
	<link rel="stylesheet" href="xtr/MarkerCluster.Default.css" />
	<script src="xtr/leaflet.markercluster-src.js"></script>
	
	<script src="xtr/realworld.10000.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-340859-34', 'auto');
  ga('send', 'pageview');

</script>

</head>
<body>

<body id="bilde">
<div id="dethele">

<?php
include("aside.ink.php");
?>


 <section>
    <header>
        <div class="venstre"><h2>Oversikt over de kommuner som er digitalisert::</h2></div>
    </header>
    <div id="map-container"><div id="map"></div>[klikk på markørene og zoom inn...]<br><br>
</section>



<span class="clearfix">&nbsp;</span>
<footer>
	<article id="bunnleft">
		Denne enkle tjeneste ble utviklet ved hjelp av<br>
		<a href="http://www.nb.no/services/search/v2/">Nasjonalbibliotekets søke-API</a>, 
		<a href="http://www.kartverket.no/Kart/Gratis-kartdata/Stedsnavnsok/">Kartverkets stedsnavnsøk</a> <br>
		og noen av <a href="https://code.google.com/p/google-maps-utility-library-v3/">Googles smarte tjenester</a>. 
	</article>
	<article id="bunnright">
		Kontaktinformasjon<br>
		<a href="mailto:flyfoto@overdalsveien.com">flyfoto@overdalsveien.com</a><br>
	</article>
</footer>

</div>
</body>

</html>



	<script type="text/javascript">

		var tiles = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
			}),
			latlng = L.latLng(64, 10);

		var map = L.map('map', {center: latlng, zoom: 4, layers: [tiles]});

		var markers = L.markerClusterGroup({ chunkedLoading: true });
		
		for (var i = 0; i < addressPoints.length; i++) {
			var a = addressPoints[i];
			var title = '<a href=kommune.php?plass=' + a[2] + '>' + a[2] + '</a>';


			var lenke = a[3];

			var marker = L.marker(L.latLng(a[0], a[1]), { title: title });
			marker.bindPopup(title);
			markers.addLayer(marker);
		}

		map.addLayer(markers);

	</script>
</body>
</html>
