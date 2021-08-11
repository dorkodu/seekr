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
use Dorkodu\Utils\Timer;
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
class Seekr
{

  /** @var float $version */
  public static $version = 1.1;

  /**
   * Current test repository to be run
   * @var TestRepository|null
   */
  private static $repo = null;

  /** @var array */
  private static $settings = array();

  /**
   * Holds the TestResult logs.
   */
  private static $log = array(
    'callbacks' => array(),
    'cases' => array()
  );

  private static $successCount = 0;
  private static $failureCount = 0;

  /** @var PerformanceProfiler $profiler */
  private static PerformanceProfiler $profiler;

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
    if (!(static::$repo instanceof TestRepository)) {
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

    static::$log = [
      'callbacks' => [],
      'cases' => []
    ];

    static::$successCount = 0;
    static::$failureCount = 0;

    static::$profiler = new PerformanceProfiler(6, 2);
  }

  /**
   * Add a TestCase to the queue that will be run
   *
   * @param TestCase $test
   */
  public static function testCase(TestCase $test)
  {
    static::newRepositoryIfEmpty();

    static::$repo->case($test);
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
    static::$repo->function($description, $closure);
  }

  /**
   * Set the current test repository to be run
   *
   * @param TestRepository $testRepository
   * @return void
   */
  public static function setRepository(TestRepository $testRepository)
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
   * @internal 
   */
  private static function getSetting(string $setting, $default = null)
  {
    if (
      isset(static::$settings[$setting])
      && !empty(static::$settings[$setting])
    ) {
      return static::$settings[$setting];
    }

    return $default;
  }

  public static function setupProfiler()
  {
    static::$profiler = new PerformanceProfiler(6, 1);
    static::$profiler->start();
  }

  public static function resetProfiler()
  {
    if (
      isset(static::$profiler)
      && static::$profiler instanceof PerformanceProfiler
    ) {
      static::$profiler->stop();
      static::$profiler->reset();
    }
  }

  public static function getTotalPassedTime()
  {
    return static::$profiler->passedTime();
  }

  public static function getMemoryPeak()
  {
    return static::$profiler->memoryPeakUsage();
  }

  /**
   * Runs the tests.
   *
   * @param array $setting You can make a few choices on how should Seekr run your tests.\
   * "hideResults" : Only will Boolean. Defaults to false.\
   * "detailed" : Will show details on each of your TestCase methods. Boolean, defaults to false.\
   * "hideHeader" : Won't show Seekr brand header if set to true. Boolean, defaults to false.\
   * 
   * @return void
   */
  public static function run(array $settings = array())
  {
    ob_start();

    static::newRepositoryIfEmpty();
    static::$settings = $settings;
    static::setupProfiler();

    # run test cases
    foreach (static::$repo->testCases() as $test) {
      self::handleTestCase($test);
    }

    # run test functions
    foreach (static::$repo->testFunctions() as $test) {
      self::handleTestFunction($test);
    }

    if (!static::getSetting('hideHeader', false)) {
      SeekrUI::brand();
    }

    # if user wants to see results
    if (!static::getSetting('hideResults', false)) {
      self::seeResults(
        static::getSetting('detailed', false)
      );
    }

    static::summary();

    ob_end_flush();
  }

  public static function exceptionOutput(TestResult $testResult)
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

  private static function testCaseLog()
  {
    return static::$log['cases'];
  }

  private static function testFunctionLog()
  {
    return static::$log['callbacks'];
  }

  /**
   * @return void
   */
  public static function seeResults(bool $showDetails = false)
  {
    foreach (static::$log['cases'] as $testCaseName => $resultSet) {
      SeekrUI::printCaseResult($resultSet, $showDetails);
    }

    foreach (static::$log['callbacks'] as $result) {
      SeekrUI::printFunctionResult($result);
    }
  }

  /**
   * Prints a summary from the current test results
   *
   * @return void
   */
  public static function summary()
  {
    SeekrUI::summary(
      static::$successCount,
      static::$failureCount,
      static::getTotalPassedTime(),
      static::getMemoryPeak()
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
     * Default Precisions :
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
      if (
        strlen($methodname) > 4
        && substr($methodname, 0, 4) == 'test'
        && $method->isPublic()
      ) {

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
