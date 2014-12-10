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
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($_FILES['file']['error']) ||
            is_array($_FILES['file']['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }

        // Check $_FILES['file']['error'] value.
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        // You should also check filesize here. 2MB.
        if ($_FILES['file']['size'] > 5000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }
        
        // You should name it uniquely.
        // DO NOT USE $_FILES['file']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $uploadDir = 'resources' . DIRECTORY_SEPARATOR;
        $now = new DateTime();
        $fileName = sprintf('%s%s', $now->format('YmdHis'), $_FILES['file']['name']);
        $filePath = sprintf('%s/%s', $uploadDir, $fileName);
        $response["filename"] = $fileName;
        $response["filePath"] = $filePath;

        // Create dir and subdirs if not exist.
        if(file_exists($uploadDir) === false) {
          mkdir($uploadDir, 0755, true);
        }
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
          throw new RuntimeException('Failed to move uploaded file.');
        }

        $response["message"] = 'File is uploaded successfully.';
        header('HTTP/1.1 200 OK');
        $response['status'] = 200;
    } catch (RuntimeException $e) {
        $response["message"] = $e->getMessage();
    }
  }
  $response['execTime'] = $execTime->getTime();
  echo json_encode($response);
