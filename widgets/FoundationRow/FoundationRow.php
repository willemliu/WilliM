<?php  
  namespace Foundation;
  /**
   * FoundationRow class
   */
  final class FoundationRow extends FoundationWidget
  {
    protected $name = "FoundationRow";
    
    /**
     * Returns the HTML of this widget.
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
      // Anchor
      $anchorNameHtml = $this->getFieldValue($dbFields, $fields, 'anchorName');
      if(empty($anchorNameHtml) === false) {
$anchorNameHtml = <<<EOT
        <a id="{$anchorNameHtml}"></a>
EOT;
      }

$html = <<<EOT
      {$anchorNameHtml}
      <div id="{$id}" class="{$echo($editVars['editable'])} row foundationRow {$classes}" data-foundation-cms-widget="{$echo($this->name)}">
        <div class="toolButtons">
          <div class="small-3 columns editWidget" title="Edit row"><i class="fi-pencil"></i></div>
          <div class="small-3 columns {$echo($editVars['dropTarget'])} addWidget" title="Add widget"><i class="fi-plus"></i></div>
          <div class="small-3 columns copyWidget" title="Copy row"><i class="fi-page-copy"></i></div>
          <div class="small-3 columns removeWidget" title="Remove row"><i class="fi-trash"></i></div>
        </div>
        {$echo($this->getFieldValue($fields, 'prependContent'))}
        {$echo($this->getFieldValue($dbFields, $fields, 'subWidgetsContent'))}
        {$echo($this->getFieldValue($fields, 'suffixContent'))}
      </div>
EOT;
      return $html;
    }
    
    /**
     * Returns the Edit HTML of this widget.
     */
    public function getEditHtml($id, array $fields = array()) {
      $html = '';
      $dbFields = $this->foundationWidgets->getWidgetContentFromDb($id);
      $echo = $this->heredoc->getHeredoc();
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
?>
