<?php  
  namespace Foundation;
  /**
   * FoundationVcard class
   */
  final class FoundationVcard extends FoundationWidget
  {
    protected $name = "FoundationVcard";

    /**
     * Returns the HTML of this widget.
     */
    public function getHtml($id, array $fields = array()) {
      $editVars = $this->getEditVars();    
      $html = '';
      $dbFields = $this->foundationWidgets->getWidgetContentFromDb($id);
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

      $fullName = $this->getFieldValue($dbFields, $fields, 'fullName');
      $streetAddress = str_replace("\n", "<br/>", $this->getFieldValue($dbFields, $fields, 'streetAddress'));
      $locality = $this->getFieldValue($dbFields, $fields, 'locality');
      $state = $this->getFieldValue($dbFields, $fields, 'state');
      $zipCode = $this->getFieldValue($dbFields, $fields, 'zipCode');
      $email = $this->getFieldValue($dbFields, $fields, 'email');
      $vCardHtml = '';
      if((empty($fullName) && empty($streetAddress) && empty($locality)
          && empty($state) && empty($zip) && empty($email)) 
          === false) {
$vCardHtml = <<<EOT
      {$anchorNameHtml}
      <ul class="vcard">
        <li class="fn">{$fullName}</li>
        <li class="street-address">{$streetAddress}</li>
        <li class="locality">{$locality}</li>
        <li><span class="state">{$state}</span>, <span class="zip">{$zipCode}</span></li>
        <li class="email"><a href="#">{$email}</a></li>
      </ul>
EOT;
      }

      $echo = $this->heredoc->getHeredoc();
$html = <<<EOT
      <div id="{$id}" class="{$echo($editVars['editable'])} {$echo($editVars['dropTarget'])} foundationVcard {$classes}" {$echo($editVars['draggable'])} data-foundation-cms-widget="{$echo($this->name)}">
        <div class="toolButtons">
          <div class="small-6 columns editWidget" title="Edit carousel"><i class="fi-pencil"></i></div>
          <div class="small-6 columns removeWidget" title="Remove carousel"><i class="fi-trash"></i></div>
        </div>
        {$vCardHtml}
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
          <label>Full name
            <input type="text" name="fullName" value="{$echo($this->getFieldValue($dbFields, $fields, 'fullName'))}" placeholder="P. Sherman"/>
          </label>
          <label>Street address
            <input type="text" name="streetAddress" value="{$echo($this->getFieldValue($dbFields, $fields, 'streetAddress'))}" placeholder="42 Wallaby Way"/>
          </label>
          <label>Locality
            <input type="text" name="locality" value="{$echo($this->getFieldValue($dbFields, $fields, 'locality'))}" placeholder="Sydney"/>
          </label>
          <label>State
            <input type="text" name="state" value="{$echo($this->getFieldValue($dbFields, $fields, 'state'))}" placeholder="New South Wales"/>
          </label>
          <label>ZIP code
            <input type="text" name="zipCode" value="{$echo($this->getFieldValue($dbFields, $fields, 'zipCode'))}" placeholder="12345"/>
          </label>
          <label>E-mail
            <input type="text" name="email" value="{$echo($this->getFieldValue($dbFields, $fields, 'email'))}" placeholder="p@sherman.com"/>
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
