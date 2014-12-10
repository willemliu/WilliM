<?php

  $html = '';
  // Check if sufficient privileges.
  if($credentials->hasEditAccess() === false) {
    $html = 'Insufficient rights<a class="close-reveal-modal">&#215;</a>';
  } else {
    $widget = $dice->create('Foundation\\' . $_REQUEST['type']);
    $html = $widget->getEditHtml($_REQUEST["id"]);
  }

  echo $html;
  echo "<!-- " . $execTime->getTime() . "ms -->\r\n";
