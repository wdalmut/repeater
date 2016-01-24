# Repeat until

```php
$repeat = new Repeater();   // by default max 5 times
$repeat = new Repeater(15); // max 15 times
```

Repeat a callable until

```php
$serviceResponse = $repeat->until(function($context) use ($service) {
  // something that can fail

  $context->ok();

  return $service->getResponse();
});
```

Return values are preserved also on failures

```php
$serviceResponse = $repeat->until(function($context) use ($service) {
  /** ... */

  return "Invalid responses from the service"
});

$serviceResponse // "Invalid responses from the service"
```

Exceptions are preserved at the end

```php
try {
  $serviceResponse = $repeat->until(function($context) use ($service) {
    /** ... */
    throw new \RuntimeException("somthing");
  });
} catch (\Exception $e) {
  // handle exceptions...
}
```

