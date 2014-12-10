<?php

  $pageName = (isset($_REQUEST['page'])?$_REQUEST['page']:'/');
  $pageSettings = $settings->getPageSettings($pageName);
  $sub = "index.php";
  if(( substr( $pageName, strlen( $pageName ) - strlen( $sub ) ) == $sub )) {
    $pageSettings = $settings->getPageSettings(str_ireplace("index.php", "", $pageName));
  }

  $resourcesHtml = $settings->getResourcesHtml();

  $html = '';
  // Check if sufficient privileges.
  if($credentials->hasEditAccess() === false) {
    $html = 'Insufficient rights<a class="close-reveal-modal">&#215;</a>';
  } else {
$html = <<<EOT
      <form class="settingsForm" method="POST">
        <h1>Page settings</h1>
        <fieldset>
          <legend>Head settings</legend>
          <label>Top of <b>&lt;head/&gt;</b>
            <textarea name="headStart" rows="5">{$echo((isset($pageSettings['headStart'])?$pageSettings['headStart']:''))}</textarea>
          </label>
          <label>Title
            <input type="text" name="title" placeholder="FoundationCMS" value="{$echo((isset($pageSettings['title'])?str_replace('"', '&quot;', $pageSettings['title']):'FoundationCMS'))}" />
          </label>          
          <label>Bottom of <b>&lt;head/&gt;</b>
            <textarea name="headEnd" rows="5">{$echo((isset($pageSettings['headEnd'])?$pageSettings['headEnd']:''))}</textarea>
          </label>
        </fieldset>
        <fieldset>
          <legend>Body settings</legend>
          <label>Bottom of <b>&lt;body/&gt;</b>
            <textarea name="bodyBottom" rows="5">{$echo((isset($pageSettings['bodyBottom'])?$pageSettings['bodyBottom']:''))}</textarea>
          </label>
        </fieldset>
        <fieldset>
          <legend>Resources</legend>
          <label>Drop resources here
            <div class="resourceDropTarget">
              <i id="resourceDropIcon" class="fi-plus"></i>
              <progress style="display: none;" id="uploadProgress" min="0" max="100" value="0">0</progress>
            </div>
          </label>
          <div class="resources">
            <ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-4">
            {$resourcesHtml}
            </ul>
          </div>
        </fieldset>
        <input type="hidden" name="removeResource" value="" />
        <input type="submit" class="button tiny" name="submit" value="Save" />
      </form>
      <a class="close-reveal-modal">&#215;</a>
EOT;
  }

  echo $html;
  echo "<!-- " . $execTime->getTime() . "ms -->\r\n";
