<!-- https://www.clinkitsolutions.com/2019/01/29/php-developer/ -->
<?php 
session_start();
$xyNumber = $_SESSION['xyNumber'];
$fullWidth = (is_numeric ( $_SESSION['fullwidth'] ) ) ? $_SESSION['fullwidth'] : 1025;

$markersHtml = '';
$markersStyle = '';

// get the markers
foreach($xyNumber as $key => $value) {
    $valueExploded = explode("-",$key);
    $valueTime = explode(":",$value->time);
    if ($valueTime[0]   < 10) {$valueTime[0]   = '0'.$valueTime[0];}
    if ($valueTime[1]   < 10) {$valueTime[1]   = '0'.$valueTime[1];}
    $valueTime = $valueTime[0].':'.$valueTime[1];
    $markersHtml .= '
    <div id="'.$key.'" class="'.$valueExploded[2].'-div node">
        <img src="images/'.$valueExploded[2].'-marker.png" alt="'.$valueExploded[2].'" class="'.$valueExploded[2].'" /><span id="'.$key.'-time" class="time">'.$valueTime.'</span>
    </div>';
}


// get style marker
foreach($xyNumber as $key => $value) {
  $valueExploded = explode("-",$key);
  $markersStyle .= '
  #'.$key.' {
    position: absolute;
    left: '.(($value->xPosPercentage/100)*$fullWidth).'px;
  }';
}

// get the tool
$toollogout = filter_var($_GET['toollogout'],FILTER_SANITIZE_STRING);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>draggableXY</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="apple-touch-icon" sizes="57x57" href="./favicon-clinkitsolutions/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="./favicon-clinkitsolutions//apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="./favicon-clinkitsolutions//apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="./favicon-clinkitsolutions//apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="./favicon-clinkitsolutions//apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="./favicon-clinkitsolutions//apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="./favicon-clinkitsolutions//apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="./favicon-clinkitsolutions//apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon-clinkitsolutions//apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="./favicon-clinkitsolutions//android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon-clinkitsolutions//favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="./favicon-clinkitsolutions//favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon-clinkitsolutions//favicon-16x16.png">
    <link rel="manifest" href="./favicon-clinkitsolutions//manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="./favicon-clinkitsolutions//ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>

    <script>
      const countMarker = <?php echo count($xyNumber);?>;
      const markerType = "<?php echo ($toollogout ? 'end' : 'start');?>";

    </script>
    
    <link rel="stylesheet" href="style.css">

<?php
echo '<style>';
echo $markersStyle;
echo '</style>';
?>

</head>

<body>
<div id="toolbox">
    <p>
      <a href="index.php"><img src="images/start-marker.png" alt="login" class="tool <?php echo ($toollogout ? '' : 'active'); ?>" /><br>
      Login</a>
    </p>
    <p>
      <a href="index.php?toollogout=true"><img src="images/end-marker.png" alt="logout" class="tool <?php echo ($toollogout ? 'active' : ''); ?>" /><br>
      Logout</a>
    </p>
</div>
<div id="timelineEditor" class="workflow-editor">
<?php
// display the markers
echo $markersHtml;
?>
  <script>
    const pagetotalWidth = $("#timelineEditor").parent().outerWidth();
  </script>
  <script src="script-another.js" type="text/javascript"></script>
  <script src="fullwidth.js"></script>
  <script>
    $('.start-div').draggableXY({dynamic: true});
    $('.end-div').draggableXY({dynamic: true});
  </script>
</div>
<center>
  <button class="clear-marker">Clear Marker</button><button class="reload-page">Reload Page</button>
  <br>Note: Drag the marker down to zoom in ruler. This code tested in Chrome and Safari only.<br>
  zoom out: 5 minutes interval<br>
  zoom in: 1 minute interval
</center>
</body>
</html>