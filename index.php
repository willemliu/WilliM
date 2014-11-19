<?php
  require_once('lib/config.php');

  // Load widgets for page /index.php and also from / because that's an alias.
  $widgets = $foundationWidgets->loadWidgetsFromDb($_SERVER['PHP_SELF']);
  $sub = "index.php";
  $str = $_SERVER['PHP_SELF'];
  if(( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub )) {
    $widgets = array_merge($widgets, $foundationWidgets->loadWidgetsFromDb(str_ireplace("index.php", "", $str)));
  }
  
  $editableHtml = '';
  $editBottomPaddingHtml = '';
  if($_SESSION['loggedIn']) {
$editableHtml = <<<EOT
    <div id="rootEditable" class="row editable"><div class="small-12 columns"><div class="toolButtons"><div class="addWidget"><i class="fi-plus"></i></div></div></div></div>
EOT;
$editBottomPaddingHtml = <<<EOT
    <div class="editBottomPadding"></div>
EOT;
  }
  
$html = <<<EOT
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Karlijn Scholten</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <script src="js/lib/modernizr.js"></script>
  </head>
  <body>
    {$editableHtml}
    {$echo($foundationWidgets->getBodyHtml($widgets))}
  
    {$editBottomPaddingHtml}
  
    <script type="text/javascript" data-main="js/config" src="js/require.js"></script>
  </body>
</html>
EOT;
echo $html;
echo "<!-- page: {$_SERVER['PHP_SELF']} -->\r\n";
echo "<!-- " . $execTime->getTime() . "ms -->\r\n";
echo "<!-- DB queries: " . $db->getQueryCount() . " -->\r\n";
?>
