<?php
  namespace Outkicker;

  use Outkicker\TestResult;

  /**
   *  A simple test library developed for writing better tests on Outsights ecosystem
   *  Outkicker provides a testable interface for a class
   */
  abstract class Outkicker
  {
    protected $testClassName = "";
    protected $testLog = array();

    protected $_successCount = 0;
    protected $_failureCount = 0;

    public function getSuccessCount()
    {
      return $this->_successCount;
    }

    public function getFailureCount()
    {
      return $this->_failureCount;
    }

    /**
     * Logs the result of a test. keeps track of results for later inspection, Overridable to log elsewhere.
     **/
    protected final function logTest(TestResult $result)
    {
      $this->testLog[] = $result;
    }

    public function serializeTestResult(TestResult $result)
    {
      $exceptionOutput = "";

      if (!$result->isSuccess()) {
        $resultException = $result->getException();
        
        if ($resultException instanceof Contradiction) {
          $exceptionOutput = $resultException->toString();
        } else {
          $exceptionOutput = sprintf( "\nException : %s"
          ,$result->getException()->getMessage() );
        }

        $exceptionMetadata = sprintf( "\nComment : \n%s \n(Lines: %d-%d ~ File: %s)\n"
            ,$result->getComment()
            ,$result->getTest()->getStartLine()
            ,$result->getTest()->getEndLine()
            ,$result->getTest()->getFileName()
        );
      }

      # returns the error log
      return sprintf( "Outkicker > %s.%s was a %s %s\n"
        ,$this->testClassName
        ,$result->getName()
        ,$result->isSuccess() ? 'SUCCESS' : 'FAILURE'
        ,$exceptionOutput
        );
    }

    public function outputTestLog()
    {
      foreach ($this->testLog as $testResult) {
        $this->serializeTestResult()
      }
    }

    public final function runTests()
    {
      $class = new \ReflectionClass( $this );
      $this->testClassName = $class->getName();

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
          } catch( Contradiction $contradiction ) {
            $result = TestResult::createFailure( $this, $method, $contradiction );
          } catch( \Exception $ex ) {
            $result = TestResult::createFailure( $this, $method, $ex );
          }

          $output = ob_get_clean();
          $result->setOutput( $output );
          $this->logTest( $result );
        }
      }

      $this->logSummary();
    }

    public final function logSummary()
    {
      foreach ($this->testLog as $logEntry) {
        if ($logEntry->isSuccess()) 
          ++$this->_successCount;
        else
          ++$this->_failureCount;
      }

      printf( "Outkicker > SUMMARY %s : %d Success %d Failed\n"
            ,$this->testClassName
            ,$this->_successCount
            ,$this->_failureCount
          );

    }
  }