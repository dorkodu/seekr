# Outkicker
## What?

Outkicker is a simple test library developed for writing better tests on Outsights ecosystem. <br>It is independent from Outsights, so anyone can use it in their code.

## Why?

Because I found TDD a little hard. Behavior Driven Development sound much easier and made sense to me. So, instead of spending days to figure out how to write tests, how to integrate them with my existing code, how to set up a "build pipeline" ; I created a simple and minimalistic PHP testing library to write more accurate, efficient tests in my code.

## How?

### **It has a few components :**

- **Say** : Provides useful assertions for Outkicker tests. Optional to use.
- **Outkicker** : the base for testable classes. Any class that implements Outkicker, gets access to helper testing methods.
- **TestResult** : An object for representing test results. This can be logged, inspected and tracked. Useful abstraction :)

### Here is a sample :

- Create your test class. Test methods should start with "test". <br>When they throw an exception, Outkicker will handle it :)

  ```php
  class MyTest extends Outkicker 
  {
    /**
     * This test is designed to succeed
     **/
    public function testOne()
    {
      Say::equal( 1, 1 );
    }
    /**
     * This test is designed to fail
     **/
    public function testTwo()
    {
      Say::equal( 1, 2 );
    }
  }
  ```

  
- Run your tests

  ```php
  // This is how to use Outkicker.
  $test = new MyTest();
  $test->runTests();
  ```

- Get the execution result in output, looks better if you use CLI

  ```bash
  Outkicker > MyTest.testOne was a SUCCESS 
  Outkicker > MyTest.testTwo was a FAILURE
    Exception : SAY · Not Equal
    Comment : 
    /**
    * This test is designed to fail
    **/ 
    (Lines: 26-29 ~ File: /home/dorkodu/code/outkicker/sample-test.php)
  
  Outkicker > SUMMARY MyTest : 1 Success 1 Failed
  ```

### Author

Doruk Dorkodu | [GitHub](https://github.com/dorukdorkodu)  | [Twitter](https://twitter.com/dorukdorkodu) | <doruk@dorkodu.com> | [dorkodu.com](https://dorkodu.com)

See also the list of [contributions](https://libre.dorkodu.com) that we are making at [Dorkodu](dorkodu.com) to the free software community.

### Licence

Outkicker is open-sourced software licensed under the [MIT license](LICENSE.md).

