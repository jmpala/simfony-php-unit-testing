# Unit Testing - Symfony

## Run Tests

```bash
ddev excc vendor/bin/phpunit
```

## Options

- "--help": Show all options
- "--testdox": Show tests in a human readable format

## How tests work

- PHP-Unit looks automatically for the phpunit.xml.dist file
- Next it looks for the declared test suites
- Then it looks for the directories defined on the test suites
- Afterwards it looks recursively for the files that end with Test.php
- Finally it looks for all public methods per matched class with prefix name test

## Important

- assertEquals internally use "=="
- assertSame internally use "==="

## Annotations

```php
#[DataProvider('functionName')]
```