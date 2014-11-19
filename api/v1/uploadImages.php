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
  
    function getMimeType($filename)
    {
        $mimeType = false;
        if(function_exists('finfo_fopen')) {
            // open with FileInfo
            $response["message"] = "No implementation for FileInfo";
        } elseif(function_exists('getimagesize')) {
            // open with GD
            $arr = getimagesize($filename);
            $mimeType = $arr['mime'];
        } elseif(function_exists('exif_imagetype')) {
          switch(exif_imagetype($filename)) {
            case IMAGETYPE_JPEG:
              $mimeType = "image/jpeg";
              break;
            case IMAGETYPE_PNG:
              $mimeType = "image/png";
              break;
            case IMAGETYPE_GIF:
              $mimeType = "image/gif";
              break;
          }
        } elseif(function_exists('mime_content_type')) {
           $mimeType = mime_content_type($filename);
        }
        return $mimeType;
    }
    
    function createThumbnail($src, $thumbWidth = 120, $thumbHeight = 120) {
      $oldH = imagesy($src);
      $oldW = imagesx($src);
      /* Calculate the New Image Dimensions */
      $limiting_dim = 0;
      if( $oldH > $oldW ){
        /* Portrait */
        $limiting_dim = $oldW;
      }else{
        /* Landscape */
        $limiting_dim = $oldH;
      }
      /* Create the New Image */
      $new = imagecreatetruecolor( $thumbWidth , $thumbHeight );
      /* Transcribe the Source Image into the New (Square) Image */
      imagecopyresampled( $new , $src , 0 , 0 , ($oldW-$limiting_dim )/2 , ( $oldH-$limiting_dim )/2 , $thumbWidth , $thumbHeight , $limiting_dim , $limiting_dim );
      
      return $new;
    }

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

        // DO NOT TRUST $_FILES['file']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $mimeType = getMimeType($_FILES['file']['tmp_name']);
        switch ($mimeType) {
          case "image/jpeg":
            $ext = "jpg";
            break;
          case "image/png":
            $ext = "png";
            break;
          case "image/gif":
            $ext = "gif";
            break;
          default:
            throw new RuntimeException('Invalid file format.');
        }
        
        // You should name it uniquely.
        // DO NOT USE $_FILES['file']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $uploadDir = 'uploads/' . intval($_REQUEST['widget_id']);
        $now = new DateTime();
        $fileName = sprintf('%s%s.%s', $now->format('YmdHis'), sha1_file($_FILES['file']['tmp_name']), $ext);
        $filePath = sprintf('%s/%s', $uploadDir, $fileName);
        $response["filename"] = $fileName;
        $response["image"] = $filePath;

        // Create dir and subdirs if not exist.
        if(file_exists($uploadDir) === false) {
          mkdir($uploadDir, 0755, true);
        }
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            throw new RuntimeException('Failed to move uploaded file.');
        } else {
          // Create thumbnail
          $thumbDir = $uploadDir . "/th";
          if(file_exists($thumbDir) === false) {
            mkdir($thumbDir, 0755, true);
          }
          $image;
          switch($mimeType) {
            case "image/jpeg":
              $image = imagecreatefromjpeg($filePath);
              break;
            case "image/png":
              $image = imagecreatefrompng($filePath);
              break;
            case "image/gif":
              $image = imagecreatefromgif($filePath);
              break;
          }
          if(isset($image)) {
            // Create thumbnail.
            $thumbnailPath = sprintf('%s/%s', $thumbDir, $fileName);
            $response["thumbnail"] = $thumbnailPath;
            // Check if custom thumbnail size.
            if(isset($_REQUEST['thumbnailHeight']) && filter_var($_REQUEST['thumbnailHeight'], FILTER_VALIDATE_INT) && 
              isset($_REQUEST['thumbnailWidth']) && filter_var($_REQUEST['thumbnailWidth'], FILTER_VALIDATE_INT)) {
              $image = createThumbnail($image, $_REQUEST['thumbnailWidth'], $_REQUEST['thumbnailHeight']);
            } else {
              $image = createThumbnail($image);
            }
            imagejpeg($image, $thumbnailPath, 100);
          }
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

?>