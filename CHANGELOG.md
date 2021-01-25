# Seekr ~ Change Log

### 1.0.0 Stability Expectations

---

- Seekr will be able to be used on production.<br>It will have enough features to be a minimalistic testing library.<br>It gives developers a few useful way which they can write and run tests locally. Easily and fast!<br>
- It will have a stable `Say` helper class which provides useful ready-to-go premises to developers. <br>People can use them to simplify their code. Work for `Say` is in progress, and seems to always be ;)

### 0.6.0 (January 24, 2021)

---

- **NEW !** Add Life Cycle Hooks.
  - A life cycle hook is a method that can be implemented in your test class and will be run on specific times while executing your tests.
  - These are current life cycle hooks for a test environment :
    - `setUp()` :  Called before starting to run tests in a test class
    - `finish()` : Called after all tests in a test class have run
    - `mountedTest()` : Called before each test of this test class is run
    - `unmountedTest()` : Called before each test of this test class is run.

### 0.5.0 (January 24, 2021)

---

- **RENAMED** the library :  ~~Outkicker~~ to **Seekr**

- Designed a logo after renaming the library.<br>

- New name with our motto :<br>

  **Seekr**<br>"Seeking more efficient and accurate tests in your code<br> while preventing potential bugs, inconsistencies fast, simply and wisely."

- **REWRITE**

  - Rewrote the whole library from scratch with what I learned through the journey.<br>I approached the concept from first principles.
  - Fixed inconsistencies
  - Simplified and decoupled some units.
  - Removed some unnecessary internal code

### 0.4.0 (January 23, 2021)

---

- **NEW !**  Add `Premise` class to let users write their own premises and evaluate them.
  - Give a statement which can be evaluated and resolved into a boolean value.
  - Give a message and code describing a unique Contradiction type.<br>No more unnecessary abstractions for every `Exception` or `Contradiction` type.<br>For example : You can write Contradiction creator functions to throw different type of contradictions by only changing the arguments. Simple, right?
- Decoupled proposing a `Premise` process from `Say` . Now anyone can write their own premises if they can't find what they need in  `Say` helper class. You no longer need to wait until we release a new version.

### 0.3.0 (January 22, 2021)

---

- **NEW !** Add `Timer` class to show how much it takes to run each test.
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
