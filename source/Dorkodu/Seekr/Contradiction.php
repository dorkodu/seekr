<?php

namespace Dorkodu\Seekr;

use Dorkodu\Utils\Color;

/**
 * Contradiction is a negative result of a Premise, simply tells you why your premise makes a contradiction occur
 */
class Contradiction extends \Exception
{
  public function __construct(string $message = "", string $code = null)
  {
    $this->message = $message;
    $this->code = $code;
  }

  public function toString()
  {
    $str = "";
    $contradictionLabel = Color::colorize("bold, fg-white, bg-red", " Contradiction ");

    if (!is_null($this->code) && !empty($this->message)) {

      $str = sprintf(
        $contradictionLabel . Color::colorize("bold", " [ %s ] : %s "),
        $this->code,
        $this->message
      );
    } else if (is_null($this->code) && !empty($this->message)) {

      $str = sprintf(
        $contradictionLabel . Color::colorize("bold", " : %s"),
        $this->message
      );
    } else if (is_null($this->code) && empty($this->message)) {
      $str = Color::colorize("bold", "An unknown contradiction occured.");
    }

    return $str;
  }
}
