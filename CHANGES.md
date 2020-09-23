## CHANGELOG ##

* @dev
  * `name()` argument is now `trim()`ed automatically.
  * Removed `invobill()` method. Use `reserved2()` instead.

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
