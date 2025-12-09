<?php
// show errors for development (ถ้าขึ้นจริงแล้วค่อยปิด)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lost_found_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);
mysqli_set_charset($conn, "utf8");

if (!$conn) {
    die("DB connect failed: " . mysqli_connect_error());
}
?>
