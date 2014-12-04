<?php  
  namespace Foundation;
  /**
   * FoundationColumn class
   */
  final class FoundationColumn extends FoundationWidget
  {
    protected $name = "FoundationColumn";
  
    /**
     * Returns the HTML of this widget.
     */
    public function getHtml($id, array $fields = array()) {
      $editVars = $this->getEditVars();    
      $html ='';
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
      $prependContent = str_replace("\n", "<br/>", $this->getFieldValue($dbFields, $fields, 'prependContent'));
      $subWidgets = $this->getFieldValue($dbFields, $fields, 'subWidgetsContent');
      $suffixContent = str_replace("\n", "<br/>", $this->getFieldValue($dbFields, $fields, 'suffixContent'));
      $columnHtml = '';
      if(empty($title) === false) {
$columnHtml = <<<EOT
        <h1>{$title}</h1>
EOT;
      }
      if(empty($prependContent) === false) {
$columnHtml .= <<<EOT
        <p>{$prependContent}</p>
EOT;
      }
      if(empty($subWidgets) === false) {
$columnHtml .= <<<EOT
        {$subWidgets}
EOT;
      }
      if(empty($suffixContent) === false) {
$columnHtml .= <<<EOT
        <p>{$suffixContent}</p>
EOT;
      }
      
$html = <<<EOT
      {$anchorNameHtml}
      <div id="{$id}" class="{$echo($editVars['editable'])} foundationColumn {$echo($editVars['dropTarget'])} {$classes}" {$echo($editVars['draggable'])} data-foundation-cms-widget="{$echo($this->name)}" data-equalizer {$echo($this->getFieldValue($dbFields, $fields, 'data-equalizer-watch'))}>
        <div class="toolButtons">
          <div class="small-4 columns editWidget" title="Edit column"><i class="fi-pencil"></i></div>
          <div class="small-4 columns {$echo($editVars['dropTarget'])} addWidget" title="Add widget"><i class="fi-plus"></i></div>
          <div class="small-4 columns removeWidget" title="Remove column"><i class="fi-trash"></i></div>
        </div>
        {$columnHtml}
      </div>
EOT;
      return $html;
    }
    
    /**
     * Returns the Edit view HTML of this widget.
     */
    public function getEditHtml($id, array $fields = array()) {
      $html ='';
      $dbFields = $this->foundationWidgets->getWidgetContentFromDb($id);
      $echo = $this->heredoc->getHeredoc();
$html = <<<EOT
          <label>Title
            <input type="text" name="title" value="{$echo($this->getFieldValue($dbFields, $fields, 'title'))}" placeholder="The content title"/>
          </label>
          <label>Prepend content
            <textarea type="text" name="prependContent" rows="5" placeholder="The content which is shown before any subwidgets">{$echo($this->getFieldValue($dbFields, $fields, 'prependContent'))}</textarea>
          </label>
          <label>Suffix content
            <textarea type="text" name="suffixContent" rows="5" placeholder="The content which is shown after any subwidgets">{$echo($this->getFieldValue($dbFields, $fields, 'suffixContent'))}</textarea>
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
?>
