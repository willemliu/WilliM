<?php
  /**
   * PageSettings class
   */
  final class PageSettings
  {
    private $db;
    private $heredoc;

    /**
     * Return key/value associative array holding page settings.
     */
    public function getPageSettings($page) {
      $fields = [];
      try {
        $dbh = $this->db->connect();
        
        $this->db->incrementQueryCount();
        $stmt = $dbh->prepare("SELECT name, value FROM page_settings WHERE page=:page");
        $stmt->bindParam("page", $page);
        $stmt->execute();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
          $fields[$row['name']] = $row['value'];
        }
      }  
      catch(PDOException $e) {  
        throw $e;
      }
      return $fields;
    }
    
    // Return array of all resource.
    private function getResources() {
      $files = [];
      $dir = "resources/";
      $imagesDesc = array_reverse(glob($dir ."/*"));
      foreach ($imagesDesc as $filename) {
        if(is_dir($filename) === false) {
          array_push($files, $filename);
        }
      }
      return $files;
    }
    
    /**
     * Return HTML for resources.
     */
    public function getResourcesHtml() {
      $resources = $this->getResources();
      $echo = $this->heredoc->getHeredoc();
      $html = '';
      foreach($resources as $key=>$value) {
        $previewResource = "class='previewResource'";
        $removeResourceHook = "<span data-foundation-cms-remove-resource='{$echo(basename($value))}'><i class='fi-trash'></i></span>";
        $removeResourceId = "id='{$echo(basename($value))}'";
        $html .= "<li {$previewResource}><span title='{$echo(basename($value))}' {$removeResourceId}>{$echo(basename($value))}</span>{$removeResourceHook}</li>";
      }
      return $html;
    }
    
    
    /**
     * Constructor
     */
    public function __construct(DB $db, Heredoc $heredoc) {
      $this->db = $db;
      $this->heredoc = $heredoc;
    }
  }
