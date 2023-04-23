<?php
include ("../db.ink.php");



$laat = round(htmlspecialchars($_COOKIE["herlat"]) * 100) / 100;
$loon = round(htmlspecialchars($_COOKIE["herlon"]) * 100) / 100;

/*
$laat = round($_GET['lat'] * 100) / 100;
$loon = round($_GET['lon'] * 100) / 1000;

*/
$laat = '68.645716';
$loon = '15.510642';


$minlat = $laat - 0.15;
$maxlat = $laat + 0.15;
$minlon = $loon - 0.15;
$maxlon = $loon + 0.15;


$linje = "var data = { \"count\": 10785236, \"steder\": [";

$sokestreng = "
    SELECT 
            sted,
            kommune,
            nbid,
            nbsted,
            lengde,
            bredde
    FROM    
            steder
    WHERE   
            lengde  BETWEEN '$minlon' 
                    AND     '$maxlon'
            AND
            bredde  BETWEEN '$minlat' 
                    AND     '$maxlat'
            AND
            character_length(nbid) > 10
";

print "<hr> lat = $laat<br><br>$sokestreng<hr>";
$finn_sak = mysql_query("$sokestreng");
while ($row = mysql_fetch_array($finn_sak, MYSQL_ASSOC)) {
        $navn            = $row["sted"];        
        $kommune         = $row["kommune"];
        $lon       = $row["lengde"];
        $lat        = $row["bredde"];
        $nbid            = $row["nbid"];

        // skriv til jason::
        $linje .= "{\"navn\": \"" . $navn . "\",\"kommune\": \"" . $kommune . "\",\"kode\": \"" . $nbid . "\",\"longitude\": " . $lon . ", \"latitude\": " . $lat . "},";$antall_steder++;
        $teller++;
}

$linje  = rtrim($linje, ",");

$linje .= "]}";

$filnavn = fopen("js/data.json", 'w');
fwrite($filnavn, $linje);


$kart = "2";
$latsenter = $laat;
$lonsenter = $lon;
$kartzoom  = 10;

include("topper.ink.php");

?>

<span class="clearfix"></span>

<aside>
	<a href="http://overdalsveien.com/wideroe/"><img src="../pikks/nylogo.png" alt="logo"></a>
	
	<div class="skroller">
		<h2>Widerøes skråfotoarkiv</h2>
		<p>
			Nasjonalbiblioteket har satt i gang en nasjonal redningsaksjon for Widerøes skråfoto. 
			De tilbyr å overta bevaringsansvaret for originalmaterialet, samtidig som bildene vil bli digitalisert.<br>
			Les mer om dette prosjektet på <a href="http://www.nb.no/Hva-skjer/Aktuelt/Nyheter/Nasjonal-redningsaksjon-for-Wideroees-flyfotografi">nb.no</a>.
		</p>
        <p></p>
        <p>
            Bruk kartet til å finne frem til de steder som noen allerede har stedsbestemt.<br>
            Zoom inn og klikk deg frem.<br>
            <br>
            Og <strong>Rett Feilene!</strong>
        </p>
	</div>	
</aside>


<section>
	<header>
		<div class="venstre"><h2>Oversiktskart</h2></div>
	</header>
	<div id="map-container"><div id="map"></div></div><br>
</section>

<?php
include("bunner.ink.php");
?>
