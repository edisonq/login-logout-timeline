<?php
session_start();
$xyNumber = $_SESSION['xyNumber'];
header('Content-Type: application/json');
echo json_encode($xyNumber);

?>