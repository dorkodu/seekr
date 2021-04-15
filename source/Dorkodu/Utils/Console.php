<?php

namespace Dorkodu\Utils;

/**
 * The simple and web browser compatible console/terminal helper
 */
class Console
{
  public static function readLine($optionalMessage = null)
  {
    $fh = fopen('php://stdin', 'r');
    if (!empty($optionalMessage)) {
      echo $optionalMessage . " ";
    }

    $userInput = trim(fgets($fh));
    fclose($fh);
    unset($fh);

    return $userInput;
  }

  public static function writeLine($message)
  {
    self::write($message . PHP_EOL);
  }

  public static function write(string $buffer)
  {
    if (!self::isCLI()) {
      $buffer = nl2br(htmlspecialchars($buffer, ENT_COMPAT | ENT_SUBSTITUTE));
    }

    print $buffer;
  }

  private static function isCLI()
  {
    return (PHP_SAPI == 'cli' || PHP_SAPI == 'phpdbg');
  }

  private static function linebreak()
  {
    if (!self::isCLI()) {
      return '<br>';
    }

    return PHP_EOL;
  }

  public static function breakLine()
  {
    echo self::linebreak();
  }

  public static function getScriptName()
  {
    return $_SERVER['argv'][0];
  }

  public static function argumentCount()
  {
    return $_SERVER['argc'];
  }

  /**
   * Undocumented function
   *
   * @param int $index Index of the argument in 'argv' array.
   * @return void
   */
  public static function getArgument($index)
  {
    if (is_int($index)) {
      if (isset($_SERVER['argv'][$index]) && !empty($_SERVER['argv'][$index])) {
        return $_SERVER['argv'][$index];
      } else return false;
    } else return false;
  }
}
