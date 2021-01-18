<?php
  namespace Outkicker;

  /**
   *  A simple test library developed for writing better tests on Outsights ecosystem
   *  Outkicker provides
   */
  abstract class Outkicker
  {
    protected $test_log = array();

    /**
     * Logs the result of a test. keeps track of results for later inspection, Overridable to log elsewhere.
     **/
    protected function log(TestResult $result)
    {
        $this->test_log[] = $result;

        printf( "Test: %s was a %s %s\n"
            ,$result->getName()
            ,$result->getSuccess() ? 'success' : 'failure'
            ,$result->getSuccess() ? '' : sprintf( "\n%s (lines:%d-%d; file:%s)"
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
  }