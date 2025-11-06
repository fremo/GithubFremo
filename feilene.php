
<?php
// alle viktige DB-data
$servername = "xxx";
$username = "xxx";
$password = "xxx+1";
$dbname = "xxx";
?>

<?php
// åpne DB
$conn = new mysqli($servername, $username, $password, $dbname);
// sjekk forbindelse
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
echo"<hr>";
?>


<?php
// FINN ,AMGE FOREKOMSTER::
//
// de første 10 steder regnet nordfra::
$limitten = 15;
$sokestreng = "
    SELECT 
            sted,
            kommune,
            nbid,
            nbsted,
            regdato,
            endredato,
            id, 
            ip,
            nbsted,
            lengde,
            bredde
    FROM    
            steder
    WHERE   
            character_length(nbid) > 10
            AND
            character_length(sted) > 1
            AND
            nbid LIKE '%URN:NBN%'
    ORDER BY 
            regdato aSC
    LIMIT
    		$limitten
";
echo "
<table>
  <tr>
    <th>navn</th>
    <th>lokasjon</th> 
    <th>NB</th>
  </tr>
";

$resultat = $conn->query($sokestreng);
while($row = $resultat->fetch_assoc()) {
	$id    = $row["id"];
	$nvn   = $row["sted"];
	$komm  = $row["kommune"];
  $ipadresse = $row["ip"];
  $registrert = $row["regdato"];
  $endret = $row["endredato"];
	echo "
	<tr>
	<td>
        " . $id . "
	</td>

	<td>
        " . $ipadresse . "
	</td>

	<td>
        " . $registrert . " / " . $endret . "
	</td>
	</tr>";    
}
echo "</table>";
?>







