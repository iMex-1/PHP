<?php
include 'db.php';
$cef = $_GET['cef'];
mysqli_query($conn, "DELETE FROM students WHERE cef=$cef");
header("Location: index.php");
?>
