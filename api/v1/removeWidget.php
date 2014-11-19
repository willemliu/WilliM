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
    $widgets = json_decode(file_get_contents("php://input"), true);
    
    // When the directory is not empty:
    function rrmdir($dir) {
      if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
          if ($object != "." && $object != "..") {
            if (filetype($dir."/".$object) == "dir") {
              rrmdir($dir."/".$object);
            }
            else {
              unlink($dir."/".$object);
            }
          }
        }
        reset($objects);
        rmdir($dir);
      }
    }
    
    try {
      $dbh = $db->connect();
      if ($dbh->inTransaction() === false) {
        $dbh->beginTransaction();
      }
      foreach($widgets as $key=>$widgetId) {
        $stmt = $dbh->prepare("DELETE FROM widgets WHERE id=:id");
        $stmt->bindValue(":id", $widgetId);
        $stmt->execute();
        
        $stmt = $dbh->prepare("DELETE FROM widget_content WHERE id=:id");
        $stmt->bindValue(":id", $widgetId);
        $stmt->execute();
      }
      if($dbh->commit()) {
        // Remove from DB successful, now we remove all images
        foreach($widgets as $key=>$widgetId) {
          if (strpos($widgetId, '.') !== FALSE || 
              strpos($widgetId, '/') !== FALSE || 
              strpos($widgetId, '\\') !== FALSE) {
            $response['message'] = "Invalid widget id";
            break;
          }
          $dir = 'uploads/' . $widgetId;
          if(file_exists($dir)) {
            rrmdir($dir);
          }
        }
        
        $response['message'] = "OK";
        header('HTTP/1.1 200 OK');
        $response['status'] = 200;
      } else {
        $response['message'] = "NOK";
      }
    }
    catch(PDOException $e) {  
      $response['message'] = $e;
    }
  }
  
  $response['execTime'] = $execTime->getTime();
  echo json_encode($response);

?>