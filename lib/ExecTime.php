<?php
  /**
   * ExecTime class
   */
  class ExecTime
  {
    private $start = array('default' => 0);
    private $stop = 0;

    /**
     * Set start time. You can use a name to identify the timer.
     * This allows you to set multiple starting times throughout the application life-cycle.
     */
    public function start($name = 'default') {
      $this->start[$name] = microtime(true);
    }

    /**
     * Get the time passed in milliseconds.
     * You can retrieve the time identified by name.
     */
    public function getTime($name = 'default') {
      $this->stop = microtime(true);
      return ($this->stop - $this->start[$name]);
    }
  
    /**
     * Constructor
     */
    public function __construct()
    {
    }
  }
?> 