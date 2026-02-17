# Testing Guide

## Run Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test file
./vendor/bin/phpunit tests/Unit/UserTest.php

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage

# Run in watch mode (auto re-run on changes)
./vendor/bin/phpunit --watch
```

## Test Structure

- `tests/Unit/` - Unit tests for individual functions
- `tests/Feature/` - Integration tests for features
- `tests/bootstrap.php` - Test setup and mocking

## Writing Tests

```php
public function test_description_of_what_is_being_tested()
{
    // Arrange
    $input = 'value';
    
    // Act
    $result = someFunction($input);
    
    // Assert
    $this->assertEquals('expected', $result);
}
```

## Available Assertions

- `assertEquals($expected, $actual)`
- `assertTrue($condition)`
- `assertFalse($condition)`
- `assertNull($value)`
- `assertNotNull($value)`
- `assertStringContainsString($needle, $haystack)`
- `assertCount($expectedCount, $array)`
