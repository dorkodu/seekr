<?php

namespace Dorkodu\Seekr;

use Dorkodu\Seekr\Test\{
  TestCase,
  TestResult,
  TestFunction,
  TestRepository
};

use Dorkodu\Seekr\Utils\PerformanceProfiler;

use Dorkodu\Utils\Color;
use Dorkodu\Utils\Console;

use Closure;
use ReflectionClass;
use Exception;

/**
 * Seekr - a minimalist PHP testing library for writing better tests easily and wisely.
 * 
 * @author     Doruk Eray (@dorkodu) <doruk@dorkodu.com>
 * @copyright  (c) 2021, Doruk Eray
 * @link       <https://github.com/dorkodu/seekr>
 * @license    The MIT License (MIT)
 */
final class Seekr
{
  /**
   * Current test repository to be run
   * @var TestRepository|null
   */
  private static $repo = null;

  /**
   * Holds the TestResult logs.
   */
  private static $log = array(
    'callbacks' => array(),
    'cases' => array()
  );

  private static $successCount = 0;
  private static $failureCount = 0;

  public static function successCount()
  {
    return static::$successCount;
  }

  public static function failureCount()
  {
    return static::$failureCount;
  }

  private static function newRepositoryIfEmpty()
  {
    if (!(static::$repo instanceof TestRepository) || is_null(static::$repo)) {
      static::$repo = new TestRepository();
    }
  }

  /**
   * Resets Seekr's all settings and test repository
   *
   * @return void
   */
  public static function reset()
  {
    static::$repo = new TestRepository();

    static::$log = array(
      'callbacks' => array(),
      'cases' => array()
    );

    static::$successCount = 0;
    static::$failureCount = 0;
  }

  /**
   * Add a TestCase to the queue that will be run
   *
   * @param TestCase $test
   */
  public static function testCase(TestCase $test)
  {
    static::newRepositoryIfEmpty();
    static::$repo->addCase($test);
  }

  /**
   * Shorthand for adding single callback tests.
   *
   * @param string $description
   * @param Closure $closure
   *
   * @return void
   */
  public static function test(string $description, Closure $closure)
  {
    static::newRepositoryIfEmpty();
    $test = new TestFunction($description, $closure);
    static::$repo->addFunction($test);
  }

  /**
   * Set the current test repository to be run
   *
   * @param TestRepository $testRepository
   * @return void
   */
  public function setRepository(TestRepository $testRepository)
  {
    static::$repo = $testRepository;
  }

  /**
   * Logs the result of a test.
   * Keeps track of results for later inspection.
   */
  protected static function log(TestResult $result)
  {
    if ($result->isFunctionTest()) {
      array_push(static::$log['callbacks'], $result);
      return;
    }

    $testClassName = $result->getTest()->class;

    if (!is_array(static::$log['cases'][$testClassName])) {
      static::$log['cases'][$testClassName] = array();
    }

    array_push(static::$log['cases'][$testClassName], $result);
  }

  /**
   * Runs the tests.
   *
   * @param boolean $showResults If you set this to false, Seekr will NOT output the results.
   * @return void
   */
  public static function run($showResults = true)
  {
    static::newRepositoryIfEmpty();

    foreach (static::$repo->testCases() as $test) {
      self::handleTestCase($test);
    }

    foreach (static::$repo->testFunctions() as $test) {
      self::handleTestFunction($test);
    }

    if ($showResults) {
      Console::breakLine();
      Console::writeLine(static::seekrBrand());
      self::seeResults();
    }
  }

  private static function seekrBrand()
  {
    return Color::colorize("bold, bg-black, fg-white", " Seekr - Simple, Wise Testing for PHP ");
  }

  private static function generateExceptionOutput(TestResult $testResult)
  {
    $exceptionOutput = "";

    if (!$testResult->isSuccess()) {

      $resultException = $testResult->getException();

      if ($resultException instanceof Contradiction) {
        $exceptionMessage = $resultException->toString();
      } else {

        $exceptionMessage =
          Color::colorize("bold, underlined, fg-red", "Exception")
          . sprintf(" %s", $resultException->getMessage());
      }

      $testOutput = empty($testResult->getOutput())
        ? ""
        : sprintf(
          "" . Color::colorize("bold, underlined, fg-yellow", "Output") . "\n%s",
          $testResult->getOutput()
        );

      $startLine = $testResult->getException()->getLine();
      $filePath = $testResult->getException()->getFile();

      $exceptionMetadata = sprintf(
        "at %s:%s",
        Color::colorize("fg-green", $filePath),
        Color::dim(
          Color::colorize("bold, fg-green", sprintf("%d", $startLine))
        )
      );

      $exceptionOutput = sprintf(
        "%s\n%s\n%s",
        $exceptionMetadata,
        $exceptionMessage,
        $testOutput
      );
    }

    return $exceptionOutput;
  }

  /**
   * @internal toString() for TestResult, will be shown on Seekr CLI UI
   */
  private static function stringifyTestResult(TestResult $testResult)
  {

    $successLabel = $testResult->isSuccess()
      ? Color::colorize("bold, fg-white, bg-green", ' PASS ')
      : Color::colorize("bold, fg-white, bg-red", ' FAIL ');

    # test name differs between TestFunction and TestCase
    if ($testResult->isFunctionTest()) {
      $testName = $testResult->getTest()->description();
    } else {
      $ref = new ReflectionClass($testResult->getTestableInstance());

      $testName = sprintf(
        "%s::%s()",
        Color::colorize("dim", $ref->getNamespaceName())
          . Color::colorize("bold", $ref->getShortName()),
        $testResult->getName()
      );
    }

    # returns the error log
    return sprintf(
      "%s %s \n%s\n%s %s",
      $successLabel,
      $testName,
      static::generateExceptionOutput($testResult),
      sprintf(
        Color::colorize("bold", "Time:") . " %.6fs",
        $testResult->getExecutionTime(), # get test execution time,
      ),
      sprintf(
        Color::colorize("bold", "Memory Peak:") . " %s",
        $testResult->getPeakMemoryUsage(), # get test execution time,
      )
    );
  }

  /**
   * @return void
   */
  public static function seeResults()
  {
    foreach (static::$log['cases'] as $testCaseName => $resultSet) {

      $filteredResults = array_filter($resultSet, function ($result) {
        # code...
      });

      foreach ($resultSet as $result) {
      }
      Console::breakLine();
      Console::writeLine(static::stringifyTestResult($result));
    }

    foreach (static::$log['callbacks'] as $result) {
      Console::breakLine();
      Console::writeLine(self::stringifyTestResult($result));
    }

    static::summary();
  }

  /**
   * Prints a summary from the current test results
   *
   * @return void
   */
  public static function summary()
  {
    Console::breakLine();
    Console::writeLine(
      sprintf(
        Color::colorize("bg-blue, fg-white, bold", " SUMMARY ")
          . " " . Color::colorize("bold, underlined", "%d") . " passed "
          . Color::colorize("bold, underlined", "%d") . " failed\n",
        static::$successCount,
        static::$failureCount
      )
    );
  }

  /**
   * @internal
   */
  private static function handleTestFunction(TestFunction $test)
  {
    $description = $test->description();

    /**
     * Performance profiling (time & memory) for test executions
     * Precisions :
     * 1/1.000.000 for time -- 1/100 for memory
     */
    $profiler = new PerformanceProfiler(6, 2);

    # started output buffering
    ob_start();

    try {
      $profiler->start(); # start profiler
      $test->callback()(); # run test method
      $result = TestResult::functionSucceed($test);
      ++static::$successCount;
    } catch (\Exception $e) {
      $result = TestResult::functionFailed($test, $e);
      ++static::$failureCount;
    }

    # stop profiler and generate the result
    $profiler->stop();

    $result->setExecutionTime($profiler->passedTime());
    $result->setPeakMemoryUsage($profiler->memoryPeakUsage());

    $output = ob_get_clean();
    $result->setOutput($output);

    self::log($result);
  }


  /**
   * @internal
   */
  private static function handleTestCase(TestCase $case)
  {
    /**
     * Performance profiling (time & memory) for test executions
     * Precisions :
     * 1/1.000.000 for time -- 1/100 for memory
     */
    $profiler = new PerformanceProfiler(6, 2);

    # create a reflection class
    $reflectionClass = new ReflectionClass($case);

    # $testClassName = $reflectionClass->getName();

    # HOOK setUp()
    $case->setUp();

    $methodsList = $reflectionClass->getMethods();

    # run every test
    foreach ($methodsList as $method) {
      $methodname = $method->getName();

      # if this is a test method, mount it !
      if (strlen($methodname) > 4 && substr($methodname, 0, 4) == 'test') {

        # HOOK mountedTest()
        $case->mountedTest();

        # started output buffering
        ob_start();

        try {
          # start profiler
          $profiler->start();
          # run test method
          $case->$methodname();

          $result = TestResult::caseSucceed($case, $method);
          ++static::$successCount;
        } catch (Exception $ex) {
          $result = TestResult::caseFailed($case, $method, $ex);
          ++static::$failureCount;
        }

        # stop profiler and get results
        $profiler->stop();

        $result->setExecutionTime($profiler->passedTime());
        $result->setPeakMemoryUsage($profiler->memoryPeakUsage());

        $output = ob_get_clean();
        $result->setOutput($output);

        self::log($result);

        # HOOK unmountedTest()
        $case->unmountedTest();
      }
    }

    # HOOK finish()
    $case->finish();
  }
}
