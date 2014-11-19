<?php
  namespace Foundation;
  /**
   * FoundationPhotos class
   */
  final class FoundationPhotos extends FoundationWidget
  {
    protected $name = "FoundationPhotos";

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
      // Individual classes
      $individualClasses = json_decode($this->getFieldValue($dbFields, $fields, 'individualClasses'));
      if(is_array($individualClasses)) {
        $individualClasses = implode(' ', $individualClasses);
      }
      // Anchor
      $anchorNameHtml = $this->getFieldValue($dbFields, $fields, 'anchorName');
      if(empty($anchorNameHtml) === false) {
$anchorNameHtml = <<<EOT
        <a id="{$anchorNameHtml}"></a>
EOT;
      }

      $imagesHtml = '';
      $title = $this->getFieldValue($dbFields, $fields, 'title');      
      $content = str_replace("\n", "<br/>", $this->getFieldValue($dbFields, $fields, 'content'));
      $images = $this->getImagesHtml($this->getImages($id), $this->getImages($id, true), $individualClasses);

      if(empty($title) === false) {
$imagesHtml = <<<EOT
        <h1>{$title}</h1>
EOT;
      }
      if(empty($content) === false) {
$imagesHtml = <<<EOT
        <p>{$content}</p>
EOT;
      }
      if(empty($images) === false) {
$imagesHtml = <<<EOT
        <ul class="clearing-thumbs small-block-grid-3 medium-block-grid-6 large-block-grid-12" data-clearing>{$images}</ul>
EOT;
      }
      
$html = <<<EOT
      {$anchorNameHtml}
      <div id="{$id}" class="{$echo($editVars['editable'])} foundationPhotos {$echo($editVars['dropTarget'])} {$classes}" {$echo($editVars['draggable'])} data-foundation-cms-widget="{$echo($this->name)}" {$echo($this->getFieldValue($dbFields, $fields, 'data-equalizer-watch'))}>
        <div class="toolButtons">
          <div class="small-4 columns editWidget" title="Edit photos"><i class="fi-pencil"></i></div>
          <div class="small-4 columns {$echo($editVars['dropTarget'])} addWidget" title="Add widget"><i class="fi-plus"></i></div>
          <div class="small-4 columns removeWidget" title="Remove photos"><i class="fi-trash"></i></div>
        </div>
        {$imagesHtml}
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
      $images = $this->getImagesHtml($this->getImages($id), [], '', true);
      
$html = <<<EOT
          <label>Title
            <input type="text" name="title" value="{$echo($this->getFieldValue($dbFields, $fields, 'title'))}" placeholder="The content title"/>
          </label>
          <label>Content
            <textarea type="text" name="content" rows="5" placeholder="The content">{$echo($this->getFieldValue($dbFields, $fields, 'content'))}</textarea>
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
          <label>Thumbnail CSS classes
            {$echo($this->getCssClassesSelectHtml(json_decode($this->getFieldValue($dbFields, $fields, 'individualClasses')), 'individualClasses', array('Shadow effects') ))}
          </label>
          <input type="hidden" name="removeImage" value="" />

EOT;
      $html = $this->decorateEditHtml($id, $fields, $dbFields, $html);
      
      return $html;
    }
    
    // Return array of all images for this widget.
    private function getImages($widgetId, $thumbnails = false) {
      $files = [];
      $dir = "uploads/{$widgetId}";
      if($thumbnails) {
        $dir = "uploads/{$widgetId}/th";
      }
      $imagesDesc = array_reverse(glob($dir ."/*"));
      foreach ($imagesDesc as $filename) {
        if(is_dir($filename) === false) {
          array_push($files, $filename);
        }
      }
      return $files;
    }
    
    /**
     * Return HTML for all given images.
     */
    private function getImagesHtml($images = [], $thumbnails = [], $classes = '', $editMode = false) {
      $echo = $this->heredoc->getHeredoc();
      $html = '';
      foreach($images as $key=>$value) {
        if(isset($thumbnails[$key])) {
          $html .= "<li><a href='{$value}'><img class='th {$classes}' src='{$thumbnails[$key]}' alt='thumbnail' /></a></li>";
        } else {
          $previewImage = '';
          $removeImageId = '';
          $removeImageHook = '';
          if($editMode) {
            $previewImage = "class='previewImage'";
            $removeImageHook = "<div data-foundation-cms-remove-image='{$echo(basename($value))}'><i class='fi-trash'></i></div>";
            $removeImageId = "id='{$echo(basename($value))}'";
          }
          $html .= "<li {$previewImage}><img class='th' src='{$value}' alt='thumbnail' {$removeImageId} />{$removeImageHook}</li>";
        }
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
?>
