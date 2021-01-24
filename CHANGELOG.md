# Seekr ~ Change Log

### 1.0.0 ( [UNKNOWN] ~ 2021)

---

- Will release to public !
- Seekr will be able to be used on production.<br>It will have enough features to be a minimalistic testing library.<br>It gives developers a few useful way which they can write and run tests locally. Easily and fast!<br>
- It will have `Say` helper class which provides useful ready-to-go premises to developers. They can use them to simplify their code. Work for `Say` is in progress, and seems to always be ;)

### 0.4.0 (January 23, 2021)

---

- Add `Premise` class to let users write their own premises and evaluate them.
  - Give a statement which can be evaluated and resolved into a boolean value.
  - Give a message and code describing a unique Contradiction type.<br>No more unnecessary abstractions for every `Exception` or `Contradiction` type.<br>For example : You can write Contradiction creator functions to throw different type of contradictions by only changing the arguments. Simple, right?
- Decoupled proposing a `Premise` process from `Say` . Now anyone can write their own premises if they can't find what they need in  `Say` helper class. You no longer need to wait until we release a new version.

### 0.3.0 (January 22, 2021)

---

- Add `Timer` class to show how much it takes to run a single test.
- Updated output for the sake of aesthetics

### 0.2.1 (January 21, 2021)

---

- Fix many bugs causing problems in outputting test results

### 0.2.0 (January 21, 2021)

---

- Add `seeTestResults()` method to get output about test results. <br>Seekr does not automatically outputs the results anymore.
- Add a few CLI UI features, like printing bold, or imitate the behaviour of `console.log()` in JavaScript
- Decouple testing logic and presentation logic.
- Add meta properties to Seekr, which can be used to access test results
- Break some internal monolith methods into smaller units, each can be used alone

### 0.1.0 (January 19, 2021) 

---

- Initial public release
