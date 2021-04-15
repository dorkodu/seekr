<?php

namespace Dorkodu\Utils;

use Dorkodu\Utils\Console;

class TerminalUI
{
  public static function bold($message)
  {
    Console::write("\033[1m" . $message . "\033[0m");
  }

  public static function underDashedTitle($message)
  {
    Console::writeLine("  \033[1m" . $message . "\033[0m" . PHP_EOL . "  --------------------------------");
  }

  public static function pipeTitle($message)
  {
    Console::writeLine("  | \033[1m" . $message . "\033[0m |");
  }

  public static function arrowTitle($message)
  {
    Console::writeLine("  \033[1m-> " . $message . "\033[0m");
  }

  public static function dotTitle($message)
  {
    Console::writeLine("  \033[1m.:: " . $message . " ::.\033[0m");
  }

  public static function titledParagraph($title, $content)
  {
    self::bold("  " . $title);
    Console::writeLine("\n  " . $content);
  }

  public static function dictionaryEntry($title, $content)
  {
    self::arrowTitle($title);
    Console::writeLine("  " . $content);
    Console::breakLine();
  }

  public static function definition($term, $content)
  {
    self::bold($term);
    Console::write(" : " . $content);
    Console::breakLine();
  }
}
