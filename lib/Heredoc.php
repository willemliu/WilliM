<?php
  /**
    * Heredoc function call support include.
    * {$echo(YOUR_FUNCTION())}
    */
  function heredoc($param) {
    // just return whatever has been passed to us
    return $param;
  }
  
  /**
   * Singleton HereDoc class
   */
  class HereDoc
  {
    
    public function getHeredoc() {
      return 'heredoc';
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    }
  }
?>