<?php

namespace Dorkodu\Seekr;

use ReflectionClass;
use Dorkodu\Utils\Color;
use Dorkodu\Utils\Console;
use Dorkodu\Seekr\Test\TestResult;

/**
 * The CLI UI output for Seekr
 */
class SeekrUI
{
  private static function resultBadge(bool $isPassed)
  {
    return $isPassed
      ? Color::colorize("bold, fg-white, bg-green", ' PASS ')
      : Color::colorize("bold, fg-white, bg-red", ' FAIL ');
  }

  public static function brand()
  {
    Console::breakLine();
    Console::writeLine(
      Color::colorize("bold, bg-black, fg-white", " .:: Seekr ::. ")
    );
    Console::writeLine(
      Color::colorize("bold, bg-black, fg-white", " Simple, Wise Testing for PHP - v1.1 - by Dorkodu et al. ")
    );
  }

  /**
   * Prints a summary from the current test results
   *
   * @return void
   */
  public static function summary(int $successCount, int $failureCount)
  {
    Console::breakLine();
    Console::writeLine(
      sprintf(
        Color::colorize("bg-blue, fg-white, bold", " SUMMARY ")
          . " " . Color::colorize("bold, underlined", "%d") . " passed "
          . Color::colorize("bold, underlined", "%d") . " failed\n",
        $successCount,
        $failureCount
      )
    );
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
  public static function stringifyTestResult(TestResult $testResult)
  {
    /**
     * If a function test, give all stats in a single block
     */
    if ($testResult->isFunctionTest()) {
      $testName = $testResult->getTest()->description();
      $successLabel = static::resultBadge($testResult->isSuccess());

      return sprintf(
        "%s %s \n%s\n%s\n%s",
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

    # for TestCase test method results
    return sprintf(
      "%s %s ~ %s %s",
      $testResult->isSuccess()
        ? Color::colorize("fg-green", "✓")
        : Color::colorize("fg-red", "✗"), # maybe will use this -> ✕ 
      $testResult->getTest()->getName(),
      sprintf(
        "in %.6fs",
        $testResult->getExecutionTime(),
      ),
      $testResult->getPeakMemoryUsage(),
    );
  }

  public static function printFunctionResult(TestResult $result)
  {
    Console::breakLine();
    Console::writeLine(static::stringifyTestResult($result));
  }

  /**
   * @param string $testCaseName
   * @param TestResult[] $resultSet
   * @return void
   * @internal
   */
  public static function printCaseResult(array $resultSet)
  {
    if (empty($resultSet)) {
      return;
    }

    $onlyFailedResults = array_filter($resultSet, function ($test) {
      return $test->isSuccess();
    });

    $resultBadge = static::resultBadge((count($onlyFailedResults) === 0));

    $ref = new ReflectionClass($resultSet[0]->getTestableInstance());
    $testCaseNamespace = $ref->getNamespaceName();
    $testCaseClassName = $ref->getShortName();

    Console::breakLine();
    Console::writeLine(sprintf(
      "%s %s%s",
      $resultBadge,
      Color::colorize("dim", $testCaseNamespace),
      Color::colorize("bold", $testCaseClassName)
    ));

    $totalTime = 0;
    $passedCount = 0;
    $failedCount = 0;

    foreach ($resultSet as $result) {
      # add to the total execution time
      $totalTime += $result->getExecutionTime();

      # increment test count
      if ($result->isSuccess())
        ++$passedCount;
      else
        ++$failedCount;

      # print test result
      Console::writeLine(
        static::stringifyTestResult($result)
      );
    }

    Console::writeLine(
      Color::colorize("bold", "Time :") . sprintf(" %.6fs", $totalTime)
    );

    Console::writeLine(
      sprintf(
        Color::colorize("bold", "Tests : %s%s"),
        # passed test stats
        sprintf(
          Color::colorize("fg-green", "%d passed "),
          ($passedCount > 0) ?  $passedCount  : ""
        ),
        # failed test stats
        sprintf(
          Color::colorize("fg-red", "%d failed "),
          ($failedCount > 0) ?  $passedCount  : ""
        )
      )
    );
  }
}
