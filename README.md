# clock
This package contains a representation of a clock with easy mocking of current date and time for testing.

## Usage
Obtain the current UTC date and time as a DateTimeImmutable object.
```
$currentUtcDateTime = Clock::getSingleton()->now();
```

## Mocking of current UTC date and time
Use ReflectionClass to set the Clock singleton to a mocked class implementing ClockInterface.
```
/**
 * @throws Exception
 */
public function testMocking()
{
    $mockedDateTimeImmutable = DateTimeImmutable::createFromFormat(
        format: 'Y-m-d H:i:s.u',
        datetime: '1999-12-31 23:59:59.999999',
        timezone: new DateTimeZone(timezone: 'UTC')
    );

    $mockedClock = $this->createMock(type: ClockInterface::class);
    $mockedClock
        ->method(constraint: 'now')
        ->willReturn($mockedDateTimeImmutable);

    $reflectionClass = new ReflectionClass(objectOrClass: Clock::class);
    $reflectionClass->setStaticPropertyValue(name: 'singleton', value: $mockedClock);

    $this->assertSame(
        expected: $mockedDateTimeImmutable->format(format: 'Y-m-d H:i:s.u'),
        actual: Clock::getSingleton()->now()->format(format: 'Y-m-d H:i:s.u')
    );
}
```

Remember to reset singleton to ensure usage of correct date and time in subsequent tests.
```
protected function tearDown(): void
{
    // Reset singleton to ensure usage of correct date and time in subsequent tests.
    $reflectionClass = new ReflectionClass(objectOrClass: Clock::class);
    $reflectionClass->setStaticPropertyValue(name: 'singleton', value: null);
}
```
