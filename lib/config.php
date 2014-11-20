<?php
  /**
   * Turn on all error reporting.
   */
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  /**
   * Start session.
   */
  if(!isset($_SESSION)) {
    session_start();
  }

  /**
   * AutoLoader class is a utility class for
   * automagically locating libraries when needed.
   */
  require_once(dirname(__FILE__) . '/AutoLoader.php');
  spl_autoload_register(array('AutoLoader', 'load'));
    
  /**
   * Require FoundationWidget interface.
   */
  require_once(dirname(__FILE__) . '/FoundationWidgetAbstract.php');
  
  /**
   * DICE Dependency Injection container.
   * note: namespace support in DICE is without preceding slash. Also note escape char.
   */
  $dice = new \Dice\Dice;
  // Create a same-instance rule to apply to all objects.
  $shareRule = new \Dice\Rule;
  $shareRule->shared = true;
  $dice->addRule('*', $shareRule);
  
  /**
   * Track execution time.
   */
  $execTime = $dice->create('ExecTime');
  $execTime->start();
  
  /**
   * Heredoc function call support include.
   * {$echo(YOUR_FUNCTION())}
   */
  $db = $dice->create('DB');
  
  /**
   * Heredoc function call support include.
   * {$echo(YOUR_FUNCTION())}
   */
  $heredoc = $dice->create('Heredoc');
  $echo = $heredoc->getHeredoc();

  /**
   * Credentials
   */
  $credentials = $dice->create('Credentials');
  if(isset($_REQUEST['u']) && isset($_REQUEST['p'])) {
    $_SESSION['u'] = $_REQUEST['u'];
    $_SESSION['p'] = $_REQUEST['p'];
  }
  if(isset($_REQUEST['logout']) || empty($_SESSION['u']) || empty($_SESSION['p'])) {
    $_SESSION['u'] = '';
    $_SESSION['p'] = '';
  }
  $_SESSION['loggedIn'] = $credentials->checkCredentials($_SESSION['u'], $_SESSION['p']);
  
  // Register widget rule:
  // - one instance
  // - registerWidget function is called upon initialization
  $registerWidgetRule = new \Dice\Rule;
  $registerWidgetRule->shared = true;
  $registerWidgetRule->call[] = ['registerWidget', []];
  foreach (glob("widgets/*") as $filename) {
    if(is_dir($filename)) {
      $dice->addRule("Foundation\\{$echo(basename($filename))}", $registerWidgetRule);
    }
  }
  
  /**
   * Page settings
   */
  $settings = $dice->create('PageSettings');
  
  /**
   * FoundationWidgets.
   * Is used to keep a register of FoundationWidgets.
   */
  $foundationWidgets = $dice->create('Foundation\\FoundationWidgets');
  $foundationWidgets->setDice($dice);
  
?>