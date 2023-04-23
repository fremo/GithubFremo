<?php

$servername = "overdalsveie.mysql.domeneshop.no";
$username = "overdalsveie";
$password = "PoseNote+1";
$dbname = "overdalsveie";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

echo "<hr>";



$sokestreng = "
    SELECT 
            *
    FROM    
            steder
    WHERE   
            character_length(nbid) > 10
            AND
            character_length(sted) > 1
    ORDER BY 
            id
    LIMIT
            50
";

$delimiter = ","; 
$filename = "10-data_" . date('Y-m-d') . ".csv"; 


$file = fopen($filename,"w");


$query = mysqli_query($conn, $sokestreng);

while ($row = mysqli_fetch_assoc($query)) {             
    // if ( $rest <> "URN:NBN") {
/*
`id` int(11) NOT NULL AUTO_INCREMENT,
`regdato` date DEFAULT NULL,
`endredato` date DEFAULT NULL,
`sted` varchar(50) DEFAULT NULL,
`kommune` varchar(50) DEFAULT NULL,
`fylke` varchar(50) DEFAULT NULL,
`nbid` varchar(50) DEFAULT NULL,
`registrert` varchar(10) DEFAULT NULL,
`lengde` decimal(10,6) NOT NULL DEFAULT '0.000000',
`bredde` decimal(10,6) NOT NULL DEFAULT '0.000000',
`ip` varchar(50) DEFAULT NULL,
`nbsted` varchar(100) DEFAULT NULL,  
*/
        $linje = array( $row['id'], 
                        $row['regdato'],
                        $row['endredato'],
                        $row['sted'],
                        $row['kommune'],
                        $row['fylke'],
                        $row['nbid'],
                        $row['registrert'],
                        $row['lengde'],
                        $row['bredde'],
                        $row['ip'],
                        $row['nbsted']
                    );
        fputcsv($file, $linje, $delimiter); 
    // }

} // while
// Move back to beginning of file 
fseek($file, 0); 
 
// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 
 
//output all remaining data on a file pointer 
fpassthru($file);

echo"slutt"; 

mysqli_close($conn);

?>