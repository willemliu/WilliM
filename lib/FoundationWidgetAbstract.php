<?php
  namespace Foundation;
  
  /**
   * FoundationWidget
   */
  abstract class FoundationWidget
  {
    protected $name = "FoundationWidget";
    
    protected $cssClasses = array(
      "Large" => array(
        "large-1 columns",
        "large-2 columns",
        "large-3 columns",
        "large-4 columns",
        "large-5 columns",
        "large-6 columns",
        "large-7 columns",
        "large-8 columns",
        "large-9 columns",
        "large-10 columns",
        "large-11 columns",
        "large-12 columns"
      ),
      "Medium" => array(
        "medium-1 columns",
        "medium-2 columns",
        "medium-3 columns",
        "medium-4 columns",
        "medium-5 columns",
        "medium-6 columns",
        "medium-7 columns",
        "medium-8 columns",
        "medium-9 columns",
        "medium-10 columns",
        "medium-11 columns",
        "medium-12 columns"
      ),
      "Small" => array(
        "small-1 columns",
        "small-2 columns",
        "small-3 columns",
        "small-4 columns",
        "small-5 columns",
        "small-6 columns",
        "small-7 columns",
        "small-8 columns",
        "small-9 columns",
        "small-10 columns",
        "small-11 columns",
        "small-12 columns"
      ),
      "Centered" => array(
        "small-centered",
        "small-uncentered",
        "medium-centered",
        "medium-uncentered",
        "large-centered",
        "large-uncentered"
      ),
      "Shadow effects" => array(
        "effect1",
        "effect2",
        "effect3",
        "effect4",
        "effect5",
        "effect6",
        "effect7",
        "effect8"
      ),
      "Markup" => array(
        "fullWidth",
        "fullHeight",
        "clear",
        "centered-width",
        "paddingTop",
        "paddingRight",
        "paddingBottom",
        "paddingLeft",
        "text-center"
      )

    );

    abstract protected function getHtml($id, array $fields = array());
    abstract protected function getEditHtml($id, array $fields = array());
    
    
    /**
     * Returns the given customFields HTML wrapped by the default required fields.
     */
    public function decorateEditHtml($id, array $fields = array(), array $dbFields = array(), $customFields) {
      $html = '';
      $echo = $this->getHeredoc()->getHeredoc();
$html = <<<EOT
      <form class="editForm" method="POST">
        <fieldset>
          <legend>Edit {$this->name}</legend>
          {$customFields}
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
          <label>Data equalizer watch
            <input type='hidden' value='' name='data-equalizer-watch'/>
            <input type="checkbox" name="data-equalizer-watch" value="data-equalizer-watch" {$echo(empty($this->getFieldValue($dbFields, $fields, 'data-equalizer-watch'))?'':'checked')}/>            
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

    // The Foundation Widgets registrar.
    protected $foundationWidgets;
    
    // The heredoc function util.
    protected $heredoc;

    public function setFoundationWidgets(FoundationWidgets $foundationWidgets) {
      $this->foundationWidgets = $foundationWidgets;
    }
    
    public function getFoundationWidgets() {
      return $this->foundationWidgets;
    }
    
    public function setHeredoc(\Heredoc $heredoc) {
      $this->heredoc = $heredoc;
    }
    
    public function getHeredoc() {
      return $this->heredoc;
    }
    
    /**
     * Register widget with the FoundationWidgets.
     * $name can be passed to override the default name.
     */
    public function registerWidget ($name = null) {
      if($name === null) {
        $name = $this->name;
      }
      $this->foundationWidgets->registerWidget($name, $this);
    }
    
    private function valueInArray($value, $array) {
      $result = false;
      if(in_array($value, $array)) {
        return true;
      }
      foreach($array as $key=>$arr) {
        if(is_array($arr)) {
          $result = $this->valueInArray($value, $arr);
          if($result) {
            break;
          }
        }
      }
      return $result;
    }
    
    /**
     * Return the HTML of the multi-select field with CSS classes.
     * Default name of the select tag is 'classes'.
     * Default all of the selectable CSS classes are selectable. You can provide a filter.
     */
    public function getCssClassesSelectHtml ($activeClasses = [], $name = 'classes', array $filter = array()) {
      $custom = [];
      // Check for custom fields. All fields not existing in cssClasses are considered custom.
      if(is_array($activeClasses)) {
        foreach($activeClasses as $value) {
          if($this->valueInArray($value, $this->cssClasses) === false) {
            array_push($custom, $value);
          }
        }
      }
      $select = "<select class='classes' name='{$name}[]' data-placeholder='Select Your Options' multiple>";
      $select .= "<option value=''></option>";
      foreach($this->cssClasses as $optgroup=>$options) {
        if(empty($filter) === false && in_array($optgroup, $filter) === false) {
          continue;
        }
        $select .= "<optgroup label='{$optgroup}'>";
        foreach($options as $option) {
          $selected = '';
          // Select active classes
          if(is_array($activeClasses) && in_array($option, $activeClasses)) {
            $selected = 'selected';
          }
          $select .= "<option value='{$option}' {$selected}>{$option}</option>";
        }
        $select .= '</optgroup>';
      }
      
      // Add custom classes. Custom classes are always selected
      $select .= "<optgroup label='Custom'>";
      foreach($custom as $value) {
        $select .= "<option value='{$value}' selected>{$value}</option>";
      }
      $select .= '</optgroup>';
      
      $select .= '</select>';
      return $select;
    }
    
    /**
     * Return the first value found in the given array(s).
     * Last argument is the needle.
     * Return an empty String if no value is found.
     */
    public function getFieldValue() {
      $result = '';
      if ( func_num_args() > 1 ) {
        $args = func_get_args();
        $needle = array_pop($args);
        foreach($args as $value) {
          if(isset($value[$needle])) {
            $result = $value[$needle];
            if(strlen($result) > 0) {
              break;
            }
          }
        }
      }
      return $result;
    }
    
    /**
     * Convenience method returns an array with values to use when user is logged in.
     */
    public function getEditVars() {
      $vars = [];
      $vars['editable'] = '';
      $vars['draggable'] = '';
      $vars['dropTarget'] = '';
      if($_SESSION['loggedIn']) {
        $vars['editable'] = 'editable';
        $vars['draggable'] = 'draggable="true"';
        $vars['dropTarget'] = 'dropTarget';
      }
      return $vars;
    }
  }
