# Changelog

All notable changes to `phpjuice/paypal-checkout-sdk` will be documented in this file.

## Version 3.0.1

### Changed

- Add support for php8.1 in #25

## Version 3.0.0

### Changed

- Update readme & changelog in #21
- Use standalone phpjuice/paypal-http-client

## Version 2.0.2

### Changed

- Add .gitattributes file

## Version 2.0.1

### Changed

- allow guzzlehttp/psr7 v2.0 dependency

## Version 2.0.0

### Added

- Add Brick/Money for handling money values.
- Add new method addItems to PurchaseUnit class
- Replace PHPUnit with PestPHP for tests.
- Add new docs.

### Changed

- Change the minimum required PHP version to 7.4.

### Deprecated

- none

### Removed

- Money::class
- Currency::class
- 7.2 PHP support

## Version 1.2.0

### Changed

- fix amount breakdown to include discount #12

### Removed

- Removed `setValue` & `setCurrencyCode` from `Amount.php`
- Removed `setValue` & `setCurrencyCode` from `PurchaseUnit`

## Version 1.1.2

### Added

- Clean and refactor code #8

## Version 1.1.1

### Added

- Remove PHP version header on sandbox environment (#7) (see #6)

## Version 1.1.0

### Added

- Upgrade guzzle http to version 7 (#3)

## Version 1.0.2

### Added

- Add type hints to all classes.
- Add the calculated total test.

## Version 1.0.1

### Added

- Update package documentation

## Version 1.0.0

### Added

- Orders API Requests
- PayPal Client
- Access Token Requests
