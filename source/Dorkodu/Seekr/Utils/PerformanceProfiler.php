<?php

namespace Dorkodu\Seekr\Utils;

use Dorkodu\Utils\Timer;

class PerformanceProfiler
{
  private int $timePrecision;
  private int $memoryPrecision;
  private $timer;

  public function __construct(int $timePrecision = 2, int $memoryPrecision = 2)
  {
    $this->timePrecision = $timePrecision;
    $this->memoryPrecision = $memoryPrecision;
    $this->timer = new Timer(true);
  }

  public function start()
  {
    $this->timer->start();
  }

  public function stop()
  {
    $this->timer->stop();
  }

  public function reset()
  {
    $this->timer->reset();
  }
}
