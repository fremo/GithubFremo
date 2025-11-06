<?php
// alle viktige DB-data
$servername = "xxx";
$username = "xxx";
$password = "xxx";
$dbname = "xxx";
?>

<?php
// åpne DB
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
?>
