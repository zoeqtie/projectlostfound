<?php
// แสดง error ช่วยดีบัก (ลบหรือปิดใน production)
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// ตั้งค่าการเชื่อมต่อ
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'lost_found_db';

// เชื่อมต่อ
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
mysqli_set_charset($conn, 'utf8');

if (!$conn) {
  die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}
?>