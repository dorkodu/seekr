<?php

namespace Dorkodu\Utils;

/**
 * A simple wrapper for Regex pattern management
 * Difficulty = Hardcore
 * 
 * @author     Doruk Eray (@dorkodu) <doruk@dorkodu.com>
 * @copyright  (c) 2021, Doruk Eray
 * @link       <https://github.com/dorukdorkodu/dorkodu-utils>
 * @license    The MIT License (MIT)
 */
class RegexTinkerer
{
  protected $patterns = array();

  public function setPattern($key, $pattern)
  {
    if (is_string($pattern)) {
      $this->patterns[$key] = $pattern;
    } else return false;
  }

  public function getPattern($key)
  {
    return array_key_exists($key, $this->patterns) ? $this->patterns[$key] : false; # regex key doesnt exist
  }

  public function isPatternKey($key)
  {
    return array_key_exists($key, $this->patterns);
  }

  public function matchPattern($patternHolder, $content)
  {
    if ($this->isPatternKey($patternHolder))
      return preg_match($this->getPattern($patternHolder), $content);
    else
      return preg_match($patternHolder, $content);
  }
}
