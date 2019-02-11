<?php
session_start();
$session = (is_array ( $_SESSION['xyNumber'] ) ) ? $_SESSION['xyNumber'] : array();;
$xyNumber = json_decode(stripslashes($_POST['xyNumber']));//filter_var($_POST['xyNumber'],FILTER_SANITIZE_STRING);

$session = array_replace($session, array($xyNumber->markerID => $xyNumber));

// update or set session
$_SESSION['xyNumber'] = $session;
header('Content-Type: application/json');
echo json_encode($session);

?>