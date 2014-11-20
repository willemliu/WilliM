<?php

  $pageName = (isset($_REQUEST['page'])?$_REQUEST['page']:'/');
  $pageSettings = $settings->getPageSettings($pageName);
  $sub = "index.php";
  if(( substr( $pageName, strlen( $pageName ) - strlen( $sub ) ) == $sub )) {
    $pageSettings = $settings->getPageSettings(str_ireplace("index.php", "", $pageName));
  }


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
          <input type="submit" class="button tiny" name="submit" value="Save" />
        </fieldset>
      </form>
      <a class="close-reveal-modal">&#215;</a>
EOT;
  }

  echo $html;
  echo "<!-- " . $execTime->getTime() . "ms -->\r\n";
?>