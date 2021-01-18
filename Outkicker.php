<?php
  namespace Outkicker;

  /**
   *  A simple test library developed for writing better tests on Outsights ecosystem
   *  Outkicker provides a testable interface for a class
   */
  abstract class Outkicker
  {
    protected $testLog = array();

    /**
     * Logs the result of a test. keeps track of results for later inspection, Overridable to log elsewhere.
     **/
    protected function log(TestResult $result)
    {
        $this->testLog[] = $result;

        printf( "Outkicker > %s was a %s %s\n"
            ,$result->getName()
            ,$result->isSuccess() ? 'SUCCESS' : 'FAILURE'
            ,$result->isSuccess() ? '' : sprintf( "\nComment : \n%s \n(Lines: %d-%d ~ File: %s)\n"
                ,$result->getComment()
                ,$result->getTest()->getStartLine()
                ,$result->getTest()->getEndLine()
                ,$result->getTest()->getFileName()
                )
            );
    }

    public final function runTests()
    {
      $class = new \ReflectionClass( $this );
      foreach( $class->getMethods() as $method )
      {
        $methodname = $method->getName();
        if ( strlen( $methodname ) > 4 && substr( $methodname, 0, 4 ) == 'test' )
        {
          ob_start();
          # started output buffering
          try {
            $this->$methodname();
            $result = TestResult::createSuccess( $this, $method );
          } catch( \Exception $ex ) {
            $result = TestResult::createFailure( $this, $method, $ex );
          }

          $output = ob_get_clean();
          $result->setOutput( $output );
          $this->log( $result );
        }
      }
    }

    public function logSummary()
    {
      $successCount = 0;
      $failureCount = 0;

      foreach ($this->testLog as $logEntry) {
        if () {
          # code...
        }
      }
    }
  }