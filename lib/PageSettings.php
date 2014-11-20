<?php
  /**
   * PageSettings class
   */
  final class PageSettings
  {
    private $db;

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
    
    /**
     * Constructor
     */
    public function __construct(DB $db) {
      $this->db = $db;
    }
  }
?>
