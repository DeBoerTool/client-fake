# Client Fake for Laravel

This package provides an easy way to fake HTTP calls for services that requires Laravel's HTTP Client in testing contexts. If you're using the HTTP Facade, this package isn't for you.

Simply extend or directly construct the `ClientFake`, register your fakes, then commit or invoke the result. When your service resolves the HTTP Client from the container, it will be resolved with the fakes applied only to that instance of the HTTP Client. 

For instance if you have `FooService` and `BarService`, if you register a fake for `BarService`, the fake only be applied to that service, and not to any others.

This is useful when you have one service that you want to fake, but not others.

## Installation

You can install the package via composer:

```bash
composer require dbt/client-fake --dev
```

## Usage

 You can use the `ClientFake` class directly, or extend it and define method fakes of your own.

If you want to use the `ClientFake` directly, you can do so like this (for example, in the body of your test):

```php
use Dbt\ClientFake\ClientFake;
use Dbt\ClientFake\Options\Options;

$clientFake = new ClientFake($app, new Options(
    service: MyService::class,
    base: 'https://my-service.com',
    // Or false if the API isn't versioned.
    version: 'v1',
    // Optional headers to add to all fake responses for use in testing.
    headers: ['X-My-Header' => 'some value'],
));

$clientFake->fake('my/endpoint', ['data' => 'some data']);

$clientFake->commit();
// or
$clientFake();
```

Then you can resolve your service and use it as normal:

```php
$service = resolve(MyService::class);

$response = $service->callMyEndpoint();

$this->assertSame(['data' => 'some data'], $response->json());
$this->assertSame('some value', $response->header('X-My-Header'));
```

### Catchall

By default, a catchall is added, which will return a 500 response for any endpoint that isn't faked. You can disable this:

```php
$clientFake->withoutCatchall();
```

### Conditionally Enabling

You can conditionally enable and disable the fake by using the `enable` method:

```php
$clientFake->enable($booleanCondition);
```

You can optionally add a callback that will be resolved from the container and executed when the boolean condition is false:

```php
$clientFake->enable($booleanCondition, function (SomeOtherService $service) {
    $service->doSomething();
});
```

### Options

You can use the `ClientFakeOptions` object as-is, or define your own by implementing the `ClientFakeOptionsInterface`.

## Etc.

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
