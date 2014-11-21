<?php
  header('HTTP/1.1 500 Internal Server Error');
  header('Content-type: application/json');
  
  $response = [];
  $response['status'] = 500;

  // Check if sufficient privileges.
  if($credentials->hasEditAccess() === false) {
  
  
    header('HTTP/1.1 401 Unauthorized');
    $response['message'] = 'Insufficient rights';
    $response['status'] = 401;
    
    
  } else {

    // Request data
    $filename = $_REQUEST['removeResource'];
    
    $removeResourcePath = "resources" . DIRECTORY_SEPARATOR;
    $removeResource = $removeResourcePath . $filename;
    $path = "resources" . DIRECTORY_SEPARATOR;
    $directory = new RecursiveDirectoryIterator($path);
    $recIterator = new RecursiveIteratorIterator($directory);
    foreach($recIterator as $item) {
      $filePath = $item->getPathName();
      if (is_file($filePath) && (strcasecmp($filePath, $removeResource) == 0)) {
        $response['message'] = "Removed {$filePath}\r\n";
        unlink($filePath);
      }
    }
    header('HTTP/1.1 200 OK');
    $response['status'] = 200;
  }
  
  $response['execTime'] = $execTime->getTime();
  echo json_encode($response);

?>