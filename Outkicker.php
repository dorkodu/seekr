<?php
  namespace Outkicker;

  /**
   *  A simple test library developed for writing better tests on Outsights ecosystem
   */
  class Outkicker
  {
    protected int $successCount = 0;
    protected int $failureCount = 0;

    /**
     * Class constructor.
     */
    public function __construct()
    {
      $this->successCount = 0;
      $this->failureCount = 0;
    }

    protected function commitFailure()
    {
      ++$this->failureCount;
      return false; # to return the value "false" directly 
    }

    protected function commitSuccess()
    {
      ++$this->successCount;
      return true; # to return the value "true" directly
    }

    public function getSuccessCount()
    {
      return $this->successCount;
    }

    public function getFailureCount()
    {
      return $this->failureCount;
    }
    
    public function getResultString()
    {
      return 
        (string) $this->successCount . " Success - " 
        . (string) $this->failureCount . " Failure";
    }
  }