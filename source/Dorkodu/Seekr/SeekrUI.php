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
      Color::colorize("bold, bg-black, fg-white", " .:: Seekr 1.1 ")
        . "by Dorkodu"
        . Color::colorize("bold, bg-black, fg-white", " ::. ")
    );
    Console::breakLine();
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
}
