# Seekr ~ Change Log

### 1.1.0 (July 16 , 2021)

---

- Seekr has a stable **`Say`** and **`Constraint`** helper classes which provide useful ready-to-go, shorthand *premises* (assertions) and *boolean statements* for you. <br>You can use them to simplify your code. We will continue adding useful premises.

### 1.0.0 (April 23, 2021)

---

- **`STABLE`** Seekr is now production-ready! 

- Seekr is now a separate library from your test cases. 

- Changed the API.

- **`REWRITE`**

  - Rewrote the whole library from scratch with what I learned through the journey.<br>I approached the concept from first principles.
  - Designed a better and cooler logo :)

  - Simplified and decoupled some units.
  - Fixed inconsistencies
  - Removed some unnecessary internal code

- **`NEW`** You can add & run functional tests, by giving a description and a callback.

- **`NEW`** Add `TestCase`, which your test classes can extend.

- **`NEW`** Seekr's CLI UI is changed. <br>Colorful, distraction-free, easy-to-use.

### 0.6.0 (January 24, 2021)

---

- **`NEW`** Add Life Cycle Hooks.
  - A life cycle hook is a method that can be implemented in your test class and will be run on specific times while executing your tests.
  - These are current life cycle hooks for a test environment :
    - `setUp()` :  Called before starting to run tests in a test class
    - `finish()` : Called after all tests in a test class have run
    - `mountedTest()` : Called before each test of this test class is run
    - `unmountedTest()` : Called before each test of this test class is run.

### 0.5.0 (January 24, 2021)

---

- **`RENAME`**  ~~Outkicker~~ to **Seekr**

- Designed a logo after renaming the library.<br>

- New name with our motto :<br>

  **Seekr**<br>"Seeking more efficient and accurate tests in your code<br> while preventing potential bugs, inconsistencies fast, simply and wisely."

- **`REWRITE`**

  - Fixed inconsistencies
  - Simplified and decoupled some units.
  - Removed some unnecessary internal code

### 0.4.0 (January 23, 2021)

---

- **`NEW`**  Add `Premise` class to let users write their own premises and evaluate them.
  - Give a statement which can be evaluated and resolved into a boolean value.
  - Give a message and code describing a unique Contradiction type.<br>No more unnecessary abstractions for every `Exception` or `Contradiction` type.<br>For example : You can write Contradiction creator functions to throw different type of contradictions by only changing the arguments. Simple, right?
- Decoupled proposing a `Premise` process from `Say` . Now anyone can write their own premises if they can't find what they need in  `Say` helper class. You no longer need to wait until we release a new version.

### 0.3.0 (January 22, 2021)

---

- **`NEW`** Add `Timer` class to show how much it takes to run each test.
- Updated output for the sake of aesthetics

### 0.2.1 (January 21, 2021)

---

- Fix many bugs causing problems in outputting test results

### 0.2.0 (January 21, 2021)

---

- Add `seeTestResults()` method to get output about test results. <br>Outkicker does not automatically outputs the results anymore.
- Add a few CLI UI features, like printing bold, or imitate the behaviour of `console.error()` in JavaScript
- Decouple testing logic and presentation logic.
- Add meta properties to `Outkicker` class, which can be used to access test results
- Break some internal monolith methods into smaller units, each can be used alone

### 0.1.0 (January 19, 2021) 

---

- Initial public release
- Outkicker is a simple testing utility for PHP.<br>It helps you kick potential bugs and unexpected behaviors before they appear on production.<br>It is intentionally developed for Outsights framework.