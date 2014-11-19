<?php
  namespace Foundation;
  /**
   * FoundationWidgets class
   */
  class FoundationWidgets
  {
    private $dice;
    private $db;
    private $execTime;
    private $widgets = array();
    
    public function setDice(\Dice\Dice $dice) {
      $this->dice = $dice;
    }
    
    /**
     * Register a widget by $name.
     */
    public function registerWidget($name, $instance) {
      $this->widgets[$name] = $instance;
    }
    
    /**
     * Returns the widget identified by $name.
     */
    public function getWidget($name) {
      return $this->widgets[$name];
    }
    
    /**
     * Removes the widget identified by $name.
     */
    public function removeWidget($name) {
      unset($this->widgets[$name]);
    }
        
    /**
     * Return all the content related to the widget.
     */
    public function getWidgetContentFromDb ($id) {
      $fields = [];
      try {
        $dbh = $this->db->connect();
        
        $this->db->incrementQueryCount();
        $stmt = $dbh->prepare("SELECT w.page, cf.name, cf.value FROM widgets AS w LEFT JOIN widget_content AS wc ON wc.widget_id=w.id LEFT JOIN contents AS c ON c.id=wc.content_id LEFT JOIN content_fields AS cf ON cf.content_id=c.id WHERE w.id=:id");
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $fields['classes'] = '';
        $fields['title'] = '';
        $fields['content'] = '';
        $fields['page'] = '';
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
          $fields[$row['name']] = $row['value'];
          $fields['page'] = $row['page'];
        }
      }  
      catch(PDOException $e) {  
        throw $e;
      }
      return $fields;
    }
    
    /**
     * Load widgets from database.
     */
    public function loadWidgetsFromDb($page) {
      try {
        $dbh = $this->db->connect();
        
        $this->db->incrementQueryCount();
        $stmt = $dbh->prepare("SELECT * from widgets WHERE page=:page ORDER BY pos, parent_id ASC");
        $stmt->bindParam("page", $page);
        $stmt->execute();
        $widgets = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
          $newWidget = $this->dice->create('Foundation\\' . $row['type']);
          $this->registerWidget($row['type'], $newWidget);
          $row['children'] = [];
          array_push($widgets, $row);
        }
        $widgetsTree = $this->createWidgetsTree($widgets);
        return $widgetsTree;
      }  
      catch(PDOException $e) {  
        throw $e;
      }
    }
    
    /**
     * Accepts an array in ascending order by parent id.
     * Returns parent->child related multi-dimensional array.
     */
    private function createWidgetsTree($widgets) {
      for($i = sizeof($widgets)-1; $i > 0; $i--) {
        if($widgets[$i]['parent_id'] == 0) {
          // No parent so we are done.
          //break;
        }
        for($j = 0; $j < sizeof($widgets); $j++) {
          if($widgets[$j]['id'] == $widgets[$i]['parent_id']) {
            array_unshift($widgets[$j]['children'], $widgets[$i]);
            break;
          }
        }
      }

      $widgets = array_filter($widgets, function($widget) {
        return $widget['parent_id'] == 0;
      });
      return $widgets;
    }
    
    /**
     * Returns a tree with the root based on the given id.
     */
    public function widgetOnlyTree($id, $widgets) {
      $result = null;
      foreach($widgets as $widget) {
        if(isset($widget['id']) && $widget['id'] == $id) {
          $result = $widget;
          break;
        }
        if(isset($widget['children'])) {
          $result = $this->widgetOnlyTree($id, $widget['children']);
          if($result != null) {
            break;
          }
        }
      }
      return $result;
    }

    /**
     * Returns the HTML of all Widgets.
     */
    public function getBodyHtml($widgets) {
      $html = '';
      foreach($widgets as $widget) {
        $html .= $this->getWidgetHtml($widget);
      }
      return $html;
    }
    
    /**
     * Recursively iterate through all the widgets and return the HTML.
     */
    private function getWidgetHtml($widget) {
      $this->execTime->start($widget['type'] . $widget['id']);
      $subHtml = '';
      if(isset($widget['children']) && sizeof($widget['children']) > 0) {
        foreach($widget['children'] as $child) {
          $subHtml .= $this->getWidgetHtml($child);
        }
      }
      $fields = [];
      $fields['subWidgetsContent'] = $subHtml;
      $html = $this->widgets[$widget['type']]->getHtml($widget['id'], $fields);
      $html .= "<!-- " . $widget['type'] . "[" . $widget['id'] . "]: " . $this->execTime->getTime($widget['type'] . $widget['id']) . "ms -->\r\n";
      return $html;
    }
    
    /**
     * Constructor
     */
    public function __construct(\DB $db, \ExecTime $execTime)
    {
      $this->db = $db;
      $this->execTime = $execTime;
    }
  }
?>
