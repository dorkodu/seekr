<?php

namespace Dorkodu\Utils;

/**
 * the simple logging library. period.
 */
class Log
{
  public const EMERGENCY = 1;
  public const ALERT = 2;
  public const CRITICAL = 3;
  public const ERROR = 4;
  public const WARNING = 5;
  public const NOTICE = 6;
  public const INFO = 7;
  public const DEBUG = 8;

  public static $filePath;

  /**
   * Logs the content to the given file.
   *
   * @param int $type Importance level of the log. Enter a const, Shepherd::ERROR, Shepherd::NOTICE etc.
   * @param string $filePath File to log.
   * @param string $message Content of the log entry.
   *
   * @return boolean true on success, false on failure.
   **/
  public static function log($type, string $message)
  {
    $importanceTitle = self::getLogTitle($type);

    if (!empty($filePath)) {
      try {
        $logFilePath = $filePath;

        if (!is_file($logFilePath)) {
          if (!touch($logFilePath, "0777"))
            return false;
        }

        if (!chmod($logFilePath, 0777))
          return false;

        $document = fopen($logFilePath, "ab+");

        $logEntryContent = "[" . date("Y-m-d H:i:s") . "] " . $importanceTitle . " " . $message . "\n";

        fputs($document, $logEntryContent);
        fclose($document);

        return true;
      } catch (\Throwable $e) {
        return false;
      }
    } else {
      return false;
    }
  }

  public static function setFile(string $path)
  {
    static::$filePath = is_string(realpath($path)) ? realpath($path) : 'storage/tokeng.log';
  }

  /**
   * Log an emergency message.
   *
   * @param string $message
   * @return bool true on success, false on failure.
   */
  public static function emergency(string $message)
  {
    return self::log(self::EMERGENCY, $message);
  }

  /**
   * Log an alert message.
   *
   * @param string $message
   * @return bool true on success, false on failure.
   */
  public static function alert(string $message)
  {
    return self::log(self::ALERT, $message);
  }

  /**
   * Log a critical message.
   *
   * @param string $message
   * @return bool true on success, false on failure.
   */
  public static function critical(string $message)
  {
    return self::log(self::CRITICAL, $message);
  }

  /**
   * Log an error message.
   *
   * @param string $message
   * @return bool true on success, false on failure.
   */
  public static function error(string $message)
  {
    return self::log(self::ERROR, $message);
  }

  /**
   * Log a warning message.
   *
   * @param string $message
   * @return bool true on success, false on failure.
   */
  public static function warning(string $message)
  {
    return self::log(self::WARNING, $message);
  }

  /**
   * Log a notice message.
   *
   * @param string $message
   * @return bool true on success, false on failure.
   */
  public static function notice(string $message)
  {
    return self::log(self::NOTICE, $message);
  }


  /**
   * Log an info message.
   *
   * @param string $message
   * @return bool true on success, false on failure.
   */
  public static function info(string $message)
  {
    return self::log(self::INFO, $message);
  }

  /**
   * Log a debug message.
   *
   * @param string $message
   * @return bool true on success, false on failure.
   */
  public static function debug(string $message)
  {
    return self::log(self::DEBUG, $message);
  }

  private static function getLogTitle($type)
  {
    $importanceTitle = '';
    switch ($type) {
      case self::EMERGENCY:
        $importanceTitle = "EMERGENCY";
        break;
      case self::ALERT:
        $importanceTitle = "ALERT";
        break;
      case self::CRITICAL:
        $importanceTitle = "CRITICAL";
        break;
      case self::ERROR:
        $importanceTitle = "ERROR";
        break;
      case self::WARNING:
        $importanceTitle = "WARNING";
        break;
      case self::NOTICE:
        $importanceTitle = "NOTICE";
        break;
      case self::INFO:
        $importanceTitle = "INFO";
        break;
      case self::DEBUG:
        $importanceTitle = "DEBUG";
        break;
      default:
        $importanceTitle = false;
        break;
    }
    return $importanceTitle;
  }
}
