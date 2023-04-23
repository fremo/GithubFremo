<?php

$side = "bilde";

$idag 		= date ("Y-m-d", mktime (0,0,0,date("m"),date("d"),date("Y")));
$ipadresse 	= $_SERVER['REMOTE_ADDR'];

$nbid		= $_GET['ID'];
$nbkommune	= $_GET['kommune'];
$nbsted		= $_GET['sted'];
$aar 		= $_GET['ar'];
$bredde		= $_POST['lat'];
$lengde		= $_POST['lon'];
$plassen 	= $nbkommune;

$tilbakelenke = htmlspecialchars($_COOKIE["denneside"]);

include ("db.ink.php");
include("geoconverter.ink.php");

if ($bredde > 0) {
	$finn_navn = "http://api.geonames.org/findNearbyPlaceName?lat=" . $bredde . "&lng=" . $lengde . "&username=fremo8617&style=full";
	$xml	= simplexml_load_file($finn_navn);
	$sted01 	= $xml->geoname->name;
	$kommune01 	= $xml->geoname->adminName2;

	if ($nbkommune <> $kommune01) $nb_sted = "";

	if ($sted01 == "") $sted01 == $nb_sted; 

	$replace = "
		REPLACE INTO steder
		SET nbid 	= '$nbid',
		lengde 		= $lengde,
		bredde 		= $bredde,
		sted 		= '$sted01',
		kommune 	= '$kommune01',
		fylke 		= '$fylke01',
		regdato		= '$idag',
		ip 			= '$ipadresse',
		nbsted 		= '$nb_sted',
		registrert 	= 'ja'
	";
//	print "<hr>" . $replace . "<hr>";
	$result = mysql_query($replace);

	$plassen 	= $kommune01;
} 

/* 	denne er plassert her for å kunne vise kommune i topper etter oppdatering	*/
include("topper.ink.php");

$funnet = "nei";
/* 	finn stedet på kartet							*/
/* 	1. se i databasen::								*/
/*	--=-- --=-- --=-- --=-- --=-- --=-- --=-- --=-- */
	$finn_noe = "
		SELECT 
		sted,
		kommune,
		fylke,
		nbid,
		nbsted,
		lengde,
		bredde
		FROM 	steder
		WHERE 	nbid = '$nbid'
	";
	$rekke = mysql_query($finn_noe);
	if (mysql_num_rows($rekke) > 0) {
		$result 		= mysql_query($finn_noe);
		$rekke 			= mysql_fetch_assoc($result);
		$base_id  		= $rekke["id"];
		$lat 			= $rekke[bredde];
		$lon 			= $rekke[lengde];
		$base_kommune 	= $rekke['kommune'];
		$base_sted 		= $rekke['sted'];
		$funnet 		= "registrert";
	}
	$tittel = $base_kommune . " - " . $base_sted;

/*	2. finn hos kartverket::						*/
/*	--=-- --=-- --=-- --=-- --=-- --=-- --=-- --=-- */
	if ($funnet == "nei") {
		$ssrid = 0;
		$url    = "https://ws.geonorge.no/SKWS3Index/ssr/sok?navn=" . $nbsted . "&amp;kommunenavn=" . $nbkommune . "&amp;maxAnt=4";
		$content = file_get_contents($url);
		if ( empty($content) ) {
			// die('XML is empty');
		} else {
			$xml = simplexml_load_string($content);
			foreach ($xml->stedsnavn as $s_navn) {
				$xml_kommune = $s_navn->kommunenavn;
				if ($xml_kommune == $nbkommune) {
					$ssrid = $s_navn->ssrId;
					$x = $s_navn->aust;
					$y = $s_navn->nord;
				}
			}
			if ($ssrid > 1) {
				$geoconverter = new GeoConverter();
				$zone = 33;
				$southhemi = false;
				$latlon = array(2);
				$geoconverter->UTMXYToLatLon($x, $y, $zone, $southhemi, $latlon);
				$lon = $geoconverter->RadToDeg($latlon[1]);
				$lat = $geoconverter->RadToDeg($latlon[0]);
				$funnet = "geonorge";
			} 

			$replace = "
				REPLACE INTO steder
				SET nbid 	= '$nbid',
				lengde 		= $lon,
				bredde 		= $lat,
				sted 		= '$nbsted',
				kommune 	= '$nbkommune',
				fylke 		= '$fylke01',
				regdato		= '$idag',
				ip 			= '$ipadresse',
				nbsted 		= '$nbsted',
				registrert 	= 'ja'
			";
	//		print  $url . "<hr>" . $replace . "<hr>";
			$result = mysql_query($replace);			
		}
	}
/*	3. hent kommune-default fra databasen::			*/
/*	--=-- --=-- --=-- --=-- --=-- --=-- --=-- --=-- */
	if ($funnet == "nei") {
		$finn_noe = "
				SELECT 
				sted,
				kommune,
				fylke,
				nbid,
				nbsted,
				lengde,
				bredde
				FROM 	steder
				WHERE 	kommune = '$nbkommune'
				AND 	character_length(nbid) < 10
		";
		$result = mysql_query($finn_noe);
		$rekke = mysql_fetch_assoc($result);
		$id  = $rekke["id"];
		$lat = $rekke[bredde];
		$lon = $rekke[lengde];
		$base_plassen = $rekke['kommune'];
		$base_sted = $rekke['sted'];
		$funnet = "kommunebase";
	}

	echo"<!-- hentet fra:: $funnet -->"; 
	$vis_lat = round($lat, 4);
	$vis_lon = round($lon, 4);
?>

	<aside>
		<header>
			<a href="http://overdalsveien.com/wideroe"><img src="../pikks/nylogo.png" alt="logo"></a>
		</header>
		<div class="txt">
			<h2>Widerøes skråfotoarkiv</h2>
			<p>
			Nasjonalbiblioteket har satt i gang en nasjonal redningsaksjon for Widerøes skråfoto. De tilbyr å overta bevaringsansvaret for originalmaterialet, samtidig som bildene vil bli digitalisert.
			Les mer om dette prosjektet på nb.no.
			</p>	
		</div>

		<div class="txtvis">
			<p>
			HUSK:
			<b>Plasseringen du registrer her blir IKKE sendt til Nasjonalbiblioteket og brukes KUN på disse sider!</b>
			</p>
			<p>
			Foreløpig har jeg fått opplyst disse koordinater:<br>
			> breddegraden: <?php echo"$vis_lon"; ?><br>
			> lengdegraden: <?php echo"$vis_lat"; ?><br>
			(yepp, i desimalgrader)<br><br>
			Hvis du mener dette er feil, så kan du flytte markøren på kartet til rett sted.
			</p>
			<p>&nbsp;</p>
			<p><a href="<?php echo"$tilbakelenke"; ?>">Tilbake til oversikten</a></p>
		</div>
	</aside>

	<section id="enkelt">
		<header>
			
			<?php 
			if ($funnet == "registrert") {
				echo"<h2>$tittel</h2>";
			} else {
				echo"<h2>$nbkommune - $nbsted</h2>$aar";
			}
			?>
		</header>
		<div id="bildebox">
			<figure class="bilde">
<?php
/*

				<img src="http://www.nb.no/services/image/resolver?url_ver=geneza&urn=URN:NBN<?php echo"$nbid"; ?>&maxLevel=6&level=3&col=0&row=0&resX=8417&resY=5937&tileWidth=1024&tileHeight=1024&pg_id=1" 

 */
?>
<img 	src="https://www.nb.no/services/image/resolver/:no-nb_digifoto_20141202_00131_NB_WF_SBK_098951/full/1000,0/0/native.jpg" 
		alt="skråfoto av sted - Askøy">
				<figcaption>bilde: Nasjonalbiblioteket</figcaption>
			</figure>
		</div>
		<div class="nbtxt">
			Hvis du er interessert i dette bildet, vil bruke det i publikasjoner eller få en papirkopi, 
			ber jeg deg å klikke deg videre til <a href="http://urn.nb.no/<?php echo"$nbid"; ?>">Nasjonalbibliotekets</a> nettside og ta direkte kontakt med dem.
		</div>
		<br>
		<div id="kartbox">
			<form action="/wideroe/bilde.php?ID=<?php echo"$nbid"; ?>&amp;sted=<?php echo"$nb_sted"; ?>" method="post">
				<fieldset class="gllpLatlonPicker" id="custom_id">
					<!-- input type="text" class="xxxgllpSearchField" -->
					<div class="gllpMap">Google Maps</div>
					<input type="hidden" class="gllpLatitude" 	name="lat" value="<?php echo"$lat"; ?>"/>
					<input type="hidden" class="gllpLongitude"  name="lon" value="<?php echo"$lon"; ?>"/>
					<input type="hidden" class="gllpZoom" value="10"/>
					<input type="submit" class="gllpSearchButton2" value=" lagre posisjon " title="finn">
				</fieldset>
			</form>
		</div>
	</section>

<?php
include("bunner.ink.php");
?>