# Outkicker
## What?

Outkicker is a simple testing library developed for writing better tests on Outsights ecosystem. <br>It is independent from Outsights, so anyone can use it in their code.

## Why?

Because I found TDD a little hard. Behavior Driven Development sound much easier and made sense to me. So, instead of spending days to figure out how to write tests, how to integrate them with my existing code, how to set up a "build pipeline" ; I created a simple and minimalistic PHP testing library to write more accurate, efficient tests in my code.

## How?

### **It has a few components :**

- **Say :** Provides useful assertions for Outkicker tests. Optional to use.
- **Outkicker :** the base for testable classes. Any class that implements Outkicker, gets access to helper testing methods.
- **TestResult :** An object for representing test results. This can be logged, inspected and tracked. Useful abstraction :)
- **Contradiction :** An object for representing premise exceptions from Say.

### Here is a sample :

- Create your test class. Test methods should start with "test". <br>When they throw an exception, Outkicker will handle it :)

  ```php
  class SampleTest extends Outkicker 
  {
    // This test is designed to succeed
    public function testOne()
    {
      Say::equal( 1, 1 );
    }
    
    // This test is designed to fail
    public function testTwo()
    {
      Say::equal( 1, 2 );
    }
  }
  ```
  
- Run your tests

  ```php
  // This is how to use Outkicker.
  $test = new SampleTest();
  $test->runTests();
  $test->seeTestResults(); // prints the results in a meaninful way
  ```

- Get the execution result in output, looks better if you use CLI

  ```bash
  Outkicker > SampleTest.testOne() was a SUCCESS ~ in 10.0136 microseconds
  Outkicker > SampleTest.testTwo() was a FAILURE ~ in 21.9345 microseconds
    (Lines: 27-30 ~ File: /home/dorkodu/code/outkicker/sample-test.php)
    Contradiction [ SAY::NOT_EQUAL ] : Not Equal
  
  Outkicker > SUMMARY SampleTest : 1 Success 1 Failed
  ```

## Author

Doruk Dorkodu : [GitHub](https://github.com/dorukdorkodu)  | [Twitter](https://twitter.com/dorukdorkodu) | [doruk@dorkodu.com](mailto:doruk@dorkodu.com) | [dorkodu.com](https://dorkodu.com)

See also the list of [contributions](https://libre.dorkodu.com) that we are making at [Dorkodu](dorkodu.com) to the free software community.

## Licence

Outkicker is open-sourced software licensed under the [MIT license](LICENSE).

