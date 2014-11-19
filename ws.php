<?php
  require_once('lib/config.php');
  
  // Check if sufficient privileges.
  if($credentials->hasApiAccess() === false) {
    echo 'Insufficient rights<a class="close-reveal-modal">&#215;</a>';
  } else {
    $version = "v1";
    if(isset($_REQUEST['v'])) {
      switch($_REQUEST['v']) {
        case "v2":
          $version = "v2";
        break;
        case "v1":
          $version = "v1";
          break;
        default:
          // This will always be updated to the latest version.
          $version = "v1";
      }
    }

    $files = scandir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $version);
    if(in_array($_REQUEST['service'] . '.php', $files)) {
      require(dirname(__FILE__)  . DIRECTORY_SEPARATOR . "api" . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . $_REQUEST['service'] . ".php");
    } else {
      echo "Unknown webservice\r\n";
    }
  }
?>