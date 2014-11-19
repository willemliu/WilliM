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
      
      $stmt = $dbh->prepare("SELECT widget_id, content_id FROM widget_content WHERE widget_id=:widget_id");
      $stmt->bindValue("widget_id", $_REQUEST['widget_id']);
      $stmt->execute();

      $newContent = true;
      while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        // Update the content item
        $newContent = false;
        if ($dbh->inTransaction() === false) {
          $dbh->beginTransaction();
        }
        $stmt = $dbh->prepare("INSERT INTO content_fields (content_id, name, value) VALUES (:content_id, :name, :value) ON DUPLICATE KEY UPDATE value=:value");
        foreach($_REQUEST as $key => $value) {
          if(strcasecmp($key, 'widget_id') != 0 && 
            strcasecmp($key, 'service') != 0 && 
            strcasecmp($key, 'page') != 0 && 
            strcasecmp($key, 'type') != 0) {
            $stmt->bindValue(":content_id", $row["content_id"], PDO::PARAM_INT);
            $stmt->bindValue(":name", $key, PDO::PARAM_STR);
            if(is_array($value)) {
              $value = json_encode($value);
            }
            $stmt->bindValue(":value", $value, PDO::PARAM_STR);
            $stmt->execute();
          }
        }
        $dbh->commit();
      }
      
      // New content item.
      if($newContent) {
        if ($dbh->inTransaction() === false) {
          $dbh->beginTransaction();
        }
        // Insert new content item.
        $stmt = $dbh->prepare("INSERT INTO contents (id) VALUES (null)");
        $stmt->execute();
        $newContentId = $dbh->lastInsertId();

        // Insert widget relation with content.
        $stmt = $dbh->prepare("INSERT INTO widget_content (widget_id, content_id) VALUES (:widget_id, :content_id)");
        $stmt->bindValue(":widget_id", $_REQUEST['widget_id'], PDO::PARAM_INT);
        $stmt->bindValue(":content_id", $newContentId, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt = $dbh->prepare("INSERT INTO content_fields (content_id, name, value) VALUES (:content_id, :name, :value) ON DUPLICATE KEY UPDATE value=:value");
        foreach($_REQUEST as $key => $value) {
          if(strcasecmp($key, 'widget_id') != 0 && 
              strcasecmp($key, 'service') != 0 && 
              strcasecmp($key, 'page') != 0 && 
              strcasecmp($key, 'type') != 0) {
            $stmt->bindValue(":content_id", $newContentId, PDO::PARAM_INT);
            $stmt->bindValue(":name", $key, PDO::PARAM_STR);
            if(is_array($value)) {
              $value = json_encode($value);
            }
            $stmt->bindValue(":value", $value, PDO::PARAM_STR);
            $stmt->execute();
          }
        }
        $dbh->commit();
      }
      
      $widgets = $foundationWidgets->widgetOnlyTree($_REQUEST['widget_id'], $foundationWidgets->loadWidgetsFromDb($_REQUEST["page"]));

      $response['widget_id'] = $_REQUEST['widget_id'];
      $response['message'] = $foundationWidgets->getBodyHtml([$widgets]);
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
