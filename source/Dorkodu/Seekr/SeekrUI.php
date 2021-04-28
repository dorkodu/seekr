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
      Color::colorize("bold", " .:: Seekr ::. ")
    );

    Console::writeLine(
      Color::colorize("bold", " Simple, Wise Testing for PHP - v"
        . Seekr::$version
        . " - (c) Dorkodu. ")
    );
  }

  /**
   * Prints a summary from the current test results
   *
   * @return void
   */
  public static function summary(int $successCount, int $failureCount, $timePassed, $memoryPeak)
  {
    Console::breakLine();

    Console::writeLine(
      sprintf(
        Color::colorize("bg-blue, fg-white, bold", " SUMMARY ") . " %s %s",

        # passed test stats
        ($successCount > 0)
          ? sprintf(
            Color::colorize("bold, underlined, fg-green", "%d") . Color::colorize("bold", " passed"),
            $successCount
          )
          : "",

        # failed test stats
        ($failureCount > 0)
          ? sprintf(
            Color::colorize("bold, underlined, fg-red", "%d") . Color::colorize("bold", " failed"),
            $failureCount
          )
          : "",
      )
    );

    Console::writeLine(
      sprintf(
        Color::colorize("bold", "Time:") . " %.6fs",
        (float) $timePassed # get test execution time
      )
    );

    Console::writeLine(
      sprintf(
        Color::colorize("bold", "Memory Peak:") . " %s",
        $memoryPeak # get test memory peak
      )
    );

    Console::breakLine();
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
          "" . Color::colorize("bold, underlined, dim, fg-yellow", "Output") . "\n%s",
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
        "\n%s\n%s\n%s",
        $exceptionMessage,
        $exceptionMetadata,
        $testOutput
      );
    }

    return $exceptionOutput;
  }

  /**
   * @internal toString() for TestResult, will be shown on Seekr CLI UI
   */
  public static function stringifyTestResult(TestResult $testResult, bool $detailedOutput = false)
  {
    /**
     * If a function test, give all stats in a single block
     */
    if ($testResult->isFunctionTest()) {
      $testName = $testResult->getTest()->description();
      $successLabel = static::resultBadge($testResult->isSuccess());

      return sprintf(
        "%s %s %s\n%s\n%s",
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

    $testMethodDetails = $detailedOutput ?
      sprintf(
        "~ in %.6f seconds ~ %s",
        $testResult->getExecutionTime(),
        $testResult->getPeakMemoryUsage()
      )
      : "";

    # for TestCase test method results
    return sprintf(
      "%s %s %s",
      $testResult->isSuccess()
        ? Color::colorize("bold, fg-green", "✓")
        : Color::colorize("bold, fg-red", "✕"), # maybe will use this ->  
      Color::colorize("bold", $testResult->getTest()->getName()),
      $testMethodDetails
    );
  }

  public static function printFunctionResult(TestResult $result, bool $detailedOutput = false)
  {
    Console::breakLine();
    Console::writeLine(static::stringifyTestResult($result, $detailedOutput));
  }

  /**
   * @param string $testCaseName
   * @param TestResult[] $resultSet
   * @return void
   * @internal
   */
  public static function printCaseResult(array $resultSet, bool $detailedOutput = false)
  {
    if (empty($resultSet)) {
      return;
    }

    # if has any test method which failed, count it.
    $isTestCaseSucceed = true;
    foreach ($resultSet as $test) {
      if (!$test->isSuccess()) {
        $isTestCaseSucceed = false;
        break;
      }
    }

    $resultBadge = static::resultBadge($isTestCaseSucceed);

    $ref = new ReflectionClass($resultSet[0]->getTestableInstance());
    $testCaseNamespace = $ref->getNamespaceName();
    $testCaseClassName = $ref->getShortName();

    Console::breakLine();

    Console::writeLine(sprintf(
      "%s %s%s",
      $resultBadge,
      Color::colorize(
        "dim",
        empty($testCaseNamespace) ? "" : $testCaseNamespace . "\\"
      ),
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
        static::stringifyTestResult($result, $detailedOutput)
      );
    }

    Console::breakLine();

    Console::writeLine(
      sprintf(
        Color::colorize("bold", "Tests: %s%s"),
        # passed test stats
        ($passedCount > 0)
          ? sprintf(Color::colorize("bold, fg-green", "%d passed "), $passedCount)
          : "",
        # failed test stats
        ($failedCount > 0)
          ? sprintf(Color::colorize("bold, fg-red", "%d failed"), $failedCount)
          : "",
      )
    );

    Console::writeLine(
      Color::colorize("bold", "Time:") . sprintf(" %.6fs", $totalTime)
    );
  }
}
