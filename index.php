<?php
  require_once('lib/config.php');

  // Load widgets and settings for page /index.php and also from / because that's an alias.
  $pageName = $_SERVER['PHP_SELF'];
  $widgets = $foundationWidgets->loadWidgetsFromDb($pageName);
  $pageSettings = $settings->getPageSettings($pageName);
  $sub = "index.php";
  if(( substr( $pageName, strlen( $pageName ) - strlen( $sub ) ) == $sub )) {
    $widgets = array_merge($widgets, $foundationWidgets->loadWidgetsFromDb(str_ireplace("index.php", "", $pageName)));
    $pageSettings = $settings->getPageSettings(str_ireplace("index.php", "", $pageName));
  }
  
  $editableHtml = '';
  $editBottomPaddingHtml = '';
  if($_SESSION['loggedIn']) {
$editableHtml = <<<EOT
    <div id="rootEditable" class="row editable">
      <div class="small-12 columns">
        <div class="toolButtons">
          <div title="Add widget" class="addWidget large-5 columns"><i class="fi-plus"></i></div>
          <div title="Page settings" class="settingsButton large-5 columns"><i class="fi-widget"></i></div>
          <div title="Logout" class="logoutButton large-2 columns"><i class="fi-stop"></i></div>
        </div>
      </div>
    </div>
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
    {$echo((isset($pageSettings['headStart'])?$pageSettings['headStart']:''))}
    <title>{$echo((isset($pageSettings['title'])?$pageSettings['title']:'FoundationCMS'))}</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <script src="js/lib/modernizr.js"></script>
    {$echo((isset($pageSettings['headEnd'])?$pageSettings['headEnd']:''))}
  </head>
  <body>
    {$editableHtml}
    {$echo($foundationWidgets->getBodyHtml($widgets))}
  
    {$editBottomPaddingHtml}
  
    <script type="text/javascript" data-main="js/config" src="js/require.js"></script>
    {$echo((isset($pageSettings['bodyBottom'])?$pageSettings['bodyBottom']:''))}
  </body>
</html>
EOT;
echo $html;
echo "<!-- page: {$_SERVER['PHP_SELF']} -->\r\n";
echo "<!-- " . $execTime->getTime() . "ms -->\r\n";
echo "<!-- DB queries: " . $db->getQueryCount() . " -->\r\n";
