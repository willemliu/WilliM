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
    $widgets = json_decode($HTTP_RAW_POST_DATA, true);
    
    $response = [];
    
    try {
      $dbh = $db->connect();
      if ($dbh->inTransaction() === false) {
        $dbh->beginTransaction();
      }

      $stmt = $dbh->prepare("INSERT INTO widgets (id, parent_id, type, pos) VALUES (:id, :parent_id, :type, :pos) ON DUPLICATE KEY UPDATE parent_id=:parent_id, type=:type, pos=:pos");
      foreach($widgets as $key=>$widget) {
        $parentId = 0;
        if(isset($widget["parentId"])) {
          $parentId = $widget["parentId"];
        }
        $pos = 0;
        if(isset($widget["pos"])) {
          $pos = $widget["pos"];
        }
        
        $stmt->bindValue(":id", $widget["id"], PDO::PARAM_INT);
        $stmt->bindValue(":parent_id", $parentId, PDO::PARAM_INT);
        $stmt->bindValue(":type", $widget["type"], PDO::PARAM_STR);
        $stmt->bindValue(":pos", $pos, PDO::PARAM_INT);
        $stmt->execute();
      }
      if($dbh->commit()) {
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