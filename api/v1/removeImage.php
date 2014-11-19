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
    $filename = $_REQUEST['removeImage'];
    $widgetId = $_REQUEST['widget_id'];
    
    $removeImagePath = "uploads" . DIRECTORY_SEPARATOR . $widgetId;
    $removeImage = $removeImagePath . DIRECTORY_SEPARATOR . $filename;
    $removeThumbnailPath = "uploads" . DIRECTORY_SEPARATOR . $widgetId . DIRECTORY_SEPARATOR . "th";
    $removeThumbnail = $removeThumbnailPath . DIRECTORY_SEPARATOR . $filename;
    $path = "uploads" . DIRECTORY_SEPARATOR;
    $directory = new RecursiveDirectoryIterator($path);
    $recIterator = new RecursiveIteratorIterator($directory);
    $regex = new RegexIterator($recIterator, '/(.*jpeg|.*jpg|.*png|.*gif)$/i');
    foreach($regex as $item) {
      $filePath = $item->getPathName();
      if (is_file($item->getPathName()) && 
          (strcasecmp($filePath, $removeImage) == 0 || strcasecmp($filePath, $removeThumbnail) == 0)) {
        $response['message'] = "Removed {$filePath}\r\n";
        unlink($filePath);
      }
    }
    if ((count(glob("{$removeThumbnailPath}/*")) === 0)) {
      rmdir($removeThumbnailPath);
    }
    if ((count(glob("{$removeImagePath}/*")) === 0)) {
      rmdir($removeImagePath);
    }
    header('HTTP/1.1 200 OK');
    $response['status'] = 200;
  }
  
  $response['execTime'] = $execTime->getTime();
  echo json_encode($response);

?>