<?php
$dns = "mysql:host=localhost;dbname=ratingdb";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dns, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON data",
    ]);
}
?>