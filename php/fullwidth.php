<?php
session_start();
$fullwidth = (is_numeric ( $_POST['fullwidth'] ) ) ? $_POST['fullwidth'] : 1025;
$_SESSION['fullwidth'] =  $fullwidth;
header('Content-Type: application/json');
echo json_encode($_SESSION['fullwidth']);

?>