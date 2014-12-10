<?php
  namespace Foundation;
  /**
   * FoundationCarousel class
   */
  final class FoundationCarousel extends FoundationWidget
  {
    protected $name = "FoundationCarousel";

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
      $images = $this->getImagesHtml($this->getImages($id));
      if(empty($images) === false) {
        $images = "<div class='title-slick' draggable='false'>{$images}</div>";
      }

$html = <<<EOT
      {$anchorNameHtml}
      <div id="{$id}" class="{$echo($editVars['editable'])} foundationCarousel {$echo($editVars['dropTarget'])} {$classes}" {$echo($editVars['draggable'])} data-foundation-cms-widget="{$echo($this->name)}" {$echo($this->getFieldValue($dbFields, $fields, 'data-equalizer-watch'))}>
        <div class="toolButtons">
          <div class="small-6 columns editWidget" title="Edit carousel"><i class="fi-pencil"></i></div>
          <div class="small-6 columns removeWidget" title="Remove carousel"><i class="fi-trash"></i></div>
        </div>
        {$images}
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
      $images = $this->getImagesHtml($this->getImages($id), true);
      
$html = <<<EOT
          <label>Title
            <input type="text" name="title" value="{$echo($this->getFieldValue($dbFields, $fields, 'title'))}" placeholder="The content title"/>
          </label>
          <label>Drop images here (jpeg, png, gif)
            <div class="imageDropTarget">
              <i id="photoDropIcon" class="fi-plus"></i>
              <progress style="display: none;" id="uploadProgress" min="0" max="100" value="0">0</progress>
            </div>
          </label>
          <div class="images">
            <ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-4">
            {$images}
            </ul>
          </div>
          <input type="hidden" name="removeImage" value="" />
EOT;
      $html = $this->decorateEditHtml($id, $fields, $dbFields, $html);
      return $html;
    }
    
    // Return array of all images for this widget.
    private function getImages($widgetId) {
      $files = [];
      $dir = "uploads/{$widgetId}";
      foreach (glob($dir ."/*") as $filename) {
        if(is_dir($filename) === false) {
          array_push($files, $filename);
        }
      }
      return $files;
    }
    
    /**
     * Return HTML for all given images.
     */
    private function getImagesHtml($images, $editMode = false) {
      $echo = $this->heredoc->getHeredoc();
      $html = '';
      foreach($images as $key=>$value) {
        $el = 'div';
        $previewImage = '';
        $removeImageId = '';
        $removeImageHook = '';
        if($editMode) {
          $el = 'li';
          $previewImage = "class='previewImage'";
          $removeImageHook = "<div data-foundation-cms-remove-image='{$echo(basename($value))}'><i class='fi-trash'></i></div>";
          $removeImageId = "id='{$echo(basename($value))}'";
        }
        $html .= "<{$el} {$previewImage}><img src='{$value}' draggable='false' {$removeImageId} alt='carousel' />{$removeImageHook}</{$el}>";
      }
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
