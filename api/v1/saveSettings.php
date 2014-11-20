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

    try {
      $dbh = $db->connect();
      
      if ($dbh->inTransaction() === false) {
        $dbh->beginTransaction();
      }
      $stmt = $dbh->prepare("INSERT INTO page_settings (name, value, page, page_md5) VALUES (:name, :value, :page, :page_md5) ON DUPLICATE KEY UPDATE value=:value");
      foreach($_REQUEST as $key => $value) {
        // When not the following request variables
        if(strcasecmp($key, 'page') != 0
            && strcasecmp($key, 'v') != 0
            && strcasecmp($key, 'service') != 0) {
          $stmt->bindValue(":name", $key, PDO::PARAM_STR);
          $stmt->bindValue(":value", $value, PDO::PARAM_STR);
          $stmt->bindValue(":page", $_REQUEST["page"], PDO::PARAM_STR);
          $stmt->bindValue(":page_md5", md5($_REQUEST["page"]), PDO::PARAM_STR);
          $stmt->execute();
        }
      }
      $dbh->commit();

      $response['message'] = "OK";
      header('HTTP/1.1 200 OK');
      $response['status'] = 200;
    }  
    catch(PDOException $e) {  
      $response['message'] = $e;
    }
  }
  
  $response['execTime'] = $execTime->getTime();
  echo json_encode($response);

?>
