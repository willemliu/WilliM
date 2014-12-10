<?php
  namespace Foundation;
  /**
   * FoundationPanel class
   */
  final class FoundationPanel extends FoundationWidget
  {
    protected $name = "FoundationPanel";

    /**
     * Returns the HTML of this widget.
     * Second parameter is an associative array of values.
     */
    public function getHtml($id, array $fields = array()) {
      $editVars = $this->getEditVars();    
      $html = '';
      $dbFields = $this->foundationWidgets->getWidgetContentFromDb($id);
      $echo = $this->heredoc->getHeredoc();
      // Classes
      $classes = json_decode($this->getFieldValue($dbFields, $fields, 'classes'));
      if(is_array($classes)) {
        $classes = implode(' ', $classes);
      }
      $classes = ($classes)?$classes:'large-12 columns';
      // Anchor
      $anchorNameHtml = $this->getFieldValue($dbFields, $fields, 'anchorName');
      if(empty($anchorNameHtml) === false) {
$anchorNameHtml = <<<EOT
        <a id="{$anchorNameHtml}"></a>
EOT;
      }

      $title = $this->getFieldValue($dbFields, $fields, 'title');
      $content = str_replace("\n", "<br/>", $this->getFieldValue($dbFields, $fields, 'content'));
      $panelHtml = '';
      if(empty($title) === false) {
$panelHtml .= <<<EOT
        <h1>{$title}</h1>
EOT;
      }
      if(empty($content) === false) {
$panelHtml .= <<<EOT
        <p>{$content}</p>
EOT;
      }
      
$html = <<<EOT
      {$anchorNameHtml}
      <div id="{$id}" class="{$echo($editVars['editable'])} foundationPanel {$echo($editVars['dropTarget'])} panel {$classes}" {$echo($editVars['draggable'])} data-foundation-cms-widget="{$echo($this->name)}" {$echo($this->getFieldValue($dbFields, $fields, 'data-equalizer-watch'))}>
        <div class="toolButtons">
          <div class="small-4 columns editWidget" title="Edit panel"><i class="fi-pencil"></i></div>
          <div class="small-4 columns {$echo($editVars['dropTarget'])} addWidget" title="Add widget"><i class="fi-plus"></i></div>
          <div class="small-4 columns removeWidget" title="Remove panel"><i class="fi-trash"></i></div>
        </div>
        {$panelHtml}
        {$echo($this->getFieldValue($dbFields, $fields, 'subWidgetsContent'))}
      </div>
EOT;
      return $html;
    }
    
    /**
     * Returns the Edit view HTML of this widget.
     */
    public function getEditHtml($id, array $fields = array()) {
      $html = '';
      $dbFields = $this->foundationWidgets->getWidgetContentFromDb($id);
      $echo = $this->heredoc->getHeredoc();
$html = <<<EOT
          <label>Title
            <input type="text" name="title" value="{$echo($this->getFieldValue($dbFields, $fields, 'title'))}" placeholder="The content title"/>
          </label>
          <label>Content
            <textarea type="text" name="content" rows="5" placeholder="The content">{$echo($this->getFieldValue($dbFields, $fields, 'content'))}</textarea>
          </label>
EOT;
      $html = $this->decorateEditHtml($id, $fields, $dbFields, $html);
      return $html;
    }
    
    /**
     * Constructor
     */
    public function __construct(FoundationWidgets $foundationWidgets, \Heredoc $heredoc)
    {
      $this->foundationWidgets = $foundationWidgets;
      $this->heredoc = $heredoc;
    }
  }  
