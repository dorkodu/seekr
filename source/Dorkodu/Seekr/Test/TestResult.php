<?php

namespace Dorkodu\Seekr\Test;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use Dorkodu\Utils\Color;
use Dorkodu\Seekr\Contradiction;
use ReflectionFunction;

/**
 * Provides a loggable entity with information on a test and how it executed
 **/
final class TestResult
{
  /**
   * @var boolean
   */
  private $isSuccess = false;

  /**
   * @var string
   */
  private $output = '';

  /**
   * @var TestFunction|ReflectionMethod
   */
  private $test = null;

  /**
   * @var TestCase
   */
  private $testableInstance = null;

  /**
   * @var Exception
   */
  private $exception = null;

  private $executionTime = null;
  private $peakMemoryUsage = null;

  private function __construct()
  {
  }


  public function isSuccess()
  {
    return $this->isSuccess;
  }

  public function getOutput()
  {
    return $this->output;
  }

  public function setOutput(string $value)
  {
    $this->output = $value;
  }

  public function setExecutionTime(float $value)
  {
    $this->executionTime = $value;
  }

  public function setPeakMemoryUsage($value)
  {
    $this->peakMemoryUsage = $value;
  }

  public function getPeakMemoryUsage()
  {
    return $this->peakMemoryUsage;
  }

  public function getExecutionTime()
  {
    return $this->executionTime;
  }

  public function getTest()
  {
    return $this->test;
  }

  public function getName()
  {
    return $this->test->getName();
  }

  public function getException()
  {
    return $this->exception;
  }

  public function getComment(ReflectionMethod $method)
  {
    $comment = $method->getDocComment();

    $lines = explode("\n", $comment);

    for ($i = 0; $i < count($lines); $i++) {
      $lines[$i] = trim($lines[$i]);
    }
    return implode("\n", $lines);
  }

  /** 
   * Creates a failed test result for TestCase 
   * @param ReflectionMethod $test 
   * @param Exception $exception 
   * @param TestCase|null $testCase 
   */
  public static function caseFailed(TestCase $testCase, ReflectionMethod $test, Exception $exception)
  {
    $result = new self();
    $result->isSuccess = false;
    $result->testableInstance = $testCase;
    $result->test = $test;
    $result->exception = $exception;

    return $result;
  }

  /**
   * Creates a successful test result for TestCase
   *
   * @param TestCase $testCase
   * @param ReflectionMethod $test
   */
  public static function caseSucceed(TestCase $testCase, ReflectionMethod $test)
  {
    $result = new self();
    $result->isSuccess = true;
    $result->testableInstance = $testCase;
    $result->test = $test;

    return $result;
  }

  /**
   * @return boolean
   */
  public function isTestFunction()
  {
    return $this->test instanceof TestFunction;
  }

  /**
   * Creates a failed test result for TestFunction
   *
   * @param TestFunction $test
   * @param Exception $exception
   */
  public static function functionFailed(TestFunction $test, Exception $exception)
  {
    $result = new self();
    $result->isSuccess = false;
    $result->test = $test;
    $result->exception = $exception;

    return $result;
  }

  /**
   * Creates a successful test result for TestFunction
   *
   * @param TestFunction $testCase
   */
  public static function functionSucceed(TestFunction $test)
  {
    $result = new self();
    $result->isSuccess = true;
    $result->test = $test;

    return $result;
  }

  /**
   * Returns the string representation of the TestResult
   *
   * @return string
   */
  public function toString()
  {
    $exceptionOutput = "";

    if (!$this->isSuccess()) {

      $resultException = $this->getException();

      if ($resultException instanceof Contradiction) {
        $exceptionMessage = $resultException->toString();
      } else {

        $exceptionMessage =
          Color::colorize("bold, underlined, fg-red", "Exception")
          . sprintf(" %s", $resultException->getMessage());
      }

      $testOutput = empty($this->getOutput())
        ? ""
        : sprintf("\nOutput : \n%s", $this->getOutput());

      if ($this->isTestFunction()) {
        $testReflection = new ReflectionFunction($this->test->callback());
      } else {
        $testReflection = $this->getTest();
      }

      $startLine = $this->exception->getLine();
      $filePath = $this->exception->getFile();

      $exceptionMetadata = sprintf(
        "\nat %s:%s\n",
        Color::colorize("fg-green", $filePath),
        Color::dim(
          Color::colorize("bold, fg-green", sprintf("%d", $startLine))
        )
      );

      $exceptionOutput = sprintf(
        "%s%s%s",
        $exceptionMetadata,
        $exceptionMessage,
        $testOutput
      );
    }

    $successLabel = $this->isSuccess()
      ? Color::colorize("bold, fg-white, bg-green", ' PASS ')
      : Color::colorize("bold, fg-white, bg-red", ' FAIL ');

    # test name differs between TestFunction and TestCase
    if ($this->isTestFunction()) {
      $testName = $this->test->description();
    } else {
      $ref = new ReflectionClass($this->testableInstance);

      $testName = sprintf(
        "%s::%s()",
        Color::colorize("dim", $ref->getNamespaceName())
          . Color::colorize("bold", $ref->getShortName()),
        $this->getName()
      );
    }

    # returns the error log
    return sprintf(
      "%s %s \n%s\n%s %s",
      $successLabel,
      $testName,
      sprintf(
        Color::colorize("bold", "Time:") . " %.6f seconds",
        $this->getExecutionTime(), # get test execution time,
      ),
      $this->getPeakMemoryUsage(), # get test peak memory usage
      $exceptionOutput
    );
  }
}
