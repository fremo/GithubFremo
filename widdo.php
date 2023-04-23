<?php
// alle viktige DB-data
$servername = "overdalsveie.mysql.domeneshop.no";
$username = "overdalsveie";
$password = "PoseNote+1";
$dbname = "overdalsveie";
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