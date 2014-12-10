<?php  
  namespace Foundation;
  /**
   * FoundationHtml class
   */
  final class FoundationHtml extends FoundationWidget
  {
    protected $name = "FoundationHtml";

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
      $classes = ($classes)?$classes:'large-12 columns';
      // Anchor
      $anchorNameHtml = $this->getFieldValue($dbFields, $fields, 'anchorName');
      if(empty($anchorNameHtml) === false) {
$anchorNameHtml = <<<EOT
        <a id="{$anchorNameHtml}"></a>
EOT;
      }

$html = <<<EOT
      {$anchorNameHtml}
      <div id="{$id}" class="{$echo($editVars['editable'])} {$echo($editVars['dropTarget'])} foundationHtml {$classes}" {$echo($editVars['draggable'])} data-foundation-cms-widget="{$echo($this->name)}">{$echo($this->getFieldValue($dbFields, $fields, 'content'))}</div>
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
      <form class="editForm" method="POST">
        <fieldset>
          <legend>Edit {$this->name}</legend>
          <label>HTML
            <textarea type="text" name="content" rows="5">{$echo($this->getFieldValue($dbFields, $fields, 'content'))}</textarea>
          </label>
          <label>Status
            <select name="status">
              <option value="draft" class="redText" {$echo((strcmp($this->getFieldValue($dbFields, $fields, 'status'), 'draft'))?'':'selected')}>draft</option>
              <option value="published" class="greenText" {$echo((strcmp($this->getFieldValue($dbFields, $fields, 'status'), 'published'))?'':'selected')}>published</option>
            </select>
          </label>
          <label>CSS classes
            {$echo($this->getCssClassesSelectHtml(json_decode($this->getFieldValue($dbFields, $fields, 'classes'))))}
          </label>
          <label>Anchor name
            <input type="text" name="anchorName" value="{$echo($this->getFieldValue($dbFields, $fields, 'anchorName'))}" placeholder="anchor"/>
          </label>
          <input type="hidden" name="page" value="{$echo($this->getFieldValue($dbFields, $fields, 'page'))}" />
          <input type="hidden" name="widget_id" value="{$echo($id)}" />
          <input type="hidden" name="type" value="{$this->name}" />
          <input type="submit" class="button tiny" name="submit" value="Save" />
          <input type="button" class="button alert right tiny removeButton" name="remove" value="Remove widget" />
        </fieldset>
      </form>
      <a class="close-reveal-modal">&#215;</a>
EOT;
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
