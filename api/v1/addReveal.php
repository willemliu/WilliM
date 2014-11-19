<?php

  $html = '';
  // Check if sufficient privileges.
  if($credentials->hasAddAccess() === false) {
    $html = 'Insufficient rights<a class="close-reveal-modal">&#215;</a>';
  } else {
    $parentId = 0;
    if(isset($_REQUEST['parentId'])) {
      $parentId = $_REQUEST['parentId'];
    }
    
    // Use the folder names in the widgets folder as options.
    $widgets = '';
    foreach (glob("widgets/*") as $filename) {
      if(is_dir($filename)) {
        $widgets .= "<option value='{$echo(basename($filename))}'>{$echo(basename($filename))}</option>";
      }
    }

$html = <<<EOT
<div class="addReveal">
  <h1>Add widget</h1>
  <select class="addSelect">
    {$widgets}
  </select>
  <input type="hidden" class="parentId" value="{$parentId}"/>
  <button type="button" class="addButton">Add</button>
  <a class="close-reveal-modal">&#215;</a>
</div>
EOT;
  }
  
echo $html;
echo "<!-- " . $execTime->getTime() . "ms -->\r\n";
?>