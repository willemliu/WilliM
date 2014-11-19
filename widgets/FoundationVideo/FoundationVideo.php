<?php  
  namespace Foundation;
  /**
   * FoundationVideo class
   */
  final class FoundationVideo extends FoundationWidget
  {
    protected $name = "FoundationVideo";

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

      $title = $this->getFieldValue($dbFields, $fields, 'title');
      $content = str_replace("\n", "<br/>", $this->getFieldValue($dbFields, $fields, 'content'));
      $embed = $this->getFieldValue($dbFields, $fields, 'embed');
      $youtubeVideoId = $this->getFieldValue($dbFields, $fields, 'youtubeVideoId');
      
      $videoHtml = '';
      if(empty($title) === false) {
$videoHtml .= <<<EOT
        <h1>{$title}</h1>
EOT;
      }
      if(empty($content) === false) {
$videoHtml .= <<<EOT
        <p>{$content}</p>
EOT;
      }
      
      if(empty($youtubeVideoId) === false) {
$videoHtml .= <<<EOT
        <div class='flex-video'>
          <div class="youtubeThumbnail">
            <a data-youtube="{$youtubeVideoId}">
              <img src="http://i.ytimg.com/vi/{$youtubeVideoId}/hqdefault.jpg" style="width:100%;" alt="Youtube" />
              <div class="youtubePlaceholder"></div>
            </a>
          </div>
        </div>
EOT;
      }
      else if(empty($embed) === false) {
$videoHtml .= <<<EOT
        <div class='flex-video'>{$embed}</div>
EOT;
      }

      $echo = $this->heredoc->getHeredoc();
$html = <<<EOT
      {$anchorNameHtml}
      <div id="{$id}" class="{$echo($editVars['editable'])} {$echo($editVars['dropTarget'])} foundationVideo {$classes}" {$echo($editVars['draggable'])} data-foundation-cms-widget="{$echo($this->name)}">
        <div class="toolButtons">
          <div class="small-4 columns editWidget" title="Edit carousel"><i class="fi-pencil"></i></div>
          <div class="small-4 columns {$echo($editVars['dropTarget'])} addWidget" title="Add widget"><i class="fi-plus"></i></div>
          <div class="small-4 columns removeWidget" title="Remove carousel"><i class="fi-trash"></i></div>
        </div>
        {$videoHtml}
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
      $echo = $this->getHeredoc()->getHeredoc();
$html = <<<EOT
          <label>Title
            <input type="text" name="title" value="{$echo($this->getFieldValue($dbFields, $fields, 'title'))}" placeholder="The content title"/>
          </label>
          <label>Content
            <textarea type="text" name="content" rows="5" placeholder="The content">{$echo($this->getFieldValue($dbFields, $fields, 'content'))}</textarea>
          </label>
          <label>Youtube video ID (overrides embed code)
            <input type="text" name="youtubeVideoId" value="{$echo($this->getFieldValue($dbFields, $fields, 'youtubeVideoId'))}" placeholder="SONEpHd84dY"/>
          </label>
          <label>Video embed code
            <textarea type="text" name="embed" rows="5">{$echo($this->getFieldValue($dbFields, $fields, 'embed'))}</textarea>
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
