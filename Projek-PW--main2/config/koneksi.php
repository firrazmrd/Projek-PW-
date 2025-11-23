<?php 
$host = "localhost";
$port = "3306";
$username = "root";
$password = "";
$db = "artikel_projek";

$koneksi = null;

try {
    $koneksi = new mysqli($host, $username, $password, $db);
} catch (Exception $e) {
    echo "Koneksi gagal ke databse";
}

?>