<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "software.sb";

try {
   
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    
    die("Erreur de connexion : " . $e->getMessage());
}
?>
