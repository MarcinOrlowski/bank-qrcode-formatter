## CHANGELOG ##

* v1.5.0 (2024-10-15)
  * Corrected amount handling setting max amount value cap lower than supported. 

* v1.4.1 (2024-10-10)
  * Added global namespace prefix to all the functions and exception classes.
  * Updated link to specification of QR code of Zwiek Banków Polskich.

* v1.4.0 (2020-09-23)
  * `name()` argument is now `trim()`ed automatically.
  * Removed `invobill()` method. Use `reserved2()` instead.
  * Corrected some tests.
  * 100% test coverage.

* v1.3.0 (2020-09-23)
  * Bumped min PHP version to 5.6
  * Added Travis-CI support.
  * Corrected `testVatIdInvalidDataInvalidCharacters()` test.

* v1.2.0 (2020-09-23)
  * Bumped min PHP version to 5.4
  * Documented library API
  * `reservedX()` methods accept `null` now.
  * Calling `reservedX()` with argument exceeding max allowed length, throws `InvalidArgumentException` now.
  * Added tests for `reservedX()` methods too.
  * Added `strict mode` (off by default) to enforce more strict data verification.

* v1.1.0 (2020-09-23)
  * Added unit tests
  * Polished code

* v1.0.0 (2020-09-22)
  * Initial release
