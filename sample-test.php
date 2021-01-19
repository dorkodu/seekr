<?php
    require __DIR__ . '/source/Outkicker.php';
    require __DIR__ . '/source/TestResult.php';
    require __DIR__ . '/source/Say.php';
    
    use Outkicker\Outkicker;
    use Outkicker\Say;

    /**
     * a simple Test suite with two tests
     **/
    class MyTest extends Outkicker
    {
      /**
       * This test is designed to succeed
       **/
      public function testOne()
      {
        Say::equal( 2, 2 );
      }

      /**
       * This test is designed to fail
       **/
      public function testTwo()
      {
        Say::equal( 1, 2 );
      }
    }

    // this is how to use it.
    $test = new MyTest();
    $test->runTests();