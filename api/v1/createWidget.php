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
    $widget = json_decode(file_get_contents("php://input"), true);
    
    $response = [];
    
    try {
      $dbh = $db->connect();
      if ($dbh->inTransaction() === false) {
        $dbh->beginTransaction();
      }

      $stmt = $dbh->prepare("INSERT INTO widgets (parent_id, type, page) VALUES (:parent_id, :type, :page)");
      $stmt->bindParam(":type", $widget["type"]);
      $stmt->bindParam(":parent_id", $widget["parentId"]);
      $stmt->bindParam(":page", $widget["page"]);
      $stmt->execute();
      
      $lastInsertId = $dbh->lastInsertId();
      
      if($dbh->commit()) {
        $widget = $dice->create('Foundation\\' . $widget["type"]);
        $response['message'] = $widget->getHtml($lastInsertId);
        header('HTTP/1.1 200 OK');
        $response['status'] = 200;
      } else {
        $response['message'] = '';
      }
    }  
    catch(PDOException $e) {  
      $response['message'] = $e;
    }
  }
  
  $response['execTime'] = $execTime->getTime();
  echo json_encode($response);
