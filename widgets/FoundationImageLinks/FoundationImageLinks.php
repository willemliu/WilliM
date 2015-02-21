<?php
  namespace Foundation;
  /**
   * FoundationImageLinks class
   */
  final class FoundationImageLinks extends FoundationWidget
  {
    protected $name = "FoundationImageLinks";

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
      // Image links
      $imageLinks = json_decode($this->getFieldValue($dbFields, $fields, 'imageLink'));
      if(empty($imageLinks)) {
        $imageLinks = [];
      }
      // Target
      $targets = json_decode($this->getFieldValue($dbFields, $fields, 'target'));
      if(empty($targets)) {
        $targets = [];
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
      $images = $this->getImages($id);
      $imagesList = $this->getImagesHtml($images, $images, $imageLinks, $targets, $individualClasses);

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
        $smallBlockGrid = $this->getFieldValue($dbFields, $fields, 'small-block-grid');
        if(empty($smallBlockGrid)) {
          $smallBlockGrid = 1;
        }
        $mediumBlockGrid = $this->getFieldValue($dbFields, $fields, 'medium-block-grid');
        if(empty($mediumBlockGrid)) {
          $mediumBlockGrid = 2;
        }
        $largeBlockGrid = $this->getFieldValue($dbFields, $fields, 'large-block-grid');
        if(empty($largeBlockGrid)) {
          $largeBlockGrid = 3;
        }
        
$imagesHtml = <<<EOT
        <ul class="small-block-grid-{$smallBlockGrid} medium-block-grid-{$mediumBlockGrid} large-block-grid-{$largeBlockGrid}">{$imagesList}</ul>
EOT;
      }

      
$html = <<<EOT
      {$anchorNameHtml}
      <div id="{$id}" class="{$echo($editVars['editable'])} foundationImageLinks {$echo($editVars['dropTarget'])} {$classes}" {$echo($editVars['draggable'])} data-foundation-cms-widget="{$echo($this->name)}" {$echo($this->getFieldValue($dbFields, $fields, 'data-equalizer-watch'))}>
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
      // Image links
      $imageLinks = json_decode($this->getFieldValue($dbFields, $fields, 'imageLink'));
      if(empty($imageLinks)) {
        $imageLinks = [];
      }
      // Target
      $targets = json_decode($this->getFieldValue($dbFields, $fields, 'target'));
      if(empty($targets)) {
        $targets = [];
      }
      $images = $this->getImagesHtml($this->getImages($id), [], $imageLinks, $targets, '', true);
      
$html = <<<EOT
          <label>Title
            <input type="text" name="title" value="{$echo($this->getFieldValue($dbFields, $fields, 'title'))}" placeholder="The content title"/>
          </label>
          <label>Content
            <textarea type="text" name="content" rows="5" placeholder="The content">{$echo($this->getFieldValue($dbFields, $fields, 'content'))}</textarea>
          </label>
          <label>Drop images here (jpeg, png, gif) (120x40)
            <div class="imageDropTarget">
              <i id="photoDropIcon" class="fi-plus"></i>
              <progress style="display: none;" id="uploadProgress" min="0" max="100" value="0">0</progress>
            </div>
          </label>
          <div class="images">
            <ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-3">
            {$images}
            </ul>
          </div>
          <label>Thumbnail CSS classes
            {$echo($this->getCssClassesSelectHtml(json_decode($this->getFieldValue($dbFields, $fields, 'individualClasses')), 'individualClasses', array('Shadow effects') ))}
          </label>
          <label>small-block-grid-[n]
            <input type="number" name="small-block-grid" value="{$echo($this->getFieldValue($dbFields, $fields, 'small-block-grid'))}" placeholder="1"/>
          </label>
          <label>medium-block-grid-[n]
            <input type="number" name="medium-block-grid" value="{$echo($this->getFieldValue($dbFields, $fields, 'medium-block-grid'))}" placeholder="2"/>
          </label>
          <label>large-block-grid-[n]
            <input type="number" name="large-block-grid" value="{$echo($this->getFieldValue($dbFields, $fields, 'large-block-grid'))}" placeholder="3"/>
          </label>
          <input type="hidden" name="removeImage" value="" />
          <input type="hidden" name="thumbnailWidth" value="120" />
          <input type="hidden" name="thumbnailHeight" value="40" />

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
    private function getImagesHtml(array $images = [], array $thumbnails = [], array $imageLinks = [], array $targets = array(), $classes = '', $editMode = false) {
      $echo = $this->heredoc->getHeredoc();
      $html = '';
      foreach($images as $key=>$value) {
        if(isset($thumbnails[$key])) {
          $html .= "<li><a href='{$echo((isset($imageLinks[$key])?$imageLinks[$key]:''))}' target='{$echo((isset($targets[$key])?$targets[$key]:''))}'><img class='{$classes}' src='{$thumbnails[$key]}' alt='thumbnail' /></a></li>";
        } else {
          $previewImage = '';
          $removeImageId = '';
          $removeImageHook = '';
          $imageLink = '';
          $target = '';
          if($editMode) {
            $previewImage = "class='previewImage'";
            $removeImageHook = "<div data-foundation-cms-remove-image='{$echo(basename($value))}'><i class='fi-trash'></i></div>";
            $removeImageId = "id='{$echo(basename($value))}'";
            if(isset($imageLinks[$key])) {
              $imageLink = "<input type='text' name='imageLink[]' value='{$imageLinks[$key]}' placeholder='http://www.willim.nl' />";
            } else {
              $imageLink = "<input type='text' name='imageLink[]' value='' placeholder='http://www.willim.nl' />";
            }
            if(isset($targets[$key])) {
              $target = "<input type='text' name='target[]' value='{$targets[$key]}' placeholder='_blank' />";
            } else {
              $target = "<input type='text' name='target[]' value='_blank' placeholder='_blank' />";
            }
          }
          $html .= "<li {$previewImage}><img class='th' src='{$value}' alt='thumbnail' {$removeImageId} />{$removeImageHook}{$imageLink}{$target}</li>";
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
